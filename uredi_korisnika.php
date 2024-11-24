<?php
require 'auth.php';
checkAuth();

if ($_SESSION['user']['uloga'] !== 'administrator') {
    header('Location: korisnici.php');
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $korisnikId = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM korisnici WHERE id = ?');
    $stmt->execute([$korisnikId]);
    $korisnik = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$korisnik) {
    header('Location: korisnici.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uredi_korisnika'])) {
    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $email = $_POST['email'];
    $uloga = $_POST['uloga'];
    $lozinka = $_POST['lozinka'];

    $stmt = $pdo->prepare('SELECT * FROM korisnici WHERE email = ? AND id != ?');
    $stmt->execute([$email, $korisnikId]);
    if ($stmt->rowCount() > 0) {
        $error = 'Korisnik s tim emailom već postoji.';
    } else {
        if ($lozinka) {
            $hashedPassword = password_hash($lozinka, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE korisnici SET ime = ?, prezime = ?, email = ?, lozinka = ?, uloga = ? WHERE id = ?');
            $stmt->execute([$ime, $prezime, $email, $hashedPassword, $uloga, $korisnikId]);
        } else {
            $stmt = $pdo->prepare('UPDATE korisnici SET ime = ?, prezime = ?, email = ?, uloga = ? WHERE id = ?');
            $stmt->execute([$ime, $prezime, $email, $uloga, $korisnikId]);
        }

        header('Location: korisnici.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Uredi korisnika</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Uredi korisnika</h1>

        <?php if (isset($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="ime">Ime:</label>
                <input type="text" id="ime" name="ime" class="form-control" value="<?php echo htmlspecialchars($korisnik['ime']); ?>" required>
            </div>
            <div class="form-group">
                <label for="prezime">Prezime:</label>
                <input type="text" id="prezime" name="prezime" class="form-control" value="<?php echo htmlspecialchars($korisnik['prezime']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($korisnik['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="uloga">Uloga:</label>
                <select id="uloga" name="uloga" class="form-control" required>
                    <option value="administrator" <?php echo $korisnik['uloga'] === 'administrator' ? 'selected' : ''; ?>>Administrator</option>
                    <option value="autor" <?php echo $korisnik['uloga'] === 'autor' ? 'selected' : ''; ?>>Autor</option>
                </select>
            </div>
            <div class="form-group">
                <label for="lozinka">Nova lozinka (ostavite prazno ako ne želite mijenjati):</label>
                <input type="password" id="lozinka" name="lozinka" class="form-control">
            </div>
            <button type="submit" name="uredi_korisnika" class="btn btn-primary">Spremi promjene</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
