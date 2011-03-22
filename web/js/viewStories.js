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
  
  
    a = true;
    nVariable = "Saved";

    $('td.edit').click(function(){
        if(a){
                
            arr = $(this).attr('class').split( " " );
            if(nVariable == "Saved"){
                    
                $(this).html(saveImgUrl);
                a = false;
            }
            if(nVariable == "Edited"){
                $(this).html(editImgUrl);
                nVariable = "Saved";
                    
            }
                

                
            $('.ajaxName').html($('.ajaxName input').val());
            $('.ajaxDate').html($('.ajaxDate input').val());
            $('.ajaxEstimation').html($('.ajaxEstimation input').val());
            $('.ajaxName').removeClass('ajaxName');
            $('.ajaxDate').removeClass('ajaxDate');
            $('.ajaxEstimation').removeClass('ajaxEstimation');
            $(this).parent().children('td.changedName').addClass('ajaxName');
            $(this).parent().children('td.changedName').html('<input id="editboxName" size="13" type="text" value="' + $(this).parent().children('td.changedName').text() + '">');
            $(this).parent().children('td.changedDate').addClass('ajaxDate');
            $(this).parent().children('td.changedDate').html('<input id="editboxDate" size="9" type="text" value="' + $(this).parent().children('td.changedDate').text() + '">');
            $(this).parent().children('td.changedEstimation').addClass('ajaxEstimation');
            $(this).parent().children('td.changedEstimation').html('<input id="editboxEstimation" size="5" type="text" value="' + $(this).parent().children('td.changedEstimation').text() + '">');
                
            $('#saveBtn').click(function(){
                a = true;
             
                $.ajax({
                    type: "post",
                    url: linkUrl,

                    data: "name="+$('.ajaxName input').val()+"&date="+$('.ajaxDate input').val()+"&estimation="+$('.ajaxEstimation input').val()+"&id="+arr[2],

                    success: function(){
                        
                        $('.ajaxName').html($('.ajaxName input').val());
                        $('.ajaxDate').html($('.ajaxDate input').val());
                        $('.ajaxEstimation').html($('.ajaxEstimation input').val());
                            
                         
                    }
                });

                nVariable = "Edited";
                


            });
        }

            
    });
});