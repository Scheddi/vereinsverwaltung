<?php
$sql = array(
    'server' => "manuelschedler.de",
    'user' => "MVD",
    'database' => "MVD",
    'password' => "1949eV",
);
$mailconfig = array(
    'server' => "smtp.ionos.de",
    'user' => "meldeliste@musikverein-bonn-duisdorf.de",
    'password' => "Schnurzel357",
    'port' => 587,
    'from' => "meldeliste@musikverein-bonn-duisdorf.de",
    'fromName' => "Musikverein Duisdorf",
    'secure' => "tls",
    'subjectprefix' => '[MVD-Meldeliste] ',
);

$conn = mysqli_connect($sql['server'], $sql['user'], $sql['password']) or die (mysqli_error($conn));

$cronID = '2955bf5d-2014-4c0e-9c52-5ab9a932b4b7';

global $mailconfig;
global $conn;
global $cronID;
?>
