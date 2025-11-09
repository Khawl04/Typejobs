<?php

class NotificacionController extends Controller {

    private $notificacionModel;

    public function __construct() {
        $this->notificacionModel = $this->model('Notificacion');
    }

    public function enviarNotificacion($usuarioId) {
        // Crear el array de datos de la notificación
        $data = [
            'id_usuario' => $usuarioId,
            'titulo'     => 'Nueva notificación',
            'contenido'  => 'Este es el contenido de la notificación.',
            'mensaje'    => 'Este es el mensaje asociado.',
            'url_accion' => 'https://example.com/accion'
        ];

        // Llamar al método para crear la notificación en la base de datos
        $this->notificacionModel->crearNotificacion($data);

        // Redirigir o devolver una respuesta al usuario
        $_SESSION['success'] = 'Notificación enviada exitosamente.';
        $this->redirect('/usuario/notificaciones'); // Suponiendo que hay una vista de notificaciones
    }
}