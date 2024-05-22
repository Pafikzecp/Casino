<?php
session_start();
$loggedIn = isset($_SESSION['user_id']);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../includes/db.php';

$loggedIn = isset($_SESSION['user_id']);
include '../../includes/navbar.php';


$loggedIn = isset($_SESSION['user_id']);
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../account/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Racing Game</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="../blackjack/css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Arvo:400,700|Lato" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
</head>
<body>

<div class="wrapper">
    <div class="road">
        <img src="img/car2.png" class="car car_1">
        <div class="finish-line"></div>
        <div class="collider"></div>
    </div>
    <div class="road">
        <img src="img/car1.png" class="car car_2">
        <div class="finish-line"></div>
        <div class="collider"></div>
    </div>
</div>
<div class="info">
    <p>Your Balance is <span class="score"></span></p>
    <p>Your Bet <span class="bet"></span></p>
    <button class="minus">-10</button>
    <button class="plus">+10</button>
    <p>Who will win?</p>
    <button class="car1" onclick="property_winner('black')">Red</button>
    <button class="car2" onclick="property_winner('white')">Green</button>
</div>
<div class="result-message"></div>

<script src="js/script.js"></script>
</body>
</html>