<?php
require 'auth.php';
checkAuth();

if ($_SESSION['user']['uloga'] !== 'administrator') {
    if (isset($_GET['id'])) {
        $knjigaId = $_GET['id'];
        $stmt = $pdo->prepare('SELECT * FROM knjige WHERE id = ? AND autor_id = ?');
        $stmt->execute([$knjigaId, $_SESSION['user']['id']]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} else {
    if (isset($_GET['id'])) {
        $knjigaId = $_GET['id'];
        $stmt = $pdo->prepare('SELECT * FROM knjige WHERE id = ?');
        $stmt->execute([$knjigaId]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

if (!$book) {
    header('Location: knjige.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uredi_knjigu'])) {
    $naslov = $_POST['naslov'];
    $izdavac = $_POST['izdavac'];
    $godina = $_POST['godina'];

    $stmt = $pdo->prepare('UPDATE knjige SET naslov = ?, izdavac = ?, godina_izdanja = ? WHERE id = ?');
    $stmt->execute([$naslov, $izdavac, $godina, $knjigaId]);

    header('Location: knjige.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Uredi knjigu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Uredi knjigu</h1>

        <form method="POST">
            <div class="form-group">
                <label for="naslov">Naslov:</label>
                <input type="text" id="naslov" name="naslov" class="form-control" value="<?php echo htmlspecialchars($book['naslov']); ?>" required>
            </div>
            <div class="form-group">
                <label for="izdavac">Izdavač:</label>
                <input type="text" id="izdavac" name="izdavac" class="form-control" value="<?php echo htmlspecialchars($book['izdavac']); ?>">
            </div>
            <div class="form-group">
                <label for="godina">Godina izdanja:</label>
                <input type="number" id="godina" name="godina" class="form-control" value="<?php echo htmlspecialchars($book['godina_izdanja']); ?>" required>
            </div>
            <button type="submit" name="uredi_knjigu" class="btn btn-primary">Spremi promjene</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
