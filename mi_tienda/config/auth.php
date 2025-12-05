<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: /mi_tienda/admin/login.php");
    exit;
}


if (!isset($_SESSION["usuario"])) {
    header("Location: /mi_tienda/pages/login.php");
    exit;
}