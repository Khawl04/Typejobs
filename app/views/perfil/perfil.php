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
.perfil-flexwrap {
    display: flex;
    justify-content: center;
    align-items: stretch;
    gap: 40px;
    max-width: 1080px;
    margin: 56px auto 0;
}
.perfil-main {
    flex: 1;
    min-width: 350px; max-width: 420px;
    background: #202331;
    border-radius: 18px;
    box-shadow: 0 0 20px #05060d44;
    color: #e8e8e8; text-align: center;
    padding: 40px 24px 32px;
    display: flex; flex-direction: column; justify-content: center;
}
.perfil-avatar {
    width:120px; height:120px;
    margin:0 auto 18px;
    border-radius:50%;
    background:#ececec38;
    object-fit:cover;
}
.perfil-nombre {
    font-size:1.4em;
    font-weight:700;
}
.perfil-estrellas {
    margin-bottom: 11px;
    color: #ffd458;
    font-size: 1.45em;
}
.perfil-bio {
    margin-bottom:24px;
    color:#d3e6f3;
    font-size:1.09em;
    line-height:1.5;
}
.perfil-contactar {
    margin-top:auto;
    background:#1dc447;
    color:#fff;
    font-weight:bold;
    border-radius:9px;
    padding:15px 0;
    font-size:1.10em;
    width:100%;
    display:block;
    border:none;
    text-decoration:none !important;
    transition:background 0.19s;
}
.perfil-contactar:hover { background:#15a133; }

.perfil-datos {
    flex: 1;
    min-width: 350px; max-width: 440px;
    background: #232a37;
    border-radius: 18px;
    padding: 43px 44px 32px 44px;
    box-shadow: 0 0 20px #05060d44;
    color: #fff;
    display: flex; flex-direction: column; justify-content: center;
}
.perfil-datos div { margin-bottom:24px; }
.perfil-datos span:first-child {
    color:#75e4a6;
    font-weight:600;
}
.perfil-datos span:last-child {
    color:#fff;
}

.perfil-servicios-titulo {
    text-align: center;
    color: #0e7746ff;
    font-size: 2.1em;
    font-weight: 800;
    letter-spacing: .03em;
    margin: 60px auto 38px;
    max-width: 900px;
}

.perfil-servicios-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(270px,1fr));
    gap: 34px;
    justify-content: center;
    max-width:1180px;
    margin:0 auto 0;
    padding-bottom: 90px;
}
.perfil-servicio-card {
    background:#202331;
    border-radius:16px;
    padding:19px;
    min-height:320px;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    box-shadow:0 3px 17px #0002;
}
.perfil-servicio-card img {
    width:100%;height:140px;object-fit:cover;border-radius:7px;background:#d6e3ef;margin-bottom:12px;
}
.perfil-servicio-card .titulo {
    font-weight:bold;
    font-size:1.11em;
    margin-bottom:8px;
    color:#fff;
}
.perfil-servicio-card .desc {
    font-size:0.99em;
    color:#cee0f5;
    margin-bottom:10px;
}
.perfil-servicio-card .precio {
    font-weight:bold;
    color:#9effc7;
}
.perfil-servicio-card .verdet {
    padding:8px 14px;
    background:#61e3a6;
    color:#222;
    border-radius:8px;
    text-decoration:none;
    font-weight:600;
    font-size:1em;
    transition:background 0.09s;
    margin-left:10px;
}
.perfil-servicio-card .verdet:hover { background:#50bb93; }
</style>
<div class="perfil-flexwrap">
    <!-- Perfil izquierdo -->
    <div class="perfil-main">
        <img src="<?= empty($proveedor['foto_perfil']) ? (BASE_URL . '/img/defaultpfp.png') : (BASE_URL . '/uploads/perfiles/' . htmlspecialchars($proveedor['foto_perfil'])) ?>" class="perfil-avatar" alt="avatar" />
        <div class="perfil-nombre"><?= htmlspecialchars($proveedor['nombre']) . ' ' . htmlspecialchars($proveedor['apellido']) ?></div>
        <div class="perfil-estrellas">
            <?php $calif = round($proveedor['calificacion_promedio'] ?? 5, 1);
            for($i=1; $i<=5; $i++) echo $i <= $calif ? '★' : '☆'; ?>
            <span style="font-size:.99em;color:#ccc;"> <?= number_format($calif,2) ?>/5</span>
        </div>
        <div class="perfil-bio"><?= nl2br(htmlspecialchars($proveedor['bio'] ?? $proveedor['descripcion'] ?? "Sin descripción")) ?></div>
        <a href="<?= BASE_URL ?>/mensaje?chat=<?= $proveedor['id_usuario'] ?>" class="perfil-contactar">
            Contactar proveedor
        </a>
    </div>
    <!-- Datos personales a la derecha -->
    <div class="perfil-datos">
        <div style="text-align:center;">
    <span style="color:#66ffaf; font-size:1.4em; font-weight:700; letter-spacing:.04em;">Datos del proveedor:</span>
</div>

        <div>
            <span>Email:</span>
            <br><span><?= htmlspecialchars($proveedor['email'] ?? 'No especificado') ?></span>
        </div>
        <div>
            <span>Teléfono:</span>
            <br><span><?= htmlspecialchars($proveedor['telefono'] ?? 'No especificado') ?></span>
        </div>
        <div>
            <span>Dirección:</span>
            <br><span><?= htmlspecialchars($proveedor['direccion'] ?? 'No especificado') ?></span>
        </div>
        <div>
            <span>Descripción:</span>
            <br><span><?= nl2br(htmlspecialchars($proveedor['descripcion'] ?? "Sin descripción")) ?></span>
        </div>
    </div>
</div>

<?php if(!empty($servicios)): ?>
    <h2 class="perfil-servicios-titulo">Publicaciones del proveedor</h2>
    <div class="perfil-servicios-grid">
    <?php foreach($servicios as $s): ?>
        <div class="perfil-servicio-card">
            <img src="<?= !empty($s['imagen_servicio']) ? (BASE_URL . '/' . htmlspecialchars($s['imagen_servicio'])) : (BASE_URL . '/img/defaultpfp.png') ?>" alt="servicio"/>
            <div>
                <div class="titulo"><?= htmlspecialchars($s['titulo']) ?></div>
                <div style="margin-bottom:6px;">
                    <?php
                        $calif = floatval($s['calificacion'] ?? 0);
                        $maxE = 5;
                        $eLlenas = floor($calif);
                        $hayMedia = ($calif - $eLlenas) >= 0.25;
                    ?>
                    <?php for ($i = 0; $i < $eLlenas; $i++): ?>
                        <span style="color:#ffc107;font-size:1.15em;">★</span>
                    <?php endfor; ?>
                    <?php if ($hayMedia): ?>
                        <span style="color:#ffc107;font-size:1.15em;">☆</span>
                    <?php endif; ?>
                    <?php for ($i = $eLlenas + $hayMedia; $i < $maxE; $i++): ?>
                        <span style="color:#ccc;font-size:1.15em;">★</span>
                    <?php endfor; ?>
                    <span style="color:#b0b0b0;font-size:0.97em;margin-left:4px;">
                        <?= number_format($calif,2) ?>/5
                    </span>
                </div>
                <div class="desc"><?= htmlspecialchars($s['descripcion']) ?></div>
            </div>
            <div style="margin-top:6px;display:flex;justify-content:space-between;align-items:flex-end;">
                <span class="precio">$<?= number_format($s['precio'] ?? 0,0) ?></span>
                <a href="<?= BASE_URL ?>/servicio/detalle?id=<?= $s['id_servicio'] ?>" class="verdet">Ver detalles</a>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>


<?php require_once __DIR__ . '/../layouts/footer.php'; ?>