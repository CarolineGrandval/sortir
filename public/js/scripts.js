window.onload = chargementTerminer;
function chargementTerminer(){
    init();
}

window.addEventListener('load' , init);

function init(){
    document.getElementById('btnAjoutLieu')
        .addEventListener('click', afficherFormulaire);
}

$(document).ready(function(){
    $(".ribad").click(function(){
        $(".cache").toggleClass("clique");
    });
});

