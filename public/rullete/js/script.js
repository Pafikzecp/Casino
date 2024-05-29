document.addEventListener("DOMContentLoaded", function () {
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

    window.placeBet = placeBet;
});
