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

.card {
    background: #232a37ed;
    border-radius: 15px;
    box-shadow: 0 4px 20px #0001;
    border: 1.5px solid #21986644;
    padding: 25px 22px 20px 22px;
    margin-bottom: 28px;
    transition: box-shadow .15s, border .19s;
}

.card:hover {
    box-shadow: 0 8px 36px #48d7a74a, 0 2px 8px #0003;
    border-color: #3cfd9e;
}

.card h2 {
    margin-top: 0;
    font-weight: 700;
    letter-spacing: .06em;
    color: #48ecb2;
    font-size: 1.5em;
    margin-bottom: 20px;
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
    cursor: pointer;
}

.btn:hover,
a.btn:hover {
    background: #1e4db8;
    color: #fff !important;
    transform: translateY(-3px) scale(1.03);
}

.btn-success {
    background: #219866 !important;
    color: #e1fcef !important;
}

.btn-success:hover {
    background: #1a7a51 !important;
}

.btn-warning {
    background: #fdad3b !important;
    color: #ab4c08 !important;
    font-weight: 800;
}

.btn-warning:hover {
    background: #e99a2a !important;
}

.btn-danger {
    background: #dc2626 !important;
    color: #fff !important;
}

.btn-danger:hover {
    background: #b91c1c !important;
}

.btn-secondary {
    background: #308159 !important;
    color: #eefffc !important;
    font-weight: 700;
}

.btn-secondary:hover {
    background: #266a49 !important;
}

.btn-small {
    padding: 7px 14px;
    font-size: 0.9em;
    margin-right: 6px;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    color: #48ecb2;
    font-weight: 700;
    margin-bottom: 8px;
    font-size: 1.05em;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 1.5px solid #21986644;
    border-radius: 8px;
    font-size: 1em;
    background: #1a212ced;
    color: #e1fcef;
    outline: none;
    transition: border .19s, box-shadow .19s;
    box-sizing: border-box;
}

.form-control:focus {
    border-color: #3cfd9e;
    box-shadow: 0 0 0 3px #48d7a733;
}

.table-responsive {
    overflow-x: auto;
    margin-top: 20px;
}

.table {
    width: 100%;
    border-collapse: collapse;
    color: #e1fcef;
}

.table thead {
    background: #1a212ced;
}

.table thead th {
    padding: 14px 12px;
    text-align: left;
    font-weight: 700;
    color: #48ecb2;
    border-bottom: 2px solid #21986644;
    font-size: 1.05em;
}

.table tbody tr {
    border-bottom: 1px solid #21986633;
    transition: background .15s;
}

.table tbody tr:hover {
    background: #1a212c88;
}

.table tbody td {
    padding: 14px 12px;
    color: #e1fcef;
}

.alert {
    padding: 14px 18px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-weight: 600;
}

.alert-success {
    background: #21986644;
    border: 1.5px solid #3cfd9e;
    color: #3cfd9e;
}

.alert-danger {
    background: #dc262644;
    border: 1.5px solid #f87171;
    color: #fca5a5;
}

.d-flex {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
}

.mb-3 {
    margin-bottom: 20px;
}

.form-inline {
    display: inline;
}

.badge-success {
    background: #219866;
    color: #e1fcef;
}

.badge-danger {
    background: #dc2626;
    color: #fff;
}

@media (max-width: 768px) {
    .table {
        font-size: 0.9em;
    }

    .btn {
        padding: 8px 16px;
        font-size: 0.95em;
    }

    .admin-title {
        font-size: 1.8em;
    }
}
</style>

<div class="container-admin">
    <h1 class="admin-title">Gestionar Categorías</h1>

    <?php if (isset($_SESSION['mensaje'])): ?>
    <div class="alert alert-<?= $_SESSION['tipo_mensaje'] ?? 'success' ?>">
        <?= $_SESSION['mensaje'] ?>
    </div>
    <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
    <?php endif; ?>

    <!-- Formulario para crear/editar categoría -->
    <div class="card">
        <h2><?= isset($data['categoriaEditar']) ? 'Editar Categoría' : 'Nueva Categoría' ?></h2>
        <form method="POST"
            action="<?= BASE_URL ?>/admin/categorias/<?= isset($data['categoriaEditar']) ? 'actualizar' : 'crear' ?>">
            <?php if (isset($data['categoriaEditar'])): ?>
            <input type="hidden" name="id" value="<?= $data['categoriaEditar']['id_categoria'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="nombre">Nombre de la Categoría *</label>
                <input type="text" class="form-control" id="nombre" name="nombre"
                    value="<?= htmlspecialchars($data['categoriaEditar']['nombre_categoria'] ?? '') ?>" required
                    placeholder="Ej: Tecnología, Salud, Educación...">
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                    placeholder="Descripción opcional de la categoría"><?= htmlspecialchars($data['categoriaEditar']['descripcion'] ?? '') ?></textarea>
            </div>

            <div class="d-flex">
                <button type="submit" class="btn btn-success">
                    <?= isset($data['categoriaEditar']) ? 'Actualizar Categoría' : 'Crear Categoría' ?>
                </button>
                <?php if (isset($data['categoriaEditar'])): ?>
                <a href="<?= BASE_URL ?>/admin/categorias" class="btn btn-secondary">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Listado de categorías -->
    <div class="card">
        <h2>Listado de Categorías</h2>

        <?php if (!empty($data['categorias'])): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Servicios</th>
                        <th>Fecha Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['categorias'] as $categoria): ?>
                    <tr>
                        <td><?= $categoria['id_categoria'] ?></td>
                        <td><strong><?= htmlspecialchars($categoria['nombre_categoria']) ?></strong></td>
                        <td><?= htmlspecialchars(substr($categoria['descripcion'] ?? '', 0, 50)) ?><?= strlen($categoria['descripcion'] ?? '') > 50 ? '...' : '' ?>
                        </td>
                        <td>
                            <?php if (($categoria['estado'] ?? 1) == 1): ?>
                            <span class="badge badge-success">Activo</span>
                            <?php else: ?>
                            <span class="badge badge-danger">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $categoria['total_servicios'] ?? 0 ?></td>
                        <td><?= date('d/m/Y', strtotime($categoria['fecha_creacion'])) ?></td>
                        <td>
                            <form method="GET" action="<?= BASE_URL ?>/admin/categorias" style="display: inline-block;">
                                <input type="hidden" name="action" value="editar">
                                <input type="hidden" name="id" value="<?= $categoria['id_categoria'] ?>">
                                <button type="submit" class="btn btn-warning btn-small">Editar</button>
                            </form>

                            <?php if (($categoria['total_servicios'] ?? 0) == 0): ?>
                            <form method="POST" action="<?= BASE_URL ?>/admin/categorias/eliminar"
                                style="display: inline-block;">
                                <input type="hidden" name="id_categoria" value="<?= $categoria['id_categoria'] ?>">
                                <button type="submit" class="btn btn-danger btn-small"
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar esta categoría? Esta acción no se puede deshacer.')">Eliminar</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p style="color: #e1fcef; text-align: center; padding: 20px;">No hay categorías registradas.</p>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <a href="<?= BASE_URL ?>/admin" class="btn btn-secondary">Volver al Panel</a>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>