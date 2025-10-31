<?php
$pageTitle = "Sobre TypeJobs";
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
    .content-page p {
        max-width: 800px;
        margin: 0 auto 20px;
        font-size: 18px;
        line-height: 1.8;
        color: #5a5447;
    }
</style>

<main class="content-page">
    <div class="container">
        <h1>Sobre TypeJobs</h1>
        <p>TypeJobs es una plataforma de servicios freelance que conecta profesionales con clientes que necesitan sus servicios.</p>
        <p>Nuestra misión es facilitar la contratación de servicios de calidad de manera rápida, segura y eficiente.</p>
        <p>Ofrecemos un espacio donde freelancers pueden mostrar su trabajo y clientes pueden encontrar el talento que necesitan.</p>
    </div>
</main>

<?php require_once __DIR__ . 'layouts/footer.php'; ?>
