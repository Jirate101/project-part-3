<?php
// loginSalt.php
session_start();

$user = $_POST['uName'] ?? '';
$pw   = $_POST['pw']    ?? '';

$con = new mysqli("localhost", "root", "", "csc3800pp1_db");
if ($con->connect_error) {
    die("DB error: " . $con->connect_error);
}

$userEsc = $con->real_escape_string($user);
$sql     = "SELECT * FROM users WHERE username = '$userEsc'";
$result  = $con->query($sql);

if ($result && $result->num_rows === 1) {
    $row     = $result->fetch_assoc();
    $salt    = $row['salt'];
    $dbHash  = $row['password_hash'];

    if ($dbHash === sha1($pw . $salt)) {
        $_SESSION['userID'] = $row['id'];
        $result->close();
        $con->close();
        header("Location: memberInfoSalt.php");
        exit();
    }
}

if ($result) $result->close();
$con->close();
header("Location: loginSalt.html?error=Invalid+credentials");
exit();
?>

