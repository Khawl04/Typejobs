<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<style>
.perfil-main {
    max-width: 430px;
    margin: 45px auto;
    background: #202331;
    padding: 36px 30px 32px;
    border-radius: 18px;
    box-shadow: 0 0 20px #05060d44;
    color: #e8e8e8;
    text-align: center;
}
.perfil-avatar {
    width: 96px; height: 96px;
    margin: 0 auto 14px;
    border-radius: 50%;
    background: #ececec38;
    object-fit: cover;
}
.perfil-nombre {
    font-size: 1.4em;
    font-weight: 700;
    margin-bottom: 7px;
}
.perfil-estrellas {
    margin-bottom: 9px;
    color: #ffd458;
    font-size: 1.45em;
}
.perfil-bio {
    margin: 20px 0 12px;
    color: #b4c0e9;
    font-size: 1.08em;
    line-height: 1.5;
}
.perfil-contactar {
    margin-top: 17px;
    background: #1dc447;
    color: #fff;
    font-weight: bold;
    border-radius: 9px;
    padding: 14px 0;
    font-size: 1.09em;
    width: 100%;
    display: block;
    border: none;
    text-decoration: none !important;
    transition: background 0.19s;
}
.perfil-contactar:hover { background: #15a133; }
</style>

<div class="perfil-flexwrap" style="display:flex;justify-content:center;align-items:stretch;gap:40px;max-width:980px;margin:56px auto 0;">
    <!-- Perfil izquierdo -->
    <div class="perfil-main" style="flex:1;min-width:340px;max-width:420px;display:flex;flex-direction:column;justify-content:center;background:#202331;border-radius:18px;box-shadow:0 0 20px #05060d44;color:#e8e8e8;text-align:center;padding:40px 24px 32px;">
        <img src="<?= empty($proveedor['foto_perfil']) ? (BASE_URL . '/img/defaultpfp.png') : (BASE_URL . '/uploads/perfiles/' . htmlspecialchars($proveedor['foto_perfil'])) ?>"
             class="perfil-avatar" style="width:120px;height:120px;margin:0 auto 18px;border-radius:50%;background:#ececec38;object-fit:cover;" alt="avatar" />
        <div class="perfil-nombre" style="font-size:1.4em;font-weight:700;"><?= htmlspecialchars($proveedor['nombre']) . ' ' . htmlspecialchars($proveedor['apellido']) ?></div>
        <div class="perfil-estrellas" style="margin-bottom:11px;color:#ffd458;font-size:1.45em;">
            <?php
            $calif = round($proveedor['calificacion_promedio'] ?? 5, 1);
            for($i=1; $i<=5; $i++) echo $i <= $calif ? '★' : '☆';
            ?>
            <span style="font-size:.99em;color:#ccc;"> <?= number_format($calif,2) ?>/5</span>
        </div>
        <div class="perfil-bio" style="margin-bottom:19px;color:#d3e6f3;font-size:1.09em;line-height:1.5;">
            <?= nl2br(htmlspecialchars($proveedor['bio'] ?? $proveedor['descripcion'] ?? "Sin descripción")) ?>
        </div>
        <a href="<?= BASE_URL ?>/mensaje?chat=<?= $proveedor['id_usuario'] ?>" class="perfil-contactar"
           style="margin-top:14px;background:#1dc447;color:#fff;font-weight:bold;border-radius:9px;padding:15px 0;font-size:1.10em;width:100%;display:block;border:none;text-decoration:none !important;transition:background 0.19s;">
            Contactar proveedor
        </a>
    </div>
    <!-- Datos a la derecha -->
    <div class="perfil-datos" style="flex:1;min-width:340px;max-width:420px;background:#232a37;border-radius:18px;padding:43px 38px 32px 38px;box-shadow:0 0 20px #05060d44;color:#fff;display:flex;flex-direction:column;justify-content:center;">
        <div style="margin-bottom:22px;">
            <span style="color:#75e4a6;font-weight:600;">Email:</span>
            <br><span style="color:#fff;"><?= htmlspecialchars($proveedor['email'] ?? '---') ?></span>
        </div>
        <div style="margin-bottom:22px;">
            <span style="color:#75e4a6;font-weight:600;">Teléfono:</span>
            <br><span style="color:#fff;"><?= htmlspecialchars($proveedor['telefono'] ?? '---') ?></span>
        </div>
        <div style="margin-bottom:22px;">
            <span style="color:#75e4a6;font-weight:600;">Dirección:</span>
            <br><span style="color:#fff;"><?= htmlspecialchars($proveedor['direccion'] ?? '---') ?></span>
        </div>
        <div>
            <span style="color:#75e4a6;font-weight:600;">Descripción:</span>
            <br><span style="color:#fff;"><?= nl2br(htmlspecialchars($proveedor['descripcion'] ?? "Sin descripción")) ?></span>
        </div>
    </div>
</div>

<?php if(!empty($servicios)): ?>
    <div style="max-width:1180px;margin:60px auto 0;">
        <h2 style="color:#61e3a6;font-weight:600;font-size:1.13em;margin-bottom:26px;">Publicaciones del proveedor</h2>
        <div style="
            display: grid;
            grid-template-columns: repeat(auto-fit,minmax(270px,1fr));
            gap: 34px;
            justify-content: center;
        ">
        <?php foreach($servicios as $s): ?>
            <div style="background:#202331;border-radius:16px;padding:19px;min-height:320px;display:flex;flex-direction:column;justify-content:space-between;box-shadow:0 3px 17px #0002;">
                <img src="<?= !empty($s['imagen_principal'])
                    ? (BASE_URL . '/' . htmlspecialchars($s['imagen_principal']))
                    : (BASE_URL . '/img/noimg.png') ?>"
                     style="width:100%;height:140px;object-fit:cover;border-radius:7px;background:#d6e3ef;margin-bottom:12px;" 
                     alt="servicio"/>
                <div>
                    <div style="font-weight:bold;font-size:1.11em;margin-bottom:8px;color:#fff;"><?= htmlspecialchars($s['titulo']) ?></div>
                    <div style="font-size:0.99em;color:#cee0f5;margin-bottom:10px;"><?= htmlspecialchars($s['descripcion']) ?></div>
                </div>
                <div style="margin-top:6px;display:flex;justify-content:space-between;align-items:flex-end;">
                    <span style="font-weight:bold;color:#9effc7;">$<?= number_format($s['precio'] ?? 0,0) ?></span>
                    <a href="<?= BASE_URL ?>/servicio/detalle?id=<?= $s['id_servicio'] ?>"
                    style="padding:8px 14px;background:#61e3a6;color:#222;border-radius:8px;text-decoration:none;font-weight:600;font-size:1em;transition:background 0.09s;">
                        Ver detalles
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>



<?php require_once __DIR__ . '/../layouts/footer.php'; ?>