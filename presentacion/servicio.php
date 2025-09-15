<?php
// servicio - Página de detalle del servicio
session_start();
require_once '../logica/auth.php';

$servicio_id = $_GET['id'] ?? 1;

$servicio = [
    'id' => $servicio_id,
    'titulo' => 'Desarrollo Web Completo y Responsive',
    'descripcion' => 'descripcion del servicio',
    'precio' => 750.00,
    'categoria' => 'Desarrollo Web',
    'tiempo_entrega' => '7-14 días',
    'proveedor' => [
        'id' => 25,
        'nombre' => 'Juan Carlos Pérez',
        'username' => 'jcperez_dev',
        'avatar' => 'J',
        'estrellas' => 5,
        'ubicacion' => 'Montevideo, Uruguay',
        'talento' => 'Desarrollador Full Stack especializado en tecnologías web modernas',
        'miembro_desde' => '2022-03-15',
        'trabajos_completados' => 47
    ],
    'imagenes' => [
        ['id' => 1, 'url' => 'placeholder1.jpg', 'alt' => 'Mockup del proyecto'],
        ['id' => 2, 'url' => 'placeholder2.jpg', 'alt' => 'Diseño responsive'],
        ['id' => 3, 'url' => 'placeholder3.jpg', 'alt' => 'Panel de administración']
    ]
];

$resenas = [
    [
        'id' => 1,
        'cliente' => 'María González',
        'avatar' => 'M',
        'estrellas' => 5,
        'fecha' => '2024-01-10',
        'comentario' => 'Excelente trabajo, muy profesional...'
    ],
    [
        'id' => 2,
        'cliente' => 'Carlos Rodríguez',
        'avatar' => 'C',
        'estrellas' => 5,
        'fecha' => '2024-01-05',
        'comentario' => 'Altamente recomendado...'
    ]
];

$usuario_logueado = Auth::estaLogueado() ? Auth::getUsuarioActual() : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TypeJobs - <?php echo htmlspecialchars($servicio['titulo']); ?></title>
    <link rel="stylesheet" href="../estilos/styleservicio.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo-section">
                <div class="logo-icon">T</div>
                <span class="logo-text">TypeJobs</span>
            </div>
            
            <nav class="nav-section" aria-label="Menú principal">
                <a href="#" class="nav-btn">Categorías</a>
            </nav>
            
            <div class="auth-section">
                <?php if ($usuario_logueado): ?>
                    <a href="<?php echo $usuario_logueado->esCliente() ? 'cliente.php' : 'proveedor.php'; ?>" class="auth-btn">Mi Perfil</a>
                    <a href="?accion=logout" class="auth-btn register">Salir</a>
                <?php else: ?>
                    <a href="index.php?form=login" class="auth-btn">Iniciar sesión</a>
                    <a href="index.php?form=registro" class="auth-btn register">Registrarse</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <article class="service-container" aria-labelledby="service-title">
            <header class="service-header">
                <h1 id="service-title"><?php echo htmlspecialchars($servicio['titulo']); ?></h1>
            </header>

            <!-- Galería de imágenes -->
            <section class="gallery-section" aria-label="Galería de imágenes del servicio">
                <div class="main-image" role="img" aria-label="Imagen principal del servicio">
                    <div class="image-placeholder main-placeholder" id="mainImage">
                        <div class="image-content">
                            <span class="image-icon">📷</span>
                            <p>Imagen Principal</p>
                        </div>
                    </div>
                    <button class="nav-button prev" onclick="previousImage()" id="prevBtn" aria-label="Imagen anterior">←</button>
                    <button class="nav-button next" onclick="nextImage()" id="nextBtn" aria-label="Siguiente imagen">→</button>
                </div>

                <div class="thumbnails" role="list">
                    <?php foreach ($servicio['imagenes'] as $index => $img): ?>
                        <div class="thumbnail-item <?php echo $index === 0 ? 'active' : ''; ?>" role="listitem" onclick="selectImage(<?php echo $index; ?>)">
                            <div class="image-placeholder">
                                <span>📷</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Descripción del servicio -->
            <section class="service-description">
                <h2>Descripción</h2>
                <p><?php echo nl2br(htmlspecialchars($servicio['descripcion'])); ?></p>
            </section>

            <!-- Información del proveedor -->
            <aside class="service-info-section">
                <div class="service-card">
                    <div class="purchase-header">
                        <a href="pago.php?servicio_id=<?php echo $servicio['id']; ?>" class="buy-button">
                            Comprar ahora $<?php echo number_format($servicio['precio'], 0); ?>
                        </a>
                    </div>

                    <section class="provider-profile" aria-label="Información del proveedor">
                        <div class="provider-avatar"><?php echo htmlspecialchars($servicio['proveedor']['avatar']); ?></div>
                        <div class="provider-info">
                            <h3 class="provider-name"><?php echo htmlspecialchars($servicio['proveedor']['nombre']); ?></h3>
                            <div class="provider-rating">
                                <div class="stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="star <?php echo $i <= $servicio['proveedor']['estrellas'] ? 'filled' : ''; ?>">★</span>
                                    <?php endfor; ?>
                                </div>
                                <span class="rating-text">Estrellas</span>
                            </div>
                        </div>
                    </section>

                    <section class="service-details">
                        <dl>
                            <dt>Talento:</dt>
                            <dd><?php echo htmlspecialchars($servicio['proveedor']['talento']); ?></dd>
                            <dt>Lugar:</dt>
                            <dd><?php echo htmlspecialchars($servicio['proveedor']['ubicacion']); ?></dd>
                            <dt>Tiempo de entrega:</dt>
                            <dd><?php echo htmlspecialchars($servicio['tiempo_entrega']); ?></dd>
                            <dt>Categoría:</dt>
                            <dd><?php echo htmlspecialchars($servicio['categoria']); ?></dd>
                        </dl>
                    </section>

                    <div class="contact-section">
                        <?php if ($usuario_logueado): ?>
                            <a href="mensaje.php?nuevo=<?php echo $servicio['proveedor']['id']; ?>" class="contact-button">Contáctame</a>
                        <?php else: ?>
                            <a href="index.php?form=login" class="contact-button">Contáctame</a>
                        <?php endif; ?>
                    </div>
                </div>
            </aside>
        </article>

        <!-- Reseñas de clientes -->
        <section class="reviews-section" aria-label="Reseñas de clientes">
            <h2 class="reviews-title">Reseñas de Clientes</h2>
            <ul class="reviews-list">
                <?php foreach ($resenas as $resena): ?>
                    <h4 class="review-item">
                        <header class="review-header">
                            <div class="reviewer-info">
                                <div class="reviewer-avatar"><?php echo htmlspecialchars($resena['avatar']); ?></div>
                                <div class="reviewer-details">
                                    <h3 class="reviewer-name"><?php echo htmlspecialchars($resena['cliente']); ?></h3>
                                    <div class="review-rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="star <?php echo $i <= $resena['estrellas'] ? 'filled' : ''; ?>">★</span>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                            <time class="review-date" datetime="<?php echo $resena['fecha']; ?>">
                                <?php echo date('d/m/Y', strtotime($resena['fecha'])); ?>
                            </time>
                        </header>
                        <p class="review-content"><?php echo htmlspecialchars($resena['comentario']); ?></p>
                    </h4>
                <?php endforeach; ?>
            </ul>
        </section>
        
    </main>
</body>
</html>