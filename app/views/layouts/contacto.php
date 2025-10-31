<?php
$pageTitle = "Contacto - TypeJobs";
require_once __DIR__ . '/layouts/header.php';
?>

<style>
    .content-page {
        min-height: 60vh;
        padding: 60px 20px;
        background-color: #f9f9f9;
    }
    .content-page h1 {
        color: #5a7355;
        margin-bottom: 30px;
        font-size: 36px;
        text-align: center;
    }
    .contact-form {
        max-width: 600px;
        margin: 30px auto;
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #5a5447;
        font-weight: 500;
    }
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #d0cbbe;
        border-radius: 6px;
        font-size: 16px;
        font-family: inherit;
    }
    .form-group textarea {
        min-height: 120px;
        resize: vertical;
    }
    .btn-submit {
        background-color: #5a7355;
        color: white;
        border: none;
        padding: 12px 32px;
        font-size: 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        width: 100%;
    }
    .btn-submit:hover {
        background-color: #3d4a38;
    }
</style>

<main class="content-page">
    <div class="container">
        <h1>Contáctanos</h1>
        <p style="text-align: center; margin-bottom: 30px;">¿Tienes alguna pregunta? Estamos aquí para ayudarte.</p>
        
        <form class="contact-form" action="<?= BASE_URL ?>/contacto/enviar" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre completo</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="asunto">Asunto</label>
                <input type="text" id="asunto" name="asunto" required>
            </div>
            
            <div class="form-group">
                <label for="mensaje">Mensaje</label>
                <textarea id="mensaje" name="mensaje" required></textarea>
            </div>
            
            <button type="submit" class="btn-submit">Enviar mensaje</button>
        </form>
    </div>
</main>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
