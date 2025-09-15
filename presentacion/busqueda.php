<?php
// Búsqueda básica de servicios
session_start();

// Obtener parámetros de búsqueda
$termino = $_GET['q'] ?? '';
$categoria = $_GET['categoria'] ?? '';
$orden = $_GET['orden'] ?? 'relevancia';

// Datos de servicios simulados
$servicios = [
    ['id' => 1, 'tipo' => 'Desarrollo Web', 'titulo' => 'Sitio Web Profesional', 'precio' => 750, 'estrellas' => 5],
    ['id' => 2, 'tipo' => 'Diseño Gráfico', 'titulo' => 'Logo y Branding', 'precio' => 350, 'estrellas' => 4],
    ['id' => 3, 'tipo' => 'Marketing Digital', 'titulo' => 'Campaña SEO', 'precio' => 500, 'estrellas' => 5],
    ['id' => 4, 'tipo' => 'Redacción', 'titulo' => 'Content Marketing', 'precio' => 200, 'estrellas' => 4],
    ['id' => 5, 'tipo' => 'Desarrollo Móvil', 'titulo' => 'App iOS y Android', 'precio' => 1200, 'estrellas' => 5],
    ['id' => 6, 'tipo' => 'Fotografía', 'titulo' => 'Sesión Fotográfica', 'precio' => 300, 'estrellas' => 4],
    ['id' => 7, 'tipo' => 'Traducción', 'titulo' => 'Traducción Profesional', 'precio' => 150, 'estrellas' => 5],
    ['id' => 8, 'tipo' => 'Consultoría', 'titulo' => 'Consultoría Empresarial', 'precio' => 800, 'estrellas' => 4]
];

// Filtrar servicios
$resultados = $servicios;
if (!empty($termino)) {
    $resultados = array_filter($resultados, function($s) use ($termino) {
        return offset($s['titulo'], $termino) !== false || offset($s['tipo'], $termino) !== false;
    });
}

// Ordenar resultados
if ($orden === 'precio_asc') {
    usort($resultados, fn($a, $b) => $a['precio'] - $b['precio']);
} elseif ($orden === 'precio_desc') {
    usort($resultados, fn($a, $b) => $b['precio'] - $a['precio']);
} elseif ($orden === 'estrellas_desc') {
    usort($resultados, fn($a, $b) => $b['estrellas'] - $a['estrellas']);
}

$total = count($resultados);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Servicios - TypeJobs</title>
    <link rel="stylesheet" href="../estilos/stylebusqueda.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><strong>TypeJobs</strong></li>
                <li><a href="inicio.php">Inicio</a></li>
                <li style="margin-left:auto;"><a href="index.php">Iniciar sesión</a></li>
                <li><a href="index.php?form=registro&accion=logout">Registrarse</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="search-header">
            <header class="search-info">
                <h1>
                    <?php if (!empty($termino)): ?>
                        Resultados para "<?php echo htmlspecialchars($termino); ?>"
                    <?php else: ?>
                        Servicios Disponibles
                    <?php endif; ?>
                </h1>
                <p class="results-count"><?php echo $total; ?> servicios encontrados</p>
            </header>
            
            <form method="GET" class="sort-form">
                <?php if (!empty($termino)): ?>
                    <input type="hidden" name="q" value="<?php echo htmlspecialchars($termino); ?>">
                <?php endif; ?>
                <label for="orden">Ordenar por:</label>
                <select name="orden" id="orden" onchange="this.form.submit()">
                    <option value="relevancia" <?php echo $orden === 'relevancia' ? 'selected' : ''; ?>>Relevancia</option>
                    <option value="precio_asc" <?php echo $orden === 'precio_asc' ? 'selected' : ''; ?>>Precio: Menor a Mayor</option>
                    <option value="precio_desc" <?php echo $orden === 'precio_desc' ? 'selected' : ''; ?>>Precio: Mayor a Menor</option>
                    <option value="estrellas_desc" <?php echo $orden === 'estrellas_desc' ? 'selected' : ''; ?>>Calificación</option>
                </select>
                <noscript>
                    <button type="submit">Ordenar</button>
                </noscript>
            </form>
        </section>
            <section class="services">
                <?php foreach ($resultados as $servicio): ?>
                    <article class="service">
                        <a href="servicio.php?id=<?php echo $servicio['id']; ?>">
                            <div class="service-image">
                                Imagen servicio
                            </div>
                            
                            <div class="service-content">
                                <div class="service-type"><?php echo htmlspecialchars($servicio['tipo']); ?></div>
                                <h2 class="service-title"><?php echo htmlspecialchars($servicio['titulo']); ?></h2>
                                
                                <footer class="service-footer">
                                    <div class="service-price">$<?php echo number_format($servicio['precio'],0); ?></div>
                                    <div class="service-rating">
                                        <div class="stars">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span class="star <?php echo $i <= $servicio['estrellas'] ? 'filled' : ''; ?>">★</span>
                                            <?php endfor; ?>
                                        </div>
                                        <div class="rating-label">ESTRELLAS</div>
                                    </div>
                                </footer>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </section>
    </main>
</body>
</html>
