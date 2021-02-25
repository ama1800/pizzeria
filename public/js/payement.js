// la clé secrete
var stripe = Stripe('pk_test_51IGWJHI5qy2R9q2Q4s4Cu3G91Et8iTjZj9CDnwC3FCx99tYQrZRMq1hZOq1m93zksADAVOnyxxL5uYEPNTWZNRtc00lm3BGumW')

// éléments pour instancier le payement
var elements = stripe.elements()

// button submit payement
var cardButton = document.getElementById("card-button")

// la clé secret recevu de l'api stripe qui authorise de proceder au payement 
var clientSecret = cardButton.dataset.secret;

// Création des éléments du formulaire de carte bancaire
var card = elements.create("card")

// mount : method attache les Elements au DOM.
card.mount("#card-element")

// Ecouteur d'evenement durant les changements (saisie des donnees de la carte credit)
card.on('change', function (event) {
  // div où s'affichera les erreurs lier à la carte
  var displayError = document.getElementById('card-errors');
  if (event.error) {
    displayError.textContent = event.error.message;
  } else {
    displayError.textContent = '';
  }
});

// On gère le paiement
// nom du titulaire de la carte exiger par la loi
var cardholderName = document.getElementById('cardholder-name')
//  le formulaire de payement
var form = document.getElementById('payment-form');
// Ecouteur d'evenement en validant le formulaire
form.addEventListener('submit', function (ev) {
  // empéche de quitter la page
  ev.preventDefault();
  // on désactive le button de validation pour empécher le payement plusieurs fois
  cardButton.disable = true
  // Les données à prendre en compte par l'API pour le payement
  stripe.confirmCardPayment(clientSecret, {
    payment_method: {
      card: card,
      billing_details: {
        name: cardholderName.value
      }
    }
  }).then(function (result) {
    if (result.error) {
      // En cas d'erreur en réactive le button de payement
      cardButton.disable = false
      // alert pour informer le client de l'erreur produite
      alert(`${result.error.message} le payement n'est pas éffectué!.`);
    } else {
      // Le payement c'était bien passé
      if (result.paymentIntent.status === 'succeeded') {
        //En récupére les données à utiliser pour sauvgarder la commande dans la base de donnees
        var paymentIntent = result.paymentIntent;
        var url = form.action;
        var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var redirect1 = 'commande/success';
        var redirect2 = 'payement';
        fetch(
          url,
          {

            headers: {
              'Content-Type': 'application/json',
              "Accept": "application/json, text/plain, */*",
              "X-Requisted-With": "XMLHttpRequest",
              "X-CSRF-TOKEN": token
            },
            method: 'post',
            body: JSON.stringify({
              paymentIntent: paymentIntent,
              csrf: token
            })
          }
        ).then((data) => {
          window.location.href = redirect1;
          alert("Le payement à été effectuer vous pouvez désormé télécharger votre facture ainsi que le bon de commande. Merci");
        }).catch((error) => {
          window.location.href = redirect2;
          alert(result.error.message);
        })
      }
    }
  });
});