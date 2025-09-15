<?php
session_start();
require_once '../logica/auth.php';

// Procesar logout si viene con el parámetro
if (isset($_GET['accion']) && $_GET['accion'] === 'logout') {
    Auth::cerrarSesion();
    // Redirigir sin parámetros para limpiar la URL
    header('Location: index.php');
    exit;
}

// Solo verificar si está logueado después de procesar logout
if (Auth::estaLogueado()) {
    $usuario = Auth::getUsuarioActual();
    if ($usuario) {
        if ($usuario->esCliente()) {
            header('Location: cliente.php');
        } else {
            header('Location: proveedor.php');
        }
        exit;
    }
}

// Variables para la interfaz
$mensaje = '';
$tipoMensaje = '';
$formularioActivo = 'login';

// Determinar que formulario mostrar
if (isset($_GET['form']) && $_GET['form'] === 'registro') {
    $formularioActivo = 'registro';
}

// PROCESAR FORMULARIO DE LOGIN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'login') {
    $emailUsuario = trim($_POST['emailUsuario'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    // Validaciones basicas de presentacion
    if (empty($emailUsuario) || empty($contrasena)) {
        $mensaje = 'Por favor completa todos los campos';
        $tipoMensaje = 'error';
    } else {
        // Enviar datos
        $usuario = Auth::iniciarSesion($emailUsuario, $contrasena);

        if ($usuario) {
            // Redirigir según el tipo de usuario
            if ($usuario->tipo === 'Cliente' || $usuario->esCliente()) {
                header('Location: cliente.php');
            } else if ($usuario->tipo === 'Proveedor' || $usuario->esProveedor()) {
                header('Location: proveedor.php');
            } else {
                // Por si hay algún problema con el tipo
                header('Location: index.php');
            }
            exit;
        } else {
            // Mostrar error si login fallido
            $mensaje = 'Correo/nombre de usuario o contraseña incorrectos';
            $tipoMensaje = 'error';
        }
    }
}

// PROCESAR FORMULARIO DE REGISTRO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'registro') {
    // Capturar todos los datos del formulario
    $datos = array(
        'nombre' => trim($_POST['nombre'] ?? ''),
        'apellido' => trim($_POST['apellido'] ?? ''),
        'nomusuario' => trim($_POST['nomusuario'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'telefono' => trim($_POST['telefono'] ?? ''),
        'contrasena' => $_POST['contrasena'] ?? '',
        'tipo' => $_POST['tipo'] ?? 'CLIENTE'
    );
    
    $confirmarcontrasena = $_POST['confirmarcontrasena'] ?? '';

    // Validaciones de presentacion
    if (empty($datos['nombre']) || empty($datos['apellido']) || empty($datos['nomusuario']) || 
        empty($datos['email']) || empty($datos['contrasena']) || empty($datos['tipo'])) {
        $mensaje = 'Todos los campos obligatorios deben ser completados';
        $tipoMensaje = 'error';
        $formularioActivo = 'registro';
    } else if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
        $mensaje = 'Email invalido';
        $tipoMensaje = 'error';
        $formularioActivo = 'registro';
    } else if (strlen($datos['contrasena']) < 6) {
        $mensaje = 'La contraseña debe tener al menos 6 caracteres';
        $tipoMensaje = 'error';
        $formularioActivo = 'registro';
    } else if ($datos['contrasena'] !== $confirmarcontrasena) {
        $mensaje = 'Las contraseñas no coinciden';
        $tipoMensaje = 'error';
        $formularioActivo = 'registro';
    } else {
        // Enviar datos a la capa de logica
        $resultado = Auth::registrarUsuario($datos);
        
        // Procesar respuesta de la capa de logica
        if ($resultado === true) {
            $mensaje = 'Registro exitoso. Ya puedes iniciar sesion';
            $tipoMensaje = 'success';
            $formularioActivo = 'login';
        } else if ($resultado === 'telefono_duplicado') {
            $mensaje = 'Este telefono ya esta registrado';
            $tipoMensaje = 'error';
            $formularioActivo = 'registro';
        } else if ($resultado === 'telefono_invalido') {
            $mensaje = 'El telefono no puede tener mas de 13 caracteres';
            $tipoMensaje = 'error';
            $formularioActivo = 'registro';
        } else {
            $mensaje = 'Error al registrar usuario o email ya existe';
            $tipoMensaje = 'error';
            $formularioActivo = 'registro';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TypeJobs - Iniciar Sesion</title>
    <link rel="stylesheet" href="../estilos/styleindex.css">
</head>
<body>
    <aside class="contenedor-login">
        <header class="encabezado-login">
            <h1>TypeJobs</h1>
            <p>Conectamos talento con oportunidades</p>
        </header>
        
        <nav class="pestanas-formulario">
            <a href="index.php?form=login" class="boton-pestana <?php echo $formularioActivo === 'login' ? 'active' : ''; ?>">
                Iniciar sesion
            </a>
            <a href="index.php?form=registro" class="boton-pestana <?php echo $formularioActivo === 'registro' ? 'active' : ''; ?>">
                Registrarse
            </a>
        </nav>
        
        <section class="formulario-login">
            <?php if (!empty($mensaje)): ?>
                <section class="mensaje <?php echo $tipoMensaje; ?>">
                    <?php echo htmlspecialchars($mensaje); ?>
                </section>
            <?php endif; ?>
            
            <!-- FORMULARIO DE LOGIN -->
            <?php if ($formularioActivo === 'login'): ?>
                <form method="POST">
                    <input type="hidden" name="accion" value="login">
                    
                    <span class="grupo-formulario">
                        <label for="emailUsuario">Email o Nombre de Usuario</label>
                        <input type="text" id="emailUsuario" name="emailUsuario" required>
                    </span>

                    <span class="grupo-formulario">
                        <label for="contrasena">Contraseña</label>
                        <input type="password" id="contrasena" name="contrasena" required>
                    </span>
                    
                    <section class="opciones-fila-formulario">
                        <span class="contenedor-checkbox">
                            <label for="recordarme">Recuerdame</label>
                            <input type="checkbox" name="recordarme" id="recordarme">
                        </span>
                        
                        <a href="#" class="enlace-olvidado" onclick="alert('Proximamente')">¿Olvidaste tu contraseña?</a>
                    </section>
                    
                    <button type="submit" class="boton-inicio-sesion">Iniciar sesion</button>
                </form>
            <?php endif; ?>
            
            <!-- FORMULARIO DE REGISTRO -->
            <?php if ($formularioActivo === 'registro'): ?>
                <form method="POST">
                    <input type="hidden" name="accion" value="registro">
                    
                    <span class="grupo-formulario">
                        <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="nombre" required>
                        <label for="apellido">Apellido</label>
                            <input type="text" id="apellido" name="apellido" required>
                        <label for="nomusuario">Nombre de usuario</label>
                            <input type="text" id="nomusuario" name="nomusuario" required>
                        <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        <label for="telefono">Telefono (opcional)</label>
                            <input type="tel" id="telefono" name="telefono">
                        <label for="tipo">Tipo de cuenta</label>
                            <select id="tipo" name="tipo" required>
                                <option value="">Seleccionar</option>
                                <option value="CLIENTE">Cliente</option>
                                <option value="PROVEEDOR">Proveedor</option>
                            </select>
                        <label for="contrasena">Contraseña</label>
                            <input type="password" id="contrasena" name="contrasena" required>
                        <label for="confirmarcontrasena">Confirmar contraseña</label>
                            <input type="password" id="confirmarcontrasena" name="confirmarcontrasena" required>    
                    </span>
                    
                    <button type="submit" class="boton-inicio-sesion">Crear Cuenta</button>
                </form>
            <?php endif; ?>
        </section>
    </aside>
</body>
</html>