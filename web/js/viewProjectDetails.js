$(document).ready(function(){

    if(showLinkTable){
        $('#linkTable').show();
    }
    
    if ($('#project_infoLink').val() == '' || $('#project_infoLink').val() == lang_typeHint) {
        $('#project_infoLink').addClass("inputFormatHint").val(lang_typeHint);
    }
    
    $('#project_infoLink').one('focus', function() {
        
        if ($(this).hasClass("inputFormatHint")) {
            $(this).val("");
            $(this).removeClass("inputFormatHint");
        }

    });
    
    var selectedProjectAdmin=$('#project_projectAdmin option:selected');
    $('#project_name').attr('disabled', true);
    $('#project_startDate').attr('disabled', true);
    $('#project_endDate').attr('disabled', true);
    $('#project_estimatedTotalEffort').attr('disabled', true);
    $('#project_currentEffort').attr('disabled', true);
    $('#project_projectAdmin').attr('disabled', true);
    $('#project_status').attr('disabled', true);
    $('#project_description').attr('disabled', true);
    $('#project_projectUserAll').hide();
    $('label[for="project_projectUserAll"]').hide();
    $('#btns').hide();
    //$('#btnRight').hide();
    $('#cancel').attr('disabled', true);

    $('#saveButton').click(function(event) {  
        if($('#saveButton').attr('value') == 'Edit') {
            event.preventDefault();
            $('#project_name').removeAttr("disabled");
            $('#project_startDate').removeAttr("disabled");
            $('#project_endDate').removeAttr("disabled");
            $('#project_estimatedTotalEffort').removeAttr("disabled");
            $('#project_currentEffort').removeAttr("disabled");
            $('#project_projectAdmin').removeAttr("disabled");
            $('#project_status').removeAttr("disabled");
            $('#project_description').removeAttr("disabled");
            $('#project_projectUserAll').show();
            $('label[for="project_projectUserAll"]').show();
            $('#project_projectUserSelected').removeAttr("disabled");
            $('#btns').show();
            //$('#btnRight').show();
            $('#cancel').removeAttr("disabled");
            $('#saveButton').attr('value','Save') 
        }      
    });
   
    $("#project_startDate, #project_endDate").datepicker(
    {
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        showAnim: "slideDown"
    });
    
    $(".changedStatus").change(function() {
        statusChanged = true;
    });
    
    $("#editboxEstimation").live('change', function() {
        statusChanged = true;
    });
    
    $(".progressbar").each(function() {
        var val = parseFloat($(this).attr('value'));
        $(this).progressbar({
            value: val
        });
        $(this).find('div').html(val + '%');
    });
    
    $("#logExpandColaps").click(function(event){
        event.preventDefault();
        $("#logDivisionContent").slideToggle("slow");
        if ($("#logExpandColaps").hasClass('show')) {
            $("#logExpandColaps").addClass('hide');
            $("#logExpandColaps").removeClass('show');
            $("#logExpandColaps").html("[+]");
        } else if ($("#logExpandColaps").hasClass('hide')) {
            $("#logExpandColaps").addClass('show');
            $("#logExpandColaps").removeClass('hide');
            $("#logExpandColaps").html("[-]");
        } 
    });

    $("#storyExpandColaps").click(function(event){
        event.preventDefault();
        $("#storyDivisionContent").slideToggle("slow");
        if ($("#storyExpandColaps").hasClass('show')) {
            $("#storyExpandColaps").addClass('hide');
            $("#storyExpandColaps").removeClass('show');
            $("#storyExpandColaps").html("[+]");
        } else if ($("#storyExpandColaps").hasClass('hide')) {
            $("#storyExpandColaps").addClass('show');
            $("#storyExpandColaps").removeClass('hide');
            $("#storyExpandColaps").html("[-]");
        } 
    });
    $('#btnRight').click(function(e) {
        var selectedOpts = $('#project_projectUserAll option:selected');
        if (selectedOpts.length == 0) {
            e.preventDefault();
        }        
        $('#project_projectUserSelected').append($(selectedOpts).clone());
        $(selectedOpts).remove();
        e.preventDefault();
    });

    $('#btnLeft').click(function(e) {
        var selectedOpts = $('#project_projectUserSelected option:selected');
        if (selectedOpts.length == 0) {
            e.preventDefault();
        }

        $('#project_projectUserAll').append($(selectedOpts).clone());
        $(selectedOpts).remove();
        e.preventDefault();
    });
    $('#project_projectAdmin').change(function() {  
        $('#project_projectUserAll').append($(selectedProjectAdmin).clone());
        selectedProjectAdmin=$('#project_projectAdmin option:selected');
        $('#project_projectUserAll option[value="'+ $(selectedProjectAdmin).val() +'"]').remove();
        $('#project_projectUserSelected option[value="'+ $(selectedProjectAdmin).val() +'"]').remove();
    });
    
    $('#addLinkButton').click(function(e) {
        var selectedLink = $('#project_infoLink').val();
        if (selectedLink.length == 0) {
            alert('link empty');
            e.preventDefault();
        }else{
            valuesArray=selectedLink.split(' ');
            var  linkId = addLinkToProject(valuesArray[0],valuesArray[1],projectId);
        }
      
    });

  
});

$('.delLink').live("click", function() {
    var id=$(this).parent().attr('id');
    var parent=$(this).parent().parent();
    
    
    $("#dialogLinkDeletion").dialog({
        buttons : {
            "OK" : function() {
                   
                deleteProjectLink(id,parent);
                $(this).dialog("close");
            },
            "Cancel" : function() {
                $(this).dialog("close");
            }
        }
    });
    
    


});

function addLinkToProject(linkName,link,projectId){
    
    $.ajax({
        type: "post",
        url: addProjectLinksUrl,
        data: {
            projectId : projectId , 
            linkName :  linkName, 
            link : link
           
        },

        success: function(msg){
           
            id= msg;
            newElement = '<tr id="'+'tableR_'+id+'"><td>'+linkName+'</td><td> <a href='+'"'+link+'"'+'target="_blank">'+link+'</a></td><td id="'+id+'"><img class="delLink" src="../../images/b_drop.png" /></td></tr>'; 
            $('#linkTable').append(newElement);
            $('#linkTable').show();
           
        }

    });
    
    
}


function deleteProjectLink(linkId,parent){
    
    $.ajax({
        type: "post",
        url: deleteProjectUrl,
        data: {
            linkId : linkId  
           
        },

        success: function(msg){
           
            id= msg;
            element='tableR_'+linkId;
            
            parent.remove();            
            var rowCount = $('#linkTable tr').length;
            if(rowCount == 1){
                $('#linkTable').hide();
            }
        }

    });
    
    
}
