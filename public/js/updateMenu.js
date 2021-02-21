
// Gérer le menu
// list node HTML récupérer par queryselector
var menuPlus = document.querySelectorAll('#ajouter');
var menuQte = document.querySelectorAll('#qtyMenu');
var produits = document.querySelectorAll('#produit');

// on recupére l'id produit et le token pour les inclure dans la requete ajax
var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

window.onload = () => {
    // Ajout d'un menu
    menuPlus.forEach(e => {
        // console.log(e.attributes['data-menu'].value);
        e.addEventListener('click', function (event) {
            // empéche l'execution de l'action par defaut (empéche l'ajout des produits)
            event.preventDefault();
            var menuId = this.attributes['data-menu'].value
            produits.forEach(el => {
                var menuOfProduitd = el.attributes['data-menuOfProduit'].value;
                if (menuId == menuOfProduitd) {
                    var produitsIds = el.attributes['data-id'].value;
                    const ids = []
                    ids.push(Number(produitsIds))
                    console.log(ids);
                    for (id of ids) {
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
                    }
                }
            })
        })
    })
    // Ajout de plusieur menus
    menuQte.forEach(e => {
        // console.log(e.attributes['data-menu'].value);
        e.addEventListener('change', function (event) {
            // empéche l'execution de l'action par defaut (empéche l'ajout des produits)
            event.preventDefault();
            var menuId = this.attributes['data-menu'].value
            produits.forEach(el => {
                var menuOfProduitd = el.attributes['data-menuOfProduit'].value;
                if (menuId == menuOfProduitd) {
                    var produitsIds = el.attributes['data-id'].value;
                    const ids = []
                    ids.push(Number(produitsIds))
                    console.log(ids);
                    for (id of ids) {
                        fetch(
                            `/panier/${id}/up`,
                            {
                                headers: {
                                    'Content-Type': 'application/json',
                                    "Accept": "application/json, text/plain, */*",
                                    "X-Requisted-With": "XMLHttpRequest",
                                    "X-CSRF-TOKEN": token
                                },
                                method: 'patch',
                                body: JSON.stringify({
                                    qte: this.value,
                                })
                            }
                        ).then((data) => {
                            // console.log(data);
                            location.reload();

                        }).catch((error) => {
                            console.log(error);
                        })
                    }
                }
            })
        })
    })
}
