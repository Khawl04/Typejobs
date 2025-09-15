<?php
//Sistema de mensajería de la pagina
session_start();
require_once '../logica/auth.php';

// Verificar que el usuario esté logueado
Auth::verificarAcceso();

// Obtener datos del usuario actual
$usuario = Auth::getUsuarioActual();

if (!$usuario) {
    header('Location: index.php');
    exit;
}

// Procesar acción de cerrar sesión
if (isset($_GET['accion']) && $_GET['accion'] === 'logout') {
    Auth::cerrarSesion();
    header('Location: index.php');
    exit;
}

// Simulación de datos de conversaciones (esto vendría de la base de datos)
$conversaciones = [
    [
        'id' => 1,
        'nombre' => 'María González',
        'ultimo_mensaje' => 'Hola, me interesa tu servicio de desarrollo web',
        'fecha' => '2024-01-15 14:30:00',
        'no_leidos' => 2,
        'avatar' => 'M',
        'activo' => false
    ],
    [
        'id' => 2,
        'nombre' => 'Carlos Rodríguez',
        'ultimo_mensaje' => 'Perfecto, cuando podemos empezar?',
        'fecha' => '2024-01-15 12:15:00',
        'no_leidos' => 0,
        'avatar' => 'C',
        'activo' => false
    ],
    [
        'id' => 3,
        'nombre' => 'Ana Martín',
        'ultimo_mensaje' => 'Muchas gracias por el trabajo realizado',
        'fecha' => '2024-01-14 18:45:00',
        'no_leidos' => 1,
        'avatar' => 'A',
        'activo' => false
    ],
    [
        'id' => 4,
        'nombre' => 'Pedro Silva',
        'ultimo_mensaje' => 'Te envío los archivos por email',
        'fecha' => '2024-01-14 09:20:00',
        'no_leidos' => 0,
        'avatar' => 'P',
        'activo' => false
    ]
];

// Obtener conversación activa (la primera por defecto o la seleccionada)
$chat_activo_id = $_GET['chat'] ?? 1;
$chat_activo = null;

foreach ($conversaciones as $key => $conv) {
    if ($conv['id'] == $chat_activo_id) {
        $conversaciones[$key]['activo'] = true;
        $chat_activo = $conversaciones[$key];
    } else {
        $conversaciones[$key]['activo'] = false;
    }
}

// Simulación de mensajes del chat activo (esto vendría de la base de datos)
$mensajes_chat = [
    [
        'id' => 1,
        'remitente' => 'María González',
        'mensaje' => 'Hola, vi tu perfil y me interesa tu servicio de desarrollo web',
        'fecha' => '2024-01-15 14:25:00',
        'es_mio' => false
    ],
    [
        'id' => 2,
        'remitente' => $usuario->nombre . ' ' . $usuario->apellido,
        'mensaje' => 'Hola María! Gracias por contactarme. ¿Qué tipo de proyecto tienes en mente?',
        'fecha' => '2024-01-15 14:28:00',
        'es_mio' => true
    ],
    [
        'id' => 3,
        'remitente' => 'María González',
        'mensaje' => 'Necesito una página web para mi negocio de repostería. Algo sencillo pero profesional',
        'fecha' => '2024-01-15 14:30:00',
        'es_mio' => false
    ]
];

