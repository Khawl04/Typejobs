<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
.busqueda-filtros-wrapper {
    display: flex;
    justify-content: center;
    align-items: flex-end;
    gap: 22px;
    margin-bottom: 36px;
    margin-top: 16px;
}
.busqueda-form {
    flex: 1;
    max-width: 430px;
    display: flex;
    justify-content: center;
}
.busqueda-form input[type="text"] {
    width: 100%;
    padding: 13px 18px;
    border: 1px solid #bdbdbd;
    border-radius: 8px;
    font-size: 1.03em;
    font-family: inherit;
    background: #f6f8f6;
}

.servicios-page {
    min-height: 80vh;
    padding: 40px 20px;
    background-color: #f5f5f5;
}
.page-header {
    text-align: center;
    margin-bottom: 40px;
}
.page-header h1 {
    color: #5a7355;
    font-size: 36px;
    margin-bottom: 10px;
}
.page-header p {
    color: #666;
    font-size: 18px;
}
.servicios-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 24px;
    max-width: 1200px;
    margin: 0 auto;
}
.servicio-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.servicio-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
}
.card-image {
    width: 100%;
    height: 200px;
    background: linear-gradient(135deg, #5a7355 0%, #7a9475 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    color: white;
}
.card-body {
    padding: 20px;
}
.card-title {
    font-size: 20px;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}
.card-description {
    font-size: 14px;
    color: #666;
    line-height: 1.6;
    margin-bottom: 15px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    border-top: 1px solid #eee;
}
.card-price {
    font-size: 18px;
    font-weight: 700;
    color: #5a7355;
}
.card-category {
    font-size: 12px;
    padding: 4px 12px;
    background-color: #e8e4d9;
    color: #5a5447;
    border-radius: 20px;
    font-weight: 500;
}
.btn-ver-mas {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 16px;
    background-color: #5a7355;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 14px;
    transition: background-color 0.3s ease;
    text-align: center;
}
.btn-ver-mas:hover {
    background-color: #3d4a38;
}
@media (max-width: 768px) {
    .servicios-grid {
        grid-template-columns: 1fr;
    }
    .busqueda-filtros-wrapper {
        flex-direction: column;
        gap: 15px;
    }
}
</style>

<main class="servicios-page">
    <div class="page-header">
        <h1>Servicios Disponibles</h1>
        <p>Encuentra el profesional que necesitas para tu proyecto</p>
    </div>

    <!-- Buscador y filtro único -->
    <form class="busqueda-filtros-wrapper" method="get" action="">
        <input 
            type="text" 
            name="busqueda" 
            placeholder="Buscar servicios, categorías, descripciones..." 
            value="<?= htmlspecialchars($_GET['busqueda'] ?? '') ?>"
            style="width:50%; padding:14px 18px; border-radius:8px; border:1px solid #bdbdbd; font-size:1.1em;"
        >
        <select name="orden" id="orden" style="padding:12px 14px; border-radius:8px; font-size:1.05em;" onchange="this.form.submit()">
            <option value="relevancia" <?= (($_GET['orden'] ?? 'relevancia') === 'relevancia') ? 'selected' : '' ?>>Relevancia</option>
            <option value="precio_asc" <?= (($_GET['orden'] ?? '') === 'precio_asc') ? 'selected' : '' ?>>Precio: Menor a Mayor</option>
            <option value="precio_desc" <?= (($_GET['orden'] ?? '') === 'precio_desc') ? 'selected' : '' ?>>Precio: Mayor a Menor</option>
            <option value="estrellas_desc" <?= (($_GET['orden'] ?? '') === 'estrellas_desc') ? 'selected' : '' ?>>Calificación</option>
        </select>
    </form>

    <div class="servicios-grid">
        <?php foreach ($servicios as $serv): ?>
        <div class="servicio-card">
            <div class="card-image">
                <?php if (!empty($serv['imagen_servicio'])): ?>
                <img src="<?= BASE_URL . '/' . htmlspecialchars($serv['imagen_servicio']) ?>" alt="Imagen"
                    style="width:100%;height:200px;object-fit:cover;">
                <?php else: ?>
                <span style="font-size:48px;">💼</span>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <h3 class="card-title"><?= htmlspecialchars($serv['titulo']) ?></h3>
                <p class="card-description"><?= htmlspecialchars($serv['descripcion']) ?></p>
                <div class="card-footer">
                    <span class="card-price">$<?= number_format($serv['precio'], 0) ?></span>
                    <span class="card-category"><?= htmlspecialchars($serv['categoria']) ?></span>
                </div>
                <a href="<?= BASE_URL ?>/servicio/detalle?id=<?= $serv['id_servicio'] ?>" class="btn-ver-mas">Ver detalles</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
