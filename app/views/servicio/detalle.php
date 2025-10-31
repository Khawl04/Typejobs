<?php 
require_once __DIR__ . '/../layouts/header.php'; ?>
<style>
.detalle-main {
    display: flex;
    flex-wrap: wrap;
    gap: 32px;
    background: #1c2432;
    color: #f1f1f1;
    min-height: 100vh;
    font-family: 'Segoe UI', Arial, sans-serif;
    padding: 20px 0;
}

.detalle-col-imagenes,
.detalle-col-info {
    flex: 1;
    min-width: 350px;
}

.detalle-titulo {
    font-size: 2em;
    font-weight: bold;
    margin-bottom: 16px;
}

.detalle-imagenes-box {
    background: #ececec10;
    border-radius: 14px;
    padding: 26px;
    text-align: center;
    margin-bottom: 18px;
}

.detalle-imagenes-box img {
    border-radius: 8px;
    max-width: 340px;
    max-height: 310px;
    object-fit: cover;
    background: #dedede;
}

.detalle-minis {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-top: 16px;
}

.detalle-minis img {
    border-radius: 3px;
    width: 54px;
    height: 54px;
    border: 2px solid #555;
    object-fit: cover;
    background: #dedede;
    cursor: pointer;
}

.detalle-minis img.selected,
.detalle-minis img:hover {
    border: 2.7px solid #4ad484;
}

.detalle-descripcion {
    background: #2b3241;
    border-radius: 14px;
    padding: 20px 22px 18px;
    font-size: 1.15em;
    margin-bottom: 28px;
}

.detalle-comprar-box {
    background: #232c3c;
    border-radius: 14px;
    padding: 18px 32px 30px;
    max-width: 350px;
    margin: 0 auto 20px;
    text-align: center;
}

.detalle-btn-comprar {
    background: #30bf61;
    color: #fff;
    font-weight: 700;
    padding: 16px;
    border-radius: 10px;
    border: none;
    width: 100%;
    font-size: 1.17em;
    margin-bottom: 14px;
    cursor: pointer;
    transition: background 0.22s;
}

.detalle-btn-comprar:hover {
    background: #209a4c;
}

.detalle-proveedor {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 8px;
}

.detalle-proveedor-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #ececec40;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.1em;
    color: #5b6473;
    font-weight: bold;
}

.detalle-link-perfil {
    font-size: 1.08em;
    font-weight: 600;
    text-decoration: none;
    color: #268b3dff;
}

.detalle-link-perfil:hover {
    color: #58e07f;
}

.detalle-proveedor-info {
    text-align: left;
    margin-bottom: 12px;
    color: #c1c1c1;
    font-size: 1em;
}

.detalle-stats-label {
    font-weight: 500;
    margin-bottom: 4px;
}

.detalle-btn-contactar {
    border: 2px solid #30bf61;
    color: #30bf61;
    background: transparent !important;
    font-size: 1.14em;
    margin-top: 32px;
    /* más espacio arriba */
    border-radius: 8px;
    font-weight: 600;
    padding: 14px 0;
    /* más alto el botón */
    width: 100%;
    cursor: pointer;
    transition: background 0.18s, color 0.18s;
    text-align: center;
    text-decoration: none !important;
    /* quita el underline */
    display: block;
}

.detalle-btn-contactar:hover {
    background: #42eb93 !important;
    color: #194924;
    text-decoration: none !important;
}

.resenas-section {
    margin-top: 48px;
    background: #232c3c;
    border-radius: 15px;
    padding: 25px 24px 12px;
}

.resenas-section h2 {
    font-size: 1.12em;
    margin-bottom: 22px;
    color: #c9d5f8;
    font-weight: 600;
}

.resena-card {
    background: #343c4d;
    border-radius: 13px;
    padding: 15px 18px;
    margin-bottom: 16px;
    display: flex;
    align-items: flex-start;
    gap: 16px;
    justify-content: space-between;
}

.resena-avatar img {
    width: 48px;
    height: 48px;
    min-width: 48px;
    min-height: 48px;
    max-width: 48px;
    max-height: 48px;
    border-radius: 50% !important;
    object-fit: cover;
    display: block;
    background: #ececec42;
}

.resena-info {
    flex: 1;
}

.resena-nombre {
    font-weight: 600;
    color: #fff;
}

.resena-fecha {
    color: #bcc6d2;
    font-size: 0.92em;
}

.resena-estrellas {
    color: #ffd458;
    margin: 3px 0 4px;
    font-size: 1.25em;
}

.crear-resena-section .detalle-btn-comprar {
    margin-top: 12px;
}

.crear-resena-box {
    background: #2b3241;
    border-radius: 11px;
    padding: 22px 14px;
    margin-top: 13px;
    box-shadow: 0 1px 8px #0002;
}

.crear-resena-stars {
    font-size: 2em;
    cursor: pointer;
    color: #ffd458;
    margin-bottom: 7px;
}

.crear-resena-stars label {
    cursor: pointer;
}
</style>

