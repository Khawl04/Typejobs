<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #1a2332;
    min-height: 100vh;
    overflow: hidden;
}

.service-section {
    width: 100%;
    max-height: 560px;
    overflow-y: auto;
}

.form-actions {
    text-align: right;
    bottom: 0;
    background: #232a37;
    padding-bottom: 10px;
    z-index: 2;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    gap: 30px;
}


.service-card {
    background: #232a37;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}


.form-header {
    background: #f5f5f5;
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
}

.service-title {
    width: 100%;
    border: none;
    background: transparent;
    font-size: 18px;
    font-weight: 600;
    color: #333;
    outline: none;
}

.service-title::placeholder {
    color: #999;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #75e4a6 ;
    margin-bottom: 8px;
}

.form-textarea {
    width: 100%;
    min-height: 120px;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    resize: vertical;
    outline: none;
}

.images-section {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-bottom: 25px;
}

.image-placeholder {
    background: #f5f5f5;
    border: 2px dashed #ddd;
    border-radius: 8px;
    height: 120px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.image-placeholder:hover {
    background: #f0f0f0;
    border-color: #ccc;
}

.image-placeholder span {
    font-size: 24px;
    margin-bottom: 8px;
}

.image-placeholder p {
    font-size: 12px;
    color: #666;
}

.price-section {
    margin-bottom: 30px;
}

.price-section label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #75e4a6;
    margin-bottom: 8px;
}

.price-input {
    display: flex;
    align-items: center;
    gap: 10px;
    max-width: 200px;
}

.currency {
    font-size: 16px;
    font-weight: 600;
    color: #333;
}

.price-input input {
    flex: 1;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 16px;
    text-align: center;
    outline: none;
}

.price-input input:focus {
    border-color: #4CAF50;
}

.currency-label {
    font-size: 14px;
    color: #75e4a6;
}



.publish-btn {
    background: #4CAF50;
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
}

.nueva-categoria-input {
    display: none;
    margin-top: 10px;
    padding: 8px 12px;
    width: 100%;
    border-radius: 6px;
    border: 1px solid #aaa;
}


@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 15px;
        padding: 0 15px;
    }



}
</style>

<div class="service-section">
    <div class="service-card">
        <form method="post" action="<?= BASE_URL ?>/servicio" enctype="multipart/form-data">
            <div class="form-header">
                <input type="text" name="titulo" class="service-title" placeholder="Título del servicio" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-textarea"
                    placeholder="Describe tu servicio en detalle..." required></textarea>
            </div>
            <div class="form-group">

                <label for="id_categoria">Categoría</label>
                <select name="id_categoria" class="form-control" id="categoria-select" required>
                    <option value="">Selecciona una categoría</option>
                    <?php foreach($categorias as $cat): ?>
                    <option value="<?= htmlspecialchars($cat['id_categoria']) ?>">
                        <?= htmlspecialchars($cat['nombre_categoria']) ?>
                    </option>
                    <?php endforeach; ?>
                    <option value="nueva">Nueva categoria</option>
                </select>
                <input type="text" name="nueva_categoria" id="nueva-categoria-input" class="nueva-categoria-input"
                    placeholder="Escribe el nombre de la nueva categoría">
            </div>
            <div class="images-section">
                <div class="image-placeholder" onclick="document.getElementById('imgfile1').click();">
                    <span>📷</span>
                    <p>Imagen principal</p>
                    <input type="file" name="imagen_servicio" id="imgfile1" accept="image/*" style="display:none;"
                        required>
                </div>
            </div>
            <script>
            // Muestra el nombre de la imagen seleccionada junto al cuadro, opcional
            document.querySelectorAll('.image-placeholder input[type="file"]').forEach(function(input) {
                input.addEventListener('change', function() {
                    if (this.files.length && this.parentNode.querySelector('p')) {
                        this.parentNode.querySelector('p').textContent = this.files[0].name;
                    }
                });
            });
            </script>

            <div class="price-section">
                <label for="precio">Precio</label>
                <div class="price-input">
                    <span class="currency">$</span>
                    <input type="number" name="precio" min="0" step="0.01" placeholder="Precio" required>
                    <span class="currency-label">UYU</span>
                </div>
            </div>
            <div class="form-group">
                <label for="duracion_estimada">Duración estimada (minutos)</label>
                <input type="number" name="duracion_estimada" min="1" placeholder="Ej: 60" required>
            </div>
            <div class="form-actions">
                <button class="publish-btn" type="submit">Publicar servicio</button>
            </div>
        </form>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var select = document.getElementById('categoria-select');
            var input = document.getElementById('nueva-categoria-input');
            select.addEventListener('change', function() {
                if (select.value === 'nueva') {
                    input.style.display = 'block';
                    input.required = true;
                } else {
                    input.style.display = 'none';
                    input.required = false;
                    input.value = '';
                }
            });
        });
        </script>
    </div>
</div>




<?php require_once __DIR__ . '/../layouts/footer.php'; ?>