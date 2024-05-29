<?php
session_start();
$loggedIn = isset($_SESSION['user_id']);
if (!$loggedIn) {
    header('Location: ../../account/login.php');
    exit();
}

// Include the database connection
include '../../includes/db.php';
include '../../includes/navbar.php';

$userId = $_SESSION['user_id'];

// Fetch user balance
$query = "SELECT balance FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$stmt->bind_result($balance);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Roulette</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../blackjack/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" href="img/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Arvo:400,700|Lato" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="../blackjack/css/style.css">
</head>
<body>
    <div class="shadow"></div>
    <div class="roulette">
        <div class="result"></div>
        <div class="numbers">
            <?php for ($i = 1; $i <= 24; $i++): ?>
                <div class="number <?php echo $i % 2 == 0 ? 'black' : 'red'; ?>"><?php echo $i; ?></div>
            <?php endfor; ?>
        </div>
    </div>
    <div class="menu">
        <p>Your Balance: <span id="balance"><?php echo $balance; ?></span></p>
        <form id="bet-form">
            <label for="bet-amount">Bet Amount:</label>
            <input type="number" id="bet-amount" name="bet-amount" min="1" max="<?php echo $balance; ?>" required>
            <label for="bet-type">Bet Type:</label>
            <select id="bet-type" name="bet-type" onchange="toggleNumberInput()">
                <option value="color">Color</option>
                <option value="number">Number</option>
            </select>
            <div id="color-bet">
                <label for="bet-color">Bet Color:</label>
                <select id="bet-color" name="bet-color">
                    <option value="red">Red</option>
                    <option value="black">Black</option>
                </select>
            </div>
            <div id="number-bet" style="display: none;">
                <label for="bet-number">Bet Number:</label>
                <input type="number" id="bet-number" name="bet-number" min="1" max="24">
            </div>
            <button type="button" onclick="placeBet()">Place Bet</button>
        </form>
    </div>

    <script src="js/script.js"></script>
    <script>
        function toggleNumberInput() {
            const betType = document.getElementById('bet-type').value;
            document.getElementById('color-bet').style.display = betType === 'color' ? 'block' : 'none';
            document.getElementById('number-bet').style.display = betType === 'number' ? 'block' : 'none';
        }

        function placeBet() {
            let betAmount = document.getElementById('bet-amount').value;
            let betType = document.getElementById('bet-type').value;
            let betValue;

            if (betType === 'color') {
                betValue = document.getElementById('bet-color').value;
            } else {
                betValue = document.getElementById('bet-number').value;
            }

            // Ensure bet amount is not more than balance
            let balanceElement = document.getElementById('balance');
            let balance = parseInt(balanceElement.innerText);
            if (betAmount > balance) {
                alert('Bet amount cannot be more than your balance');
                return;
            }

            // Ensure bet amount is not empty
            if (!betAmount) {
                alert('Please enter a valid bet amount');
                return;
            }

            // Ensure a valid number is selected for number bets
            if (betType === 'number' && (!betValue || betValue < 1 || betValue > 24)) {
                alert('Please enter a valid number between 1 and 24');
                return;
            }

            // Start the roulette
            startRoulette(betAmount, betType, betValue);
        }

        function startRoulette(betAmount, betType, betValue) {
            var numbersBlock = document.querySelector('.numbers');
            var numbers = document.querySelectorAll('.numbers > .number');

            for (var i = 0; i < numbers.length; i++) {
                numbers[i].style.background = numbers[i].classList.contains('red') ? 'red' : 'black';
                numbers[i].style.color = numbers[i].classList.contains('black') ? 'white' : 'black';
                numbers[i].classList.remove('highlight'); // Remove highlight from previous round
            }

            var randomIndex = Math.floor(Math.random() * numbers.length);
            numbersBlock.style.left = -randomIndex * 100 + 'px';

            setTimeout(function() {
                numbers[randomIndex].classList.add('highlight');
                checkBet(numbers[randomIndex], betAmount, betType, betValue);
            }, 5000);
        }

        function checkBet(winningNumber, betAmount, betType, betValue) {
            let isWin = false;
            if (betType === 'color') {
                isWin = winningNumber.classList.contains(betValue);
            } else {
                isWin = winningNumber.innerText === betValue;
            }

            let balanceElement = document.getElementById('balance');
            let balance = parseInt(balanceElement.innerText);

            if (isWin) {
                balance += betType === 'color' ? betAmount * 2 : betAmount * 24;
            } else {
                balance -= betAmount;
            }

            balanceElement.innerText = balance;

            // Update balance in the database
            fetch('update_balance.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ balance: balance })
            }).then(response => response.json())
            .then(data => {
                if (data.status !== 'success') {
                    console.error('Failed to update balance');
                }
            }).catch(error => console.error('Error updating balance:', error));
        }
    </script>
</body>
</html>
