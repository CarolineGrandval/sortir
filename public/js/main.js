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
    campusChoice = campus.options[campus.selectedIndex].text;
    //TextBox
    motcleffill = document.getElementById("motclef").value;
    //Date
    dateDebut = document.getElementById("dateDebut").value;
    dateFin = document.getElementById("dateFin").value;
    //CheckBox
    organisateurCheck = document.getElementById("organisateur").checked;
    inscritCheck = inscrit.checked;
    pasInscritCheck = document.getElementById("pasInscrit").checked;
    passeesCheck = document.getElementById("passees").checked;
    var data = {};

};

//renvoie ces données vers le formulaire au retour de la page


//Fonction d'initialisation
function init(){
    //Associe l'événement au bouton Rechercher de la page d'accueil
    document.querySelector('#rechercher').addEventListener('click', clicBoutonRechercher);
    //initialisation des différentes variables
    var inscrit = document.getElementById("inscrit");
    var campus = document.getElementById("campus");
    var organisateurCheck = false;
    var motcleffill = '';
    var inscritCheck = false;
    var pasInscritCheck = false;
    var passeesCheck = false;
}

