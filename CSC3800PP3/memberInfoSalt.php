<?php
session_start();

// 1. Check login session
if (!isset($_SESSION['userID'])) {
    header("Location: loginSalt.html");
    exit();
}

$id = intval($_SESSION['userID']);

// 2. Connect to DB
$con = new mysqli("localhost", "root", "", "csc3800pp1_db");
if ($con->connect_error) {
    die("DB connection error: " . $con->connect_error);
}

// 3. Fetch user row
$sql  = "SELECT * FROM users WHERE id = $id";
$result = $con->query($sql);

if (!$result || $result->num_rows === 0) {
    die("User not found in database. (ID: $id)");
}

$user = $result->fetch_assoc();

$result->close();
$con->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Member Info</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="page">
    <div class="card profile-card">
        <div class="card-header">
            <div class="card-title">Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</div>
            <div class="card-subtitle">Account overview</div>
        </div>

        <div class="profile-field">
            <span>Full Name</span>
            <span><?php echo htmlspecialchars($user['full_name']); ?></span>
        </div>

        <div class="profile-field">
            <span>Email</span>
            <span><?php echo htmlspecialchars($user['email']); ?></span>
        </div>

        <div class="profile-field">
            <span>Deposit</span>
            <span>$<?php echo htmlspecialchars($user['deposit']); ?></span>
        </div>
    </div>
</div>
</body>
</html>



