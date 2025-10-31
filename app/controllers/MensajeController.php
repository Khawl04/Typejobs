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
    $chat_seleccionado = $_GET['chat'] ?? (isset($conversaciones[0]['id_otro_usuario']) ? $conversaciones[0]['id_otro_usuario'] : null);

    // Usa tu constante BASE_URL (ajusta si tienes otra variable global)
    $uploadsPath = BASE_URL . '/uploads/perfiles/';
    $defaultAvatar = BASE_URL . '/img/defaultpfp.png';

    // Busca si el seleccionado está entre las conversaciones existentes
    foreach ($conversaciones as &$conv) {
        $usuarioOtro = $this->usuarioModel->getById($conv['id_otro_usuario']);
        $conv['nombre_otro_usuario'] = trim(($usuarioOtro['nombre'] ?? '') . ' ' . ($usuarioOtro['apellido'] ?? ''));
        $conv['avatar'] = !empty($usuarioOtro['foto_perfil'])
            ? $uploadsPath . $usuarioOtro['foto_perfil']
            : $defaultAvatar;

        if ($chat_seleccionado && $conv['id_otro_usuario'] == $chat_seleccionado) {
            $chat_activo = $conv;
        }
    }
    unset($conv);

    // Si no existe aún conversación previa pero hay parámetro chat, crea chat activo manualmente
   if (!$chat_activo && $chat_seleccionado) {
    $usuarioDestino = $this->usuarioModel->getById($chat_seleccionado);
    if ($usuarioDestino) {
        $chat_activo = [
            'id_otro_usuario' => $chat_seleccionado,
            'nombre_otro_usuario' => trim(($usuarioDestino['nombre'] ?? '') . ' ' . ($usuarioDestino['apellido'] ?? '')),
            'avatar' => !empty($usuarioDestino['foto_perfil'])
                ? $uploadsPath . $usuarioDestino['foto_perfil']
                : $defaultAvatar
        ];
    } else {
        // Opcional: Redirecciona o muestra mensaje de error si no existe tal user
        $_SESSION['error'] = "El usuario destino no existe.";
        $this->redirect("/mensaje");
    }
    $mensajes_chat = [];
    } elseif ($chat_activo) {
        $this->mensajeModel->marcarLeidos($chat_activo['id_otro_usuario'], $_SESSION['id_usuario']);
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
        $this->mensajeModel->marcarLeidos($idDestinatario, $_SESSION['id_usuario']);
        $mensajes = $this->mensajeModel->obtenerMensajes($_SESSION['id_usuario'], $idDestinatario);
        $destinatario = $this->usuarioModel->getById($idDestinatario);
        $this->view('mensaje/mensaje', [
            'mensajes' => $mensajes,
            'destinatario' => $destinatario
        ]);
    }

    // Iniciar conversación desde un perfil
    public function iniciarConversacion($idUsuarioDestino) {
        $this->redirect('/mensaje?chat=' . (int)$idUsuarioDestino);
    }

    // Enviar mensaje (con o sin archivo)
   public function enviar() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect('/mensaje');
    }
    $idRemitente = $_SESSION['id_usuario'];
    $idDestinatario = (int)$_POST['id_destinatario'];
    $contenido = trim($_POST['contenido'] ?? '');

    // Comprobar campo archivo
    $archivoAdjunto = null;
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
        $extension = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));
        if (in_array($extension, ALLOWED_EXTENSIONS) && $_FILES['archivo']['size'] <= MAX_FILE_SIZE) {
            $nombreArchivo = uniqid() . '_' . basename($_FILES['archivo']['name']);
            $rutaDestino = UPLOAD_PATH . 'mensajes/' . $nombreArchivo;
            if (!is_dir(UPLOAD_PATH . 'mensajes/')) {
                mkdir(UPLOAD_PATH . 'mensajes/', 0755, true);
            }
            if (move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaDestino)) {
                $archivoAdjunto = 'uploads/mensajes/' . $nombreArchivo;
            }
        }
    }

    // Permitir mensaje si hay texto o archivo (al menos uno)
    if (empty($contenido) && !$archivoAdjunto) {
        $_SESSION['error'] = "El mensaje no puede estar vacío";
        $this->redirect('/mensaje?chat=' . $idDestinatario);
    }

    $this->mensajeModel->enviar($idRemitente, $idDestinatario, $contenido, $archivoAdjunto);

    $this->notificacionModel->crearNotificacion([
        'id_usuario' => $idDestinatario,
        'tipo' => 'mensaje',
        'titulo' => 'Nuevo mensaje recibido',
        'mensaje' => "Tienes un nuevo mensaje de " . $_SESSION['nombre'],
        'url_accion' => '/mensaje?chat=' . $idRemitente
    ]);
    $_SESSION['success'] = "Mensaje enviado exitosamente";
    $this->redirect('/mensaje?chat=' . $idDestinatario);
}

    // Devuelve la cantidad de mensajes no leídos para el dashboard
    public function contarNoLeidos() {
        $total = $this->mensajeModel->noLeidosPorUsuario($_SESSION['id_usuario']);
        echo json_encode(['no_leidos' => $total]);
        exit();
    }
}
?>