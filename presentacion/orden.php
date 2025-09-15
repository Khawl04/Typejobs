<?php
// orden - Confirmación de la orden
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

// Obtener datos de la orden
$orden_id = $_GET['orden_id'] ?? 1;
$orden = [
    'id' => $orden_id,
    'servicio' => 'Desarrollo de Sitio Web Completo',
    'proveedor' => 'Juan Pérez',
    'descripcion' => 'Desarrollo de sitio web responsive con panel de administración, carrito de compras y sistema de pagos integrado.',
    'total' => 850.00,
    'fecha_completada' => date('Y-m-d H:i:s'),
    'estado' => 'completada'
];

// Cerrar sesión
if (isset($_GET['accion']) && $_GET['accion'] === 'logout') {
    Auth::cerrarSesion();
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TypeJobs - Orden Completada</title>
    <link rel="stylesheet" href="../estilos/styleorden.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo-section">
                <span class="logo-icon">T</span>
                <span class="logo-text">TypeJobs</span>
            </div>
            
            <nav class="nav-section" aria-label="Navegación principal">
                <a href="#" class="nav-btn">Categorías</a>
            </nav>
            
            <div class="auth-section">
                <a href="index.php?form=login" class="auth-btn">Iniciar sesión</a>
                <a href="index.php?form=registro" class="auth-btn register">Registrarse</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <section class="order-container" aria-labelledby="order-status-title">
            <article class="order-card">
                <!-- Estado de la Orden -->
                <header class="order-header">
                    <figure class="status-icon">
                        <div class="icon-circle">
                            <span class="checkmark" aria-hidden="true">✓</span>
                        </div>
                        <figcaption class="sr-only">Orden completada</figcaption>
                    </figure>
                    <h1 id="order-status-title" class="order-status">ORDEN COMPLETADA</h1>
                </header>
                
                <!-- Detalles de la Orden -->
                <section class="order-details">
                    <h2>Detalles de la Orden</h2>
                    <dl class="detail-list">
                        <div class="detail-item">
                            <dt>ID de Orden:</dt>
                            <dd>#<?php echo str_pad($orden['id'], 6, '0', STR_PAD_LEFT); ?></dd>
                        </div>
                        <div class="detail-item">
                            <dt>Fecha:</dt>
                            <dd><?php echo date('d/m/Y H:i', strtotime($orden['fecha_completada'])); ?></dd>
                        </div>
                        <div class="detail-item">
                            <dt>Proveedor:</dt>
                            <dd><?php echo htmlspecialchars($orden['proveedor']); ?></dd>
                        </div>
                    </dl>

                    <section class="service-section">
                        <h3>Servicio</h3>
                        <article class="service-info">
                            <h4><?php echo htmlspecialchars($orden['servicio']); ?></h4>
                            <p><?php echo htmlspecialchars($orden['descripcion']); ?></p>
                        </article>
                    </section>

                    <section class="total-section">
                        <h3>Total</h3>
                        <p class="total-value">$<?php echo number_format($orden['total'], 2); ?> USD</p>
                    </section>
                </section>
                
                <!-- Acciones -->
                <nav class="order-actions" aria-label="Acciones de la orden">
                    <a href="inicio.php" 
                       class="action-btn primary">Inicio</a>
                    
                    <a href="#" onclick="window.print(); return false;" 
                       class="action-btn secondary">Imprimir</a>
                    
                    <a href="mensaje.php" class="action-btn secondary">Contactar Proveedor</a>
                </nav>

                <!-- Mensaje de agradecimiento -->
                <section class="additional-info">
                    <p><strong>¡Gracias por confiar en TypeJobs!</strong></p>
                    <p>Tu orden ha sido completada exitosamente.</p>
                    <p>Recibirás un email de confirmación con todos los detalles.</p>
                </section>
            </article>
        </section>
    </main>

    <!-- Footer -->
    <footer class="page-footer">
        <div class="footer-content">
            <nav class="footer-links" aria-label="Enlaces de pie de página">
                <a href="#">Términos de Servicio</a>
                <a href="#">Política de Privacidad</a>
                <a href="#">Soporte</a>
            </nav>
        </div>
    </footer>
</body>
</html>
