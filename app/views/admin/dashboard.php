<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #274c3d;
    min-height: 100vh;
    margin: 0;
}

.container-admin {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 14px 40px 14px;
    width: 100%;
}

.admin-title {
    background: #232a37ed;
    text-align: center;
    color: #3cfd9e;
    padding: 22px 0 15px 0;
    margin: 0 0 28px 0;
    letter-spacing: 1.3px;
    font-weight: 900;
    font-size: 2.3em;
    border-radius: 11px 11px 0 0;
    box-shadow: 0 5px 22px #0002;
    position: relative;
    z-index: 2;
}

.grid {
    display: grid;
    gap: 32px;
}

.grid-4 {
    grid-template-columns: repeat(4, 1fr);
}

.card {
    background: #232a37ed;
    border-radius: 15px;
    box-shadow: 0 4px 20px #0001;
    border: 1.5px solid #21986644;
    padding: 25px 18px 16px 22px;
    min-height: 120px;
    transition: box-shadow .15s, border .19s;
}

.card:hover {
    box-shadow: 0 8px 36px #48d7a74a, 0 2px 8px #0003;
    border-color: #3cfd9e;
}

.card h2,
.card h3 {
    margin-top: 0;
    font-weight: 700;
    letter-spacing: .06em;
}

.card h2 {
    color: #48ecb2;
    font-size: 1.21em;
    margin-bottom: 14px;
}

.card h3 {
    color: #67cf8b;
    font-size: 1.09em;
    margin-bottom: 14px;
}

.card p {
    color: #e1fcef;
    text-shadow: 0 2px 18px #2ad08419, 0 1.5px 4px #0003;
    font-variant-numeric: tabular-nums;
    font-size: 2.3em;
    font-weight: 900;
    margin: 0;
}

.d-flex,
.actions-quick {
    display: flex;
    flex-wrap: wrap;
    gap: 13px 17px;
    margin-bottom: 2px;
}

.btn,
a.btn {
    display: inline-block;
    border-radius: 8px;
    font-weight: 700;
    text-decoration: none !important;
    border: none;
    font-size: 1em;
    outline: none;
    padding: 10px 21px;
    margin-bottom: 4px;
    color: #fff !important;
    background: #2563eb;
    box-shadow: 0 1px 7px #36ad6e19;
    transition: background .19s, transform .15s;
}

.btn:hover,
a.btn:hover {
    background: #1e4db8;
    color: #fff !important;
    transform: translateY(-3px) scale(1.03);
}

.btn-warning {
    background: #fdad3b !important;
    color: #ab4c08 !important;
    font-weight: 800;
}

.btn-secondary {
    background: #308159 !important;
    color: #eefffc !important;
    font-weight: 700;
}

@media (max-width: 768px) {
    .grid-4 {
        grid-template-columns: 1fr;
    }
    .container-admin {
        padding: 0 9px 24px 9px;
    }
    .admin-title {
        font-size: 1.45em;
        padding: 13px 0 12px 0;
        margin-bottom: 13px;
        border-radius: 12px 12px 0 0;
    }
    .card {
        box-shadow: 0 1px 6px #0001;
        border-width: 1px;
        padding: 17px 12px 10px 13px;
        font-size: .98em;
        margin-bottom: 14px;
    }
    .card h3, .card h2 { font-size: 1.06em; }
    .d-flex, .actions-quick { 
        gap: 10px 6px;
        flex-wrap: wrap;
    }
    .btn, a.btn {
        font-size: 0.99em;
        padding: 9px 10px;
        margin: 2px 0;
        min-width: 120px;
    }
}
</style>

<div class="container-admin">
    <h1 class="admin-title">Panel de Administración</h1>
    <div class="grid grid-4" style="margin-top:0;">
        <div class="card">
            <h3>Usuarios Totales</h3>
            <p data-metric><?= $data['totalUsuarios'] ?? 0 ?></p>
        </div>
        <div class="card">
            <h3>Servicios Activos</h3>
            <p data-metric><?= $data['totalServicios'] ?? 0 ?></p>
        </div>
        <div class="card">
            <h3>Categorías</h3>
            <p data-metric><?= $data['totalCategorias'] ?? 0 ?></p>
        </div>
        <div class="card">
            <h3>Mensajes</h3>
            <p data-metric><?= $data['totalMensajes'] ?? 0 ?></p>
        </div>
    </div>

    <div class="card" style="margin-top:39px;">
        <h2>Gestión</h2>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>/admin/usuarios" class="btn btn-primary">Gestionar Usuarios</a>
            <a href="<?= BASE_URL ?>/admin/servicios" class="btn btn-primary">Gestionar Servicios</a>
            <a href="<?= BASE_URL ?>/admin/categorias" class="btn btn-primary">Gestionar Categorías</a>
            <a href="<?= BASE_URL ?>/admin/mensajes" class="btn btn-primary">Gestionar Mensajes</a>
            <a href="<?= BASE_URL ?>/admin/perfil" class="btn btn-primary">Editar Perfil</a>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>