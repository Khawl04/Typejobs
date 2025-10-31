<?php
$pageTitle = "Registrarse - TypeJobs";
require_once __DIR__ . '/../layouts/header.php';
?>

<style>
.auth-wrapper {
    max-width: 400px;
    margin: 40px auto;
    background: #fff;
    padding: 32px 28px;
    border-radius: 12px;
    box-shadow: 0 2px 14px rgba(50,50,50,0.09);
}
.auth-title {
    text-align: center;
    font-size: 28px;
    color: #5a7355;
    margin-bottom: 24px;
    font-weight: 700;
}
.grupo-formulario {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 16px;
}
.grupo-formulario label {
    color: #5a7355;
    font-weight: 500;
    margin-bottom: 2px;
}
.grupo-formulario input,
.grupo-formulario select {
    padding: 11px;
    border: 1px solid #bdbdbd;
    border-radius: 7px;
    font-size: 16px;
    background: #f6f8f6;
    margin-bottom: 5px;
    font-family: inherit;
}
.auth-btn {
    display: block;
    width: 100%;
    background: #5a7355;
    color: white;
    font-size: 17px;
    padding: 12px;
    border: none;
    border-radius: 7px;
    margin-top: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}
.auth-btn:hover {
    background: #3d4a38;
}
.auth-link {
    display: block;
    text-align: center;
    margin-top: 18px;
    color: #7a9475;
    text-decoration: underline;
    font-size: 15px;
}
</style>

<main>
    <div class="auth-wrapper">
        <h1 class="auth-title">Registrarse</h1>
        <?php if (!empty($mensaje_error)): ?>
    <div style="color:#b13a3a; margin-bottom:15px; text-align:center;">
        <?= htmlspecialchars($mensaje_error) ?>
    </div>
<?php endif; ?>
<?php if (!empty($mensaje_exito)): ?>
    <div style="color:#357f38; margin-bottom:15px; text-align:center;">
        <?= htmlspecialchars($mensaje_exito) ?>
    </div>
<?php endif; ?>
        <form method="POST" action="<?= BASE_URL ?>/register">
            <span class="grupo-formulario">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required>
                
                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" required>
                
                <label for="nomusuario">Nombre de usuario</label>
                <input type="text" id="nomusuario" name="nomusuario" required>
                
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                
                <label for="telefono">Teléfono (opcional)</label>
                <input type="tel" id="telefono" name="telefono" maxlength="20" pattern="\d{1,20}" title="Debe contener 20 o menos dígitos">

                
                <label for="tipo_usuario">Tipo de cuenta</label>
                <select id="tipo_usuario" name="tipo_usuario" required>
                    <option value="">Seleccionar</option>
                    <option value="CLIENTE">Cliente</option>
                    <option value="PROVEEDOR">Proveedor</option>
                    
                </select>
                
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" required>
                
                <label for="confirmarcontrasena">Confirmar contraseña</label>
                <input type="password" id="confirmarcontrasena" name="confirmarcontrasena" required>
            </span>
            <button type="submit" class="auth-btn">Crear cuenta</button>
        </form>
        <a class="auth-link" href="<?= BASE_URL ?>/login">¿Ya tienes cuenta? Inicia sesión</a>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
