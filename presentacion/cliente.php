<?php
// Perfil de cliente
session_start();
require_once '../logica/auth.php';

// Verificar que el usuario esté logueado
Auth::verificarAcceso();

// Obtener datos del usuario actual
$usuario = Auth::getUsuarioActual();

if (!$usuario) {
    header('Location: cliente.php');
    exit;
}


// Procesar acción de cerrar sesión
if (isset($_GET['accion']) && $_GET['accion'] === 'logout') {
    Auth::cerrarSesion();
    header('Location: cliente.php');
    exit;
}

// Variables para el perfil
$nombre = htmlspecialchars($usuario->nombre . ' ' . $usuario->apellido);
$descripcion = "descripcion del cliente"; 
$ciudad = "Montevideo, Uruguay"; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TypeJobs - Mi Perfil</title>
    <link rel="stylesheet" href="../estilos/stylecliente.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo-section">
                <div class="logo-icon">T</div>
                <span class="logo-text">TypeJobs</span>
            </div>
            
            <nav class="nav-section">
                <a href="busqueda.php" class="nav-btn active">Categorías</a>
            </nav>
            
            <div class="user-section">
                <a href="index.php?accion=logout" class="user-greeting">Salir</a>   
                <div class="user-menu">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($usuario->nombre, 0, 1)); ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="profile-container">
            <div class="profile-card">

                <!-- Avatar -->
                <div class="profile-avatar">
                    <div class="avatar-circle">
                        <?php echo strtoupper(substr($usuario->nombre, 0, 1)); ?>
                    </div>
                </div>
                
                <!-- Información básica -->
                <div class="profile-header">
                    <h1 class="profile-name"><?php echo $nombre; ?></h1>
                </div>
                
                <!-- Descripción -->
                <div class="profile-section">
                    <h3 class="section-title">Descripción</h3>
                    <p class="section-content"><?php echo htmlspecialchars($descripcion); ?></p>
                </div>
                
                <!-- Ciudad -->
                <div class="profile-section">
                    <h3 class="section-title">Ciudad</h3>
                    <p class="section-content"><?php echo htmlspecialchars($ciudad); ?></p>
                </div>
                
                <!-- Información adicional del usuario -->
                <div class="profile-section">
                    <h3 class="section-title">Información de Cuenta</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Email:</span>
                            <span class="info-value"><?php echo htmlspecialchars($usuario->email); ?></span>
                        </div>
                        
                        <?php if ($usuario->telefono): ?>
                        <div class="info-item">
                            <span class="info-label">Teléfono:</span>
                            <span class="info-value"><?php echo htmlspecialchars($usuario->telefono); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <div class="info-item">
                            <span class="info-label">Usuario:</span>
                            <span class="info-value"><?php echo htmlspecialchars($usuario->nomusuario); ?></span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Miembro desde:</span>
                            <span class="info-value"><?php echo date('F Y', strtotime($usuario->fechaRegistro)); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Acciones rápidas -->
                <div class="quick-actions">
                    <a href="busqueda.php" class="action-btn primary">
                        Buscar Servicios
                    </a>
                    
                    <a href="mensaje.php" class="action-btn secondary">
                        Ver Mensajes
                    </a>
                    
                    <button class="action-btn secondary" onclick="editProfile()">
                        Editar Perfil
                    </button>
                </div>
                
             
            </div>
        </div>
    </main>
</body>
</html>