$(function() {

    // controle de la véracité de la ville lors du submit
    $('#evenement-form').on('submit', function(event){
        console.log("form submition attempt");

        var ville = $('#ville').val(); // recupere la valeur du champ
        if(ville.length <= 2)
            event.preventDefault();
        else{
            $.ville(ville, function (input, cities) {
                var found = false;
                cities.map(function (entry) {
                    if(entry.city == ville){
                        found = true;
                    }
                });
                if(found != true)
                    event.preventDefault();
            });
        }
    });



    // PICKADATE INIT
    $('.datepicker').pickadate({
        // Escape any “rule” characters with an exclamation mark (!).
        format: 'dd/mm/yyyy',
        formatSubmit: 'dd/mm/yyyy',
        hiddenPrefix: 'prefix__',
        hiddenSuffix: '__suffix'
    });

    $('.timepicker').pickatime({
        // Escape any “rule” characters with an exclamation mark (!).
        format: 'HH:i',
        formatLabel: 'HH:i',
        formatSubmit: 'HH:i',
        hiddenPrefix: 'prefix__',
        hiddenSuffix: '__suffix'
    });



    // CHOIX DE LA VILLE
    $('#ville').keyup(function (e) {
        if(e.keyCode == 13) {
            var $ville = $(this);
            $.vicopo($ville.val(), function (input, cities) {
                if(input == $ville.val() && cities[0]) {
                    $ville.val(cities[0].city).vicopoTargets().vicopoClean();
                }
            });
        }
    });
    $('#list_ville').on('click', 'li', function(){
        $('#ville').val(this.innerText).vicopoTargets().vicopoClean();
    });

});

function change() {
    select = document.getElementById("select");
    select.style.backgroundImage = select.options[select.selectedIndex].style.backgroundImage;
}

