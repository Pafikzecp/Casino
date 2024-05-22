// This file contains logic for what happens after the round is determined to be over

function gameOver() {
	isGameOver = true;
	setTimeout(function(){
		flipHiddenCard();
	}, 750);
	disableButton(standButton);
	disableButton(hitButton);
	disableButton(splitButton);
	disableButton(doubleDownButton);
	if (dealerHandTotal === 21) {
		if (playerHandTotal === 21 || playerSplitHandTotal === 21) {
			gameWinner = "tie";
		} else {
			gameWinner = "dealer";
		}
	} else if (dealerHandTotal > 21) {
		if (playerHandTotal <= 21) {
			gameWinner = "player";
		} else if (splitGame === true && playerSplitHandTotal <= 21) {
			gameWinner = "player";
		} else {
			gameWinner = "tie";
		}
	} else if (dealerHandTotal < 21) {
		if (playerHandTotal === 21  || playerSplitHandTotal === 21) {
			gameWinner = "player";
		} else if (playerHandTotal < 21 && playerHandTotal > dealerHandTotal) {
			gameWinner = "player";
		} else if (playerSplitHandTotal < 21 && playerSplitHandTotal > dealerHandTotal) {
			gameWinner = "player";
		} else if (playerSplitHandTotal < 21 && playerSplitHandTotal === dealerHandTotal ||
			playerHandTotal < 21 && playerHandTotal === dealerHandTotal) {
			gameWinner = "tie";
		} else {
			gameWinner = "dealer";
		}
	}
	updateChipBalance();
	setTimeout(announceWinner, 2500); // Slight delay to give time to see the final cards play out
} 

function updateChipBalance() {
    if (gameWinner === "player") {
        if (splitGame === false && playerHasAce === true && playerHandTotal === 21 && playerHand.length === 2) {
            currentChipBalance += currentWager * (3 / 2) + currentWager;
        } else {
            currentChipBalance += currentWager * 2;
        }
    } else if (gameWinner === "tie") {
        currentChipBalance += currentWager;
    }

    updateVisibleChipBalances();

    $.ajax({
        url: 'update_balance.php',
        method: 'POST',
        data: { balance: currentChipBalance },
        dataType: 'json',
        success: function(data) {
            if (data.status !== 'success') {
                console.error('Failed to update balance');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error updating balance:', error);
        }
    });
}

function announceWinner() {
	updateVisibleHandTotals();
	currentWager = 0;
	updateVisibleChipBalances();
	$("#game-board").hide();
	enlargeDeck(playerSplitGameBoard, playerSplitHandTotalDisplay);
	enlargeDeck(playerGameBoard, playerHandTotalDisplay);

	// Move betting options from welcome screen to game over screen to play again
	$("#wager-options").appendTo($("#game-over")); 
	$(playAgainButton).appendTo($("#wager-options")); // to move to bottom of container
	$(startButton).hide(); 
	$("#game-over").show("drop", 500);

	if (gameWinner === "player") {
		$("#game-outcome").text("You won");
	} else if (gameWinner === "dealer") {
		$("#game-outcome").text("Dealer won");
	} else if (gameWinner === "tie") {
		$("#game-outcome").text("You tied");
	}
}