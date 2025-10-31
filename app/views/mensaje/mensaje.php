<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<style>
body {
    background: #263142;
}

.dashboard-mensajes {
    display: flex;
    gap: 0;
    min-height: 500px;
    background: #263142;
}

.conversaciones-panel {
    width: 350px;
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
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: #8eadcf;
    color: #223;
    padding: 2px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.21em;
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
    padding: 0 0 0 0;
    min-width: 340px;
    display: flex;
    flex-direction: column;
}

.chat-header {
    padding: 26px 33px 10px;
    background: transparent;
}

.chat-mensajes {
    flex: 1;
    padding: 10px 33px 10px 33px;
    overflow-y: auto;
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
</style>

<div class="dashboard-mensajes">
    <div class="conversaciones-panel">
        <h2>Conversaciones</h2>
        <ul class="conversacion-list">
            <?php foreach($conversaciones as $conv): ?>
            <li
                class="conversacion-item <?= ($chat_activo && $conv['id_otro_usuario'] == $chat_activo['id_otro_usuario']) ? 'active' : '' ?>">
                <a href="?chat=<?= $conv['id_otro_usuario'] ?>"
                    style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:13px;width:100%;">
                    <span
                        class="conversacion-avatar"><?= strtoupper(substr($conv['nombre_otro_usuario'] ?? 'U',0,1)) ?></span>
                    <div class="conversacion-info">
                        <div class="conversacion-nombre">
                            <?= htmlspecialchars($conv['nombre_otro_usuario'] ?? 'Usuario') ?>
                        </div>
                        <div class="conversacion-preview"><?= htmlspecialchars($conv['ultimo_mensaje'] ?? '') ?></div>
                        <?php if (!empty($conv['no_leidos'])): ?>
                        <span class="badge-no-leidos"><?= $conv['no_leidos'] ?></span>
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
            <span
                class="conversacion-avatar"><?= strtoupper(substr($chat_activo['nombre_otro_usuario'] ?? 'U',0,1)) ?></span>
            <span style="font-size:1.18em;font-weight:600;color:#162238;">
                <?= htmlspecialchars($chat_activo['nombre_otro_usuario'] ?? 'Usuario') ?>
            </span>
        </div>
        <div class="chat-mensajes">
            <?php foreach($mensajes_chat as $msg): ?>
            <div class="mensaje-row <?= $msg['id_usuario'] == $_SESSION['id_usuario'] ? 'enviado' : '' ?>">
                <div class="mensaje-bubble">
                    <?= htmlspecialchars($msg['contenido']) ?>
                    <div class="mensaje-hora"><?= date('H:i', strtotime($msg['fecha_envio'] ?? '')) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <form class="chat-input-bar" method="POST" action="" autocomplete="off">
            <input type="hidden" name="id_destinatario" value="<?= $chat_activo['id_otro_usuario'] ?>">
            <input type="text" name="contenido" placeholder="Escribir mensaje..." required>
            <button type="submit">&#9658;</button>
        </form>
        <?php else: ?>
        <div style="padding:80px;text-align:center;color:#333;">Selecciona una conversación</div>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>