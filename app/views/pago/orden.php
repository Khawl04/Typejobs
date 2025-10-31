<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
.orden-main {
    display: flex;
    justify-content: center;
    padding: 48px 0;
    color: #222;
}

.orden-resumen {
    min-width: 340px;
    max-width: 480px;
    background: #f1f4fb;
    border-radius: 11px;
    box-shadow: 0 0 12px #0002;
    padding: 28px 34px 22px 34px;
}

.orden-resumen h2 {
    margin-top: 0;
    margin-bottom: 21px;
    font-size: 1.55em;
    color: #3656d7;
}

.orden-status {
    display: flex;
    align-items: center;
    margin-bottom: 18px;
}

.orden-status .icon {
    font-size: 27px;
    color: #4bb543;
    background: #ddeedd;
    border-radius: 50%;
    width: 37px;
    height: 37px;
    text-align: center;
    line-height: 36px;
    margin-right: 12px;
}

.orden-label strong {
    color: #5661ab;
}

.orden-label {
    margin-bottom: 12px;
}

.orden-seccion {
    margin: 19px 0;
}

.orden-resumen .total-value {
    font-size: 1.38em;
    color: #294aaa;
    font-weight: bold;
}

.orden-acciones {
    margin-top: 28px;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.orden-acciones a,
.orden-acciones button {
    background: #3656d7;
    color: #fff;
    padding: 9px 17px;
    border-radius: 7px;
    border: none;
    text-decoration: none;
    font-size: 1em;
    cursor: pointer;
    display: inline-block;
    transition: background .2s;
}

.orden-acciones a.secondary,
.orden-acciones button.secondary {
    background: #eee;
    color: #3656d7;
    font-weight: 500;
}

.orden-agradecimiento {
    border-top: 1px solid #dcdeee;
    margin-top: 22px;
    padding-top: 17px;
    font-size: 1.05em;
}
</style>

<div class="orden-main">
    <div class="orden-resumen">
        <div class="orden-status">
            <?php if ($reserva['estado'] === 'confirmada'): ?>
            <span class="icon">✓</span>
            <span><b>¡Orden completada!</b></span>
            <?php else: ?>
            <span class="icon" style="color:#fd9a01;background:#fff2e2;">⏳</span>
            <span><b>Tu orden no está paga</b></span>
            <span class="alert alert-info">
                <b>Imprime el comprobante y preséntalo al proveedor.</b>
            </span>
            <?php endif; ?>
        </div>

        <div class="orden-label">
            <strong>ID de orden:</strong> #<?= str_pad($reserva['id_reserva'], 6, '0', STR_PAD_LEFT); ?><br>
            <strong>Proveedor:</strong>
            <?= htmlspecialchars($reserva['proveedor_nombre'] . ' ' . $reserva['proveedor_apellido']) ?><br>
            <strong>Fecha:</strong>
            <?php
$fecha = isset($reserva['fecha_reserva']) ? $reserva['fecha_reserva'] : '';
$hora = isset($reserva['hora_inicio']) ? $reserva['hora_inicio'] : '';

if ($fecha && $hora) {
    // Reemplaza la hora en fecha_reserva por hora_inicio
    $datePart = date('Y-m-d', strtotime($fecha));
    $datetime = $datePart . ' ' . $hora;

    if (strtotime($datetime) !== false) {
        echo date('d/m/Y H:i', strtotime($datetime));
    } else {
        echo 'Fecha/Hora inválida';
    }
} else {
    echo 'Fecha/Hora inválida';
}
?>
        </div>


        <div class="orden-seccion">
            <strong>Servicio:</strong><br>
            <?= htmlspecialchars($reserva['servicio_titulo']) ?>
            <div style="color:#666; margin:5px 0 6px 0;"><?= htmlspecialchars($reserva['servicio_descripcion']) ?></div>
        </div>

        <div class="orden-seccion">
            <strong>Total:</strong>
            <span class="total-value">$<?= number_format($reserva['servicio_precio'], 2) ?> UYU</span>
        </div>

        <div class="orden-acciones">
            <a href="<?= BASE_URL ?>/servicio" class="primary">Ir al inicio</a>
            <a href="#" onclick="window.print(); return false;" class="secondary">Imprimir</a>
            <?php if (isset($reserva['id_proveedor'])): ?>
            <a href="<?= BASE_URL ?>/mensaje.php?id=<?= $reserva['id_proveedor'] ?>" class="secondary">Contactar
                proveedor</a>
            <?php endif; ?>

        </div>

        <div class="orden-agradecimiento">
            <?php if ($reserva['estado'] === 'confirmada'): ?>
            <b>¡Gracias por confiar en TypeJobs!</b><br>
            Tu orden ha sido completada exitosamente.<br>
            Recibirás un email de confirmación con todos los detalles.
            <?php else: ?>
            <span style="color:#fd9a01;"><b>¡Atención!</b></span> Finaliza y paga tu orden para confirmarla.<br>
            Recibirás tu confirmación al completar el pago.
            <?php endif; ?>
        </div>
    </div>
</div>


<?php require_once __DIR__ . '/../layouts/footer.php'; ?>