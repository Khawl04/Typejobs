<?php
$pageTitle = "Iniciar sesión - TypeJobs";
require_once __DIR__ . '/../layouts/header.php';
?>

<style>
body { background:#274c3d;}
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
    margin-bottom: 14px;
    letter-spacing: .01em;
}
.auth-subtitle {
    font-size:1.03em;
    color: #c3cecaff;
    margin-bottom:22px;
}
.form-group {
    margin-bottom: 18px;
    text-align:left;
}
.form-group label { color: #58926d; font-weight: 600;}
.form-group input {
    width: 100%;
    padding: 11px;
    border: 1px solid #bdbdbd;
    border-radius: 7px;
    font-size: 16px;
    background: #f6f8f6;
    margin-bottom: 5px;
    transition: .13s border;
}
.form-group input:focus { border:1.3px solid #2cd89b;}
.auth-btn {
    width: 100%;
    background: #29be7b;
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
        <img src="<?= BASE_URL ?>/img/typejobslogo.png" class="auth-logo" alt="Logo TypeJobs">
        <h1 class="auth-title">Iniciar sesión</h1>
        <div class="auth-subtitle">Accede a tu cuenta y conecta con la comunidad TypeJobs.</div>
        <?php if (!empty($mensaje_error)): ?>
            <div style="color:#b13a3a; margin-bottom:13px; text-align:center;">
                <?= htmlspecialchars($mensaje_error) ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="<?= BASE_URL ?>/login">
            <div class="form-group">
                <label for="usuario">Usuario o correo</label>
                <input type="text" id="usuario" name="usuario" required placeholder="Tu email o usuario">
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" required placeholder="Tu contraseña">
            </div>
            <button type="submit" class="auth-btn">Entrar</button>
        </form>
        <a class="auth-link" href="<?= BASE_URL ?>/register">¿No tienes cuenta? Regístrate</a>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
