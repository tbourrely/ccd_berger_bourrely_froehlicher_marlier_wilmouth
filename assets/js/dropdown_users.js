/**
 * Created by keiko on 09/02/17.
 */
$(function() {

    {
        var inputNote = $("#note-selector");
        var url = window.location.href;
        var index = url.indexOf('?note=');
        if(index>0){
            url = url.slice(index);
            url = url.replace("?note=","");
            inputNote.val(url);
        }else{
            inputNote.val(0);
        }
    }




    var list = $("#user_list_dropdown");
    $('#groupe-form').on('keydown', function(event){
        if(event.keyCode == 13)
            event.preventDefault();
    });


    $('#user_list_dropdown_input').keyup(function (event) {
        //list.empty();
        if($('#user_list_dropdown_input').val()){
            var search = $('#user_list_dropdown_input').val();
            var childs = $("#user_list_dropdown li");
            if(event.keyCode == 13){
                if(childs.length>0){
                    $(this).val(childs[0].innerHTML);
                    retrieveId($('#user_list_dropdown_input').val());
                    list.empty();
                }
            }else{
                $.ajax({
                    url:path + '/utilisateur/list-json/' + search,
                    type: "get"
                }).done(function(data){
                    list.empty();
                    var users= $.parseJSON(data);
                    users.forEach(function(element){
                        list.append("<li>" + element + "</li>");
                    });
                });
            }

        }else{
            list.empty();
            $("#user_container").empty();
        }

    });



    list.on('click', 'li', function(){
        $("#user_container").empty();
        $('#user_list_dropdown_input').val(this.innerHTML);
        retrieveId($('#user_list_dropdown_input').val());
        list.empty();
    });


});


function retrieveId(name){
    $.ajax({
        url: path + '/utilisateur/name/' + name,
        type: "get"
    }).done(function(data){
        var element = '<div class="text-center">\
            <div class="jumbotron" >\
                <div>\
                    <img src="/ccd_berger_bourrely_froehlicher_marlier_wilmouth//assets/img/user/'+ data +'.jpg">\
                </div>\
                <div class="text-center">\
                    <h3>'+ name + '</h3>\
                </div>\
                <p><a class="btn btn-primary btn-lg" onclick="adduser('+data+')" role="button">ajouter au groupe</a></p>\
            </div>\
        </div>';

        $("#user_container").append(element);
    });
}


function adduser(id){
    var url = path + "/group/add/" + id;
    $.ajax({
        url: url,
        type: "GET"

    }).done(function (data) {
        console.log(data);
        if(data === 'inserted'){
            $("#user_container").empty();
            $('#user_list_dropdown_input').val('');
            window.location.reload();
        }
    });
}
