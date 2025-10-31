<?php
class MensajeController extends Controller {
    private $mensajeModel;
    private $notificacionModel;
    private $usuarioModel;

    public function __construct() {
        $this->requireAuth();
        $this->mensajeModel = $this->model('Mensaje');
        $this->notificacionModel = $this->model('Notificacion');
        $this->usuarioModel = $this->model('Usuario');
    }

    // Método principal (route /mensaje) - muestra inbox/lista de conversaciones
    public function mensaje() {
        $this->dashboard();
    }

    // Lista general de conversaciones para el usuario
    public function dashboard() {
    $conversaciones = $this->mensajeModel->obtenerConversaciones($_SESSION['id_usuario']);
    $chat_activo = null;
    $mensajes_chat = [];
    // Si hay selección por GET ?chat=ID, busca esa. Si no, la primera
    $chat_seleccionado = $_GET['chat'] ?? ($conversaciones[0]['id_usuario'] ?? null);

    // Determina la conversación activa
    foreach ($conversaciones as $conv) {
        if ($conv['id_otro_usuario'] == $chat_seleccionado) {
            $chat_activo = $conv;
            break;
        }
    }
    if(!$chat_activo && !empty($conversaciones)){
        $chat_activo = $conversaciones[0];
    }

    // Si hay chat activo, trae los mensajes:
    if ($chat_activo) {
        $mensajes_chat = $this->mensajeModel->obtenerMensajes($_SESSION['id_usuario'], $chat_activo['id_otro_usuario']);
    }

    $this->view('mensaje/mensaje', [
        'conversaciones' => $conversaciones,
        'mensajes_chat' => $mensajes_chat,
        'chat_activo' => $chat_activo
    ]);
}

    // Ver una conversación específica (con un usuario)
    public function conversacion($idDestinatario) {
        // Marcar mensajes como leídos (de ese destinatario para mí)
        $this->mensajeModel->marcarLeidos($idDestinatario, $_SESSION['id_usuario']);
        // Obtener mensajes (enviados y recibidos entre ambos)
        $mensajes = $this->mensajeModel->obtenerMensajes($_SESSION['id_usuario'], $idDestinatario);
        $destinatario = $this->usuarioModel->find($idDestinatario);
        $this->view('mensajes/conversacion', [
            'mensajes' => $mensajes,
            'destinatario' => $destinatario
        ]);
    }

    // Iniciar conversación desde un perfil
    public function iniciarConversacion($idUsuarioDestino) {
        $this->redirect('/mensajes/conversacion/' . (int)$idUsuarioDestino);
    }

    // Enviar mensaje (con o sin archivo)
    public function enviar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/mensajes');
        }
        $idRemitente = $_SESSION['id_usuario'];
        $idDestinatario = (int)$_POST['id_destinatario'];
        $contenido = trim($_POST['contenido']);
        if (empty($contenido)) {
            $_SESSION['error'] = "El mensaje no puede estar vacío";
            $this->redirect('/mensajes/conversacion/' . $idDestinatario);
        }
        $archivoAdjunto = null;
        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
            $extension = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));
            if (in_array($extension, ALLOWED_EXTENSIONS) && $_FILES['archivo']['size'] <= MAX_FILE_SIZE) {
                $nombreArchivo = uniqid() . '_' . basename($_FILES['archivo']['name']);
                $rutaDestino = UPLOAD_PATH . 'mensaje/' . $nombreArchivo;
                if (!is_dir(UPLOAD_PATH . 'mensaje/')) {
                    mkdir(UPLOAD_PATH . 'mensaje/', 0755, true);
                }
                if (move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaDestino)) {
                    $archivoAdjunto = 'uploads/mensaje/' . $nombreArchivo;
                }
            }
        }
        // IMPORTANTE: usa id_usuario y id_usuario_dest
        $this->mensajeModel->enviar($idRemitente, $idDestinatario, $contenido, $archivoAdjunto);
        $this->notificacionModel->crearNotificacion([
            'id_usuario' => $idDestinatario,
            'tipo' => 'mensaje',
            'titulo' => 'Nuevo mensaje recibido',
            'contenido' => "Tienes un nuevo mensaje de " . $_SESSION['nombre'],
            'url_accion' => '/mensajes/conversacion/' . $idRemitente
        ]);
        $_SESSION['success'] = "Mensaje enviado exitosamente";
        $this->redirect('/mensajes/conversacion/' . $idDestinatario);
    }

    // Devuelve la cantidad de mensajes no leídos para el dashboard
    public function contarNoLeidos() {
        $total = $this->mensajeModel->noLeidosPorUsuario($_SESSION['id_usuario']);
        echo json_encode(['no_leidos' => $total]);
        exit();
    }
}
?>