<?php
require 'db.php';
session_start();

$hashedPasswordAdmin = password_hash('admin', PASSWORD_DEFAULT);
$stmt = $pdo->prepare('UPDATE korisnici SET lozinka = ? WHERE email = ?');
$stmt->execute([$hashedPasswordAdmin, 'admin@example.com']);

$hashedPasswordAutor = password_hash('autor', PASSWORD_DEFAULT);
$stmt = $pdo->prepare('UPDATE korisnici SET lozinka = ? WHERE email = ?');
$stmt->execute([$hashedPasswordAutor, 'autor@example.com']);

session_destroy();
header('Location: login.php');
exit;
