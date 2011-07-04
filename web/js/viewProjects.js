
$(document).ready(function() {

    $("#dialog").dialog({
        autoOpen: false,
        modal: true
    });
  

    $(".confirmLink").click(function(e) {
     
        e.preventDefault();
        var targetUrl = $(this).attr("href");

        $("#dialog").dialog({
            buttons : {
                "OK" : function() {
                    window.location.href = targetUrl;
                },
                "Cancel" : function() {
                    $(this).dialog("close");
                }
            }
        });

        $("#dialog").dialog("open");
    });

    synchronizedVariable = true;
    toggleVariable = "Saved";
    dropdownToggleVariable = true;


    $('td.edit').click(function() {

        if(synchronizedVariable) {

            classNameArray = $(this).attr('class').split( " " );

            if(toggleVariable == "Saved") {
                $(this).html(saveImgUrl);
                synchronizedVariable = false;
                dropdownToggleVariable = true;
            }
            if(toggleVariable == "Edited") {
                $(this).html(editImgUrl);
                toggleVariable = "Saved";
            }

            $('.ajaxName').html($('.ajaxName input').val());
            $('.ajaxProjectStatus').html($('.ajaxProjectStatus select').val());

            $('.ajaxName').removeClass('ajaxName');
            $('.ajaxProjectStatus').removeClass('ajaxProjectStatus');

            $(this).parent().children('td.changedName').addClass('ajaxName');
            $(this).parent().children('td.changedName').html('<input id="editboxName" size="13" type="text" value="'+$(this).parent().children('td.changedName').text()+'">');

            $(this).parent().children('td.changedProjectStatus').addClass('ajaxProjectStatus');
            if(dropdownToggleVariable){

                var previousUserType = jQuery.trim($(this).parent().children('td.changedProjectStatus').text());

                $(this).parent().children('td.changedProjectStatus').html('<select name="changedProjectStatus" id="changedProjectStatus">'+
                    '<option value="1">Scheduled</option>'+
                    '<option value="2">In progress</option>'+
                    '<option value="3">Closed</option>'+
                    '</select> ');

                $("#changedProjectStatus").val(previousUserType);

                dropdownToggleVariable = false;
            }

            $('#saveBtn').click(function() {

                synchronizedVariable = true;
                isValidEmail = true;

                if(!($('.ajaxName input').val()=='')){
                    $.ajax({
                        type: "post",
                        url: linkUrl,

                        data: "name="+$('.ajaxName input').val()+"&id="+classNameArray[2]+"&projectStatus="+jQuery.trim($('.ajaxProjectStatus select').val()),

                        success: function(){


                            $('.ajaxName').html($('.ajaxName input').val());
                            $('.ajaxProjectStatus').html($('.ajaxProjectStatus select').text());

                            $('.ajaxProjectStatus').removeClass('ajaxProjectStatus');

                        }

                    });

                    dropdownToggleVariable = false;
                    toggleVariable = "Edited";
               location.href="viewProjects";
                }

            });

        }

    });

});
