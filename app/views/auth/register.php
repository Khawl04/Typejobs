<?php
$pageTitle = "Registrarse - TypeJobs";
require_once __DIR__ . '/../layouts/header.php';
?>

<style>
body { background: linear-gradient(115deg, #f3f5fa 60%, #e8fce3 100%);}
.auth-wrapper {
    max-width: 410px;
    margin: 52px auto 48px auto;
    background: #202331;
    padding: 36px 28px 28px 28px;
    border-radius: 16px;
    box-shadow: 0 7px 32px rgba(87,153,135,0.08), 0 1.5px 7px rgba(21,32,22,0.05);
    position:relative;
    text-align:center;
}
.auth-logo {
    width: 56px; height:56px;
    border-radius:50%;
    object-fit:cover;
    margin: 0 auto 9px auto;
    background:#e3fbe4;
    display:block;
    box-shadow:0 2px 12px rgba(30,60,40,.05);
}
.auth-title {
    font-size: 2em;
    color: #37b980;
    font-weight: 800;
    margin-bottom: 10px;
    letter-spacing: .01em;
}
.auth-subtitle {
    font-size:1.03em;
    color:#c3cecaff;
    margin-bottom:22px;
}
.grupo-formulario {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 16px;
    text-align:left;
}
.grupo-formulario label { color: #58926d;font-weight: 600;}
.grupo-formulario input,
.grupo-formulario select {
    padding: 11px;
    border: 1px solid #bdbdbd;
    border-radius: 7px;
    font-size: 16px;
    background: #f6f8f6;
    margin-bottom: 5px;
    font-family: inherit;
    transition:.13s border;
}
.grupo-formulario input:focus, .grupo-formulario select:focus { border:1.3px solid #2cd89b;}
.auth-btn {
    width: 100%;
    background: linear-gradient(90deg,#51cfa0 30%,#29be7b 90%);
    color: #fff;
    font-size: 17px;
    padding: 12px;
    border: none;
    border-radius: 7px;
    margin-top: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
    box-shadow: 0 1.5px 12px #8ff2c911;
}
.auth-btn:hover { background:#179e5f; }
.auth-link {
    display: block;
    text-align: center;
    margin-top: 18px;
    color: #26a077;
    text-decoration: underline;
    font-size: 15px;
}
/* Responsive */
@media (max-width: 500px){
    .auth-wrapper {margin:23px 2.5vw;}
    .auth-title{font-size:1.18em;}
}
</style>

<main>
    <div class="auth-wrapper">
        <!-- Logo arriba si lo tienes -->
        <img src="<?= BASE_URL ?>/img/typejobslogo.png" class="auth-logo" alt="Logo TypeJobs">
        <h1 class="auth-title">Registrarse</h1>
        <div class="auth-subtitle">Crea tu cuenta para aprovechar todos los beneficios de la plataforma.</div>
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
            