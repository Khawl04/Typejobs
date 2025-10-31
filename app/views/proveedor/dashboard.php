<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<style>
.dashboard-proveedor {
    display: flex;
    justify-content: flex-start;
    gap: 36px;
    margin-top: 32px;
}

.perfil-card {
    min-width: 320px;
    max-width: 340px;
    background: #f3f5fa;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(40, 40, 60, 0.12);
    padding: 28px 24px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.perfil-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #d5dae0;
    font-size: 2rem;
    color: #666;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-bottom: 8px;
}

.perfil-nombre {
    font-size: 1.33rem;
    font-weight: bold;
    margin-bottom: 2px;
}

.perfil-profesion {
    color: #555;
    font-size: 1.05rem;
    margin-bottom: 12px;
}

.perfil-estrellas {
    color: #ffc107;
    font-size: 1.3em;
    margin-bottom: 3px;
}

.perfil-seccion {
    margin-bottom: 7px;
}

.perfil-seccion strong {
    display: block;
    color: #222;
    font-weight: 500;
    margin-bottom: 2px;
}

.perfil-seccion {
    color: #3a4367;
    font-size: 1rem;
}

.panel-opciones {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.panel-botones {
    display: flex;
    gap: 13px;
    margin-bottom: 14px;
}

.panel-botones .btn {
    background: #10b981;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 12px 18px;
    font-size: 1.02rem;
    cursor: pointer;
    text-decoration: none;
    box-shadow: 0 1px 6px rgba(40, 40, 60, 0.08);
    transition: 0.18s;
}

.panel-botones .btn-success {
    background: #10b981;
}

.panel-botones .btn-mensajes {
    background: #10b981;
    color: #ffffffff;
}

.panel-botones .btn:hover {
    opacity: 0.87;
}

.panel-info {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 1px 6px rgba(40, 40, 60, 0.08);
    margin-bottom: 12px;
    padding: 22px 20px;
    display: flex;
    justify-content: space-between;
    gap: 24px;
}

.panel-info-item {
    flex: 1;
    text-align: center;
    border-right: 1px solid #e4e4e4;
}

.panel-info-item:last-child {
    border-right: none;
}

.panel-info-item-label {
    font-weight: 600;
    color: #697297;
}

.panel-info-item-value {
    font-size: 2.2rem;
    font-weight: bold;
    margin-top: 7px;
    margin-bottom: 2px;
    color: #2563eb;
}
.mis-servicios-panel {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.07);
    margin: 2em auto;
    max-width: 530px;
    padding: 1.5em;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.mis-servicios-panel h2 {
    font-size: 1.28em;
    margin-bottom:10px;
    color:#345675;
    font-weight:600;
}
.servicios-list {
    padding: 0;
    margin: 0;
    list-style: none;
}
.servicios-list li {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #f6f8fa;
    border-radius: 8px;
    margin-bottom: 10px;
    padding: 10px 16px;
    transition: background 0.2s;
}
.servicios-list li:hover {
    background: #eaf1fa;
}
.servicios-list span {
    font-size: 1em;
    color: #23293b;
    font-weight: 500;
}
.btn-danger.btn-sm {
    padding: 5px 14px;
    font-size: 0.97em;
    border-radius: 6px;
    background: #ef4444;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: box-shadow 0.1s, background 0.2s;
    margin-left:12px;
}
.btn-danger.btn-sm:hover {
    background: #b91c1c;
    box-shadow: 0 2px 6px rgba(239,68,68,0.17);
}
</style>

<?php
$calificacion = floatval($proveedor['calificacion_promedio'] ?? 0); // e.g., 4.3
$estrellasMax = 5;
$estrellasLlenas = floor($calificacion);
$hayMedia = ($calificacion - $estrellasLlenas) >= 0.25; // muestra media si >0.25
?>

<div class="dashboard-proveedor">

    <!-- Card lateral perfil -->
    <div class="perfil-card">
        <?php if (!empty($proveedor['foto_perfil'])): ?>
        <img class="perfil-avatar"
            src="<?= BASE_URL ?>/uploads/perfiles/<?= htmlspecialchars($proveedor['foto_perfil']) ?>"
            alt="Foto de perfil" style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin-bottom:8px;">
        <?php else: ?>
        <div class="perfil-avatar">
            <?= strtoupper(mb_substr($proveedor['nombre'] ?? $proveedor['nomusuario'] ?? '', 0, 1)) ?>
        </div>
        <?php endif; ?>

        <div class="perfil-nombre">
            <?= htmlspecialchars($proveedor['nombre'] ?? '') ?> <?= htmlspecialchars($proveedor['apellido'] ?? '') ?>
        </div>
        <div class="perfil-estrellas">
            <?php for ($i = 0; $i < $estrellasLlenas; $i++): ?>
            <span style="color:#ffc107;font-size:1.2em;">★</span>
            <?php endfor; ?>
            <?php if ($hayMedia): ?>
            <span style="color:#ffc107;font-size:1.2em;">☆</span>
            <?php endif; ?>
            <?php for ($i = $estrellasLlenas + $hayMedia; $i < $estrellasMax; $i++): ?>
            <span style="color:#eee;font-size:1.2em;">★</span>
            <?php endfor; ?>
            <span style="color:#555;font-size:0.86em;margin-left:6px;">
                <?= number_format($calificacion, 2) ?>/5
            </span>
        </div>
        <div class="perfil-seccion"><strong>Descripción</strong>
            <?= htmlspecialchars($proveedor['descripcion'] ?? 'Sin descripción aún.') ?>
        </div>
        <div class="perfil-seccion"><strong>Dirección</strong>
            <?= htmlspecialchars($proveedor['direccion'] ?? '') ?>
        </div>
    </div>

    <div class="panel-opciones">
        <!-- Panel info resumen -->
        <div class="panel-info">
            <div class="panel-info-item">
                <div class="panel-info-item-label">Servicios</div>
                <div class="panel-info-item-value"><?= $totalServicios ?? 0 ?></div>
            </div>
            <div class="panel-info-item">
                <div class="panel-info-item-label">Reservas Pendientes</div>
                <div class="panel-info-item-value" style="color: #f59e0b;"><?= $reservasPendientes ?? 0 ?></div>
            </div>
            <div class="panel-info-item">
                <div class="panel-info-item-label">Mensajes no leidos</div>
                <div class="panel-info-item-value" style="color: #10b981;"><?= $mensajesNoLeidos ?? 0 ?></div>
            </div>
        </div>


        <!-- Panel de acciones/botones -->
        <div class="panel-botones">
            <a href="<?= BASE_URL ?>/proveedor/servicios" class="btn btn-success">+ Crear Servicio</a>
            <a href="<?= BASE_URL ?>/servicio" class="btn">Ver Servicios</a>
            <a href="<?= BASE_URL ?>/proveedor/reservas" class="btn">Reservas</a>
            <a href="<?= BASE_URL ?>/mensaje" class="btn btn-mensajes">Mensajes</a>
            <a href="<?= BASE_URL ?>/proveedor/perfil" class="btn btn-perfil">Perfil</a>
        </div>

        <div class="mis-servicios-panel">
    <h2>Mis servicios publicados</h2>
    <?php if (!empty($serviciosPropios)): ?>
    <ul class="servicios-list">
        <?php foreach ($serviciosPropios as $serv): ?>
        <li>
            <span><?= htmlspecialchars($serv['titulo']) ?></span>
            <form method="post" action="<?= BASE_URL ?>/proveedor" style="display:inline;"
                onsubmit="return confirm('¿Seguro que deseas borrar este servicio?');">
                <input type="hidden" name="id_servicio" value="<?= $serv['id_servicio'] ?>">
                <button type="submit" class="btn btn-danger btn-sm">Borrar</button>
            </form>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php else: ?>
    <p style="color:#666;">No tienes servicios publicados todavía.</p>
    <?php endif; ?>
</div>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>