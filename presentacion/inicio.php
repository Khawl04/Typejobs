<?php
// Página principal de la web
session_start();
require_once '../logica/auth.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TypeJobs - Conectamos talento con oportunidades</title>
    <link rel="stylesheet" href="../estilos/styleinicio.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <div class="logo-icon">T</div>
                <h1>TypeJobs</h1>
            </div>
            <nav class="nav-links">
                <a href="busqueda.php" class="nav-link">Categorías</a>
                <a href="#quienes-somos" class="nav-link">¿Quiénes somos?</a>
                <a href="#soporte" class="nav-link">Soporte</a>
            </nav>
            <div class="auth-buttons">
                <a href="index.php?form=login" class="btn btn-outline">Iniciar sesión</a>
                <a href="index.php?form=registro" class="btn btn-primary">Registrarse</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Decoración de fondo -->
        <div class="bg-decoration" aria-hidden="true">
            <span class="bg-circle"></span>
            <span class="bg-circle"></span>
            <span class="bg-circle"></span>
        </div>
        
        <section class="main-section" aria-labelledby="main-title">
            <h1 id="main-title" class="main-title">Conectamos talento con oportunidades</h1>
            <p class="main-subtitle">Encuentra los mejores servicios profesionales o ofrece tus habilidades al mundo</p>
            
            <form class="search-container" role="search" onsubmit="realizarBusqueda(); return false;">
                <label for="search-box" class="sr-only">Buscar servicios</label>
                <div class="search-icon" aria-hidden="true">🔍</div>
                <input type="text" id="search-box" class="search-box" placeholder="¿Qué buscas?" />
                <button type="submit" class="search-btn">Buscar</button>
            </form>
        </section>
    </main>
</body>
</html>