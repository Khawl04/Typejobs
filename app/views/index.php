<?php
require_once __DIR__ . '/layouts/header.php';
?>

<style>
    /* Hero Section */
    .hero {
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #e8e4d9;
        padding: 60px 20px;
    }
    
    .hero-content {
        text-align: center;
        max-width: 800px;
    }
    
    .hero-title {
        font-size: 96px;
        font-weight: 700;
        color: #5a7355;
        margin-bottom: 20px;
        letter-spacing: -2px;
    }
    
    .hero-subtitle {
        font-size: 20px;
        color: #5a5447;
        margin-bottom: 40px;
        font-weight: 400;
    }
    
    .hero-actions {
        display: flex;
        justify-content: center;
        gap: 20px;
    }
    
    .btn {
        display: inline-block;
        padding: 12px 32px;
        font-size: 16px;
        font-weight: 500;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .btn-primary {
        background-color: transparent;
        color: #5a5447;
        border: 2px solid #9ca396;
    }
    
    .btn-primary:hover {
        background-color: #f0ede4;
        border-color: #5a7355;
        color: #3d4a38;
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 56px;
        }
        .hero-subtitle {
            font-size: 18px;
        }
    }
    
</style>

<main class="hero">
    <div class="hero-content">
        <h1 class="hero-title">TypeJobs</h1>
        <p class="hero-subtitle">Bienvenido a TypeJobs Servicio de Contrataciones</p>
        <div class="hero-actions">
            <a href="<?= BASE_URL ?>/register" class="btn btn-primary">Registrarse</a>
            <a href="<?= BASE_URL ?>/login" class="btn btn-primary">Iniciar sesión</a>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
