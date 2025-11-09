<?php 
require_once __DIR__ . '/layouts/header.php'; 
if (session_status() !== PHP_SESSION_ACTIVE) session_start(); 
?>

<style>
.hero {
    min-height: 82vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(120deg, #232a37 80%, #232a37 100%);
    padding: 0 20px;
    position: relative;
}
.hero-content {
    text-align: center;
    max-width: 720px;
    margin: 0 auto;
}
.hero-logo {
    width: 78px; height: 78px;
    border-radius: 50%;
    object-fit: cover;
    margin: 0 auto 16px auto;
    background: #d5eed6;
    box-shadow: 0 6px 34px rgba(40,80,60,.06);
    display: block;
}
.hero-title {
    font-size: 3.3em;
    font-weight: 900;
    color: #75e4a6;
    margin-bottom: 12px;
    letter-spacing: -.04em;
    text-shadow:0 2px 28px #9ce1b534;
}
.hero-subtitle {
    font-size: 1.28em;
    color: #75e4a6;
    margin-bottom: 34px;
    font-weight: 400;
}
.hero-actions {
    display: flex;
    justify-content: center;
    gap: 24px;
    margin-bottom: 14px;
}
.btn-main {
    background: linear-gradient(95deg,#10b981 60%,#1cb98e 100%);
    color: #202331;
    border: none;
    border-radius: 9px;
    padding: 15px 37px;
    font-size: 1.18em;
    font-weight: 700;
    text-shadow:0 1px 7px #0a5f4f22;
    cursor: pointer;
    text-decoration: none;
    box-shadow: 0 2px 9px rgba(80,130,80,0.07);
    transition: 0.18s;
}
.btn-main:hover {
    background:linear-gradient(92deg,#077d5a 20%,#47eea8 100%);
    color:#fff;
    transform: translateY(-2px) scale(1.035);
}
.btn-secondary {
    background: linear-gradient(95deg,#10b981 60%,#1cb98e 100%);
    color: #202331;
    border: 2px solid #a9ffe2;
    border-radius: 9px;
    padding: 14px 32px;
    font-size: 1.09em;
    font-weight: 700;
    text-decoration: none;
    margin-left: 5px;
    box-shadow: 0 2px 7px rgba(80,130,80,0.05);
    transition: 0.15s;
}
.btn-secondary:hover {
    background: #e6fff4;
    color: #07976d;
    border-color:#08e29d;
}
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.1em;
        margin-bottom: 10px;
    }

    .hero-subtitle {
        font-size: 1.1em;
        margin-bottom: 20px;
    }

    .hero-logo {
        width: 54px;
        height: 54px;
    }

    .hero-actions {
        flex-direction: column;
        gap: 14px;
    }

    .btn-main,
    .btn-secondary {
        width: 100%; 
        padding: 12px 0;
        font-size: 1em;
    }
}

</style>

<main class="hero">
    <div class="hero-content">
        <img class="hero-logo" src="<?= BASE_URL ?>/img/typejobslogo.png" alt="TypeJobs Logo"/>
        <h1 class="hero-title">TypeJobs</h1>
        <p class="hero-subtitle">Bienvenido a la plataforma de servicios freelance donde los clientes conectan con los mejores profesionales.<br> Encontrá talento o hacé crecer tu negocio rápidamente.</p>
        
        <?php if (empty($_SESSION['id_usuario'])): ?>
        <div class="hero-actions">
            <a href="<?= BASE_URL ?>/register" class="btn-main">Registrarse</a>
            <a href="<?= BASE_URL ?>/login" class="btn-secondary">Iniciar sesión</a>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
