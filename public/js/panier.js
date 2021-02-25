// list node
var plus = document.querySelectorAll('#plus')
var moins = document.querySelectorAll('#moins');
var sup = document.querySelectorAll('#sup');
// on recupére l'id produit et le token pour les inclure dans la requete ajax
var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// on la met dans un tableau pour pouvoir boucler dessus
Array.from(plus).forEach(e => {

    // on ajout un eventlistner (click)
    e.addEventListener('click', function (event) {
        // empéche de quitter la page
        event.preventDefault();
        var id = this.getAttribute('data-id')
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
                    csrf: token
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

// on la met dans un tableau pour pouvoir boucler dessus
Array.from(moins).forEach(e => {
    e.addEventListener('click', function (event) {

        // empéche de submit ou suivre lien
        event.preventDefault();

        var id = this.getAttribute('data-id')
        fetch(
            `/panier/${id}/moins`,
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
                    csrf: token
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
// on la met dans un tableau pour pouvoir boucler dessus
Array.from(sup).forEach(e => {
    e.addEventListener('click', function (event) {

        // empéche de submit ou suivre lien
        event.preventDefault();

        var id = this.getAttribute('data-id')
        fetch(
            `/panier/${id}/remove`,
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
                    csrf: token
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