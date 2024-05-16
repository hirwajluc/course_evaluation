 $(document).ready (function () {
    $('textarea').hide();
    $('a[class=save]').hide();
    $('a.delete').html ('Del');
    $('a.edit').html ('Save');
});
$(".edit_tr").live ('click', function()
{
    var ID=$(this).attr('id');
    ID = correct_id (ID);
    $('span').show();
    $('textarea').hide();
    $("#one_"+ID).hide();
    $("#one_input_"+ID).show();
    $("#save_" + ID).show();
   
}) ;

$("a.save").live ('click',  function () {
    var el = $(this).attr('id');
    
    id =  correct_id(el.split ('_')[1]);

    var name = $('#one_input_' + id).val();
   
    name = name.trim();

    $.ajax({
        type: "POST",
        url: "edit_comment.php?action=edit&id=" + id + "&name=" + name,
        data: "id=" + id + "&name=" + name,
        cache: false,
        success: function(e){
            if(e == 'success')
            {
                $('#one_input_' + id) .hide();
                $('#one_' + id).html (name);
                $('#one_' + id).show();
                $("#save_" + id).hide();
            }
        }
    });
});

$("a.delete").live ('click',  function () {
    var el = $(this).attr('id');

    id =  correct_id(el.split ('_')[1]);

    var name = $('#one_input_' + id).val();
    name = name.trim();
   

    $.ajax({
        type: "POST",
        url: "edit_comment.php?action=delete&id=" + id + "&name=" + name,
        data: "id=" + id + "&name=" + name,
        cache: false,
        success: function(e){
            if(e == 'success')
            {
                $ ('#' + id).remove();

            }
        }
    });
});


function correct_id (str) {

    var tmp = "";
    for (var i = 0; i < str.length ; i++){
        if (str.charAt (i) == "'")
            continue;
        tmp += str.charAt (i);
    }
    return tmp;
}$(document).ready (function () {
    $('textarea').hide();
    $('a[class=save]').hide();
    $('a.delete').html ('Del');
    $('a.edit').html ('Save');
});
$(".edit_tr").live ('click', function()
{
    var ID=$(this).attr('id');
    ID = correct_id (ID);
    $('span').show();
    $('textarea').hide();
    $("#one_"+ID).hide();
    $("#one_input_"+ID).show();
    $("#save_" + ID).show();
   
}) ;

$("a.save").live ('click',  function () {
    var el = $(this).attr('id');
    
    id =  correct_id(el.split ('_')[1]);

    var name = $('#one_input_' + id).val();
   
    name = name.trim();

    $.ajax({
        type: "POST",
        url: "edit_comment.php?action=edit&id=" + id + "&name=" + name,
        data: "id=" + id + "&name=" + name,
        cache: false,
        success: function(e){
            if(e == 'success')
            {
                $('#one_input_' + id) .hide();
                $('#one_' + id).html (name);
                $('#one_' + id).show();
                $("#save_" + id).hide();
            }
        }
    });
});

$("a.delete").live ('click',  function () {
    var el = $(this).attr('id');

    id =  correct_id(el.split ('_')[1]);

    var name = $('#one_input_' + id).val();
    name = name.trim();
   

    $.ajax({
        type: "POST",
        url: "edit_comment.php?action=delete&id=" + id + "&name=" + name,
        data: "id=" + id + "&name=" + name,
        cache: false,
        success: function(e){
            if(e == 'success')
            {
                $.children('#' + id).remove(); 
                $ ('#' + id).remove();

            }
        }
    });
});


function correct_id (str) {

    var tmp = "";
    for (var i = 0; i < str.length ; i++){
        if (str.charAt (i) == "'")
            continue;
        tmp += str.charAt (i);
    }
    return tmp;
}