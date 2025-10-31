<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
.reservas-panel-admin {
    max-width: 900px;
    margin: 30px auto 35px auto;
    background: #fff;
    box-shadow: 0 2px 18px #e7ecf4;
    border-radius: 15px;
    padding: 30px 32px;
}

.reservas-panel-admin h1 {
    font-size: 2.05rem;
    font-weight: 700;
    color: #293640;
    margin-bottom: 18px;
}

.reservas-panel-admin .card {
    background: #f7fafc;
    border-radius: 14px;
    box-shadow: 0 2px 11px #e8eefd;
    padding: 10px 6px;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 18px;
    background: none;
}

.table th {
    background: #e8eeff;
    color: #24383c;
    font-weight: 600;
    padding: 13px 6px;
    border-bottom: 2px solid #d3def0;
    text-align: left;
    font-size: 1.05em;
}

.table td {
    padding: 11px 7px;
    font-size: 1em;
    border-bottom: 1px solid #edf2fa;
    text-align: left;
}

.table tbody tr:nth-child(even) {
    background: #f5f8fd;
}

.table tbody tr:nth-child(odd) {
    background: #fff;
}

.btn-info {
    background: #47a0ff;
    color: #fff;
    border-radius: 5px;
    font-size: 0.96em;
    padding: 5px 14px;
    border: none;
}

.btn-danger {
    background: #ef4f4f;
    color: #fff;
    border-radius: 5px;
    font-size: 0.96em;
    padding: 5px 14px;
    border: none;
}

.btn-info:hover {
    background: #2281d3;
}

.btn-danger:hover {
    background: #b61c1c;
}

.alert-info {
    background: #e3f2ff;
    color: #298dce;
    font-size: 1.09em;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 18px;
}

.estado-span {
    font-weight: bold;
    border-radius: 13px;
    padding: 3px 18px;
    font-size: 1em;
    display: inline-block;
}

.estado-pendiente {
    background: #fbe6b1;
    color: #7c5312;
}

.estado-finalizada {
    background: #c0f8cf;
    color: #15843b;
}

.estado-cancelada {
    background: #ffe6e6;
    color: #bd1a1a;
}
</style>

<div class="reservas-panel-admin">
    <h1>Reservas Recientes</h1>
    <?php if (!empty($data['reservas'])): ?>
    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Servicio</th>
                    <th>Cliente</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['reservas'] as $i => $reserva): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($reserva['servicio_titulo'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($reserva['cliente_nombre'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($reserva['cliente_telefono'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($reserva['cliente_email'] ?? '-') ?></td>
                    <td>
                        <?= date('d/m/Y', strtotime($reserva['fecha_reserva'])) ?>
                        <?= date('H:i', strtotime($reserva['hora_inicio'])) ?>
                    </td>
                    <td>
                        <?php
                            $estado = $reserva['estado'];
                            $estado_class = ($estado == 'pendiente') ? 'estado-pendiente' : (($estado == 'cancelada') ? 'estado-cancelada' : 'estado-finalizada');
                        ?>
                        <span class="estado-span <?= $estado_class ?>">
                            <?= ucfirst($estado) ?>
                        </span>
                    </td>
                    <td>
                        <div style="display:flex; gap:8px;">
                            <a href="<?= BASE_URL ?>/reserva/<?= $reserva['id_reserva'] ?>"
                                class="btn btn-info btn-sm">Ver</a>
                            <?php if ($estado == 'pendiente'): ?>
                            <a href="<?= BASE_URL ?>/reserva/cancelar/<?= $reserva['id_reserva'] ?>"
                                class="btn btn-danger btn-sm">Cancelar</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="alert alert-info mt-3">
        No tienes reservas recientes.
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>