<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
body {
    background: #274c3d;
}

.servicios-grid-ed {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(330px, 1fr));
    gap: 28px;
    margin-bottom: 36px;
    max-width: 1150px;
    margin-left: auto;
    margin-right: auto;
}

.serv-card-ed {
    background: #202331;
    border-radius: 13px;
    box-shadow: 0 2px 10px #151b2558;
    overflow: hidden;
    transition: border 0.15s, transform .14s;
    position: relative;
    border: 2.2px solid transparent;
    display: flex;
    flex-direction: column;
    min-height: 328px;
}

.serv-card-ed.selected,
.serv-card-ed:hover {
    border-color: #43d5bf;
    transform: translateY(-4px) scale(1.016);
    box-shadow: 0 7px 30px #62f8e830;
}

.serv-card-ed img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    background: #e4eee4;
    border-radius: 0 0 8px 8px;
    margin-bottom: 0px;
}

.serv-card-ed .titulo {
    font-size: 1.09em;
    font-weight: 700;
    color: #fff;
    margin-bottom: 5px;
    margin-top: 14px;
    padding: 0 16px;
}

.serv-card-ed .desc {
    font-size: .99em;
    color: #cee0f5;
    margin-bottom: 11px;
    padding: 0 16px;
    min-height: 32px;
    opacity: 0.92;
    line-height: 1.44;
}

.info-row {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
    margin-bottom: 7px;
    padding: 0 16px;
}

.serv-info-tag,
.cat-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border-radius: 17px;
    padding: 2.5px 11px;
    font-size: .99em;
    background: #e9faf3;
    color: #44ebb6;
    margin-right: 0;
    margin-bottom: 2px;
}

.cat-chip {
    background: #efe2b9;
    color: #aa990e;
    font-weight: 600;
    border: 1.1px solid #f5e6b8;
}

