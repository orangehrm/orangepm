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
            $("#logExpandColaps").html(" ► Log List");
        } else if ($("#logExpandColaps").hasClass('hide')) {
            $("#logExpandColaps").addClass('show');
            $("#logExpandColaps").removeClass('hide');
            $("#logExpandColaps").html(" ▼ Log List");
        } 
    });

    $("#storyExpandColaps").click(function(event){
        event.preventDefault();
        $("#storyDivisionContent").slideToggle("slow");
        if ($("#storyExpandColaps").hasClass('show')) {
            $("#storyExpandColaps").addClass('hide');
            $("#storyExpandColaps").removeClass('show');
            $("#storyExpandColaps").html(" ► Story List");
        } else if ($("#storyExpandColaps").hasClass('hide')) {
            $("#storyExpandColaps").addClass('show');
            $("#storyExpandColaps").removeClass('hide');
            $("#storyExpandColaps").html(" ▼ Story List");
        } 
    });
});