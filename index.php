<?php
session_start();
$loggedIn = isset($_SESSION['user_id']);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/db.php';

$loggedIn = isset($_SESSION['user_id']);
include 'includes/navbar.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blackjack Casino</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" href="img/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Arvo:400,700|Lato" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="public/blackjack/css/style.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col s12">
            <h1>Welcome to online Casino</h1>
            <p>Click below to start playing Blackjack!</p>
            <a href="public/blackjack/bj.php"><img src="public/img/bj.png" alt="Blackjack" style="max-width:30%;height:30%;"></a>
            <a href="public/race/race.php"><img src="public/img/race.png" alt="Blackjack" style="max-width:30%;height:30%;"></a>
        </div>
    </div>
</div>
</body>
</html>
