<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
.dashboard-box {
    background: #232a37;
    border-radius: 16px;
    box-shadow: 0 4px 18px rgba(60, 60, 60, 0.12);
    max-width: 540px;
    margin: 48px auto;
    padding: 38px 32px 32px 32px;
    text-align: center;
}

.dashboard-top {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 22px;
    margin-bottom: 19px;
}
.dashboard-avatar {
    width: 90px; min-width:90px;
    height: 90px;
    background: #dbeee6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.3em;
    color: #228869;
    font-weight: 700;
    box-shadow: 0 1px 8px 0 #ccc2;
    overflow:hidden;
}
.avatar-dashboard {
    width: 100%; height: 100%; object-fit: cover; border-radius: 50%;
    background: #dbeee6;
    display: block;
}
.dashboard-info {
    text-align: left;
    font-size: 1em;
    color: #444;
    padding: 0 0 0 0;
    line-height:1.6;
}
.dashboard-info strong {
    color: #228869;
    margin-bottom: 4px;
    display:block;
    font-size: 1.08em;
    font-weight:700;
    margin-top: 0;
}
.dashboard-info .dato-label {color:#78998b;font-weight:600;}
.dashboard-info .dato-valor {color: #7279a1ff;font-weight:500;}
.dashboard-titulo {
    font-size: 2.2em;
    color: #228869;
    margin-bottom: 12px;
    font-weight: 700;
}
.dashboard-bienvenida {
    font-size: 1.21em;
    margin-bottom: 22px;
    color: #7279a1ff;
}
.dashboard-menu a {
    display: block;
    margin: 14px auto;
    max-width: 250px;
    font-size: 1.1em;
    padding: 13px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: background 0.17s;
    border: none;
}
.dashboard-menu .btn-primary { background: #57a07f; color: #fff; }
.dashboard-menu .btn-secondary { background: #f3f6f1; color: #228869; border: 1.2px solid #57a07f }
.dashboard-menu .btn-success { background: #98e2b1; color: #187b54; }
.dashboard-menu .btn-msg { background: #e9eefb; color: #1e5fc5; border: 1.2px solid #1e5fc5; }
.dashboard-menu a:hover { background: #228869; color: #fff; }
@media (max-width: 600px){
    .dashboard-box {padding:24px 4vw;}
    .dashboard-top {flex-direction:column; gap:6px;}
    .dashboard-info{text-align:center;}
}
</style>

<div class="dashboard-box">
    <div class="dashboard-top">
        <div class="dashboard-avatar">
            <?php if (!empty($cliente['foto_perfil'])): ?>
                <img src="<?= BASE_URL . '/uploads/perfiles/' . htmlspecialchars($cliente['foto_perfil']) ?>"
                     alt="Foto de perfil actual" class="avatar-dashboard">
            <?php else: ?>
                <?= strtoupper(substr($cliente['nombre'] ?? $_SESSION['nombre'] ?? 'U', 0, 1)) ?>
                <?= strtoupper(substr($cliente['apellido'] ?? $_SESSION['apellido'] ?? 'U', 0, 1)) ?>
            <?php endif; ?>
        </div>
        <div class="dashboard-info">
            <strong>Información de Cuenta</strong>
            <div><span class="dato-label">Email:</span>
                <span class="dato-valor"><?= htmlspecialchars($cliente['email'] ?? '') ?></span></div>
            <div><span class="dato-label">Usuario:</span>
                <span class="dato-valor"><?= htmlspecialchars($cliente['nomusuario'] ?? '') ?></span></div>
            <div><span class="dato-label">Miembro desde:</span>
                <span class="dato-valor"><?= date('F Y', strtotime($cliente['fecha_registro'] ?? 'now')) ?></span></div>
        </div>
    </div>

    <div class="dashboard-titulo">Panel del Cliente</div>
    <div class="dashboard-bienvenida">
        Bienvenido/a,
        <b><?= htmlspecialchars($cliente['nombre'] ?? $_SESSION['nombre'] ?? '') ?>
        <?= htmlspecialchars($cliente['apellido'] ?? $_SESSION['apellido'] ?? '') ?></b>
    </div>

    <div class="dashboard-menu">
        <a class="btn btn-primary" href="<?= BASE_URL ?>/cliente/perfil">Mi Perfil</a>
        <a class="btn btn-secondary" href="<?= BASE_URL ?>/cliente/reservas">Mis Reservas</a>
        <a class="btn btn-success" href="<?= BASE_URL ?>/cliente/pagos">Historial de Pagos</a>
        <a class="btn btn-msg" href="<?= BASE_URL ?>/mensaje">Ver Mensajes</a>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
