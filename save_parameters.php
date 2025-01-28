<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$db = new SQLite3('users.db');
if (isset($_SESSION['logged_in']) && isset($_POST['velocity']) && isset($_POST['height']) && isset($_POST['gravity'])) {
    $stmt = $db->prepare('UPDATE users SET velocity = :velocity, height = :height, gravity = :gravity WHERE username = :username');
    $stmt->bindValue(':username', $_SESSION['username']);
    $stmt->bindValue(':velocity', $_POST['velocity']);
    $stmt->bindValue(':height', $_POST['height']);
    $stmt->bindValue(':gravity', $_POST['gravity']);
    $stmt->execute();
    echo json_encode(['status' => 'success', 'message' => 'Dane zostały zapisane pomyślnie.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Nieprawidłowe dane lub użytkownik niezalogowany.']);
}
?>