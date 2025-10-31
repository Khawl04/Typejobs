<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
.dashboard-box {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 18px rgba(60, 60, 60, 0.12);
    max-width: 500px;
    margin: 48px auto;
    padding: 38px 30px 32px 30px;
    text-align: center;
}

.dashboard-titulo {
    font-size: 2.2em;
    color: #228869;
    margin-bottom: 12px;
    font-weight: 700;
}

.dashboard-bienvenida {
    font-size: 1.21em;
    margin-bottom: 22px;
    color: #333;
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

.dashboard-menu .btn-primary {
    background: #57a07f;
    color: #fff;
}

.dashboard-menu .btn-secondary {
    background: #f3f6f1;
    color: #228869;
    border: 1.2px solid #57a07f
}

.dashboard-menu .btn-success {
    background: #98e2b1;
    color: #187b54;
}

.dashboard-menu .btn-msg {
    background: #e9eefb;
    color: #1e5fc5;
    border: 1.2px solid #1e5fc5;
}

.dashboard-menu a:hover {
    background: #228869;
    color: #fff;
}

.dashboard-info {
    margin-top: 30px;
    text-align: left;
    font-size: 1em;
    color: #444;
    border-top: 1px solid #ececec;
    padding-top: 20px;
}

.dashboard-info strong {
    color: #228869;
    display: block;
    margin-bottom: 2px;
    font-weight: 600;
}

.dashboard-avatar {
    width: 73px;
    height: 73px;
    background: #dbeee6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2em;
    color: #228869;
    margin: 0 auto 15px auto;
    font-weight: 700;
}
.avatar-dashboard {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    background: #dbeee6;
    box-shadow: 0 1px 8px 0 #ccc0;
    display: block;
}
</style>

<div class="dashboard-box">
    
    <div class="dashboard-avatar">
        <?php if (!empty($cliente['foto_perfil'])): ?>
        <img src="<?= BASE_URL . '/uploads/perfiles/' . htmlspecialchars($cliente['foto_perfil']) ?>"
            alt="Foto de perfil actual" class="avatar-dashboard">
        <?php else: ?>
        <?= strtoupper(substr($cliente['nombre'] ?? $_SESSION['nombre'] ?? 'U', 0, 1)) ?>
        <?= strtoupper(substr($cliente['apellido'] ?? $_SESSION['apellido'] ?? 'U', 0, 1)) ?>
        <?php endif; ?>
    </div>

    <div class="dashboard-titulo">Panel del Cliente</div>
    <div class="dashboard-bienvenida">
        Bienvenido/a,
        <b><?= htmlspecialchars($cliente['nombre'] ?? $_SESSION['nombre'] ?? '') ?>
        <?= htmlspecialchars($cliente['apellido'] ?? $_SESSION['apellido'] ?? '') ?>
        </b>
    </div>

    <div class="dashboard-menu">
        <a class="btn btn-primary" href="<?= BASE_URL ?>/cliente/perfil">Mi Perfil</a>
        <a class="btn btn-secondary" href="<?= BASE_URL ?>/cliente/reservas">Mis Reservas</a>
        <a class="btn btn-success" href="<?= BASE_URL ?>/cliente/pagos">Historial de Pagos</a>
        <a class="btn btn-msg" href="<?= BASE_URL ?>/mensaje">Ver Mensajes</a>
    </div>

    <div class="dashboard-info">
        <strong>Información de Cuenta</strong>
        <div>Email: <?= htmlspecialchars($cliente['email'] ?? '') ?></div>
        <div>Usuario: <?= htmlspecialchars($cliente['nomusuario'] ?? '') ?></div>
        <div>Miembro desde: <?= date('F Y', strtotime($cliente['fecha_registro'] ?? 'now')) ?></div>
        <!-- Puedes agregar ciudad/país/descripción aquí si lo tienes -->
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>