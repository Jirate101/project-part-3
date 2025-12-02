<?php
// registerSalt.php

$fname    = $_POST['fname']    ?? '';
$lname    = $_POST['lname']    ?? '';
$email    = $_POST['email']    ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['pw']       ?? '';
$deposit  = $_POST['deposit']  ?? 0;

// combine first + last into full_name
$full_name = trim($fname . ' ' . $lname);

$con = new mysqli("localhost", "root", "", "csc3800pp1_db");
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// generate salt & salted hash
$salt   = bin2hex(random_bytes(16));          // 32-char hex salt
$hashed = sha1($password . $salt);            // salted SHA-1

// escape values
$full_name_esc = $con->real_escape_string($full_name);
$email_esc     = $con->real_escape_string($email);
$username_esc  = $con->real_escape_string($username);
$deposit_val   = floatval($deposit);

// insert into users table
$sql = "INSERT INTO users (full_name, email, username, password_hash, salt, deposit)
        VALUES ('$full_name_esc', '$email_esc', '$username_esc', '$hashed', '$salt', $deposit_val)";

if ($con->query($sql) === TRUE) {
    $con->close();
    header("Location: loginSalt.html?msg=Registered+successfully");
    exit();
} else {
    echo "Error: " . $con->error;
    $con->close();
}
?>

