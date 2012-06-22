
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
    
    
    $("#projectSearch_status").change(function(){
        location.href="viewProjects?selectedStatusId=" + $("#projectSearch_status").val();
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
            
            $('.ajaxStartDate').html($('.ajaxStartDate input').val());
            $('.ajaxEndDate').html($('.ajaxEndDate input').val());
            
            if($('#changedProjectAdmin').val() == 0) {
                $('.ajaxProjectAdmin').html("");
            } else {
                $('.ajaxProjectAdmin').html($('#changedProjectAdmin option:selected').text());
            }
            
            $('.ajaxName').removeClass('ajaxName');
            $('.ajaxStartDate').removeClass('ajaxStartDate');
            $('.ajaxEndDate').removeClass('ajaxEndDate');
            $('.ajaxProjectStatus').removeClass('ajaxProjectStatus');
            $('.ajaxProjectAdmin').removeClass('ajaxProjectAdmin');

            $(this).parent().children('td.changedName').addClass('ajaxName');
            $(this).parent().children('td.changedName').html('<input id="editboxName" size="55" type="text" value="'+escapeQuotes($(this).parent().children('td.changedName').text())+'">');

            $(this).parent().children('td.changedStartDate').addClass('ajaxStartDate');
            $(this).parent().children('td.changedStartDate').html('<input id="editboxStartDate" size="9" type="text" value="'+$(this).parent().children('td.changedStartDate').text()+'">');
            
            $(this).parent().children('td.changedEndDate').addClass('ajaxEndDate');
            $(this).parent().children('td.changedEndDate').html('<input id="editboxEndDate" size="9" type="text" value="'+$(this).parent().children('td.changedEndDate').text()+'">');
            
            $("#editboxStartDate, #editboxEndDate").datepicker(
                    {
                        dateFormat: 'yy-mm-dd',
                        changeMonth: true,
                        changeYear: true,
                        showAnim: "slideDown"
             });
            
            if(dropdownToggleVariable){
                
                $(this).parent().children('td.changedProjectStatus').addClass('ajaxProjectStatus');
                var previousProjectStatus = jQuery.trim($(this).parent().children('td.changedProjectStatus').text());

                $(this).parent().children('td.changedProjectStatus').html('<select name="changedProjectStatus" id="changedProjectStatus">'+
                    '<option value="In-progress">In-progress</option>'+
                    '<option value="Scheduled">Scheduled</option>'+
                    '<option value="Closed">Closed</option>'+
                    '</select> ');
                $("#changedProjectStatus").val(previousProjectStatus);
                
                
                $(this).parent().children('td.changedProjectAdmin').addClass('ajaxProjectAdmin');
                var previousProjectAdmin = jQuery.trim($(this).parent().children('td.changedProjectAdmin').text());
                var previousProjectAdminOptionValue;
                
                var projectAdminOptions = "";
                for(i=0; i<projectAdmins.length; i++) {
                    projectAdminOptions = projectAdminOptions + "<option value="+projectAdmins[i][0]+">"+projectAdmins[i][1]+"</option>";
                    if(projectAdmins[i][1] == previousProjectAdmin) {
                        previousProjectAdminOptionValue = projectAdmins[i][0];
                    }
                }
                
                $(this).parent().children('td.changedProjectAdmin').html('<select name="changedProjectAdmin" id="changedProjectAdmin">'+ projectAdminOptions + '</select> ');
                $("#changedProjectAdmin").val(previousProjectAdminOptionValue);
                

                dropdownToggleVariable = false;
            }

            $('#saveBtn').click(function() {

                synchronizedVariable = true;
                var status;                

                if(!($('.ajaxName input').val()=='')) {
                    
                    if($('.ajaxProjectStatus select').val() == "In-progress") {
                        status = 1;
                    } else if($('.ajaxProjectStatus select').val() == "Scheduled"){
                        status = 2;
                    } else if($('.ajaxProjectStatus select').val() == "Closed"){
                        status = 3;
                    }
                    
                    removeMainErrorMessage();
                    
                    $.ajax({
                        type: "post",
                        url: linkUrl,
                        
                        data: "name="+$('.ajaxName input').val().trim()+"&startDate="+$('.ajaxStartDate input').val().trim()+"&endDate="+$('.ajaxEndDate input').val().trim()+"&id="+classNameArray[2]+"&projectStatus="+status + "&projectAdminId=" + $('#changedProjectAdmin option:selected').val(),

                        success: function(){
                            var hstring = '<a href='+'viewProjectDetails?'+'projectId='+classNameArray[2]+' > '+$('.ajaxName input').val().trim()+'</a>';
                            $('.ajaxName').html(hstring);                            
                            $('.ajaxName').removeClass('ajaxName');                           
                            $('.ajaxStartDate').html($('.ajaxStartDate input').val().trim());
                            $('.ajaxEndDate').html($('.ajaxEndDate input').val().trim());
                            $('.ajaxStartDate').removeClass('ajaxStartDate');
                            $('.ajaxEndDate').removeClass('ajaxEndDate');
                        }

                    });

                    toggleVariable = "Edited";
                    
                } else {
                    setMainErrorMessage('Project Name is empty');
                }

            });

        }

    });
    
});


function setMainErrorMessage(message) {
    $('#mainErrorDiv').empty();
    $('#mainErrorDiv').append(message);
}

function removeMainErrorMessage() {
    $('#mainErrorDiv').empty();
}
function escapeQuotes(words){
    words=words.replace("&","&amp;");
    words=words.replace('"',"&#34;");
    words=words.replace("'","&#39;");
    return words;
}