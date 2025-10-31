<?php
$pageTitle = "Iniciar sesión - TypeJobs";
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
.form-group {
    margin-bottom: 18px;
}
.form-group label {
    display: block;
    color: #5a7355;
    margin-bottom: 5px;
    font-weight: 500;
}
.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #bdbdbd;
    border-radius: 7px;
    font-size: 16px;
    font-family: inherit;
    background: #f6f8f6;
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
        <h1 class="auth-title">Iniciar sesión</h1>

        <!-- Mensaje de error, si lo hay -->
        <?php if (!empty($mensaje_error)): ?>
            <div style="color:#b13a3a; margin-top:20px; text-align:center;">
                <?= htmlspecialchars($mensaje_error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/login">
            <div class="form-group">
                <label for="usuario">Usuario o correo</label>
                <input type="text" id="usuario" name="usuario" required>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>
            <button type="submit" class="auth-btn">Entrar</button>
        </form>
        <a class="auth-link" href="<?= BASE_URL ?>/register">¿No tienes cuenta? Regístrate</a>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
