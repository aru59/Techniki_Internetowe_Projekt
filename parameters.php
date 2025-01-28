<?php
session_start();
$db = new SQLite3('users.db');
// Sprawdź, czy użytkownik jest zalogowany
if (!isset($_SESSION['logged_in'])) {
    header('Location: index.php');
    exit;
}

// Pobierz zapisane parametry użytkownika z bazy danych
$stmt = $db->prepare('SELECT velocity, height, gravity FROM users WHERE username = :username');
$stmt->bindValue(':username', $_SESSION['username']);
$result = $stmt->execute();
$user = $result->fetchArray();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Zapisane Parametry</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <header>
        <h1>Zapisane Parametry</h1>
        <?php if (isset($_SESSION['username'])): ?>
            <div class="user-info">
                <p>Login: <strong><?= htmlspecialchars($_SESSION['username']); ?></strong></p>
            </div>
        <?php endif; ?>
    </header>
    <main>
        <?php if ($user): ?>
            <div class="saved-parameters">
                <h2>Twoje zapisane parametry:</h2>
                <p><strong>Prędkość początkowa:</strong> <?= htmlspecialchars($user['velocity']); ?> m/s</p>
                <p><strong>Wysokość początkowa:</strong> <?= htmlspecialchars($user['height']); ?> m</p>
                <p><strong>Przyspieszenie grawitacyjne:</strong> <?= htmlspecialchars($user['gravity']); ?> m/s²</p>
            </div>
        <?php else: ?>
            <p>Brak zapisanych parametrów. Ustaw je w formularzu na stronie głównej!</p>
        <?php endif; ?>
        <button onclick="window.location.href='index.php'">Powrót</button>
    </main>
</body>
</html>