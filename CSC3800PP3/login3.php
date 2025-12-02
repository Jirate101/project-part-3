<?php
// login3.php - SQL injection–safe login
session_start();

$user = $_POST['uName'] ?? '';
$pw   = $_POST['pw']    ?? '';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=csc3800pp1_db", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :u");
    $stmt->execute([':u' => $user]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $hash = sha1($pw . $row['salt']);

        if ($hash === $row['password_hash']) {
            $_SESSION['userID'] = $row['id'];
            header("Location: memberInfoSalt.php");
            exit();
        }
    }

    header("Location: loginSalt.html?error=Invalid+credentials");
    exit();

} catch (PDOException $e) {
    echo "SQL Error";
}
?>

