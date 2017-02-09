function change() {
    select = document.getElementById("select");
    select.style.backgroundImage = select.options[select.selectedIndex].style.backgroundImage;
}

function note_select() {
    var input = $("#note-selector");
    var url = window.location.href;
    var index = url.indexOf('?note=');
    if(index>0){
        url = url.slice(0, index);
    }
    url+="?note=" + input.val();
    window.location.href = url;
}