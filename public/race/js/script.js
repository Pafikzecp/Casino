$(document).ready(function() {
    $.ajax({
        url: 'get_balance.php',
        method: 'GET',
        dataType: 'json', // Ensure the data is parsed as JSON
        success: function(data) {
            if (data && typeof data.balance !== 'undefined') {
                score = parseInt(data.balance, 10); // Parse the balance as an integer
                updateVisibleBalances();
            } else {
                console.error('Balance data not received properly');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching balance:', error);
        }
    });
});

let score = 0;
let bet = 0;
let has_win = false;
let which_car = "";
const car1 = document.querySelector('.car_1');
const car2 = document.querySelector('.car_2');
const minus = document.querySelector(".minus");
const plus = document.querySelector(".plus");
const resultMessage = document.querySelector(".result-message");

function updateVisibleBalances() {
    document.querySelector(".score").innerHTML = score;
    document.querySelector(".bet").innerHTML = bet;
    updateBalanceInDB(score);
}

plus.onclick = () => {
    if (bet >= score) {
        plus.disabled = true;
    } else {
        plus.disabled = false;
        minus.disabled = false;
        bet += 10;
        updateVisibleBalances();
    }
};

minus.onclick = () => {
    if (bet <= 0) {
        minus.disabled = true;
    } else {
        plus.disabled = false;
        minus.disabled = false;
        bet -= 10;
        updateVisibleBalances();
    }
};

const speed_car = (car, color) => {
    let margin = 0;
    const interval = setInterval(() => {
        const speed = Math.random() * 0.1;
        margin = margin + speed;
        car.style.marginLeft = margin + "%";

        // Collision detection
        const carRect = car.getBoundingClientRect();
        const collider = car.parentElement.querySelector('.collider');
        const colliderRect = collider.getBoundingClientRect();

        if (carRect.right >= colliderRect.left) {
            if (!has_win) {
                has_win = true;
                if (which_car === color) {
                    score += bet * 2;
                    displayResult("You win!");
                } else {
                    displayResult("You lose!");
                }
                updateVisibleBalances();
            }
            clearInterval(interval);
        }
    }, 5);
};

const property_winner = (color) => {
    if (bet <= 0 || score <= 0) {
        displayResult("Place a bet to start a race");
    } else {
        has_win = false;
        resultMessage.innerHTML = ""; // Clear previous message
        speed_car(car1, "white");
        speed_car(car2, "black");
        which_car = color;
        score -= bet;
        updateVisibleBalances();
    }
};

function updateBalanceInDB(balance) {
    $.ajax({
        url: 'update_balance.php',
        method: 'POST',
        data: { balance: balance },
        dataType: 'json',
        success: function(data) {
            if (data.status !== 'success') {
                console.error('Failed to update balance in DB');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error updating balance in DB:', error);
        }
    });
}

function displayResult(message) {
    resultMessage.innerHTML = message;
}
