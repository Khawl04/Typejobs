<?php
//pago - Página de pago
session_start();
require_once '../logica/auth.php';

// Verificar acceso
Auth::verificarAcceso();
$usuario = Auth::getUsuarioActual();
if (!$usuario) {
    header('Location: index.php');
    exit;
}
if (!$usuario->esCliente()) {
    header('Location: proveedor.php');
    exit;
}

// Servicio
$servicio_id = $_GET['servicio_id'] ?? 1;
$servicio = [
    'id' => $servicio_id,
    'titulo' => 'Desarrollo Web Completo',
    'descripcion' => 'Desarrollo de sitio web responsive con panel de administración',
    'precio' => 750.00,
    'proveedor' => 'Juan Pérez'
];

// Mensajes
$mensaje = $_SESSION['mensaje'] ?? '';
$tipoMensaje = $_SESSION['tipoMensaje'] ?? '';
unset($_SESSION['mensaje'], $_SESSION['tipoMensaje']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TypeJobs - Checkout</title>
<link rel="stylesheet" href="../estilos/stylepago.css">
</head>
<body>

<header class="header">
    <div class="header-content">
        <h1 class="logo-text">TypeJobs</h1>
        <nav>
            <ul class="nav-section">
                <li><a href="#">Categorías</a></li>
            </ul>
        </nav>
        <div class="auth-section">
            <a href="index.php?form=login">Iniciar sesión</a>
            <a href="index.php?form=registro">Registrarse</a>
        </div>
    </div>
</header>

<main class="main-content">
    <section class="checkout-container">
        <!-- Formulario de Compra -->
        <article class="purchase-section">
            <h2>Su compra</h2>

            <?php if (!empty($mensaje)): ?>
                <p class="message <?= htmlspecialchars($tipoMensaje) ?>"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>

            <form method="POST" action="orden.php">
                <input type="hidden" name="servicio_id" value="<?= $servicio['id'] ?>">

                <fieldset>
                    <legend>Datos personales</legend>
                    <label for="primer_nombre">Primer nombre *</label>
                    <input type="text" id="primer_nombre" name="primer_nombre" value="<?= htmlspecialchars($usuario->nombre) ?>" required>

                    <label for="segundo_nombre">Segundo nombre</label>
                    <input type="text" id="segundo_nombre" name="segundo_nombre" value="<?= htmlspecialchars($usuario->apellido) ?>">

                    <label for="ciudad">Ciudad *</label>
                    <input type="text" id="ciudad" name="ciudad" placeholder="Montevideo" required>

                    <label for="departamento">Departamento</label>
                    <select id="departamento" name="departamento">
                        <option value="">Seleccionar</option>
                        <option value="Montevideo">Montevideo</option>
                        <option value="Canelones">Canelones</option>
                        <option value="Maldonado">Maldonado</option>
                        <option value="Colonia">Colonia</option>
                        <option value="San José">San José</option>
                    </select>

                    <label for="direccion">Dirección *</label>
                    <input type="text" id="direccion" name="direccion" placeholder="Calle, número, apto" required>

                    <label for="celular">Celular *</label>
                    <input type="tel" id="celular" name="celular" placeholder="099 123 456" required pattern="\d{3}\s?\d{3}\s?\d{3}">

                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario->email) ?>" required>

                    <label for="tarjeta">Tarjeta *</label>
                    <input type="text" id="tarjeta" name="tarjeta" placeholder="1234 5678 9012 3456" maxlength="19" required pattern="\d{4}\s?\d{4}\s?\d{4}\s?\d{4}">
                </fieldset>

                <button type="submit">Pagar $<?= number_format($servicio['precio'], 2) ?></button>
            </form>
        </article>

        <!-- Resumen de Orden -->
        <aside class="order-section">
            <h2>Tu orden</h2>
            <div class="order-card">
                <p><strong>Producto:</strong> <?= htmlspecialchars($servicio['titulo']) ?></p>
                <p><?= htmlspecialchars($servicio['descripcion']) ?></p>
                <p><small>Proveedor: <?= htmlspecialchars($servicio['proveedor']) ?></small></p>
                <p><strong>Total:</strong> $<?= number_format($servicio['precio'], 2) ?></p>
            </div>
        </aside>
    </section>
</main>
</body>
</html>