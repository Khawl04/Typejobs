<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
body {
    background: #274c3d;
}
.orden-main {
    display: flex;
    justify-content: center;
    padding: 48px 0;
    color: #222;
}

.orden-resumen {
    min-width: 340px;
    max-width: 480px;
    background: #232a37;
    border-radius: 11px;
    box-shadow: 0 0 12px #0002;
    padding: 28px 34px 22px 34px;
}

.orden-resumen h2 {
    margin-top: 0;
    margin-bottom: 21px;
    font-size: 1.55em;
    color: #75e4a6;
}

.orden-status {
    display: flex;
    align-items: center;
    margin-bottom: 18px;
    color: #75e4a6;
    flex-wrap: wrap;
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
    color: #75e4a6;
}

.orden-seccion {
    margin: 5px 0 6px 0;
    color: #75e4a6;
}

.orden-resumen .total-value {
    font-size: 1.38em;
    color: #fff;
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
    color: #75e4a6;
}

/* ?? Responsive para móviles (hasta 480px) */
@media (max-width: 480px) {
    .orden-main {
        padding: 25px 10px;
    }

    .orden-resumen {
        width: 100%;
        min-width: auto;
        max-width: 100%;
        padding: 22px 18px;
        border-radius: 8px;
        box-shadow: none;
    }

    .orden-resumen h2 {
        font-size: 1.3em;
        text-align: center;
    }

    .orden-status {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .orden-status .icon {
        margin-right: 0;
        margin-bottom: 8px;
    }

    .orden-label, 
    .orden-seccion,
    .orden-agradecimiento {
        font-size: 0.95em;
        line-height: 1.4;
        text-align: left;
    }

    .orden-resumen .total-value {
        font-size: 1.2em;
    }

    .orden-acciones {
        flex-direction: column;
        gap: 10px;
        align-items: stretch;
    }

    .orden-acciones a,
    .orden-acciones button {
        width: 100%;
        text-align: center;
        font-size: 1em;
        padding: 11px;
    }

    .orden-agradecimiento {
        font-size: 0.95em;
        text-align: center;
        margin-top: 18px;
    }
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
            <a href="<?= BASE_URL ?>/mensaje?chat=<?= $reserva['id_proveedor'] ?>" class="primary">Contactar
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