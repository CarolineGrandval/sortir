window.onload = chargementTerminer; //chargement de la page
function chargementTerminer(){
    init();
}

// let inscrit = document.getElementById("inscrit");
// let organisateurCheck = false;
// let motcleffill = '';
//onclick sur le bouton Rechercher - récupérer les cases et autres informations renseignées
function clicBoutonRechercher(event){
    //ComboBox
    campusChoice = document.getElementById("campus").
    //TextBox
    //motcleffill = document.getElementById('motclef').value;
    //CheckBox
    organisateurCheck = document.getElementById("organisateur").checked;
    inscritCheck = inscrit.checked;
    pasInscritCheck = document.getElementById("pasInscrit").checked;
    passeesCheck = document.getElementById("passees").checked;

};

//renvoie ces données vers le formulaire au retour de la page

//Fonction d'initialisation
function init(){
    //Associe l'événement au bouton Rechercher de la page d'accueil
    document.querySelector('#rechercher').addEventListener('click', clicBoutonRechercher);
    var inscrit = document.getElementById("inscrit");
    var organisateurCheck = false;
    var motcleffill = '';
    var inscritCheck = false;
    var pasInscritCheck = false;
    var passeesCheck = false;
}

