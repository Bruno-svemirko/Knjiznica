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
</head>
<body>
    <div class="container mt-4">
        <h1>Prijava</h1>

        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="lozinka">Lozinka:</label>
                <input type="password" name="lozinka" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Prijavi se</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>