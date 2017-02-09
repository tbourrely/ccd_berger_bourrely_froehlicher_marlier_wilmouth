/**
 * Created by Pierre on 09/02/2017.
 */

window.addEventListener('load', function(e) {

    var toCopy  = document.getElementById( 'to-copy' ),
        btnCopy = document.getElementById( 'copy' );

    btnCopy.addEventListener( 'click', function(){
        toCopy.select();
        document.execCommand( 'copy' );
        return false;
    } );

});

