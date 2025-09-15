<?php
session_start();
require_once '../logica/auth.php';

Auth::cerrarSesion();
header('Location: index.php');
exit;
?>