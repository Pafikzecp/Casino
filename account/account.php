<?php
session_start();
$loggedIn = isset($_SESSION['user_id']);
include '../includes/navbar.php';
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_balance = $_POST['balance'];
    $user_id = $_SESSION['user_id'];

    $query = "UPDATE users SET balance = $new_balance WHERE id = $user_id";
    if ($conn->query($query) === TRUE) {
        $_SESSION['balance'] = $new_balance;
        $message = "Balance updated successfully.";
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account</title>
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
            <h1>Account</h1>
            <?php if (isset($message)): ?>
                <p style="color: green;"><?php echo $message; ?></p>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="POST" action="account.php">
                <div class="input-field">
                    <input type="number" name="balance" id="balance" value="<?php echo $_SESSION['balance']; ?>" required>
                    <label for="balance" class="active">Balance</label>
                </div>
                <button type="submit" class="account-button">Update Balance</button>
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
