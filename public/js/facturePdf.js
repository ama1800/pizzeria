

// var factures = document.querySelectorAll('#lienFacture')
// // on recupére le token pour les inclure dans la requete ajax
// var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// // on met la list dans un tableau pour pouvoir boucler dessus
// Array.from(factures).forEach(e => {

//     // on ajout un eventlistner (au changement de la valeur de data-id)
//     e.addEventListener('click', function (event) {
//         // empéche l'execution de l'action par defaut (empéche l'ajout des produits)
//         event.preventDefault();
//         var htmlToPdf = document.querySelector('#toPdf')
//         console.log(htmlToPdf);
//         var numero = this.getAttribute('data-numero')
//         fetch(
//             // l'url de l'action à executer
//             `/facture/pdf/${numero}`,
//             {
//                 //  les headers avec le token de sécurité
//                 headers: {
//                     'Content-Type': 'application/json',
//                     "Accept": "application/json, text/plain, */*",
//                     "X-Requisted-With": "XMLHttpRequest",
//                     "X-CSRF-TOKEN": token
//                 },
//                 // la méthode à exucuter
//                 method: 'post',
//             }
//             //fin du fetch début de l'exploitation du données (ds le controller)
//         ).then((response) => {
//             return response.json()
//         }).then((data) => {
//             console.log(data);
//             // redirect (ici en reste sur la méme page)
//             // location.reload();
//             // Cas d'erreur ici en pourra connaitre quel est l'erreur produite
//         }).catch((error) => {
//             alert(error);
//         })
//     })
// })