<div class="detalle-main">
    <!-- Columna imágenes y descripción -->
    <div class="detalle-col-imagenes">
        <div class="detalle-titulo"><?= htmlspecialchars($servicio['titulo']) ?></div>
        <div class="detalle-imagenes-box">
            <?php if (!empty($imagenes)): ?>
            <img id="imgMain" src="<?= BASE_URL . '/' . htmlspecialchars($imagenes[0]) ?>" alt="Imagen principal" />
            <div class="detalle-minis">
                <?php foreach ($imagenes as $idx => $img): ?>
                <img src="<?= BASE_URL . '/' . htmlspecialchars($img) ?>" class="<?= $idx == 0 ? 'selected' : '' ?>"
                    onclick="document.getElementById('imgMain').src=this.src; 
                          document.querySelectorAll('.detalle-minis img').forEach(e => e.classList.remove('selected'));
                          this.classList.add('selected');">
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div style="background:#dadada;width:240px;height:190px;display:flex;align-items:center;justify-content:center;
                    color:#7b7c82;font-size:2.5em;border-radius:7px;margin:0 auto;">
                <span>📷</span>
            </div>
            <?php endif; ?>
        </div>
        <div class="detalle-descripcion">
            <h2>Descripción</h2>
            <?= htmlspecialchars($servicio['descripcion']) ?>
        </div>
        <!-- Reseñas igual que antes -->
        <!-- ... (todo el demás bloque de reseñas/form estrellas igual que antes) ... -->
    </div>

    <!-- Col info y compra -->
    <div class="detalle-col-info" style="max-width:370px;">
        <div class="detalle-comprar-box">
            <form method="get" action="<?= BASE_URL ?>/reserva">
                <input type="hidden" name="id_servicio" value="<?= $servicio['id_servicio'] ?>">
                <button class="detalle-btn-comprar">
                    Comprar ahora $<?= number_format($servicio['precio'], 0) ?>
                </button>
            </form>
            <div class="detalle-proveedor">
                <?php if (!empty($proveedor['foto_perfil'])): ?>
                <img src="<?= BASE_URL ?>/uploads/perfiles/<?= htmlspecialchars($proveedor['foto_perfil']) ?>"
                    class="detalle-proveedor-avatar" alt="avatar" />
                <?php else: ?>
                <img src="<?= BASE_URL ?>/img/defaultpfp.png" class="detalle-proveedor-avatar" alt="default" />
                <?php endif; ?>
                <div>
                    <a href="<?= BASE_URL ?>/perfil?id=<?= $proveedor['id_usuario'] ?>" class="detalle-link-perfil">
                        <?= htmlspecialchars($proveedor['nombre'] ?? '') . ' ' . htmlspecialchars($proveedor['apellido'] ?? '') ?>
                    </a>
                    <span class="resena-estrellas">
                        <?php $calif = round($proveedor['calificacion_promedio'] ?? 5, 1);
                        for($i=1; $i<=5; $i++) echo $i <= $calif ? '★' : '☆'; ?>
                    </span>
                    <span style="font-size:.98em;color:#aaa;">
                        <?= number_format($calif,2) ?>/5 Estrellas
                    </span>
                    <!-- Aquí va la DURACIÓN, bien visible debajo -->
                    <div style="margin-top:11px;">
                        <span style="
                            background: #d8fae9;
                            color: #217d55;
                            font-size: 1.03em;
                            border-radius: 13px;
                            padding: 5px 17px 4px 15px;
                            display: inline-block;
                        ">
                            <?= htmlspecialchars($servicio['duracion_estimada'] ?? '-') ?> min
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <?php if(!empty($resenas)):
            $destacada = $resenas[0];
        ?>
        <a href="<?= BASE_URL ?>/mensaje?chat=<?= $proveedor['id_usuario'] ?>"
            class="detalle-btn-contactar">Contáctame</a>
        <div class="resenas-section" style="margin-bottom:27px;padding:15px 10px 10px 10px;">
            <h2 style="color:#90e8a3;margin-bottom:10px;">Reseña Destacada</h2>
            <div class="resena-card" style="background:#2f3647;">
                <div class="resena-avatar">
                    <?php if (!empty($destacada['foto_perfil'])): ?>
                    <img src="<?= BASE_URL ?>/uploads/perfiles/<?= htmlspecialchars($destacada['foto_perfil']) ?>"
                        alt="avatar" />
                    <?php else: ?>
                    <img src="<?= BASE_URL ?>/img/defaultpfp.png" alt="default" />
                    <?php endif; ?>
                </div>
                <div class="resena-info">
                    <div class="resena-nombre">
                        <?= htmlspecialchars($destacada['nombre_cliente']) ?>
                        <?= htmlspecialchars($destacada['apellido_cliente']) ?>
                    </div>
                    <div class="resena-estrellas">
                        <?= str_repeat('★', (int)$destacada['calificacion']) . str_repeat('☆', 5-(int)$destacada['calificacion']) ?>
                    </div>
                    <div style="color:#e1ffe9;"><?= htmlspecialchars($destacada['texto']) ?></div>
                </div>
                <div class="resena-fecha"><?= date("d/m/Y", strtotime($destacada['fecha'])) ?></div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>