// Procesar envío de mensaje
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'enviar_mensaje') {
    $nuevo_mensaje = trim($_POST['mensaje'] ?? '');
    
    if (!empty($nuevo_mensaje)) {
        // Simulación de guardar mensaje
        $nuevo_id = count($mensajes_chat) + 1;
        $mensajes_chat[] = [
            'id' => $nuevo_id,
            'remitente' => $usuario->nombre . ' ' . $usuario->apellido,
            'mensaje' => $nuevo_mensaje,
            'fecha' => date('Y-m-d H:i:s'),
            'es_mio' => true
        ];
        
        // Redirigir para evitar reenvío
        header("Location: mensaje.php?chat=" . $chat_activo_id);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TypeJobs - Mensajes</title>
    <link rel="stylesheet" href="../estilos/stylemensaje.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo-section">
                <span class="logo-icon">T</span>
                <span class="logo-text">TypeJobs</span>
            </div>
            <div class="back-button-container">
                        <a href="<?= $usuario->esCliente() ? 'cliente.php' : 'proveedor.php'; ?>" class="back-button">Volver</a>
                    </div>
            <nav class="nav-section">
                <a href="busqueda.php" class="nav-btn active">Categorías</a>
            </nav>
            
            <div class="user-section">
                <a href="index.php?accion=logout" class="user-greeting">Salir</a>  
                <details class="user-menu">
                    <summary class="user-avatar">
                        <?php echo strtoupper(substr($usuario->nombre, 0, 1)); ?>
                    </summary>
                </details>
            </div>
        </div>
    </header>
            
    <!-- Main Content -->
    <main class="main-content">
        <div class="chat-container">
            
            <!-- Lista de Conversaciones -->
            <aside class="conversations-panel">
                <header class="conversations-header">
                    <h2>Conversaciones</h2>
                </header>
                
                <ul class="conversations-list">
                    <?php foreach ($conversaciones as $conv): ?>
                        <li class="conversation-item <?php echo $conv['activo'] ? 'active' : ''; ?>">
                            <a href="mensaje.php?chat=<?php echo $conv['id']; ?>">
                                <span class="conversation-avatar">
                                    <?php echo htmlspecialchars($conv['avatar']); ?>
                                </span>
                                <div class="conversation-info">
                                    <header class="conversation-header">
                                        <h3 class="conversation-name"><?php echo htmlspecialchars($conv['nombre']); ?></h3>
                                        <time class="conversation-time" datetime="<?php echo $conv['fecha']; ?>">
                                            <?php echo date('H:i', strtotime($conv['fecha'])); ?>
                                        </time>
                                    </header>
                                    <p class="last-message"><?php echo htmlspecialchars($conv['ultimo_mensaje']); ?></p>
                                    <?php if ($conv['no_leidos'] > 0): ?>
                                        <span class="unread-badge"><?php echo $conv['no_leidos']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <!-- Panel de Chat -->
            <section class="chat-panel">
                <?php if ($chat_activo): ?>
                    <header class="chat-header">
                        <span class="chat-avatar"><?php echo htmlspecialchars($chat_activo['avatar']); ?></span>
                        <h3 class="chat-user-name"><?php echo htmlspecialchars($chat_activo['nombre']); ?></h3>
                    </header>

                    <section class="chat-messages">
                        <?php foreach ($mensajes_chat as $mensaje): ?>
                            <article class="message <?php echo $mensaje['es_mio'] ? 'message-own' : 'message-other'; ?>">
                                <?php if (!$mensaje['es_mio']): ?>
                                    <span class="message-avatar"><?php echo strtoupper(substr($mensaje['remitente'], 0, 1)); ?></span>
                                <?php endif; ?>
                                <p class="message-bubble"><?php echo nl2br(htmlspecialchars($mensaje['mensaje'])); ?></p>
                                <time class="message-time" datetime="<?php echo $mensaje['fecha']; ?>">
                                    <?php echo date('H:i', strtotime($mensaje['fecha'])); ?>
                                </time>
                            </article>
                        <?php endforeach; ?>
                    </section>

                    <footer class="chat-input">
                        <form method="POST" class="chat-input-form">
                            <input type="hidden" name="accion" value="enviar_mensaje">
                            <label>
                                <span class="sr-only">Escribir mensaje</span>
                                <input type="text" name="mensaje" class="message-input" placeholder="Escribir mensaje..." required autocomplete="off">
                            </label>
                            <button type="submit" class="send-button">➤</button>
                        </form>
                    </footer>
                <?php else: ?>
                    <section class="no-chat-selected">
                        <h3>Selecciona una conversación para comenzar</h3>
                        <p>Elige una conversación de la lista para ver los mensajes</p>
                    </section>
                <?php endif; ?>
            </section>
        </div>
    </main>
</body>
</html>
