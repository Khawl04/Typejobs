<?php
$pageTitle = "Sobre TypeJobs";
require_once __DIR__ . '/header.php';
?>

<style>
body { background:#274c3d;}

.sobre-section {
    max-width: 800px;
    background: #232a37;
    margin: 52px auto 80px auto;
    padding: 54px 36px 44px 36px;
    border-radius: 16px;
    box-shadow: 0 10px 32px rgba(60, 70, 110, 0.07), 0 2.5px 10px rgba(46, 68, 112, 0.09);
    color: #29443c;
}

.sobre-section h1 {
    font-size: 2.5em;
    font-weight: 800;
    color: #96b196ff; 
    margin-bottom: 18px;
    letter-spacing: .5px;
    text-align: center;
}

.sobre-section .slogan {
    font-size: 1.25em;
    color: #96b196ff;
    margin-bottom: 24px;
    text-align: center;
    font-weight: 600;
}

.sobre-section p {
    font-size: 1.17em;
    color: #75e4a6;
    margin-bottom: 16px;
    line-height: 1.76;
    text-align: center;
}

.icn {
    display: block;
    width: 60px;
    height: 60px;
    margin: 0 auto 26px auto;
    background: linear-gradient(135deg, #cee7e5 60%, #b8d5c2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.pilares {
    margin: 44px 0 12px 0;
    display: flex;
    gap: 28px;
    flex-wrap: wrap;
    justify-content: center;
}

.pilar-card {
    background: #6f7886ff;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(44, 70, 100, .022);
    padding: 22px 29px;
    max-width: 270px;
    margin: 0 8px 18px 8px;
    text-align: center;
}

.pilar-card h3 {
    color: #335e44;
    font-size: 1.13em;
    font-weight: bold;
    margin-bottom: 7px;
}

.pilar-card p {
    color: #75e4a6;
    font-size: .98em;
    margin-bottom: 0;
    line-height: 1.5;
}

.icn {
    background:#e5eee5;
    display:flex;
    align-items:center;
    justify-content:center;
    width:68px;
    height:68px;
    margin:0 auto 26px auto;
    border-radius:50%;
    box-shadow:0 2px 8px rgba(80,120,110,.08);
    overflow: hidden;}

</style>

<main class="sobre-section">
    <span class="icn">
        <img src="<?= BASE_URL ?>/img/typejobslogo.png" alt="TypeJobs Logo"
            style="width:54px;height:54px;object-fit:contain;border-radius:50%;box-shadow:0 2px 8px rgba(80,120,110,.08);background:#e5eee5;">
    </span>

    <h1>Sobre TypeJobs</h1>
    <div class="slogan">Impulsando conexiones de valor entre talento y necesidad</div>

    <p>TypeJobs es una plataforma de servicios freelance que conecta profesionales con clientes que necesitan sus
        servicios.</p>
    <p>Nuestra misión es facilitar la contratación de servicios de calidad de manera rápida, segura y eficiente.</p>
    <p>Ofrecemos un espacio donde freelancers pueden mostrar su trabajo y clientes pueden encontrar el talento que
        necesitan.</p>

    <div class="pilares">
        <div class="pilar-card">
            <h3>Rapidez y facilidad</h3>
            <p>Encuentra y contrata en minutos. Todo el proceso es ágil y seguro.</p>
        </div>
        <div class="pilar-card">
            <h3>Confianza garantizada</h3>
            <p>Validamos perfiles y opiniones para que tu experiencia siempre sea positiva.</p>
        </div>
        <div class="pilar-card">
            <h3>Visibilidad real</h3>
            <p>Freelancers pueden mostrar su portafolio y recibir oportunidades relevantes.</p>
        </div>
        <div class="pilar-card">
            <h3>Comunidad</h3>
            <p>Un lugar donde profesionales y clientes crecen juntos, potenciando el trabajo independiente.</p>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>