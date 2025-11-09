<?php

class SoporteController extends Controller {
    
    private $asistenciaModel;
    private $respuestaModel;
    
    public function __construct() {
        $this->requireAuth();
        $this->requireRole('soporte');
        
        $this->asistenciaModel = $this->model('Asistencia');
        $this->respuestaModel = $this->model('RespuestaTicket');
    }
    
    // RF-57: Dashboard de soporte
    public function dashboard() {
        $stats = [
            'ticketsAbiertos' => $this->asistenciaModel->contarPorEstado('abierto'),
            'ticketsEnProceso' => $this->asistenciaModel->contarPorEstado('en_proceso'),
            'ticketsResueltos' => $this->asistenciaModel->contarPorEstado('resuelto'),
            'misTickets' => $this->asistenciaModel->obtenerTicketsAsignados($_SESSION['soporte_id'])
        ];
        
        $this->view('soporte/dashboard', $stats);
    }
    
    // RF-57: Ver todos los tickets
    public function tickets() {
        $filtro = $_GET['estado'] ?? 'todos';
        $prioridad = $_GET['prioridad'] ?? null;
        
        if ($filtro === 'mis-tickets') {
            $tickets = $this->asistenciaModel->obtenerTicketsAsignados($_SESSION['soporte_id']);
        } elseif ($filtro === 'todos') {
            $tickets = $this->asistenciaModel->obtenerTodos();
        } else {
            $tickets = $this->asistenciaModel->obtenerPorEstado($filtro);
        }
        
        // Aplicar filtro de prioridad si existe
        if ($prioridad) {
            $tickets = array_filter($tickets, function($ticket) use ($prioridad) {
                return $ticket['prioridad'] === $prioridad;
            });
        }
        
        $this->view('soporte/tickets/index', [
            'tickets' => $tickets,
            'filtroActual' => $filtro,
            'prioridadActual' => $prioridad
        ]);
    }
    
    // RF-57: Ver detalle de un ticket
    public function verTicket($id) {
        $ticket = $this->asistenciaModel->obtenerConUsuario($id);
        
        if (!$ticket) {
            $_SESSION['error'] = "Ticket no encontrado";
            $this->redirect('/soporte/tickets');
        }
        
        // Obtener historial de respuestas
        $respuestas = $this->respuestaModel->obtenerPorTicket($id);
        
        // Marcar ticket como visto
        if ($ticket['estado'] === 'abierto') {
            $this->asistenciaModel->update($id, [
                'estado' => 'en_proceso',
                'id_soporte' => $_SESSION['soporte_id']
            ]);
        }
        
        $this->view('soporte/tickets/detalle', [
            'ticket' => $ticket,
            'respuestas' => $respuestas
        ]);
    }
    
    // RF-57: Responder a un ticket
    public function responderTicket($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/soporte/ticket/' . $id);
        }
        
        $mensaje = $this->sanitize($_POST['mensaje'] ?? '');
        $cerrarTicket = isset($_POST['cerrar_ticket']);
        
        if (empty($mensaje)) {
            $_SESSION['error'] = "El mensaje no puede estar vacío";
            $this->redirect('/soporte/ticket/' . $id);
        }
        
        try {
            // Crear respuesta
            $this->respuestaModel->create([
                'id_ticket' => $id,
                'id_usuario' => $_SESSION['user_id'],
                'mensaje' => $mensaje
            ]);
            
            // Actualizar estado del ticket
            $nuevoEstado = $cerrarTicket ? 'resuelto' : 'en_proceso';
            $this->asistenciaModel->update($id, [
                'estado' => $nuevoEstado,
                'id_soporte' => $_SESSION['soporte_id']
            ]);
            
            // Crear notificación para el usuario
            $ticket = $this->asistenciaModel->find($id);
            $notificacionModel = $this->model('Notificacion');
            $notificacionModel->create([
                'id_usuario' => $ticket['id_usuario'],
                'tipo' => 'sistema',
                'titulo' => 'Respuesta a tu ticket de soporte',
                'contenido' => 'El equipo de soporte ha respondido a tu ticket #' . $id,
                'url_accion' => '/cliente/ticket/' . $id
            ]);
            
            $_SESSION['success'] = $cerrarTicket ? 
                "Ticket cerrado exitosamente" : 
                "Respuesta enviada exitosamente";
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al responder ticket: " . $e->getMessage();
        }
        
        $this->redirect('/soporte/ticket/' . $id);
    }
    
    // Tomar asignación de un ticket
    public function tomarTicket($id) {
        try {
            $this->asistenciaModel->update($id, [
                'id_soporte' => $_SESSION['soporte_id'],
                'estado' => 'en_proceso'
            ]);
            
            $_SESSION['success'] = "Ticket asignado a ti exitosamente";
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al tomar ticket: " . $e->getMessage();
        }
        
        $this->redirect('/soporte/ticket/' . $id);
    }
    
    // Cambiar prioridad de un ticket
    public function cambiarPrioridad($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/soporte/tickets');
        }
        
        $prioridad = $_POST['prioridad'] ?? 'media';
        
        try {
            $this->asistenciaModel->update($id, [
                'prioridad' => $prioridad
            ]);
            
            $_SESSION['success'] = "Prioridad actualizada";
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al cambiar prioridad: " . $e->getMessage();
        }
        
        $this->redirect('/soporte/ticket/' . $id);
    }
    
    // Estadísticas personales
    public function misEstadisticas() {
        $stats = [
            'ticketsResueltos' => $this->asistenciaModel->contarResueltoPorSoporte($_SESSION['soporte_id']),
            'ticketsActivos' => $this->asistenciaModel->contarActivosPorSoporte($_SESSION['soporte_id']),
            'tiempoPromedioResolucion' => $this->asistenciaModel->calcularTiempoPromedio($_SESSION['soporte_id']),
            'historial' => $this->asistenciaModel->obtenerHistorialSoporte($_SESSION['soporte_id'], 20)
        ];
        
        $this->view('soporte/estadisticas', $stats);
    }
}