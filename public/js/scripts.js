window.onload = chargementTerminer;
function chargementTerminer(){
    init();
}

window.addEventListener('load' , init);

function init(){
    document.getElementById('btnAjoutLieu')
        .addEventListener('click', messageAlerte);
}

function messageAlerte(){
    let isExecuted = confirm("Voulez-vous créer un nouveau lieu ?");
    console.log(isExecuted);
}

$(document).ready(function(){
    $(".ribad").click(function(){
        $(".cache").toggleClass("clique");
    });
});

