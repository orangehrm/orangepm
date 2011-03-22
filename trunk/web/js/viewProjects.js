
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

    nVar = true;
    nVariable = "Saved";

    $('td.edit').click(function(){
        if(nVar){

            if(nVariable == "Saved"){
              
                $(this).html(saveImgUrl);
                
                nVar = false;
            }

            if(nVariable == "Edited"){
                $(this).html(editImgUrl);
                nVariable = "Saved";
            }

            arr = $(this).attr('class').split( " " );

            $('.ajax').html($('.ajax input').val());
            $('.ajax').removeClass('ajax');

            $(this).parent().children('td.change').addClass('ajax');
            $(this).parent().children('td.change').html('<input id="editbox" size="'+16+'" type="text" value="'+$(this).parent().children('td.change').text()+'">');

            $('#saveBtn').click(function(){
                nVar = true;
              
                $.ajax({
                    type: "post",
                    url: linkUrl,
                    data: "name="+$('.ajax input').val()+"&id="+arr[2],
                    success: function(){
                        
                        var hstring = '<a href='+'viewStories?'+'id='+arr[2]+' > '+$('.ajax input').val()+'</a>';
                        $('.ajax').html(hstring);

                    }
                });

                nVariable = "Edited";

            }
            );
        }
    });






});
