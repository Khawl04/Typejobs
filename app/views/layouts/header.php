<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
error_log('SESSION HEADER (test): ' . print_r($_SESSION,true));

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'TypeJobs' ?></title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
        background-color: #e8e4d9;
        color: #333;
        line-height: 1.6;
    }

    /* ========== HEADER ========== */
    .navbar {
        background: #232a37;
        border-bottom: 1px solid #d0cbbe;
        padding: 15px 20px;
        position: sticky;
        top: 0; 
        z-index: 1000;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo-section {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .logo-img {
        width: 50px;
        height: 50px;
        background-color: #5a7355;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        overflow: hidden;
        padding: 0;
    }

    .logo-img img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center;
        display: block;
    }

    .logo-text {
        font-size: 22px;
        font-weight: 600;
        color: #5a7355;
        text-decoration: none;
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 30px;
    }

    .navbar a {
        color: #75e4a6;
        text-decoration: none;
        font-size: 16px;
        font-weight: 400;
        transition: color 0.3s ease;
        position: relative;
    }

    .navbar a:hover {
        color: #3d4a38;
    }

    .navbar a.active {
        font-weight: 500;
    }

    .navbar a.active::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #5a7355;
    }

    .btn-nav {
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
    }

    .btn-register {
        background-color: transparent;
        border: 1px solid #9ca396;
    }

    .btn-register:hover {
        background-color: #f0ede4;
    }

    .btn-login {
        background-color: transparent;
        border: 1px solid #9ca396;
    }

    .btn-login:hover {
        background-color: #f0ede4;
    }

    /* Usuario dropdown mejorado */
    .user-dropdown {
        position: relative;
        display: inline-block;
    }

    .user-nombre {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        font-weight: 600;
        font-size: 20px;
        /* MÁS GRANDE */
        color: #228869;
        padding: 8px 22px 8px 13px;
        /* más espacio */
        background: #f0f7f3;
        border-radius: 32px;
        min-width: 120px;
        transition: background 0.2s;
        box-shadow: 0 2px 8px rgba(60, 60, 60, 0.04);
    }

    .user-nombre:hover,
    .user-dropdown:hover .user-nombre {
        background: #e6f3ec;
    }

    .user-menu {
        display: none;
        position: absolute;
        right: 0;
        top: 40px;
        min-width: 170px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 16px rgba(60, 60, 60, 0.15);
        z-index: 100;
        text-align: left;
    }

    .user-menu a {
        display: block;
        padding: 12px 20px;
        text-decoration: none;
        color: #18644e;
        border-bottom: 1px solid #f1f2f6;
        transition: background 0.15s;
    }

    .user-menu a.logout-link {
        color: #d35757;
    }

    .user-menu a:last-child {
        border-bottom: none;
    }

    .user-menu a:hover {
        background: #f3fbf7;
    }

    .user-dropdown:hover .user-menu {
        display: block;
    }

    .arrow {
        font-size: 1.3em;
        margin-left: 8px;
        color: #909c91;
    }

    .avatar-header {
        height: 40px;
        width: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #d0ede0;
        background: #fff;
        margin-right: 6px;
    }


    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .container {
            flex-direction: column;
            gap: 15px;
        }

        .nav-links {
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }

        .navbar a {
            font-size: 14px;
        }

        .user-nombre {
            font-size: 1em;
            padding: 9px 10px;
        }

        .avatar-header {
            height: 30px;
            width: 30px;
        }
    }
    </style>
</head>

<body>

    <?php
        $dashboardUrl = BASE_URL . '/cliente'; // valor por defecto
            if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'proveedor') {
        $dashboardUrl = BASE_URL . '/proveedor'; // panel de proveedor
    }
    ?>
    <header class="navbar">
        <div class="container">
            <div class="logo-section">
                <div class="logo-img">
                    <img src="<?= BASE_URL ?>/img/typejobslogo.png" alt="TypeJobs Logo">
                </div>
                <a href="<?= BASE_URL ?>/" class="logo-text" style="font-size:26px;">TypeJobs</a>
            </div>
            <nav class="nav-links">
                <a href="<?= BASE_URL ?>/servicio" ...>Servicios</a>
                <a href="<?= BASE_URL ?>/layouts/sobre" ...>Sobre</a>
                <a href="<?= BASE_URL ?>/layouts/contacto" ...>Contacto</a>
                <?php if (isset($_SESSION['id_usuario'])): ?>
                <div class="user-dropdown" id="userDropdown">
                    <?php 
                        $foto = $_SESSION['foto_perfil'] ?? null;
                        $fotoSrc = $foto ? (BASE_URL . '/uploads/perfiles/' . $foto) : (BASE_URL . '/img/defaultpfp.png');
                    ?>
                    <span class="user-nombre" id="userDropdownToggle">
                        <img src="<?= $fotoSrc ?>" class="avatar-header" alt="Avatar">
                        <b>
                            <?= htmlspecialchars(($_SESSION['nombre'] ?? '') . ' ' . ($_SESSION['apellido'] ?? '')) ?>
                        </b>

                        <span class="arrow">▼</span>
                    </span>
                    <div class="user-menu" id="userDropdownMenu">
                        <a href="<?= $dashboardUrl ?>">Panel del usuario</a>
                        <a href="<?= BASE_URL ?>/logout" class="logout-link">Cerrar sesión</a>
                    </div>
                </div>
                <?php else: ?>
                <a href="<?= BASE_URL ?>/register" class="btn-nav btn-register ...">Registrarse</a>
                <a href="<?= BASE_URL ?>/login" class="btn-nav btn-login ...">Iniciar Sesión</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('userDropdownToggle');
        const menu = document.getElementById('userDropdownMenu');
        const box = document.getElementById('userDropdown');
        let open = false;

        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
            open = (menu.style.display === 'block');
        });

        // Cierra si clickeas fuera
        document.addEventListener('click', function(e) {
            if (open && !box.contains(e.target)) {
                menu.style.display = 'none';
                open = false;
            }
        });

        // Prevenir que un clic en el menú lo cierre antes de tiempo
        menu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    </script>
</body>