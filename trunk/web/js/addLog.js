$(document).ready(function(){

    $("td.logEdit").click(function() {
        var description;
        if(!$(this).parent().children('td.description').hasClass('ajaxDescription')) {
            $(this).html(saveImgUrl);
            description = $(this).parent().children('td.description').text();
            $(this).parent().children('td.description').addClass('ajaxDescription');
            $(this).parent().children('td.description').html("<textarea class='description'>"+description+"</textarea>");
        } else {
            $(this).html(editImgUrl);
            var descriptionText = $(this).parent().children('td.description').find('textarea').attr('value');
            var loggedDate = $(this).parent().children('td.loggedDate').text();
            var addedBy = $(this).parent().children('td.addedBy').attr('value');
            var classString = $(this).attr('class');
            var id = classString.split(' ')[1];
            $(this).parent().children('td.description').removeClass('ajaxDescription');
            var $descriptionElement = $(this).parent().children('td.description');
            $.ajax({
                type: "post",
                url: updateLinkUrl,
                data: "logId="+id+"&loggedDate="+loggedDate+"&addedBy="+addedBy+"&description="+descriptionText+"&projectId="+projectId,
                success: function(){
                    $descriptionElement.html(descriptionText);
                },
                fail: function() {
                    $descriptionElement.html(description);
                }
            });
        }
    });
    
    $("#dialog").dialog({
        autoOpen: false,
        modal: true
    });
    
    $("td.logDelete").click(function(event) {
        event.preventDefault();
        var classString = $(this).attr('class');
        var id = classString.split(' ')[1];
        var url = deleteLinkUrl+'/logId/'+id; 
        
        $("#dialog").html('Log Will Be Deleted?').hide();
        
        $("#dialog").dialog({
            buttons : {
                "OK" : function() {
                    window.location.href = url;
                },
                "Cancel" : function() {
                    $(this).dialog("close");
                }
            }
        });

        $("#dialog").dialog("open");
    });
    
    $('#addButton').click(function() {
        var currentTime = new Date();
        var month = currentTime.getMonth() + 1;
        var day = currentTime.getDate();
        var year = currentTime.getFullYear();
        var date = year + "-" + month + "-" + day;
        $("table.showTable tbody").append('<tr><td id="ajaxLoggedDate">'+date+'</td><td id="ajaxUserName" value="'+userId+'" >'+userName+"</td><td><textarea id='ajaxDescription'></textarea></td>"+
            '<td class="logSave" colspan="2"><img id="logSaveBtn" src="/orangepm/web/images/b_save.gif"></td></tr>');
    });
    
    $('#logSaveBtn').live('click', function() {
        var addedBy = $('#ajaxUserName').attr('value');
        var loggedDate = $('#ajaxLoggedDate').html().trim();
        var description = $('#ajaxDescription').val();
        $.ajax({
            type: "post",
            url: addLinkUrl,
            data: "projectId="+projectId+"&projectName="+projectName+"&loggedDate="+loggedDate+"&addedBy="+addedBy+"&description="+description,
            success: function(){
                window.location.href = addLinkUrl+"/projectId/"+projectId+"/projectName/"+projectName;
            },
        });
    });
});