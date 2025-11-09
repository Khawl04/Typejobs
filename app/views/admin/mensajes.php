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
    background: #0891b2;
    box-shadow: 0 1px 7px #36ad6e19;
    transition: background .19s, transform .15s;
    cursor: pointer;
}
.btn:hover,
a.btn:hover {
    background: #0e7490;
    color: #fff !important;
    transform: translateY(-3px) scale(1.03);
}
.btn-danger {
    background: #dc2626 !important;
    color: #fff !important;
}
.btn-danger:hover {
    background: #b91c1c !important;
}
.btn-small {
    padding: 7px 14px;
    font-size: 0.9em;
    margin-right: 6px;
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
.badge-leido {
    background: #219866;
    color: #e1fcef;
    padding: 5px 11px;
    border-radius: 5px;
    font-size: 0.89em;
    font-weight: bold;
}
.badge-no {
    background: #dc2626;
    color: #fff;
    padding: 5px 11px;
    border-radius: 5px;
    font-size: 0.89em;
    font-weight: bold;
}
.btn-secondary {
    background: #308159 !important;
    color: #eefffc !important;
    font-weight: 700;
    display: inline-block;
    border-radius: 8px;
    text-decoration: none !important;
    border: none;
    font-size: 1em;
    outline: none;
    padding: 10px 21px;
    margin-bottom: 4px;
    box-shadow: 0 1px 7px #36ad6e19;
    transition: background .19s, transform .15s;
    cursor: pointer;
}

.btn-secondary:hover {
    background: #266a49 !important;
}
@media (max-width: 768px) {
    .table { font-size: 0.9em; }
    .btn { padding: 8px 16px; font-size: 0.95em; }
    .admin-title { font-size: 1.8em; }
    .mb-3 {margin-bottom: 26px; }
}
</style>

<div class="container-admin">
    <h1 class="admin-title">Gestionar Mensajes</h1>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?= $_SESSION['tipo_mensaje'] ?? 'success' ?>">
            <?= $_SESSION['mensaje'] ?>
        </div>
        <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
    <?php endif; ?>

    <div class="card">
        <h2>Listado de Mensajes</h2>
        <?php if (!empty($data['mensajes'])): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Remitente</th>
                        <th>Destinatario</th>
                        <th>Mensaje</th>
                        <th>Archivo</th>
                        <th>Leído</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['mensajes'] as $mensaje): ?>
                    <tr>
                        <td><?= $mensaje['id_mensaje'] ?></td>
                        <td><?= $mensaje['id_usuario'] ?></td>
                        <td><?= $mensaje['id_usuario_dest'] ?></td>
                        <td><?= htmlspecialchars($mensaje['contenido']) ?></td>
                        <td>
                            <?php if (!empty($mensaje['archivo_adjunto'])): ?>
                                <a href="<?= BASE_URL ?>/<?= $mensaje['archivo_adjunto'] ?>" target="_blank" class="btn btn-small">Ver</a>
                            <?php else: ?>
                                Sin archivo
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($mensaje['leido']): ?>
                                <span class="badge-leido">Leído</span>
                            <?php else: ?>
                                <span class="badge-no">No leído</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($mensaje['fecha_envio'])) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>/mensaje?chat=<?= $mensaje['id_usuario_dest'] ?>" class="btn btn-small">Ver chat</a>
                            <form method="POST" action="<?= BASE_URL ?>/admin/mensajes/eliminar" style="display:inline;">
                                <input type="hidden" name="id_mensaje" value="<?= $mensaje['id_mensaje'] ?>">
                                <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('¿Eliminar este mensaje?');">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p style="color: #e1fcef; text-align: center; padding: 20px;">No hay mensajes registrados.</p>
        <?php endif; ?>
    </div>

    <div class="mb-3" style="margin-top: 30px;">
        <a href="<?= BASE_URL ?>/admin" class="btn btn-secondary">Volver al Panel</a>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