.precio {
    color: #9effc7;
    font-weight: 800;
    font-size: 1.23em;
    margin-bottom: 2px;
    padding: 0 16px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.rating {
    margin-left: 16px;
    margin-bottom: 5px;
}

.rating .star {
    font-size: 1.15em;
    color: #ffd458;
}

.rating .star.gray {
    color: #899094;
}

.rating .calif-score {
    font-size: .96em;
    color: #b4bbbb;
    margin-left: 8px;
}

.acciones {
    position: absolute;
    top: 14px;
    right: 14px;
    display: flex;
    gap: 7px;
}

.titulo-sec {
    text-align: center;
    font-size: 2.4em;
    font-weight: bold;
    color: #75e4a6;
    letter-spacing: -1.2px;
    margin-top: 0;
    margin-bottom: 32px;
    padding-bottom: 13px;
}

.btn-del {
    background: #f55380;
    color: #fff;
    border: none;
    border-radius: 7px;
    font-weight: 700;
    padding: 6px 11px;
    font-size: 1.49em;
    line-height: 1;
    cursor: pointer;
    box-shadow: 0 0.5px 3px #f8b0c9c2;
    transition: background .13s;
}

.btn-del:hover {
    background: #b81836;
}

@media (max-width: 768px) {
    .servicios-grid-ed {
        grid-template-columns: 1fr;
    }

    .serv-card-ed img {
        height: 130px;
    }
}

.editar-form-panel {
    max-width: 530px;
    margin: 0 auto 16px auto;
    background: #1c2533;
    border-radius: 13px;
    padding: 35px 28px 26px 28px;
    box-shadow: 0 1.9px 14px #2bfca622;
}

.editar-form-panel label {
    font-weight: 600;
    color: #81ebad;
}

.editar-form-panel input,
.editar-form-panel textarea,
.editar-form-panel select {
    width: 100%;
    border-radius: 7px;
    background: #f2f7f7;
    border: 1.1px solid #b2d4c3;
    padding: 12px;
    font-size: 1.09em;
    margin-bottom: 14px;
}

.editar-form-panel textarea {
    min-height: 72px;
    resize: vertical;
}

.editar-form-panel .editar-btn {
    background: #0c8171;
    color: #fff;
    border: none;
    border-radius: 7px;
    padding: 13px 0;
    font-size: 1.13em;
    font-weight: 800;
    width: 100%;
    margin-top: 5px;
    cursor: pointer;
    transition: .17s background;
}

.editar-form-panel .editar-btn:hover {
    background: #19807d;
}
</style>

<div class="editarServicios-wrap">
    <div class="titulo-sec">Tus servicios</div>
    <div class="servicios-grid-ed" id="servicios-ed-list">
        <?php foreach($serviciosPropios as $serv): ?>
        <div class="serv-card-ed" data-id="<?= htmlspecialchars((string)($serv['id_servicio'] ?? '')) ?>"
            data-categoria="<?= htmlspecialchars((string)($serv['id_categoria'] ?? '')) ?>"
            data-duracion="<?= htmlspecialchars((string)($serv['duracion_estimada'] ?? '')) ?>">
            <img src="<?= !empty($serv['imagen_servicio']) ? (BASE_URL . '/' . htmlspecialchars((string)$serv['imagen_servicio'])) : (BASE_URL . '/img/defaultpfp.png') ?>"
                alt="servicio">
            <div class="titulo"><?= htmlspecialchars((string)($serv['titulo'] ?? '')) ?></div>
            <div class="rating">
                <?php
                $calif = round($serv['calificacion'] ?? 0, 1);
                for ($i = 1; $i <= 5; $i++) {
                    echo '<span class="star'.($i <= $calif ? '' : ' gray').'">&#9733;</span>';
                }
                ?>
                <span class="calif-score"><?= number_format($calif,2) ?>/5</span>
            </div>
            <div class="desc"><?= htmlspecialchars((string)($serv['descripcion'] ?? '')) ?></div>
            <div class="info-row">
                <span class="serv-info-tag">
                    <!-- SIN iconos, solo texto -->
                    <?= htmlspecialchars((string)($serv['duracion_estimada'] ?? 'No definido')) ?> min
                </span>
                <span class="cat-chip">
                    <?= htmlspecialchars((string)($serv['nombre_categoria'] ?? 'Sin categoría')) ?>
                </span>
            </div>
            <div class="precio">
                $<?= number_format((float)($serv['precio'] ?? 0),0) ?> UYU
            </div>
            <div class="acciones">
                <form method="post" action="<?= BASE_URL ?>/proveedor/borrarServicio"
                    onsubmit="return confirm('¿Seguro que deseas eliminar este servicio?');" style="display:inline;">
                    <input type="hidden" name="id_servicio"
                        value="<?= htmlspecialchars((string)($serv['id_servicio'] ?? '')) ?>">
                    <button type="submit" class="btn-del" title="Eliminar servicio">
                        &times;
                    </button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Panel de edición -->
    <form class="editar-form-panel" id="form-editar-servicio" method="POST"
        action="<?= BASE_URL ?>/proveedor/editarservicio" enctype="multipart/form-data" style="display:none;">
        <input type="hidden" name="id_servicio" id="editar-id_servicio">
        <label for="editar-titulo">Título</label>
        <input type="text" name="titulo" id="editar-titulo" required>
        <label for="editar-categoria">Categoría</label>
        <select name="id_categoria" id="editar-categoria" required>
            <?php foreach($categorias as $cat): ?>
            <option value="<?= htmlspecialchars((string)($cat['id_categoria'] ?? '')) ?>">
                <?= htmlspecialchars((string)($cat['nombre_categoria'] ?? 'Sin nombre')) ?>
            </option>
            <?php endforeach; ?>
        </select>
        <label for="editar-duracion-estimada">Duración (min)</label>
        <input type="number" name="duracion_estimada" id="editar-duracion-estimada" min="1" required>
        <label>Imagen actual</label>
        <img id="editar-imagen-preview" src="" alt="Imagen actual"
            style="width:90px;height:60px;border-radius:8px;background:#e7f4e8;object-fit:cover;margin-bottom:5px;">
        <label for="editar-imagen">Cambiar imagen</label>
        <input type="file" name="imagen_servicio" id="editar-imagen" accept="image/*">
        <label for="editar-descripcion">Descripción</label>
        <textarea name="descripcion" id="editar-descripcion" required></textarea>
        <label for="editar-precio">Precio</label>
        <input type="number" name="precio" id="editar-precio" min="0" step="1" required>
        <button type="submit" class="editar-btn">Guardar Cambios</button>
    </form>
</div>

<script>
const cards = document.querySelectorAll('.serv-card-ed');
const form = document.getElementById('form-editar-servicio');
const titulo = document.getElementById('editar-titulo');
const descripcion = document.getElementById('editar-descripcion');
const precio = document.getElementById('editar-precio');
const idserv = document.getElementById('editar-id_servicio');
const categoria = document.getElementById('editar-categoria');
const duracion = document.getElementById('editar-duracion-estimada');
const imgPreview = document.getElementById('editar-imagen-preview');

cards.forEach(card => {
    card.addEventListener('click', function(e) {
        if (e.target.closest('.btn-del')) return;
        cards.forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');
        idserv.value = card.dataset.id ?? "";
        titulo.value = card.querySelector('.titulo')?.textContent.trim() ?? "";
        descripcion.value = card.querySelector('.desc')?.textContent.trim() ?? "";
        precio.value = card.querySelector('.precio')?.textContent.replace(/[^0-9]/g, '') ?? "";
        categoria.value = card.dataset.categoria ?? "";
        duracion.value = card.dataset.duracion ?? "";
        imgPreview.src = card.querySelector('img')?.src ?? "";
        form.style.display = "block";
        form.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    });
});
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>