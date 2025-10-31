<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
body {
    background: #263142;
}

.dashboard-mensajes {
    display: flex;
    min-height: 600px;
    background: #263142;
}

.conversaciones-panel {
    width: 340px;
    background: #1d2531;
    color: white;
    height: 600px;
    border-radius: 16px 0 0 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.conversaciones-panel h2 {
    margin: 0;
    font-size: 1.15rem;
    padding: 24px 26px 0;
}

.conversacion-list {
    overflow-y: auto;
    flex: 1;
    margin: 0;
    padding: 0;
    list-style: none;
}

.conversacion-item {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 17px 23px;
    border-left: 4px solid transparent;
    border-bottom: 1px solid #242c39;
    background: transparent;
    transition: 0.14s;
}

.conversacion-item.active,
.conversacion-item:hover {
    background: #23304a;
    border-left: 4px solid #7ce89c;
    cursor: pointer;
}

.conversacion-avatar {
    width: 43px;
    height: 43px;
    border-radius: 50%;
    background: #8eadcf;
    color: #223;
    padding: 2px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.21em;
    overflow: hidden;
}

.avatar-img {
    width: 43px;
    height: 43px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 1px 6px #0002;
}

.conversacion-info {
    flex: 1;
}

.conversacion-nombre {
    font-weight: 700;
    margin-bottom: 2px;
}

.conversacion-hora {
    font-size: .89rem;
    float: right;
    color: #b2c0d2;
}

.conversacion-preview {
    font-size: 1.01rem;
    color: #dde3ef;
}

.badge-no-leidos {
    background: #43e06c;
    color: #161e29;
    border-radius: 10px;
    padding: 2px 8px;
    font-size: 12px;
    font-weight: bold;
    margin-top: 4px;
    display: inline-block;
}

/* Chat */
.chat-panel {
    flex: 1;
    background: white;
    border-radius: 0 16px 16px 0;
    min-width: 340px;
    display: flex;
    flex-direction: column;
    position: relative;
}

.chat-header {
    padding: 26px 33px 10px;
    background: transparent;
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 13px;
    position: relative;
    background-color: #d6d6d6ff;
}

.chat-header-separador {
    position: absolute;
    left: 0;
    right: 0;
    bottom: -3px;
    height: 2px;
    background: rgba(170, 170, 170, .11);
}

.chat-header-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
    font-size: 1.18em;
    font-weight: 600;
    color: #162238;
}

.chat-mensajes {
    flex: 1;
    padding: 13px 33px 4px 33px;
    overflow-y: auto;
    max-height: 395px;
}

.mensaje-row {
    display: flex;
    align-items: flex-end;
    margin-bottom: 20px;
}

.mensaje-row.enviado {
    flex-direction: row-reverse;
}

.mensaje-bubble {
    background: #7ce89c;
    color: #191c12;
    padding: 14px 23px;
    border-radius: 22px;
    font-size: 1.08rem;
    max-width: 65%;
    margin: 0 10px;
    line-height: 1.43;
}

.mensaje-row.enviado .mensaje-bubble {
    background: #51b977;
    color: white;
}

.mensaje-hora {
    font-size: 0.91em;
    color: #6f6f6f;
    margin-top: 3px;
}

.chat-input-bar {
    display: flex;
    align-items: center;
    padding: 20px 33px 20px 33px;
    background: #f2f8fa;
    border: 0;
    border-radius: 0 0 16px 0;
    position: sticky;
    bottom: 0;
    z-index: 9;
}

.chat-input-bar input {
    flex: 1;
    padding: 10.5px 18px;
    font-size: 1.03em;
    border-radius: 16px;
    border: 1px solid #ccc;
}

.chat-input-bar button {
    margin-left: 12px;
    background: #43e06c;
    color: #232;
    padding: 10px 20px;
    font-size: 1.2em;
    border: 0;
    border-radius: 100px;
    cursor: pointer;
}

.btn-adjuntar {
    margin-left: 12px;
    margin-right: 0;
    display: flex;
    align-items: center;
}

.icon-clip {
    background: #43e06c;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 100px;
    width: 42px;
    height: 42px;
    cursor: pointer;
    font-size: 1.2em;
    color: #232;
    margin-right: 10px;
    user-select: none;
    transition: background 0.2s;
    border: none;
    outline: none;
}

.icon-clip:active,
.icon-clip:focus,
.icon-clip:hover {
    background: #36be51;
}
</style>

