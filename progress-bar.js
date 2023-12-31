
document.addEventListener('DOMContentLoaded', function() {
  // Get the progress bar element
  var progressBar = document.querySelector('.progress-bar');

  // Get the thresholds from the data attribute
  var thresholds = JSON.parse(progressBar.getAttribute('data-thresholds').replace(/&quot;/g, '"'));

  // Get the cart total from an element with the ID 'cart-total'
  var cartTotalElement = document.getElementById('cart-total');
  var cartTotal = cartTotalElement ? parseFloat(cartTotalElement.textContent) : 0;

  // Calculate the progress
  var progress;

  if (cartTotal <= thresholds[0].amount) {
    progress = (cartTotal / thresholds[0].amount) * 40; // 40% for the first circle
  } else if (cartTotal <= thresholds[1].amount) {
    progress = 40 + ((cartTotal - thresholds[0].amount) / (thresholds[1].amount - thresholds[0].amount)) * 30; // 30% for the second circle
  } else {
    progress = 70 + ((cartTotal - thresholds[1].amount) / (thresholds[2].amount - thresholds[1].amount)) * 30; // 30% for the third circle
  }

  // Set the width of the progress bar fill
  document.querySelector('.progress-bar-fill').style.width = progress + '%';

// Get all the circles
var circles = document.querySelectorAll('.progress-bar-circle');

// Change the color of the circles if the goal amount is reached
circles.forEach(function(circle, index) {
  if (cartTotal >= thresholds[index].amount) {
    circle.classList.add('goal-reached');
    if (index === 2) {
      // For the third circle (maximum), update the content and triangle color
      circle.style.backgroundColor = '#90c091';
      circle.style.color = 'white';
      circle.querySelector('.progress-bar-circle::before').style.backgroundColor = '#90c091';
      circle.querySelector('.progress-bar-circle::after').style.borderBottomColor = '#90c091';
    }
  }
});


// Calculate the values for the text
var nextThresholdIndex = thresholds.length;
var remainingAmount = 0;
var nextDiscount = 0;
for (var i = 0; i < thresholds.length; i++) {
    if (cartTotal < thresholds[i].amount) {
      nextThresholdIndex = i;
      remainingAmount = thresholds[i].amount - cartTotal;
      nextDiscount = thresholds[i].discount;
      break;
    }
}

if (nextThresholdIndex == thresholds.length) {

    // If cartTotal is greater than the highest threshold
    nextDiscount = thresholds[thresholds.length - 1].discount;
    remainingAmount = 0;
}

// Update the text under the progress bar
var textElement = document.getElementById('progress-bar-text');
if (textElement) {
    if (nextDiscount == 0) {
        textElement.textContent = 'Füge noch ' + remainingAmount.toFixed(2) + ' € deinem Warenkorb hinzu, um Kostenlosen Versand auf deinen Warenkorb zu erhalten. Mehr erfahren';
    } else {
        textElement.textContent = 'Füge noch ' + remainingAmount.toFixed(2) + ' € deinem Warenkorb hinzu, um ' + nextDiscount + ' % Rabatt auf deinen Warenkorb zu erhalten. Mehr erfahren';
    }
    console.log('Next discount:', nextDiscount);
} else {
    console.error("Element mit der ID 'progress-bar-text' wurde nicht gefunden.");
}


  // Update the link URL
  var learnMoreLink = document.getElementById('learn-more-link');
  learnMoreLink.href = 'https://neurolab-vital.de/lieferkonditionen/';
});


function updateProgressBar() {
  // AJAX-Anfrage an get_cart_total.php, um den aktualisierten Warenkorbwert zu erhalten
  fetch('/wp-content/plugins/elementor-nl-balken/get_cart_total.php')
      .then(response => {
          if (!response.ok) {
              throw new Error('Netzwerkantwort war nicht ok.');
          }
          return response.json();
      })
      .then(data => {
        var cartTotal = parseFloat(data.cart_total_with_tax);

          console.log("Current cart total:", cartTotal);
          console.log("Thresholds:", thresholds);
          
          // Aktualisieren Sie den Fortschrittsbalken basierend auf dem neuen Warenkorbwert
          var progressBar = document.querySelector('.progress-bar');
          var thresholds = JSON.parse(progressBar.getAttribute('data-thresholds').replace(/&quot;/g, '\"'));
          var progress;

          if (cartTotal <= thresholds[0].amount) {
              progress = (cartTotal / thresholds[0].amount) * 40; // 40% for the first circle
          } else if (cartTotal <= thresholds[1].amount) {
              progress = 40 + ((cartTotal - thresholds[0].amount) / (thresholds[1].amount - thresholds[0].amount)) * 30; // 30% for the second circle
          } else {
              progress = 70 + ((cartTotal - thresholds[1].amount) / (thresholds[2].amount - thresholds[1].amount)) * 30; // 30% for the third circle
          }

          // Setzen Sie die Breite des Fortschrittsbalken-Füllbereichs
          document.querySelector('.progress-bar-fill').style.width = progress + '%';

          // Definieren Sie die circles Variable
          var circles = document.querySelectorAll('.progress-bar-circle');

          // Ändern Sie die Farbe der Kreise, wenn der Zielbetrag erreicht ist
          circles.forEach(function(circle, index) {
            if (cartTotal >= thresholds[index].amount) {
                console.log("Circle", index, "should be green");
                circle.classList.add('goal-reached');
            } else {
                console.log("Circle", index, "should be gray");
                circle.classList.remove('goal-reached');
            }
        });
        
      })
      .catch(error => {
          console.log('Es gab ein Problem mit der Fetch-Operation:', error.message);
      });
}


// Rufen Sie die updateProgressBar Funktion alle 1 Sekunden auf
setInterval(updateProgressBar, 10000);