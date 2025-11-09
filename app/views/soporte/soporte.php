<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
body {
    background: #f5f7fa;
}

.soporte-wrap {
    max-width: 980px;
    margin: 64px auto 30px auto;
    padding: 38px 17px 33px 17px;
    background: #232a37;
    border-radius: 18px;
    box-shadow: 0 11px 30px rgba(50, 60, 70, .07);
}

.soporte-title {
    font-size: 2.1em;
    color: #75e4a6;
    text-align: center;
    font-weight: 800;
    margin-bottom: 16px;
}

.faq-head {
    font-size: 1.28em;
    color: #8faca6ff;
    margin: 40px 0 12px 0;
    font-weight: 700;
}

.faqs {
    margin-bottom: 26px;
}

.faq-item {
    background: #232a37;
    margin: 0 0 9px 0;
    padding: 17px 24px;
    border-radius: 9px;
    font-size: 1.07em;
    color: #92c7a9ff;
    box-shadow: 0 1.5px 7px rgba(80, 120, 110, .035);
}

.faq-q {
    font-weight: 600;
    color: #8faca6ff;
}

.soporte-chat {
    background: #8faca6ff;
    border-radius: 12px;
    padding: 18px 22px 10px 22px;
    max-width: 490px;
    margin: 32px auto 0 auto;
    box-shadow: 0 1px 6px rgba(68, 122, 110, .06);
}

.soporte-chat h2 {
    font-size: 1.18em;
    font-weight: 700;
    color: #274c3d;
    margin-bottom: 11px;
}

.chat-form label {
    font-size: .97em;
    font-weight: 600;
    color: #274c3d;
    margin-bottom: 3.5px;
    display: block;
}

.chat-form input,
.chat-form textarea {
    width: 100%;
    border-radius: 7px;
    border: 1.1px solid #97d6b2;
    padding: 12px;
    margin-bottom: 12px;
    font-size: 1em;
    background: #f8fafb;
}

.chat-form textarea {
    min-height: 62px;
    max-height: 160px;
    resize: vertical;
}

.chat-form button {
    background: linear-gradient(90deg, #355a48ff 0, #0f502eff 95%);
    color: #fff;
    padding: 12px 0;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    width: 100%;
    font-size: 1.14em;
    cursor: pointer;
    transition: background .22s;
}

.chat-form button:hover {
    background: linear-gradient(85deg, #436d63 5%, #7bb891 95%)
}

.msg-enviado {
    background: #e0ffe5;
    color: #206843;
    border: 1px solid #b7ebbc;
    margin-bottom: 13px;
    border-radius: 8px;
    padding: 12px;
    text-align: center;
    font-weight: 600;
}
</style>

<main class="soporte-wrap">
    <div class="soporte-title">Soporte y Ayuda</div>
    <div class="faq-head">Preguntas Frecuentes</div>
    <div class="faqs">
        <div class="faq-item">
            <span class="faq-q">¿Cómo registro mi perfil en TypeJobs?</span><br>
            Simplemente ve a <a href="<?= BASE_URL ?>/register">registro</a>, completa tus datos y valida el correo.
        </div>
        <div class="faq-item">
            <span class="faq-q">¿Cómo contacto a un profesional?</span><br>
            Busca en <a href="<?= BASE_URL ?>/servicio">Servicios</a>, ver detalles y contáctalo desde el botón
            "Contactame".
        </div>
        <div class="faq-item">
            <span class="faq-q">¿Cómo califico a un profesional?</span><br>
            Tras un trabajo terminado, ingresa al detalle del servicio y deja tu calificación y reseña.
        </div>
        <div class="faq-item">
            <span class="faq-q">¿Cómo edito mi perfil o servicios?</span><br>
            En tu menú usuario, elige "Perfil" o "Mis servicios" y modifica lo necesario.
        </div>
        <div class="faq-item">
            <span class="faq-q">¿Cómo cambio mi correo o contraseña?</span><br>
            Desde tu panel de usuario, en "Mi Perfil" puedes actualizar ambas fácilmente.
        </div>
        <div class="faq-item">
            <span class="faq-q">¿Qué hago si tengo un problema con un pago?</span><br>
            Escríbenos a través del formulario de soporte o al correo soporte y lo resolveremos.
        </div>
        <div class="faq-item">
            <span class="faq-q">¿Puedo cancelar una reserva ya contratada?</span><br>
            Sí eres un cliente,puedes cancelar la reserva en la pestaña <a href="<?= BASE_URL ?>/cliente/reservas">Mis
                Reservas</a>.
        </div>
        <div class="faq-item">
            <span class="faq-q">¿Cómo denuncio a un usuario o servicio?</span><br>
            Contacta con el soporte a través del formulario de soporte o al correo soporte.
        </div>
        <div class="faq-item">
            <span class="faq-q">¿Cómo puedo mejorar mi perfil en las búsquedas?</span><br>
            Mantén tus datos actualizados, buenas calificaciones y servicios atractivos con descripciones claras.
        </div>
    </div>

    <section class="soporte-chat">
        <h2>¿No resolviste tu duda? Escribinos:</h2>
        <?php if (isset($_POST['soportemsg'])): ?>
        <div class="msg-enviado">¡Tu mensaje fue enviado y será respondido a la brevedad!</div>
        <?php endif; ?>
        <form class="chat-form" action="" method="POST" autocomplete="off">
            <label for="s-nombre">Nombre</label>
            <input type="text" name="soporte_nombre" id="s-nombre" required value="">
            <label for="s-email">Email</label>
            <input type="email" name="soporte_email" id="s-email" required value="">
            <label for="s-msg">Mensaje</label>
            <textarea name="soportemsg" id="s-msg" required></textarea>
            <button type="submit">Enviar Mensaje</button>
        </form>
    </section>
</main>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>