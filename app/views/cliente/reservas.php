<?php
require_once '../config/config.php';
require_once '../app/models/Reserva.php';
$reservaModel = new Reserva();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Editar
    if (isset($_POST['id_reserva'])) {
        $reservaModel->update($_POST['id_reserva'], [
            'fecha_reserva' => $_POST['fecha_reserva'],
            'hora_inicio'   => $_POST['hora_inicio'],
            'hora_fin'      => $_POST['hora_fin'],
            'notas'         => $_POST['notas']
        ]);
        $_SESSION['success'] = "Reserva modificada correctamente.";
        header("Location: " . BASE_URL . "/cliente/reservas");
        exit;
    }
    // Eliminar
    if (isset($_POST['eliminar_reserva'])) {
        $reservaModel->delete($_POST['eliminar_reserva']);
        $_SESSION['success'] = "Reserva eliminada.";
        header("Location: " . BASE_URL . "/cliente/reservas");
        exit;
    }
}

// Ahora cargas tus reservas para mostrar la tabla
$reservas = $reservaModel->obtenerPorCliente($_SESSION['id_usuario']);
?>

<?php require_once __DIR__ . '/../layouts/header.php'; ?>


<style>
.reservas-panel {
    max-width: 880px;
    margin: 25px auto 35px auto;
    background: #fff;
    box-shadow: 0 2px 18px #e7ecf4;
    border-radius: 15px;
    padding: 28px 32px;
}

.reservas-panel h1 {
    font-size: 2.1rem;
    font-weight: 700;
    color: #24383c;
    margin-bottom: 18px;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 18px;
}

.table th {
    background: #e8eeff;
    color: #1a2930;
    font-weight: 600;
    padding: 13px 7px;
    border-bottom: 2px solid #d3def0;
}

.table td {
    padding: 11px 8px;
    font-size: 1rem;
    border-bottom: 1px solid #edf2fa;
}

.table tbody tr:nth-child(even) {
    background: #f6f8fc;
}

.estado-box {
    display: inline-block;
    padding: 2px 16px;
    font-weight: 600;
    font-size: 0.95rem;
    border-radius: 12px;
    background: #c2e4be;
    color: #256a33;
}

.estado-pendiente {
    background: #fbe6b1;
    color: #7c5312;
}

.estado-cancelada {
    background: #ffe4e2;
    color: #aa3a27;
}

.estado-finalizada {
    background: #d5e7fa;
    color: #305d89;
}

.table-actions {
    display: flex;
    gap: 12px;
}

.action-btn {
    padding: 6px 18px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-size: 0.97rem;
    background: #4f72e5;
    color: #fff;
    box-shadow: 0 2px 7px #e6ecf9;
    transition: background 0.12s;
}

.action-btn:hover {
    background: #2d528f;
}

.delete-btn {
    background: #e15555;
}

.delete-btn:hover {
    background: #bd1a1a;
}

.edit-btn {
    background: #fabd27;
    color: #312a1e;
}

.edit-btn:hover {
    background: #c99b14;
}

.no-reservas {
    text-align: center;
    color: #a49e8a;
    padding: 28px 0;
    font-size: 1.2rem;
}
</style>



<div class="reservas-panel">
    <h1>Mis Reservas</h1>
    <?php if (empty($reservas)): ?>
    <div class="no-reservas">
        No tienes reservas.<br>
        <a href="<?= BASE_URL ?>/servicio" style="color:#2b5bbb; text-decoration: underline;">Buscar servicios</a>
    </div>
    <?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>Servicio</th>
                <th>Proveedor</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Estado</th>
                <th style="width:175px">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservas as $reserva): ?>
            <tr>
                <td><?= htmlspecialchars($reserva['servicio_nombre'] ?? $reserva['servicio_titulo'] ?? '') ?></td>
                <td><?= htmlspecialchars($reserva['proveedor_nombre'] ?? '') ?></td>
                <td><?= htmlspecialchars(isset($reserva['fecha_reserva']) ? date('d/m/Y', strtotime($reserva['fecha_reserva'])) : '-') ?>
                </td>
                <td><?= htmlspecialchars($reserva['hora_inicio'] ?? '-') ?></td>
                <td>
                    <span class="estado-box estado-<?= strtolower($reserva['estado']) ?>">
                        <?= ucfirst($reserva['estado'] ?? '-') ?>
                    </span>
                </td>
                <td class="table-actions">
                    <button type="button" class="action-btn edit-btn"
                        onclick="mostrarEditor(<?= $reserva['id_reserva'] ?>)">Cambiar</button>
                    <form action="<?= BASE_URL ?>/cliente/reservas" method="post" style="display:inline;"
                        onsubmit="return confirm('¿Seguro que quieres eliminar esta reserva?');">
                        <input type="hidden" name="eliminar_reserva" value="<?= $reserva['id_reserva'] ?>">
                        <button type="submit" class="action-btn delete-btn">Eliminar</button>
                    </form>
                </td>
            </tr>
            <!-- Fila de edición (oculta por defecto) -->
            <tr id="editor-<?= $reserva['id_reserva'] ?>" class="editor-row" style="display:none;background:#fdf7ea;">
                <td colspan="6">
                    <form method="post" action="<?= BASE_URL ?>/cliente/reservas" class="edit-inline-form"

                        style="display:flex;gap:20px;align-items:center;">
                        <input type="hidden" name="id_reserva" value="<?= $reserva['id_reserva'] ?>">
                        <label>Fecha: <input type="date" name="fecha_reserva"
                                value="<?= substr($reserva['fecha_reserva'], 0, 10) ?>"></label>
                        <label>Inicio: <input type="time" name="hora_inicio"
                                value="<?= $reserva['hora_inicio'] ?>"></label>
                        <label>Fin: <input type="time" name="hora_fin" value="<?= $reserva['hora_fin'] ?>"></label>
                        <label>Notas: <input name="notas" value="<?= htmlspecialchars($reserva['notas'] ?? '') ?>"
                                style="width:120px;"></label>
                        <button type="submit" class="action-btn edit-btn">Guardar</button>
                        <button type="button" class="action-btn"
                            onclick="ocultarEditor(<?= $reserva['id_reserva'] ?>)">Cancelar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<script>
function mostrarEditor(id) {
    document.querySelectorAll('.editor-row').forEach(x=>x.style.display='none');
    document.getElementById('editor-'+id).style.display = 'table-row';
}
function ocultarEditor(id) {
    document.getElementById('editor-'+id).style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>