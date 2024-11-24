<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['uloga'] !== 'administrator') {
    header('Location: login.php');
    exit;
}

if (isset($_GET['deaktiviraj']) && is_numeric($_GET['deaktiviraj'])) {
    $userId = $_GET['deaktiviraj'];
    $stmt = $pdo->prepare('UPDATE korisnici SET aktivan = FALSE WHERE id = ?');
    $stmt->execute([$userId]);
    header('Location: korisnici.php');
    exit;
}

if (isset($_GET['brisanje']) && is_numeric($_GET['brisanje'])) {
    $userId = $_GET['brisanje'];

    $stmt = $pdo->prepare('SELECT uloga, aktivan FROM korisnici WHERE id = ?');
    $stmt->execute([$userId]);
    $korisnik = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($korisnik) {
        if ($korisnik['uloga'] === 'autor' || ($korisnik['uloga'] === 'administrator' && !$korisnik['aktivan'])) {
            $stmt = $pdo->prepare('DELETE FROM korisnici WHERE id = ?');
            $stmt->execute([$userId]);
        }
    }

    header('Location: korisnici.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dodaj_korisnika'])) {
    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $email = $_POST['email'];
    $lozinka = $_POST['lozinka'];
    $uloga = $_POST['uloga'];

    $stmt = $pdo->prepare('SELECT * FROM korisnici WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $error = 'Korisnik s tim emailom već postoji.';
    } else {

        $hashedPassword = password_hash($lozinka, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare('
            INSERT INTO korisnici (ime, prezime, email, lozinka, uloga, aktivan)
            VALUES (?, ?, ?, ?, ?, TRUE)
        ');
        $stmt->execute([$ime, $prezime, $email, $hashedPassword, $uloga]);

        header('Location: korisnici.php');
        exit;
    }
}

if (isset($_GET['promijeni_status']) && is_numeric($_GET['promijeni_status'])) {
    $userId = $_GET['promijeni_status'];

    $stmt = $pdo->prepare('SELECT aktivan FROM korisnici WHERE id = ?');
    $stmt->execute([$userId]);
    $korisnik = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($korisnik) {
        $noviStatus = !$korisnik['aktivan'];
        $stmt = $pdo->prepare('UPDATE korisnici SET aktivan = ? WHERE id = ?');
        $stmt->execute([$noviStatus, $userId]);
    }

    header('Location: korisnici.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM korisnici');
$stmt->execute();
$korisnici = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Lista korisnika</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        #addUserForm {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>Lista korisnika</h1>
        <a href="index.php" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Nazad
        </a>
        <h2 class="mt-4">Svi korisnici</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ime</th>
                    <th>Prezime</th>
                    <th>Email</th>
                    <th>Uloga</th>
                    <th>Status</th>
                    <th>Akcije</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($korisnici as $korisnik): ?>
                    <tr>
                        <td><?php echo $korisnik['id']; ?></td>
                        <td><?php echo $korisnik['ime']; ?></td>
                        <td><?php echo $korisnik['prezime']; ?></td>
                        <td><?php echo $korisnik['email']; ?></td>
                        <td><?php echo ucfirst($korisnik['uloga']); ?></td>
                        <td><?php echo $korisnik['aktivan'] ? 'Aktivan' : 'Neaktivan'; ?></td>
                        <td>
                            <?php if ($korisnik['id'] !== $_SESSION['user']['id']): ?>
                                <a href="uredi_korisnika.php?id=<?php echo $korisnik['id']; ?>" class="btn btn-info btn-sm">Uredi</a>
                                <a href="korisnici.php?promijeni_status=<?php echo $korisnik['id']; ?>" 
                                class="btn btn-sm <?php echo $korisnik['aktivan'] ? 'btn-warning' : 'btn-success'; ?>">
                                <?php echo $korisnik['aktivan'] ? 'Deaktiviraj' : 'Aktiviraj'; ?>
                                </a>
                                <?php if ($korisnik['uloga'] === 'autor' || ($korisnik['uloga'] === 'administrator' && !$korisnik['aktivan'])): ?>
                                    <a href="korisnici.php?brisanje=<?php echo $korisnik['id']; ?>" 
                                    class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Sigurno želite obrisati ovog korisnika?');">Obriši</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <button class="btn btn-primary" onclick="toggleForm()">Dodaj novog korisnika</button>

        <div id="addUserForm" class="mt-4">
            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <form method="POST" action="korisnici.php">
                <div class="form-group">
                    <label for="ime">Ime:</label>
                    <input type="text" id="ime" name="ime" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="prezime">Prezime:</label>
                    <input type="text" id="prezime" name="prezime" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="lozinka">Lozinka:</label>
                    <input type="password" id="lozinka" name="lozinka" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="uloga">Uloga:</label>
                    <select id="uloga" name="uloga" class="form-control" required>
                        <option value="administrator">Administrator</option>
                        <option value="autor">Autor</option>
                    </select>
                </div>
                <button type="submit" name="dodaj_korisnika" class="btn btn-primary">Dodaj korisnika</button>
            </form>
        </div> 

    </div>

    <script>
        function toggleForm() {
    var form = document.getElementById('addUserForm');
    if (form.style.display === "none" || form.style.display === "") {
        form.style.display = "block";
    } else {
        form.style.display = "none";
    }
}
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
