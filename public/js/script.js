/* Menu burger */
var burger = document.getElementById("imgburger")
var nav = document.getElementById("headdown");
burger.addEventListener('click', function () {
  if (nav.style.display === "grid") {
    nav.style.display = "none";
  } else {
    nav.style.display = "grid";
  }
})

/*light box */
var myModal = document.getElementById("myModal")
if (myModal) {
  // Open the Modal
  function openModal() {
    myModal.style.display = "block";
  }
  // Close the Modal
  function closeModal() {
    myModal.style.display = "none";
  }
}
var slides = document.getElementsByClassName("mySlides");
var dots = document.getElementsByClassName("demo");
var captionText = document.getElementById("caption");
if (slides && dots && captionText) {
  var slideIndex = 1;
  showSlides(slideIndex);
  // Next/previous controls
  function plusSlides(n) {
    showSlides(slideIndex += n);
  }
  // Thumbnail image controls
  function currentSlide(n) {
    showSlides(slideIndex = n);
  }
}
function showSlides(n) {
  if (n > slides.length) { slideIndex = 1 }
  if (n < 1) { slideIndex = slides.length }
  for (let i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (let i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex - 1].style.display = "block";
  dots[slideIndex - 1].className += " active";
  captionText.innerHTML = dots[slideIndex - 1].alt;
}
/* accordion */

var acc = document.getElementsByClassName("accordion");
for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function () {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.display == "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}
/**panier */
// payement
// console.log($(document));
// $(document).ready(() => {
//   var pathname = window.location.pathname; // Returns path only (/path/example.html)
//   // var url      = window.location.href;     // Returns full URL (https://example.com/path/example.html)
//   // var origin   = window.location.origin;   // Returns base URL (https://example.com)
//   var payer = $('#payer')
//   var section = $('.right')
//   payer.on('click', () => {
//     if (pathname == '/new') {
//       section.style.opacity = 0;
//     }
//   })
// })


/* ZipCode */

$(document).ready(function () {
  const comUrl = "https://geo.api.gouv.fr/communes?codePostal="
  const adressUrl = "https://api-adresse.data.gouv.fr/search/?q="//8+bd+du+port&postcode=44380
  const format = "&format=json"
  let nom = "";
  let cp = $('#user_cp')
  let ville = $('#user_commune')
  let adresse = $('#user_adresse')
  let adresses = $('#adresses')
  $(cp).on('blur', function () {
    let code = $(this).val()
    let url = comUrl + code + format
    console.log(url)
    fetch(
      url,
      { method: 'get' }).then(response => response.json()).then(results => {
        // console.log(results)
        if (results.length) {
          $.each(results, function (k, v) {
            // console.log(v)
            // console.log(v.nom)
            $(ville).append('<option value="' + v.nom + '">' + v.nom + '</option>')
          })
        }
      }).catch(err => {
        console.log(err)
      })
  })

  /** Adresse */
  $(adresse).on('keypress', function () {
    let code = $(cp).val()
    let nom = $(this).val().split(' ')
    let part = ""
    for (let i = 0; i < nom.length; i++) {
      part += nom[i] + '+'
      if (part.length >= 1) {
        let urlAdresse = adressUrl + part + "&postcode=" + code + format
        console.log(urlAdresse)
        fetch(
          urlAdresse,
          { method: 'get' }).then(response => response.json()).then(results => {
            if (results.features) {
              let data = results.features
              data.forEach((adresse) => {
                document.querySelector('#adresses').innerHTML += `<option value="${adresse.properties.name}">`
              })
            }
          }).catch(err => {
            console.log(err)
          })
      }

    }
  })
})

// Plus Moins Produits
// Plus
// function onclick(event) {
//   pmButtonAddOne('productnumberO7N5PQPP01,basketproductnrspanO7N5PQPP01');
//   updateBtnPrice($('#isidedishselectionformO7N5PQPP01 .sidedish-add-button'), $('#isidedishpriceO7N5PQPP01').val(), 'O7N5PQPP01');
//   return false;
// }
// // Moins
// function onclick(event) {
//   pmButtonSubtractOne('productnumberO7N5PQPP01,basketproductnrspanO7N5PQPP01', 1);
//   updateBtnPrice($('#isidedishselectionformO7N5PQPP01 .sidedish-add-button'), $('#isidedishpriceO7N5PQPP01').val(), 'O7N5PQPP01');
//   return false;
// }
// // button Add
// function onclick(event) {
//   $('#isidedishselectionformO7N5PQPP01').submit();
//   $('#productformpopularO7N5PQPP01 .sidedish-pop-in').trigger('closeandscroll');
//   return false;
// }


// /** modifier password */
// let pass = document.getElementById("editPass")
// let input = document.getElementById("pass")
// pass.addEventListener('click', function () {
//     if (input.style.display == "block") {
//         input.style.display = "none"
//     }
//     else {
//         input.style.display = "block"
//     }
// });
// /**
//  API grv des meteo
//  */
// let divMeteo = $('#meteo')
// let commune=$('#commune')
// let met= $('.met')
// let urlMeteo = "https://api.meteo-concept.com/api/forecast/daily?token=252724d997e784c487b348613a6faf2ed719bdf72fa2ed845eba772b5192e80d&insee=67482"
// fetch(
//     urlMeteo,
//     { method: 'get' }).then(response => response.json()).then(results => {
//         let location = results.city.name
//         $(document).ready(function(){
//             $(commune).html= location
//         })
//         let tab = results.forecast
//         console.log(tab)
//         $.each(tab, function (k, v) {
//             for (let i = 0; i < tab.length; i++) {
//                 let heur = v.datetime
//                 let heure = heur[i]
//                 for(let j=0; j<=heure;j+=24){
//                     let heures=heure[j]
//                 $(met).append('<option class="resultat" value="' + heures + '">' + heures + '</option>')
//                 }
//             }
//         })
//             // console.log(results.forecast)

//             // Récupération de certains résultats
//             // var temperature = meteo.current_observation.temp_c;
//             // var humidite = meteo.current_observation.relative_humidity;
//             // var imageUrl = meteo.current_observation.icon_url;
//             // // Affichage des résultats
//             // var conditionsElt = document.createElement("div");
//             // conditionsElt.textContent = "Il fait actuellement " + temperature +
//             //     "°C et l'humidité est de " + humidite;
//             // var imageElt = document.createElement("img");
//             // imageElt.src = imageUrl;
//             // var meteoElt = document.getElementById("meteo");
//             // meteoElt.appendChild(conditionsElt);
//             // meteoElt.appendChild(imageElt);
//             // divMeteo.value = conditionsElt.textContent + imageElt.src + meteoElt.appendChild(conditionsElt) + meteoElt.appendChild(imageElt)
//         })

//         /* change theme */


// let goute = document.getElementById('btn-goute')
// let style1 = document.getElementById("btn-style1")
// let style2 = document.getElementById("btn-style2")
// let origine = document.getElementById("origine")
// let logo = document.getElementById("logo-img")
// let burger = document.getElementById("imgburger")
// let themeWeb = document.getElementById('theme_css')
// let themeMob = document.getElementById('themeMob_css')
// function changeTheme(){
// goute.addEventListener('click',function () { 
//   style1.style.display="block"
//   style2.style.display="block"
//   origine.style.display="block"
//   style1.addEventListener('click',function () {
//   themeWeb.href = 'theme/style1.css';
//   document.getElementById('logo-img').src='img/vibes2.png';
//   document.getElementById('imgburger').src='img/burger1.png';
//   })
//   style2.addEventListener('click',function () {
//   themeWeb.href = 'theme/style2.css';
//   document.getElementById('logo-img').src='img/vibes3.png';
//   document.getElementById('imgburger').src='img/burger2.png';
//   })
//   origine.addEventListener('click',function () {
//   themeWeb.href = 'style.css';
//   style1.style.display="none"
//   style2.style.display="none"
//   origine.style.display="none"
//   document.getElementById('logo-img').src='img/vibes1.png';
//   document.getElementById('imgburger').src='img/burger.jpg';
//   })
// }) 
// }
// function changeThemeMob(){
// goute.addEventListener('click',function () { 
//   style1.style.display="block"
//   style2.style.display="block"
//   origine.style.display="block"
//   style1.addEventListener('click',function () {
//   themeMob.href = 'mobile.css';
//   document.getElementById('logo-img').src='img/vibes2.png';
//   document.getElementById('imgburger').src='img/burger1.png';
//   })
//   style2.addEventListener('click',function () {
//   themeMob.href = 'mobile.css';
//   document.getElementById('logo-img').src='img/vibes3.png';
//   document.getElementById('imgburger').src='img/burger2.png';
//   })
//   origine.addEventListener('click',function () {
//   themeMob.href = 'mobile.css';
//   style1.style.display="none"
//   style2.style.display="none"
//   origine.style.display="none"
//   document.getElementById('logo-img').src='img/vibes1.png';
//   document.getElementById('imgburger').src='img/burger.jpg';
//   })
// }) 
// }
// changeTheme(themeWeb)
// changeThemeMob(themeMob)


