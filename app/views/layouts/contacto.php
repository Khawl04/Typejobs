<?php
$pageTitle = "Contacto - TypeJobs";
require_once __DIR__ . '/header.php';

// Mensaje de éxito y limpieza de variables al enviar
$formEnviado = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $formEnviado = true;
    // Aquí podrías procesar/envíar email/guardar, según necesites.
    $_POST = [];
}
?>
<style>
body { background:#274c3d;}
.content-page {
    min-height: 67vh;
    padding: 48px 13px;
    background: #232a37 ;
    max-width: 690px;
    margin: 42px auto 64px auto;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(44,62,80,0.10), 0 1.5px 6px rgba(22,42,112,0.04);
}
.content-page h1 {
    color: #75e4a6;
    font-size: 2.5em;
    font-weight: 700;
    text-align: center;
    margin-bottom: 16px;
    letter-spacing: 1px;
}
.intro {
    color: #9b9999ff;
    font-size:1.19em;
    text-align:center;
    margin-bottom:30px;
}
.contact-form {
    margin: 0 auto;
    width: 100%;
}
.form-group {
    margin-bottom: 22px;
}
.form-group label {
    font-weight: 600;
    color: #9b9999ff;
    font-size: 1em;
    margin-bottom: 0.4em;
    display: block;
}
.form-group input,
.form-group textarea {
    width: 100%;
    padding: 14px;
    border: 1px solid #cfd8dc;
    border-radius: 7px;
    font-size: 17px;
    outline: none;
    box-sizing: border-box;
    margin-top: 4px;
    background: #f8fafc;
    transition: border 0.2s;
}
.form-group input:focus,
.form-group textarea:focus {
    border: 1.5px solid #7bb891;
    background: #f6fff9;
}
.form-group textarea {
    min-height: 110px;
    resize: vertical;
}
.btn-submit {
    background: linear-gradient(90deg, #5e947a 0%, #42786e 100%);
    color: #fff;
    border: none;
    font-size: 1.16em;
    padding: 12px 0;
    border-radius: 7px;
    font-weight: 600;
    cursor: pointer;
    width: 100%;
    margin-top: 5px;
    transition: background 0.2s;
    letter-spacing: 1px;
}
.btn-submit:hover {
    background: linear-gradient(90deg, #42786e 10%, #5e947a  100%);
}
.succes-msg {
    background: #e2f8e5;
    border: 1px solid #c8ecd3;
    color: #30714d;
    border-radius: 7px;
    padding: 18px;
    text-align: center;
    margin-bottom: 28px;
    font-weight: 600;
    font-size: 1.1em;
}
</style>

<main class="content-page">
    <h1>Contáctanos</h1>
    <div class="intro">¿Tienes alguna pregunta? Estamos aquí para ayudarte.</div>

    <?php if ($formEnviado): ?>
        <div class="succes-msg">¡Tu mensaje fue enviado correctamente!</div>
    <?php endif; ?>

    <form class="contact-form" action="" method="POST" autocomplete="off">
        <div class="form-group">
            <label for="nombre">Nombre completo</label>
            <input type="text" id="nombre" name="nombre" required value="">
        </div>
        <div class="form-group">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" required value="">
        </div>
        <div class="form-group">
            <label for="asunto">Asunto</label>
            <input type="text" id="asunto" name="asunto" required value="">
        </div>
        <div class="form-group">
            <label for="mensaje">Mensaje</label>
            <textarea id="mensaje" name="mensaje" required></textarea>
        </div>
        <button type="submit" class="btn-submit">Enviar mensaje</button>
    </form>
</main>
<?php require_once __DIR__ . '/footer.php'; ?>
