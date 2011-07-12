
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

                var previousProjectStatus = jQuery.trim($(this).parent().children('td.changedProjectStatus').text());

                $(this).parent().children('td.changedProjectStatus').html('<select name="changedProjectStatus" id="changedProjectStatus">'+
                    '<option value="Inprogress">Inprogress</option>'+
                    '<option value="Scheduled">Scheduled</option>'+
                    '<option value="Closed">Closed</option>'+
                    '</select> ');

                $("#changedProjectStatus").val(previousProjectStatus);

                dropdownToggleVariable = false;
            }

            $('#saveBtn').click(function() {

                synchronizedVariable = true;
                var status;                

                if(!($('.ajaxName input').val()=='')){
                    
                    if($('.ajaxProjectStatus select').val() == "Inprogress") {
                        status = 1;
                    } else if($('.ajaxProjectStatus select').val() == "Scheduled"){
                        status = 2;
                    } else if($('.ajaxProjectStatus select').val() == "Closed"){
                        status = 3;
                    } 
                  
                    $.ajax({
                        type: "post",
                        url: linkUrl,

                        data: "name="+$('.ajaxName input').val().trim()+"&id="+classNameArray[2]+"&projectStatus="+status,

                        success: function(){

                            var hstring = '<a href='+'viewStories?'+'id='+classNameArray[2]+' > '+$('.ajaxName input').val().trim()+'</a>';
                            $('.ajaxName').html(hstring);
                            $('.ajaxProjectStatus').html($('.ajaxProjectStatus select').val());
                            $('.ajaxName').removeClass('ajaxName');
                            $('.ajaxProjectStatus').removeClass('ajaxProjectStatus');

                        }

                    });

                    toggleVariable = "Edited";
                }

            });

        }

    });

});
