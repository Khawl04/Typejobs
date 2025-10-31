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

.detalle-proveedor-nombre {
    font-size: 1.08em;
    font-weight: 600;
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
    margin-top: 8px;
    border-radius: 8px;
    font-weight: 600;
    padding: 9px 0;
    width: 100%;
    cursor: pointer;
    transition: background 0.18s, color 0.18s;
}

.detalle-btn-contactar:hover {
    background: #42eb93 !important;
    color: #194924;
}

.reseñas-section {
    margin-top: 48px;
    background: #232c3c;
    border-radius: 15px;
    padding: 25px 24px 12px;
}

.reseñas-section h2 {
    font-size: 1.12em;
    margin-bottom: 22px;
    color: #c9d5f8;
    font-weight: 600;
}

.reseña-card {
    background: #343c4d;
    border-radius: 13px;
    padding: 15px 18px;
    margin-bottom: 16px;
    display: flex;
    align-items: flex-start;
    gap: 16px;
    justify-content: space-between;
}

.reseña-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #ececec42;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5em;
    color: #5b6473;
    font-weight: 700;
}

.reseña-info {
    flex: 1;
}

.reseña-nombre {
    font-weight: 600;
    color: #fff;
}

.reseña-fecha {
    color: #bcc6d2;
    font-size: 0.92em;
}

.reseña-estrellas {
    color: #ffd458;
    margin: 3px 0 4px;
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
    </div>
    <!-- Columna info y compra -->
    <div class="detalle-col-info" style="max-width:370px;">
        <div class="detalle-comprar-box">
            <form method="get" action="<?= BASE_URL ?>/reserva">
                <input type="hidden" name="id_servicio" value="<?= $servicio['id_servicio'] ?>">
                <button class="detalle-btn-comprar">Comprar ahora $<?= number_format($servicio['precio'], 0) ?></button>
            </form>
            <div class="detalle-proveedor">
                <?php if (!empty($proveedor['foto_perfil'])): ?>
                <img src="<?= BASE_URL ?>/uploads/perfiles/<?= htmlspecialchars($proveedor['foto_perfil']) ?>"
                    class="detalle-proveedor-avatar" alt="avatar" />
                <?php else: ?>
                <div class="detalle-proveedor-avatar">
                    <?= strtoupper(mb_substr($proveedor['nombre'] ?? $proveedor['nomusuario'] ?? '', 0, 1)) ?></div>
                <?php endif; ?>
                <div>
                    <div class="detalle-proveedor-nombre"><?= htmlspecialchars($proveedor['nombre'] ?? '') ?>
                        <?= htmlspecialchars($proveedor['apellido'] ?? '') ?></div>
                    <div>
                        <span class="reseña-estrellas">★★★★★</span>
                        <span style="font-size:.98em;color:#aaa;">
                            <?= number_format($proveedor['calificacion_promedio'] ?? 5,2) ?>/5 Estrellas</span>
                    </div>
                </div>
            </div>
            <div class="detalle-proveedor-info">
                <div><span class="detalle-stats-label">Talento:</span>
                    <?= htmlspecialchars($proveedor['titulo'] ?? '---') ?></div>
                <div><span class="detalle-stats-label">Lugar:</span>
                    <?= htmlspecialchars($proveedor['ubicacion'] ?? $proveedor['direccion'] ?? '---') ?></div>
                <div><span class="detalle-stats-label">Tiempo de entrega:</span>
                    <?= htmlspecialchars($servicio['tiempo_entrega'] ?? '---') ?></div>
                <div><span class="detalle-stats-label">Categoría:</span>
                    <?= htmlspecialchars($servicio['categoria'] ?? '') ?></div>
            </div>
            <a href="<?= BASE_URL ?>/mensaje/enviar?dest=<?= $proveedor['id_usuario'] ?>"
                class="detalle-btn-contactar">Contáctame</a>
        </div>
        <?php if(!empty($resenas)): ?>
        <div class="reseñas-section">
            <h2>Reseñas de Clientes</h2>
            <?php foreach ($resenas as $r): ?>
            <div class="reseña-card">
                <div class="reseña-avatar"><?= strtoupper(mb_substr($r['nombre_cliente'] ?? '',0,1)) ?></div>
                <div class="reseña-info">
                    <div class="reseña-nombre"><?= htmlspecialchars($r['nombre_cliente']) ?></div>
                    <div class="reseña-estrellas">★★★★★</div>
                    <div><?= htmlspecialchars($r['texto']) ?></div>
                </div>
                <div class="reseña-fecha"><?= date("d/m/Y", strtotime($r['fecha'])) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <?php if ($puedeReseniar): ?>
        <div class="crear-resena-section" style="margin-top:35px;">
            <button type="button" onclick="document.getElementById('box-resena').style.display='block';">
                Crear reseña
            </button>
            <div id="box-resena" style="display:none;margin-top:18px;">
                <form method="post" action="<?= BASE_URL ?>/servicio/agregarResena">
                    <input type="hidden" name="id_servicio" value="<?= $servicio['id_servicio'] ?>">
                    <label>Puntaje:</label>
                    <select name="calificacion" required>
                        <option value="5">★★★★★</option>
                        <option value="4">★★★★</option>
                        <option value="3">★★★</option>
                        <option value="2">★★</option>
                        <option value="1">★</option>
                    </select>
                    <br>
                    <label>Comentario:</label>
                    <textarea name="texto" required rows="3" style="width:90%"></textarea>
                    <br>
                    <button type="submit">Enviar reseña</button>
                </form>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>