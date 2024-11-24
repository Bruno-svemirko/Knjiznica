<?php
require 'auth.php';
checkAuth();

if ($_SESSION['user']['uloga'] !== 'autor') {
    header('Location: index.php');
    exit;
}

require 'db.php';

$userId = $_SESSION['user']['id'];

$stmt = $pdo->prepare('UPDATE korisnici SET aktivan = FALSE WHERE id = ?');
$stmt->execute([$userId]);

session_destroy();
header('Location: login.php');
exit;
?>