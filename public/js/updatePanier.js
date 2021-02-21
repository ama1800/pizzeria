// list node
var selects = document.querySelectorAll('#qte')
var addToCart = document.querySelectorAll('#ajouter');
// on recupére l'id produit et le token pour les inclure dans la requete ajax
var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// on met la list dans un tableau pour pouvoir boucler dessus
Array.from(selects).forEach(e => {

    // on ajout un eventlistner (au changement de la valeur de data-id)
    e.addEventListener('change', function (event) {
        // empéche l'execution de l'action par defaut (empéche l'ajout des produits)
        event.preventDefault();
        var id = this.getAttribute('data-id')
        fetch(
            // l'url de l'action à executer
            `/panier/${id}/up`,
            {
                //  les headers avec le token de sécurité
                headers: {
                    'Content-Type': 'application/json',
                    "Accept": "application/json, text/plain, */*",
                    "X-Requisted-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": token
                },
                // la méthode à exucuter
                method: 'patch',
                // conversion du json en string pour l'exploiter
                body: JSON.stringify({
                    //apés stringify en récupére la quantite choisi
                    qte: this.value
                })
            }
            //fin du fetch début de l'exploitation du données (ds le controller)
        ).then((data) => {
            // redirect (ici en reste sur la méme page)
            location.reload();
            // Cas d'erreur ici en pourra connaitre quel est l'erreur produite
        }).catch((error) => {
            alert(error);
        })

    })
})

// on la met dans un tableau pour pouvoir boucler dessus
Array.from(addToCart).forEach(e => {
    e.addEventListener('click', function (event) {

        // empéche de quitter la page
        event.preventDefault();

        var id = this.getAttribute('data-id')
        // console.log(id);
        fetch(
            `/panier/add/${id}`,
            {
                headers: {
                    'Content-Type': 'application/json',
                    "Accept": "application/json, text/plain, */*",
                    "X-Requisted-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": token
                },
                method: 'patch',
                body: JSON.stringify({
                    qte: 1,
                })
            }
        ).then((data) => {
            // console.log(data);
            location.reload();

        }).catch((error) => {
            console.log(error);
        })

    })
})
