<?php
session_start();
$loggedIn = isset($_SESSION['user_id']);
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../account/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>blackjack</title>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="shortcut icon" href="img/favicon.ico">
	<link href="https://fonts.googleapis.com/css?family=Arvo:400,700|Lato" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="css/keyframes.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<div class="navbar-fixed z-depth-0">
	<nav>
		<div class="nav-wrapper">
			<a href="../../index.php" class="left brand-logo">Casino</a>
			<ul id="nav-mobile" class="right">
				<li><a href="#" id="reset-game">Reset Game</a></li>
				<li><a href="#" class="rules-nav"><i class="material-icons">help</i></a></li>
			</ul>
	    </div>
	</nav>
</div>
<div class="container" id="rules">
  	<div class="row">
		<div class="col s12">
			<h4>Game Rules</h4>
		</div>
	</div>
	<div class="row">
		<div class="col s12 m6">
			<p><strong>Goal</strong> Beat the dealer by getting a hand as close to 21 as possible, without going over 21. A blackjack occurs when you get one ace and one 10 point card, without having split the deck first.</p>
			<p><strong>Gameplay</strong> The dealer will give 2 cards to themself and 2 cards to you. The dealer's second card will be played face down. You can choose to either hit (receive more cards) or stand (move on to the next hand). You can hit as many times as you choose so long as your deck is under 21.</p>
			<p><strong>Card Values</strong> Queens, Kings, and Jacks are worth 10, pip cards are worth their face value, but Aces can be worth either 11 or 1. In this game, Aces have a default value is 11 unless you go over 21 on your hand.</p>
		</div>
		<div class="col s12 m6">
			<p><strong>Splitting Pairs</strong> If you receive 2 cards of the same face value, you may choose to split your pairs and play each hand separately. At this table, you can only split once. After you split, one new card will be dealt to each hand then you can choose to either hit or stand for each hand accordingly.</p>
			<p><strong>Betting</strong> Bets must be placed prior to playing. After receiving your first 2 cards, you can choose to double down (double your original bet). After your next move, you can no longer choose to double down.</p>
			<p><strong>Payout</strong> Payout is 1:1 unless you get a blackjack. For a blackjack, payout is 3:2.</p>
			<button id="rules-close">Close</button>
		</div>
	</div>
</div>
<div class="container">
  	<div class="row">
		<div class="col s12" id="welcome">
			<h1>blackjack</h1>
			<p>Start playing by selecting your bet below. To view the rules at any time, click the icon in the top right.</p>
			<div id="wager-options">
				<a href="#" id="chip-10"><img src="img/10-chip.png" class="wager wager-clickable"></a>
				<a href="#" id="chip-25"><img src="img/25-chip.png" class="wager wager-clickable"></a>
				<a href="#" id="chip-50"><img src="img/50-chip.png" class="wager wager-clickable"></a>
				<a href="#" id="chip-100"><img src="img/100-chip.png" class="wager wager-clickable"></a>
				<br>
				<span class="current-wager">0</span>
				<img src="img/pile-chip.png" class="wager-small">
				<span class="current-chip-balance">500</span>
				<br>
				<button id="start-game-button">Play</button>
			</div>	
		</div>
	</div>
</div>
<div class="container inactive" id="game-board">
	<div class="row">
		<div class="col m8 s12">
			<h4>Dealer</h4>
			<div id="deck-pile" class="hide-on-small-only">
				<img src="img/card_back.png" class="card pile">
			</div>
			<div id="dealer"></div>
			<span class="dealer-hand-total game-board-totals"></span>
		</div>
		<div class="col s12 m4">
			<div class="row">
				<div class="col s5 m6 offset-s1 wager-gameplay">
					<img src="img/blank-chip.png" class="wager">
					<span class="current-wager">___</span>
				</div>
				<div class="col s5 m6 wager-gameplay">
					<img src="img/pile-chip.png" class="wager">
					<span class="current-chip-balance">___</span>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
      	<div class="col s12 m8">
      		<h4>Player</h4>
	      		<div id="user-hand"></div>
	      		<span class="hand-total game-board-totals">0</span>
	      		<div id="user-split-hand" class="inactive"></div>
	      		<span class="split-hand-total game-board-totals inactive">0</span>
		</div>
		<div class="col s12 m4" id="sidebar">
			<div class="row">
				<div class="col m6 s3">
        			<button id="hit-button">Hit</button>
        		</div>
        		<div class="col m6 s3">
        			<button id="double-down-button" class="secondary">Double</button>
        		</div>
        		<div class="col m6 s3">
        			<button id="stand-button" class="secondary">Stand</button>
        		</div>
        		<div class="col m6 s3">
					<button class="disabled-button split-button" class="secondary">Split</button>
				</div>
			</div>
     	</div>
	</div>
</div>
<div id="two-aces-prompt" class="modal modal-fixed-footer">
	<div class="modal-content">
		<h4>You drew two aces</h4>
		<p>Would you like to split?</p>
		<button class="split-button modal-action modal-close">Yes</button>
		<button class="reduce-aces-button modal-action modal-close">No</button>
		<p>Dealer's Hand:</p> <span class="dealer-hand-total"></span>
    </div>
</div>
<div class="container">
	<div class="row">
		<div class="col s12 inactive" id="game-over">
			<h3 id="game-outcome">Win message here</h3>
			<table>
 			 <tr>
				<td class="total-label"><h5>Dealer</h5></td>
				<td class="total-output"><span class="dealer-hand-total"></span></td> 
			</tr>
			<tr>
				<td class="total-label"><h5>Player</h5></td>
				<td class="total-output"><span class="hand-total"></span> <span class="split-hand-total inactive"></span></td>
			</tr>
			</table>
			<button class="new-game-button">Play Again</button>
		</div>
  	</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/cards.js"></script>
<script type="text/javascript" src="js/game-play-logic.js"></script>
<script type="text/javascript" src="js/game-win-logic.js"></script>
<script type="text/javascript" src="js/button-actions.js"></script>
<script type="text/javascript" src="js/main.js"></script>
</body>
</html>