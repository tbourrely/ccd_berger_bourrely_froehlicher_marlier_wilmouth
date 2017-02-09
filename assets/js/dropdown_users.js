/**
 * Created by keiko on 09/02/17.
 */
$(function() {
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
                    url:'/ccd_berger_bourrely_froehlicher_marlier_wilmouth/utilisateur/list-json/' + search,
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
        $('#user_list_dropdown_input').val(this.innerHTML);
        retrieveId($('#user_list_dropdown_input').val());
        list.empty();
    });




});
/*
<div class="col-md-3 text-center">
    <div class="jumbotron" >
    <div class="col-sm-12 col-xs-6">
    <img src="{{ base_url() }}/assets/img/user/{{ u.id }}.jpg">
    </div>
    <div class="text-center">
    <h3>{{ u.nom }}</h3>
</div>
<p><a class="btn btn-primary btn-lg" href="{{ path_for('detailsUser',{'id' : u.id}) }}" role="button">voir profil</a></p>
</div>
</div>
*/

function retrieveId(name){
    $.ajax({
        url:'/ccd_berger_bourrely_froehlicher_marlier_wilmouth/utilisateur/name/' + name,
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
        <p><a class="btn btn-primary btn-lg" href="" role="button">ajouter au groupe</a></p>\
        </div>\
        </div>';
        $("#user_container").empty();
        $("#user_container").append(element);
    });
}