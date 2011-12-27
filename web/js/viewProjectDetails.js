$(document).ready(function(){
    $('#project_name').attr('disabled', true);
    $('#project_startDate').attr('disabled', true);
    $('#project_endDate').attr('disabled', true);
    $('#project_projectAdmin').attr('disabled', true);
    $('#project_status').attr('disabled', true);
    $('#project_description').attr('disabled', true);
    $('#cancel').attr('disabled', true);
    
    $('#saveButton').click(function(event) {
        if($('#saveButton').attr('value') == 'Edit') {
            event.preventDefault();
            $('#project_name').removeAttr("disabled");
            $('#project_startDate').removeAttr("disabled");
            $('#project_endDate').removeAttr("disabled");
            $('#project_projectAdmin').removeAttr("disabled");
            $('#project_status').removeAttr("disabled");
            $('#project_description').removeAttr("disabled");
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

    $(".progressbar").each(function() {
        var val = parseInt($(this).attr('value'));
        $(this).progressbar({
            value: val
        });
        $(this).find('div').html(val + '%');
    });

});