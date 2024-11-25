<?php
require 'auth.php';
checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dodaj_knjigu'])) {
    $naslov = $_POST['naslov'];
    $izdavac = $_POST['izdavac'];
    $godina = $_POST['godina'];
    $autor_id = $_SESSION['user']['id'];

    $stmt = $pdo->prepare('INSERT INTO knjige (naslov, izdavac, godina_izdanja, autor_id) VALUES (?, ?, ?, ?)');
    $stmt->execute([$naslov, $izdavac, $godina, $autor_id]);

    header('Location: knjige.php');
    exit;
}

if (isset($_GET['brisanje']) && is_numeric($_GET['brisanje'])) {
    $knjigaId = $_GET['brisanje'];

    if ($_SESSION['user']['uloga'] === 'administrator') {
        $stmt = $pdo->prepare('DELETE FROM knjige WHERE id = ?');
        $stmt->execute([$knjigaId]);
    } elseif ($_SESSION['user']['uloga'] === 'autor') {
        $stmt = $pdo->prepare('DELETE FROM knjige WHERE id = ? AND autor_id = ?');
        $stmt->execute([$knjigaId, $_SESSION['user']['id']]);
    }

    header('Location: knjige.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uredi_knjigu'])) {
    $knjigaId = $_POST['knjiga_id'];
    $naslov = $_POST['naslov'];
    $izdavac = $_POST['izdavac'];
    $godina = $_POST['godina'];

    if ($_SESSION['user']['uloga'] === 'administrator') {
        $stmt = $pdo->prepare('UPDATE knjige SET naslov = ?, izdavac = ?, godina_izdanja = ? WHERE id = ?');
        $stmt->execute([$naslov, $izdavac, $godina, $knjigaId]);
    } elseif ($_SESSION['user']['uloga'] === 'autor') {
        $stmt = $pdo->prepare('UPDATE knjige SET naslov = ?, izdavac = ?, godina_izdanja = ? WHERE id = ? AND autor_id = ?');
        $stmt->execute([$naslov, $izdavac, $godina, $knjigaId, $_SESSION['user']['id']]);
    }

    header('Location: knjige.php');
    exit;
}

if ($_SESSION['user']['uloga'] === 'administrator') {
    $books = $pdo->query('SELECT knjige.*, korisnici.ime, korisnici.prezime FROM knjige JOIN korisnici ON knjige.autor_id = korisnici.id')->fetchAll(PDO::FETCH_ASSOC);
} else {
    $userId = $_SESSION['user']['id'];
    $stmt = $pdo->prepare('SELECT knjige.*, korisnici.ime, korisnici.prezime FROM knjige JOIN korisnici ON knjige.autor_id = korisnici.id WHERE knjige.autor_id = ?');
    $stmt->execute([$userId]);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Knjige</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        #addBookForm {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>Knjige</h1>
        <a href="index.php" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Nazad
        </a>
        <h2 class="mt-4">Popis knjiga</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Naslov</th>
                    <th>Izdavač</th>
                    <th>Godina izdanja</th>
                    <th>Autor</th>
                    <?php if ($_SESSION['user']['uloga'] === 'administrator'): ?>
                        <th>Akcije</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($book['naslov']); ?></td>
                        <td><?php echo htmlspecialchars($book['izdavac']); ?></td>
                        <td><?php echo htmlspecialchars($book['godina_izdanja']); ?></td>
                        <td><?php echo htmlspecialchars($book['ime'] . ' ' . $book['prezime']); ?></td>
                        <?php if ($_SESSION['user']['uloga'] === 'administrator'): ?>
                            <td>
                                <a href="knjige.php?brisanje=<?php echo $book['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Sigurno želite obrisati ovu knjigu?');">Obriši</a>
                                <a href="uredi_knjigu.php?id=<?php echo $book['id']; ?>" class="btn btn-warning btn-sm">Uredi</a>
                            </td>
                        <?php elseif ($_SESSION['user']['uloga'] === 'autor' && $book['autor_id'] == $_SESSION['user']['id']): ?>
                            <td>
                                <a href="knjige.php?brisanje=<?php echo $book['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Sigurno želite obrisati ovu knjigu?');">Obriši</a>
                                <a href="uredi_knjigu.php?id=<?php echo $book['id']; ?>" class="btn btn-warning btn-sm">Uredi</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ($_SESSION['user']['uloga'] === 'administrator' || $_SESSION['user']['uloga'] === 'autor'): ?>
            <button class="btn btn-primary" onclick="toggleForm()">Dodaj knjigu</button>
        <?php endif; ?>

        <div id="addBookForm" class="mt-4">
            <form method="POST">
                <div class="form-group">
                    <label for="naslov">Naslov:</label>
                    <input type="text" id="naslov" name="naslov" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="izdavac">Izdavač:</label>
                    <input type="text" id="izdavac" name="izdavac" class="form-control">
                </div>
                <div class="form-group">
                    <label for="godina">Godina izdanja:</label>
                    <input type="number" id="godina" name="godina" class="form-control" required>
                </div>
                <button type="submit" name="dodaj_knjigu" class="btn btn-primary">Unesi</button>
            </form>
        </div>

    </div>

    <script>
        function toggleForm() {
            var form = document.getElementById('addBookForm');
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