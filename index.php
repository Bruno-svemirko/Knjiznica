<?php
require 'auth.php';
checkAuth();

if ($_SESSION['user']['uloga'] === 'autor' && isset($_GET['deaktiviraj_racun'])) {
    require 'db.php';

    $stmt = $pdo->prepare('UPDATE korisnici SET aktivan = FALSE WHERE id = ?');
    $stmt->execute([$_SESSION['user']['id']]);

    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Dobrodošli</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Centriranje sadržaja */
        .content-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            text-align: center;
        }

        .list-group-item {
            text-align: center;
        }

        .btn-custom {
            margin-top: 20px;
        }

        h1, h3 {
            color: black;
        }

        .list-group a {
            font-weight: bold;
        }

        .list-group a:hover {
            text-decoration: none;
            color: #007bff;
        }

        .alert-custom {
            margin-top: 20px;
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="content-container">
            <h1>Dobrodošli, <?= htmlspecialchars($_SESSION['user']['ime']) ?>!</h1>
            <p><strong>Uloga:</strong> <?= htmlspecialchars($_SESSION['user']['uloga']) ?></p>

            <div class="mt-4">
                <h3>Brzi linkovi</h3>
                <ul class="list-group">
                    <li class="list-group-item">
                        <a href="knjige.php">Knjige</a>
                    </li>
                    <?php if (isAdmin()) : ?>
                        <li class="list-group-item">
                            <a href="korisnici.php">Korisnici</a>
                        </li>
                    <?php elseif ($_SESSION['user']['uloga'] === 'autor'): ?>
                        <li class="list-group-item">
                            <a href="deaktiviraj_racun.php" onclick="return confirm('Jeste li sigurni da želite deaktivirati svoj račun?');">Deaktiviraj račun</a>
                        </li>
                    <?php endif; ?>
                    <li class="list-group-item">
                        <a href="logout.php">Odjava</a>
                    </li
