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

/* SERVICIOS PANEL - profesional cards */
.servicios-admin-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
    gap: 30px;
    margin-top: 38px;
    padding-bottom: 32px;
}

.servicio-admin-card {
    background: #232a37;
    border-radius: 15px;
    padding: 19px;
    box-shadow: 0 4px 18px #05080a16;
    display: flex;
    flex-direction: column;
    min-height: 320px;
    justify-content: space-between;
}

.servicio-admin-card img {
    width: 100%;
    height: 140px;
    object-fit: cover;
    border-radius: 7px;
    background: #d6e3ef;
    margin-bottom: 13px;
}

.servicio-admin-card .titulo {
    font-size: 1.13em;
    font-weight: bold;
    color: #fff;
    margin-bottom: 7px;
    letter-spacing: .011em;
}

.servicio-admin-card .desc {
    font-size: 0.99em;
    color: #cee0f5;
    margin-bottom: 9px;
}

.servicio-admin-card .precio {
    font-weight: bold;
    color: #9effc7;
    margin-bottom: 8px;
}

.servicio-admin-card .acciones {
    display: flex;
    gap: 12px;
    align-items: flex-end;
}

.servicio-admin-card .verdet {
    padding: 7px 15px;
    border-radius: 8px;
    background: #61e3a6;
    color: #202;
    font-weight: 700;
    text-decoration: none;
    font-size: 1em;
    transition: background 0.14s;
}

.servicio-admin-card .verdet:hover {
    background: #45bb8d;
    color: #fff;
}

.servicio-admin-card .btn-borrar {
    padding: 7px 16px;
    border-radius: 8px;
    background: #ef3d4b;
    color: #fff;
    font-weight: 700;
    border: none;
    font-size: 1em;
    cursor: pointer;
    transition: background 0.15s;
}

.servicio-admin-card .btn-borrar:hover {
    background: #ae2034;
}
</style>

<?php
$calificacion = floatval($proveedor['calificacion_promedio'] ?? 0);
$estrellasMax = 5;
$estrellasLlenas = floor($calificacion);
$hayMedia = ($calificacion - $estrellasLlenas) >= 0.25;
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

        <h2 style="text-align:center;color:#61e3a6;font-size:2.0em;font-weight:800;margin:50px auto 25px;">Mis servicios
            publicados</h2>
        <div class="servicios-admin-grid">
            <?php foreach ($serviciosPropios as $serv): ?>
            <div class="servicio-admin-card">
                <img src="<?= !empty($serv['imagen_principal']) ? (BASE_URL . '/' . htmlspecialchars($serv['imagen_principal'])) : (BASE_URL . '/img/noimg.png') ?>"
                    alt="servicio">
                <div class="titulo"><?= htmlspecialchars($serv['titulo']) ?></div>
                <div class="desc"><?= htmlspecialchars($serv['descripcion']) ?></div>
                <div class="precio">$<?= number_format($serv['precio'] ?? 0,0) ?></div>
                <div class="acciones">
                    <a href="<?= BASE_URL ?>/servicio/detalle?id=<?= $serv['id_servicio'] ?>" class="verdet">Ver
                        detalles</a>
                    <form method="post" action="<?= BASE_URL ?>/proveedor"
                        onsubmit="return confirm('¿Seguro que deseas borrar este servicio?');"
                        style="display:inline-block;">
                        <input type="hidden" name="id_servicio" value="<?= $serv['id_servicio'] ?>">
                        <button type="submit" class="btn-borrar">Borrar</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php if (empty($serviciosPropios)): ?>
        <p style="text-align:center;color:#bbb;font-size:1.1em;">No tienes servicios publicados todavía.</p>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>