<div class="dashboard-mensajes">
    <div class="conversaciones-panel">
        <h2>Conversaciones</h2>
        <ul class="conversacion-list">
            <?php foreach($conversaciones as $conv): ?>
            <li class="conversacion-item <?= ($chat_activo && $conv['id_otro_usuario'] == $chat_activo['id_otro_usuario']) ? 'active' : '' ?>">
                <a href="?chat=<?= $conv['id_otro_usuario'] ?>" style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:13px;width:100%;">
                    <?php if (!empty($conv['avatar']) && $conv['avatar'] !== BASE_URL . '/img/defaultpfp.png'): ?>
                        <img src="<?= htmlspecialchars($conv['avatar']) ?>" class="avatar-img">
                    <?php else: ?>
                        <img src="<?= BASE_URL . '/img/defaultpfp.png' ?>" class="avatar-img">
                    <?php endif; ?>
                    <div class="conversacion-info">
                        <div class="conversacion-nombre"><?= htmlspecialchars($conv['nombre_otro_usuario'] ?? 'Usuario') ?></div>
                        <div class="conversacion-preview"><?= htmlspecialchars($conv['ultimo_mensaje'] ?? '') ?></div>
                        <?php if (!empty($conv['no_leidos'])): ?>
                        <span class="badge-no_leidos"><?= $conv['no_leidos'] ?></span>
                        <?php endif; ?>
                    </div>
                    <span class="conversacion-hora"><?= htmlspecialchars($conv['hora_ultimo'] ?? '') ?></span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="chat-panel">
        <?php if($chat_activo): ?>
        <div class="chat-header">
            <?php if (!empty($chat_activo['avatar']) && $chat_activo['avatar'] !== BASE_URL . '/img/defaultpfp.png'): ?>
                <img src="<?= htmlspecialchars($chat_activo['avatar']) ?>" class="avatar-img">
            <?php else: ?>
                <img src="<?= BASE_URL . '/img/defaultpfp.png' ?>" class="avatar-img">
            <?php endif; ?>
            <div class="chat-header-info">
                <span>
                    <?= htmlspecialchars($chat_activo['nombre_otro_usuario'] ?? 'Usuario') ?>
                </span>
            </div>
            <div class="chat-header-separador"></div>
        </div>
        <div class="chat-mensajes" id="chatMensajes">
            <?php foreach($mensajes_chat as $msg): ?>
            <div class="mensaje-row <?= $msg['id_usuario'] == $_SESSION['id_usuario'] ? 'enviado' : '' ?>">
                <div class="mensaje-bubble">
                    <?php if (!empty($msg['contenido'])): ?>
                        <?= htmlspecialchars($msg['contenido']) ?><br>
                    <?php endif; ?>
                    <?php if (!empty($msg['archivo_adjunto'])): ?>
                        <?php
                        $ext = strtolower(pathinfo($msg['archivo_adjunto'], PATHINFO_EXTENSION));
                        $esImagen = in_array($ext, ['jpg','jpeg','png','gif','bmp','webp']);
                        ?>
                        <?php if ($esImagen): ?>
                            <a href="<?= BASE_URL . '/' . $msg['archivo_adjunto'] ?>" target="_blank">
                                <img src="<?= BASE_URL . '/' . $msg['archivo_adjunto'] ?>" alt="Adjunto" style="max-width:120px;max-height:120px;border-radius:8px;display:block;margin-top:7px;">
                            </a>
                        <?php else: ?>
                            <a href="<?= BASE_URL . '/' . $msg['archivo_adjunto'] ?>" download style="color:#2980b9;font-weight:bold;text-decoration:none" title="Descargar archivo">
                                <?= htmlspecialchars(basename($msg['archivo_adjunto'])) ?>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="mensaje-hora">
                        <?= !empty($msg['fecha_envio']) ? date('H:i', strtotime($msg['fecha_envio'])) : '' ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <form class="chat-input-bar" method="POST"
            action="<?= BASE_URL ?>/mensaje?chat=<?= $chat_activo['id_otro_usuario'] ?>" autocomplete="off"
            enctype="multipart/form-data">
            <input type="hidden" name="id_destinatario" value="<?= $chat_activo['id_otro_usuario'] ?>">

            <!-- Previsualización de adjunto -->
            <div id="adjunto-preview"
                style="display:none; align-items:center; gap:9px; margin-right:10px; margin-top:-8px;">
                <img id="preview-img" src=""
                    style="max-width: 56px; max-height:44px; border-radius:7px; display:none; margin-right:6px;">
                <span id="preview-nombre" style="color:#454545;font-size:.98em;display:none;"></span>
                <button type="button" onclick="quitarAdjunto()"
                    style="margin-left:7px; border:none; background:#fff; color:#e74c3c; font-size:1.4em; border-radius:99px; cursor:pointer; padding: 0 10px;">&times;</button>
            </div>

            <input type="text" name="contenido" placeholder="Escribir mensaje...">
            <!-- Botón de adjuntar -->
            <label class="btn-adjuntar">
                <input type="file" name="archivo" id="adjuntoInput" style="display:none;" onchange="archivoSeleccionado()">
                <span class="icon-clip" title="Adjuntar archivo">&#128206;</span>
            </label>
            <button type="submit">&#9658;</button>
        </form>
        <?php else: ?>
        <div style="padding:80px;text-align:center;color:#333;">Selecciona una conversación</div>
        <?php endif; ?>
    </div>
</div>


<script>
window.onload = function() {
    var msgs = document.getElementById('chatMensajes');
    if (msgs) msgs.scrollTop = msgs.scrollHeight;
};

function archivoSeleccionado() {
    var input = document.getElementById('adjuntoInput');
    var previewDiv = document.getElementById('adjunto-preview');
    var img = document.getElementById('preview-img');
    var nombre = document.getElementById('preview-nombre');
    previewDiv.style.display = input.files.length ? "flex" : "none";
    img.style.display = nombre.style.display = "none";
    if (input.files.length && input.files[0].type.startsWith('image/')) {
        var reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            img.style.display = "inline-block";
        };
        reader.readAsDataURL(input.files[0]);
    } else if (input.files.length) {
        nombre.textContent = input.files[0].name;
        nombre.style.display = "inline-block";
    }
}

function quitarAdjunto() {
    var input = document.getElementById('adjuntoInput');
    input.value = "";
    document.getElementById('adjunto-preview').style.display = "none";
    document.getElementById('preview-img').style.display = "none";
    document.getElementById('preview-nombre').style.display = "none";
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>