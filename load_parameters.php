<?php
session_start();
header('Content-Type: application/json');

// Sprawdź, czy użytkownik jest zalogowany
if (!isset($_SESSION['logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Użytkownik nie jest zalogowany.']);
    exit;
}

$db = new SQLite3('users.db');
// Pobierz zapisane parametry użytkownika
$stmt = $db->prepare('SELECT velocity, height, gravity FROM users WHERE username = :username');
$stmt->bindValue(':username', $_SESSION['username']);
$result = $stmt->execute();
$user = $result->fetchArray();

if ($user) {
    echo json_encode([
        'success' => true,
        'velocity' => $user['velocity'],
        'height' => $user['height'],
        'gravity' => $user['gravity']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Brak zapisanych parametrów.']);
}
?>