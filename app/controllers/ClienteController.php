<?php

class ClienteController extends Controller {
    private $clienteModel;
    private $usuarioModel;

    public function __construct() {
        $this->requireAuth();
        $this->requireRole('cliente');
        $this->clienteModel = $this->model('Cliente');
        $this->usuarioModel = $this->model('Usuario');
    }

    // RF-02: Panel del cliente
    public function dashboard() {
        $cliente = $this->clienteModel->perfilCompletoPorUsuario($_SESSION['id_usuario']);
        $this->view('cliente/dashboard', ['cliente' => $cliente]);
    }

    // RF-35 y RF-04: Ver y editar perfil
   public function perfil() {
    $mensaje = null;

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        
        'nomusuario' => $this->sanitize($_POST['nomusuario']),
        'telefono'   => $this->sanitize($_POST['telefono']),
        'email'      => $this->sanitize($_POST['email']),
    ];

    // SOLO sube la foto si realmente se seleccionó un archivo NUEVO
    if (
        isset($_FILES['foto_perfil'])
        && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK
        && is_uploaded_file($_FILES['foto_perfil']['tmp_name'])
        && $_FILES['foto_perfil']['size'] > 0
    ) {
        // Borrar foto anterior
        $clienteActual = $this->clienteModel->perfilCompletoPorUsuario($_SESSION['id_usuario']);
        if (!empty($clienteActual['foto_perfil'])) {
            $oldPath = __DIR__ . '/../../public/uploads/perfiles/' . $clienteActual['foto_perfil'];
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
        // Nueva foto
        $ext = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            $fotoNombre = 'perfil_' . $_SESSION['id_usuario'] . '_' . time() . '.' . $ext;
            $destino = __DIR__ . '/../../public/uploads/perfiles/' . $fotoNombre;
            move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $destino);
            $data['foto_perfil'] = $fotoNombre;
            $_SESSION['foto_perfil'] = $fotoNombre;
        }

    }
    if (!empty($_POST['nueva_contrasena'])) {
    $contrasenaActual = $_POST['contrasena_actual'] ?? '';
    $id_usuario = $_SESSION['id_usuario'];
    $userDB = $this->usuarioModel->findById($id_usuario); // O getById(), según tu modelo
    $hashGuardado = $userDB['contrasena'];

    if (password_verify($contrasenaActual, $hashGuardado)) {
        $nuevoHash = password_hash($_POST['nueva_contrasena'], PASSWORD_DEFAULT);
        $this->usuarioModel->update($id_usuario, ['contrasena' => $nuevoHash]);
        $_SESSION['success'] = 'Contraseña cambiada correctamente.';
        // Redirecciona o muestra éxito
    } else {
        $_SESSION['error'] = 'La contraseña actual es incorrecta.';
        $this->redirect('/cliente/perfil');
        return;
    }
}

    $this->usuarioModel->update($_SESSION['id_usuario'], $data);
    $mensaje = "¡Perfil actualizado correctamente!";
}

$cliente = $this->clienteModel->perfilCompletoPorUsuario($_SESSION['id_usuario']);
$this->view('cliente/perfil', ['cliente' => $cliente, 'mensaje' => $mensaje]);
   }
   

    // RF-62: Listar reservas
    public function reservas() {
        $cliente = $this->clienteModel->findBy('id_usuario', $_SESSION['id_usuario']);
        $reservas = $this->clienteModel->reservas($cliente['id_usuario']);
        $this->view('cliente/reservas', ['reservas' => $reservas]);
    }

    // RF-61: Historial de pagos
    public function pagos() {
    $cliente = $this->clienteModel->findBy('id_usuario', $_SESSION['id_usuario']);
    $pagos = [];
    if ($cliente) {
        $pagos = $this->clienteModel->pagos($cliente['id_usuario']);
    }
    // Incluso si $pagos es vacío, igual mostramos la vista
    $this->view('cliente/pagos', ['pagos' => $pagos]);
}


    // RF-03: Eliminar cuenta
        public function eliminarCuenta() {
            $this->clienteModel->eliminarCliente($_SESSION['id_usuario']);
            session_unset();
            session_destroy();
            $this->redirect('/');
        }

    // RF-43: Cancelar reserva
    public function cancelarReserva($idReserva) {
        $this->clienteModel->cancelarReserva($idReserva);
        $this->redirect('/cliente/reservas');
    }
    
    
}