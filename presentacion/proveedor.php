<?php
// Perfil del proveedor
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

// Variables para el perfil
$nombre = htmlspecialchars($usuario->nombre . ' ' . $usuario->apellido);
$titulo = "Desarrollador Full Stack";
$descripcion = "Especializado en desarrollo web con más de 5 años de experiencia. Trabajo con tecnologías modernas y me enfoco en crear soluciones innovadoras para mis clientes.";
$ciudad = "Montevideo, Uruguay";
$estudios = "Ingeniero en Sistemas - Universidad ORT Uruguay";
$estrellas = 5; 

// Procesar formulario de publicación de servicio
$mensaje = '';
$tipoMensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'publicar_servicio') {
    $tituloServicio = trim($_POST['titulo_servicio'] ?? '');
    $descripcionServicio = trim($_POST['descripcion_servicio'] ?? '');
    $precioServicio = trim($_POST['precio_servicio'] ?? '');
    
    // Validaciones básicas
    if (empty($tituloServicio) || empty($descripcionServicio) || empty($precioServicio)) {
        $mensaje = 'Todos los campos son obligatorios';
        $tipoMensaje = 'error';
    } else if (!is_numeric($precioServicio) || $precioServicio <= 0) {
        $mensaje = 'El precio debe ser un número válido mayor a 0';
        $tipoMensaje = 'error';
    } else {
        // La logica para guardar el servicio en la base de datos
        $mensaje = 'Servicio publicado exitosamente';
        $tipoMensaje = 'success';
        
        // Limpiar el formulario después del éxito
        if ($tipoMensaje === 'success') {
            $tituloServicio = '';
            $descripcionServicio = '';
            $precioServicio = '';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TypeJobs - Perfil de <?php echo $nombre; ?></title>
    <link rel="stylesheet" href="../estilos/styleproveedor.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <section class="header-content">
            <div class="logo-section">
                <span class="logo-icon">T</span>
                <span class="logo-text">TypeJobs</span>
            </div>
            
            <nav class="nav-section">
                <a href="#" class="nav-btn active">Categorías</a>
            </nav>
            
            <aside class="user-section">
                <a href="index.php?accion=logout" class="user-greeting">Salir</a>
                <figure class="user-avatar">
                    <?php echo strtoupper(substr($usuario->nombre, 0, 1)); ?>
                </figure>
            </aside>
        </section>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <section class="container">
            
            <!-- Perfil del Proveedor -->
            <article class="profile-card">
                <figure class="profile-avatar">
                    <span class="avatar-circle">
                        <?php echo strtoupper(substr($usuario->nombre, 0, 1)); ?>
                    </span>
                </figure>
                
                <section class="rating-section">
                    <span class="rating-label">Estrellas</span>
                    <div class="stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="star <?php echo $i <= $estrellas ? 'filled' : ''; ?>">★</span>
                        <?php endfor; ?>
                    </div>
                </section>
                
                <section class="profile-info">
                    <h2 class="profile-name"><?php echo $nombre; ?></h2>
                    <h3 class="profile-title"><?php echo htmlspecialchars($titulo); ?></h3>
                    
                    <article class="profile-field">
                        <h4>Descripción</h4>
                        <p><?php echo htmlspecialchars($descripcion); ?></p>
                    </article>
                    
                    <article class="profile-field">
                        <h4>Ciudad</h4>
                        <p><?php echo htmlspecialchars($ciudad); ?></p>
                    </article>
                    
                    <article class="profile-field">
                        <h4>Estudios/Habilidades</h4>
                        <p><?php echo htmlspecialchars($estudios); ?></p>
                    </article>
                </section>
            </article>

            <!-- Publicación de Servicios -->
            <article class="service-card">
                <?php if (!empty($mensaje)): ?>
                    <p class="message <?php echo $tipoMensaje; ?>">
                        <?php echo htmlspecialchars($mensaje); ?>
                    </p>
                <?php endif; ?>
                
                <form method="POST" class="service-form">
                    <input type="hidden" name="accion" value="publicar_servicio">
                    
                    <!-- Título del servicio -->
                    <header class="form-header">
                        <input type="text" name="titulo_servicio" class="service-title" 
                               placeholder="Título del servicio" 
                               value="<?php echo htmlspecialchars($tituloServicio ?? ''); ?>" required>
                    </header>
                    
                    <!-- Descripción -->
                    <label class="form-group">
                        Descripción
                        <textarea name="descripcion_servicio" class="form-textarea" 
                                  placeholder="Describe tu servicio en detalle..."
                                  required><?php echo htmlspecialchars($descripcionServicio ?? ''); ?></textarea>
                    </label>
                    
                    <!-- Imágenes placeholder -->
                    <section class="images-section">
                        <figure class="image-placeholder">
                            <span>📷</span>
                            <figcaption>Imagen 1</figcaption>
                        </figure>
                        <figure class="image-placeholder">
                            <span>📷</span>
                            <figcaption>Imagen 2</figcaption>
                        </figure>
                        <figure class="image-placeholder">
                            <span>📷</span>
                            <figcaption>Imagen 3</figcaption>
                        </figure>
                    </section>
                </form>
            </article>
        </section>
    </main>
</body>
</html>