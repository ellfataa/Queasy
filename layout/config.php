<?php
    error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "queasy";

    $mysqli = new mysqli($host, $username, $password, $database);

    if ($mysqli->connect_error) {
        die("Koneksi gagal: " . $mysqli->connect_error);
    } else {
        // echo "Koneksi berhasil!";
    }
?>