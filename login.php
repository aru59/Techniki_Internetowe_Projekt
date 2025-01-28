<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$db = new SQLite3('users.db');
// Tworzenie tabeli użytkowników, jeśli jeszcze nie istnieje
try {
    $db = new SQLite3('users.db');
    $db->exec('
        CREATE TABLE IF NOT EXISTS users (
            username TEXT PRIMARY KEY,
            password TEXT NOT NULL,
            velocity REAL DEFAULT 0,
            height REAL DEFAULT 0,
            gravity REAL DEFAULT 9.81
        )
    ');
} catch (Exception $e) {
    die('Błąd połączenia z bazą danych: ' . $e->getMessage());
}

// Funkcja rejestracji nowego użytkownika
function registerUser($db, $username, $password) {
    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindValue(':username', $username);
    $result = $stmt->execute();
    $user = $result->fetchArray();

    if ($user) {
        $_SESSION['message'] = "This username is already taken. Please choose a different one.";
        header('Location: index.php');
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':password', $hashedPassword);
    $stmt->execute();

    $_SESSION['logged_in'] = true;
    $_SESSION['username'] = $username;
    header('Location: index.php');
    exit;
}

// Funkcja logowania użytkownika
function loginUser($db, $username, $password) {
    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindValue(':username', $username);
    $result = $stmt->execute();
    $user = $result->fetchArray();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header('Location: index.php');
        exit;
    }

    $_SESSION['message'] = 'Invalid username or password.';
    header('Location: index.php');
    exit;
}

// Obsługa żądania POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register']) && isset($_POST['username']) && isset($_POST['password'])) {
        registerUser($db, $_POST['username'], $_POST['password']);
    } elseif (isset($_POST['login']) && isset($_POST['username']) && isset($_POST['password'])) {
        loginUser($db, $_POST['username'], $_POST['password']);
    } else {
        $_SESSION['message'] = 'Invalid request.';
        header('Location: index.php');
        exit;
    }
}
?>