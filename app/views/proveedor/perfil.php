<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<style>
body {
background: #274c3d;
}
.perfil-main-container {
    max-width: 900px;
    margin: 42px auto 0;
    background: #232a37;
    border-radius: 20px;
    box-shadow: 0 2px 12px rgba(40, 40, 90, 0.11);
    padding: 42px 28px 32px 28px;
    margin-bottom: 64px;
}

.perfil-header-row {
    display: flex;
    align-items: center;
    gap: 26px;
    margin-bottom: 39px;
}

.perfil-avatar {
    width: 80px;
    height: 80px;
    background: #8eadcf;
    border-radius: 50%;
    color: #223;
    font-weight: 700;
    font-size: 2.8em;
    display: flex;
    align-items: center;
    justify-content: center;
}

.perfil-nombre {
    font-size: 1.7em;
    font-weight: 600;
    color: #75e4a6;
}

.perfil-rol {
    font-size: 1.13em;
    color: #7688a8;
    margin-top: 3px;
}

.perfil-edit-form label {
    font-weight: 500;
    color: #7688a8;
    margin-top: 17px;
}

.perfil-edit-form input,
.perfil-edit-form textarea {
    width: 100%;
    padding: 8px 11px;
    margin-top: 8px;
    margin-bottom: 15px;
    font-size: 1.07em;
    background: #f5f7fa;
    border-radius: 8px;
    border: 1px solid #c6d3e3;
}

.perfil-edit-form textarea {
    resize: vertical;
    min-height: 75px;
}

.btn-guardar-perfil {
    background: #2563eb;
    color: #fff;
    font-weight: bold;
    padding: 11px 36px;
    font-size: 1.14em;
    border-radius: 8px;
    border: 0;
    margin-top: 11px;
    cursor: pointer;
    transition: background 0.18s;
}

.btn-guardar-perfil:hover {
    background: #1845a7;
}

.perfil-mensaje {
    padding: 13px 20px;
    border-radius: 10px;
    margin-bottom: 19px;
}

.perfil-mensaje.success {
    background: #d3f9dc;
    color: #116332;
}

.perfil-mensaje.error {
    background: #ffe0e0;
    color: #a33232;
}

.perfil-avatar-img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 50%;
    box-shadow: 0 1px 3px #bbb;
    background: #eee;
    display: inline-block;
    margin-right: 20px;
}

input[type="file"] {
    margin-bottom: 15px;
}
</style>

<div class="perfil-main-container">

    <div class="perfil-header-row">
        <?php if (!empty($proveedor['foto_perfil'])): ?>
        <img src="<?= BASE_URL ?>/uploads/perfiles/<?= htmlspecialchars($proveedor['foto_perfil']) ?>"
            class="perfil-avatar-img" alt="Foto de perfil">
        <?php else: ?>
        <div class="perfil-avatar">
            <?= strtoupper(substr($proveedor['nombre'] ?? '', 0, 1)); ?>
            <?= strtoupper(substr($proveedor['apellido'] ?? '', 0, 1)); ?>
        </div>
        <?php endif; ?>
        <div>
            <div class="perfil-nombre">
                <?= htmlspecialchars($proveedor['nombre'] ?? '') ?>
                <?= htmlspecialchars($proveedor['apellido'] ?? '') ?>
            </div>
            <div class="perfil-rol">Proveedor verificado</div>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
    <div class="perfil-mensaje success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
    <div class="perfil-mensaje error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form class="perfil-edit-form" method="POST" action="<?= BASE_URL ?>/proveedor/perfil"
        enctype="multipart/form-data">
        <label for="foto_perfil">Foto de perfil</label>
        <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*">

        <label for="nombre_usuario">Nombre de usuario</label>
        <input type="text" required name="nomusuario" id="nomusuario"
            value="<?= htmlspecialchars($proveedor['nomusuario'] ?? '') ?>">

        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($proveedor['email'] ?? '') ?>">

        <label for="telefono">Teléfono</label>
        <input type="text" name="telefono" id="telefono" value="<?= htmlspecialchars($proveedor['telefono'] ?? '') ?>">

        <label for="direccion">Dirección</label>
        <input type="text" name="direccion" id="direccion"
            value="<?= htmlspecialchars($proveedor['direccion'] ?? '') ?>">

        <label for="descripcion">Descripción profesional/bio</label>
        <textarea name="descripcion"
            id="descripcion"><?= htmlspecialchars($proveedor['descripcion'] ?? '') ?></textarea>

        <label for="contrasena_actual">Contraseña actual</label>
        <input type="password" name="contrasena_actual" id="contrasena_actual" autocomplete="current-password"
            placeholder="Contraseña actual">

        <label for="nueva_contrasena">Nueva contraseña</label>
        <input type="password" name="nueva_contrasena" id="nueva_contrasena" autocomplete="new-password"
            placeholder="Nueva contraseña">

        <div style="display: flex; width: 100%; justify-content: space-between; align-items: center; margin-top: 22px;">
            <button type="submit" class="btn-guardar-perfil">Guardar cambios</button>
        </div>
    </form>

    <!-- Form de eliminar cuenta SEPARADO y en la otra punta -->
    <form method="post" action="<?= BASE_URL ?>/proveedor/eliminarCuenta"
          onsubmit="return confirm('¿Seguro que deseas eliminar tu cuenta de proveedor?');"
          style="margin-top: -52px; float: right;">
        <button type="submit" class="btn"
            style="background:#ef4444;color:#fff;border-radius:8px;padding:13px 24px;font-weight:700;">
            Eliminar cuenta
        </button>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>