<?php
session_start();
$loggedIn = isset($_SESSION['user_id']);
include '../includes/navbar.php';
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Username already exists. Please choose another.";
    } else {
        // Insert the new user
        $stmt->close();
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password, balance) VALUES (?, ?, 500)");
        $stmt->bind_param('ss', $username, $hashed_password);
        
        if ($stmt->execute()) {
            header('Location: login.php');
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" href="img/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Arvo:400,700|Lato" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../public/blackjack/css/style.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col s12">
            <h1>Sign Up</h1>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="POST" action="signup.php">
                <div class="input-field" style="align-items: left;">
                    <input type="text" name="username" id="username" required>
                    <label for="username">Username</label>
                </div>
                <div class="input-field" style="align-items: left;">
                    <input type="password" name="password" id="password" required>
                    <label for="password">Password</label>
                </div>
                <button type="submit" class="account-button" style="align-items: left;">Sign Up</button>
            </form>
        </div>
    </div>
</div>

<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Include Materialize JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
<script>
    // Initialize Materialize CSS input fields
    $(document).ready(function() {
        M.updateTextFields();
    });
</script>
</body>
</html>
