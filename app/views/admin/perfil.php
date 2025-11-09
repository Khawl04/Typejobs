<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<style>
body {
    background: #274c3d;
}
.perfil-container {
    max-width: 400px;
    margin: 48px auto 0 auto;
    background: #232a37ed;
    border-radius: 12px;
    box-shadow: 0 4px 18px #0002;
    padding: 32px 28px 30px 28px;
    color: #e1fcef;
    text-align: center;
    margin-bottom: 26px;
}
.perfil-title {
    font-size: 1.35em;
    color: #48ecb2;
    margin-bottom: 22px;
    margin-top: 2px;
    font-weight: bold;
}
.foto-perfil-actual {
    width: 115px;
    height: 115px;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid #3cfd9e44;
    margin: 0 auto 30px auto;
    display: block;
    background: #e5e7eb;
    box-shadow: 0 2px 8px #0003;
}
.form-btn-block {
    display: flex;
    flex-direction: column;
    gap: 18px;
    align-items: stretch;
}
.file-input-label {
    width: 100%;
    background: #219866;
    color: #e1fcef;
    border-radius: 7px;
    padding: 14px 0;
    font-size: 1.09em;
    font-weight: 600;
    cursor: pointer;
    transition: background .15s;
    margin: 0 0 0 0;
    text-align: center;
    display: block;
    margin-bottom: 0;
}
.file-input-label:hover {
    background: #1a7a51;
}
input[type="file"] {
    display: none;
}
.btn-guardar {
    width: 100%;
    background: #2563eb;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 1.09em;
    font-weight: 700;
    padding: 14px 0;
    margin: 0;
    cursor: pointer;
    box-shadow: 0 1px 7px #36ad6e19;
    transition: background .19s;
}
.btn-guardar:hover {
    background: #122655;
}
.btn-eliminar-foto {
    width: 100%;
    background: #b82f2f;
    margin: 0;
    color: #fff;
    padding: 14px 0;
    border-radius: 8px;
    font-weight: 700;
    font-size: 1.09em;
}
.btn-eliminar-foto:hover {
    background: #8d1313;
}
.btn-secondary {
    background: #308159 !important;
    color: #eefffc !important;
    font-weight: 700;
    display: inline-block;
    border-radius: 8px;
    text-decoration: none !important;
    border: none;
    font-size: 1em;
    outline: none;
    padding: 10px 21px;
    margin-bottom: 4px;
    box-shadow: 0 1px 7px #36ad6e19;
    transition: background .19s, transform .15s;
    cursor: pointer;
}

.btn-secondary:hover {
    background: #266a49 !important;
}

@media (max-width: 768px) {
    .perfil-container {
        max-width: 98vw;
        margin: 28px auto 0 auto;
        padding: 19px 9px 17px 9px;
        border-radius: 7px;
        box-shadow: 0 1px 7px #0002;
        font-size: .98em;
        margin-bottom: 18px;
    }
    .perfil-title {
        font-size: 1.09em;
        margin-bottom: 16px;
        margin-top: 2px;
    }
    .foto-perfil-actual {
        width: 75px;
        height: 75px;
        margin: 0 auto 20px auto;
        border-radius: 50%;
        border-width: 3px;
        box-shadow: 0 1px 4px #0002;
    }
    .form-btn-block {
        gap: 12px;
    }
    .file-input-label, .btn-guardar, .btn-eliminar-foto {
        font-size: 1em;
        padding: 10px 0;
        min-width: 94px;
    }
    .btn-guardar, .btn-eliminar-foto {
        margin: 0;
        border-radius: 7px;
    }
    .btn-secondary {
        font-size: .97em;
        padding: 9px 14px;
        min-width: 98px;
    }
    .mb-3 {
        margin-top: 17px !important;
    }
}

</style>

<div class="perfil-container">
    <div class="perfil-title">Editar Foto de Perfil</div>
    <?php
        $fotoPerfil = $usuario['foto_perfil'] ?? '';
        $fotoSrc = $fotoPerfil ? BASE_URL . '/uploads/perfiles/' . $fotoPerfil : BASE_URL . '/img/defaultpfp.png';
    ?>
    <form method="POST" enctype="multipart/form-data" action="<?= BASE_URL ?>/admin/perfil" id="perfilForm">
        <img id="foto-preview" src="<?= $fotoSrc ?>" class="foto-perfil-actual" alt="Foto de perfil"
            onerror="this.onerror=null;this.src='<?= BASE_URL ?>/img/defaultpfp.png';">
        <input type="hidden" name="eliminar_foto" id="eliminar_foto_input" value="0">
        <div class="form-btn-block">
            <label for="foto" class="file-input-label">Seleccionar Nueva Foto</label>
            <?php if ($fotoPerfil): ?>
                <button type="button" class="btn-eliminar-foto" onclick="eliminarFotoVista()">Eliminar Foto de Perfil</button>
            <?php endif; ?>
            <button type="submit" class="btn-guardar">Guardar</button>
        </div>
    </form>
    <div class="mb-3" style="margin-top: 30px;">
        <a href="<?= BASE_URL ?>/admin" class="btn btn-secondary">Volver al Panel</a>
    </div>
</div>


<script>
function vistaPreviaFoto() {
    const input = document.getElementById('foto');
    const preview = document.getElementById('foto-preview');
    document.getElementById('eliminar_foto_input').value = '0';
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            var headerImg = document.getElementById('header-foto-perfil');
            if (headerImg) headerImg.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function eliminarFotoVista() {
    var preview = document.getElementById('foto-preview');
    preview.src = '<?= BASE_URL ?>/img/defaultpfp.png';
    document.getElementById('eliminar_foto_input').value = "1";
}
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
