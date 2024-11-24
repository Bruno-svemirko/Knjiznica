<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $lozinka = $_POST['lozinka'];

    $stmt = $pdo->prepare('SELECT * FROM korisnici WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (!$user['aktivan']) {
            $error = 'Ovaj račun je deaktiviran. Kontaktirajte administratora za ponovno aktiviranje.';
        } elseif (password_verify($lozinka, $user['lozinka'])) {
            $_SESSION['user'] = $user;
            header('Location: index.php');
            exit;
        } else {
            $error = 'Pogrešna lozinka.';
        }
    } else {
        $error = 'Korisnik s unesenim emailom ne postoji.';
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Prijava</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Centriranje sadržaja */
        .content-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 30px;
            text-align: center;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: black;
        }

        .form-group label {
            text-align: left;
        }

        .alert-custom {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="content-container">
            <h1>Prijava</h1>

            <?php if (isset($error)) : ?>
                <div class="alert-custom">
                    <strong>Greška:</strong> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="lozinka">Lozinka:</label>
                    <input type="password" name="lozinka" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Prijavi se</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
