<?php
require __DIR__ . "/../../dbpwd.php";
$db = mysqli_connect('srv1228.hstgr.io', 'u893274511_beautysalon', $salonPWD , 'u893274511_beautysalondb');


if (!$db) {
    echo "Error: No se pudo conectar a MySQL.";
    echo "errno de depuración: " . mysqli_connect_errno();
    echo "error de depuración: " . mysqli_connect_error();
    exit;
}
