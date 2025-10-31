<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
.pagos-panel {
    max-width: 820px;
    margin: 28px auto 35px auto;
    background: #fff;
    box-shadow: 0 2px 18px #e7ecf4;
    border-radius: 15px;
    padding: 28px 32px;
}
.pagos-panel h1 {
    font-size: 2rem;
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
    color: #293640;
    font-weight: 600;
    padding: 13px 7px;
    border-bottom: 2px solid #d3def0;
    text-align: left;
}
.table td {
    padding: 11px 8px;
    font-size: 1rem;
    border-bottom: 1px solid #edf2fa;
    text-align: left;
}
.table tbody tr:nth-child(even) {
    background: #f7fafc;
}
.status-completado { background: #c0f8cf; color: #15843b; padding: 2px 18px; border-radius: 12px; font-weight:600;}
.status-pendiente { background: #ffe8b8; color: #ad7906; padding: 2px 18px; border-radius: 12px; font-weight:600;}
.status-cancelado { background: #ffe6e6; color: #c02d2d; padding: 2px 18px; border-radius: 12px; font-weight:600;}
.alert-warning {
    margin: 12px 0;
    font-size: 1.1rem;
}
.boton-orden {
    background: #47a0ff;
    color: #fff;
    border-radius: 7px;
    border: none;
    padding: 4px 14px;
    font-size: 0.95em;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.18s;
    text-decoration: none;
    display: inline-block;
}
.boton-orden:hover {
    background: #2281d3;
}
</style>

<div class="pagos-panel">
    <h1>Historial de Pagos</h1>
    <?php if (empty($pagos)): ?>
        <div class="alert alert-warning">
            No tienes pagos registrados.
        </div>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>Método</th>
                    <th>Estado</th>
                    <th>Ver orden</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($pagos as $pago): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars(date('d/m/Y', strtotime($pago['fecha_pago'] ?? $pago['fecha'] ?? '-'))) ?>
                    </td>
                    <td>
                        <b>$<?= number_format($pago['monto'], 2) ?></b>
                    </td>
                    <td>
                        <?= htmlspecialchars(ucfirst($pago['metodo'] ?? $pago['metodo_pago'] ?? '-')) ?>
                    </td>
                    <td>
                        <?php
                            $st = strtolower($pago['estado'] ?? '-');
                            $class = $st === 'completado' ? 'status-completado' : ($st === 'pendiente' ? 'status-pendiente' : 'status-cancelado');
                        ?>
                        <span class="<?= $class ?>">
                            <?= ucfirst($st) ?>
                        </span>
                    </td>
                    <td>
                        <?php if (!empty($pago['id_reserva'])): ?>
                            <a href="<?= BASE_URL ?>/pago/orden?id_reserva=<?= $pago['id_reserva'] ?>"
                               class="boton-orden btn btn-sm">
                                Ver orden
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
