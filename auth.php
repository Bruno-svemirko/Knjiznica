<?php
session_start();
require 'db.php';

function checkAuth() {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}

function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['uloga'] === 'administrator';
}

function isAuthor() {
    return isset($_SESSION['user']) && $_SESSION['user']['uloga'] === 'autor';
}
?>