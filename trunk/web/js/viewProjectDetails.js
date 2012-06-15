$(document).ready(function(){
    selectedProjectAdmin=$('#project_projectAdmin option:selected');
    $('#project_projectUserAll option[value*="'+ $(selectedProjectAdmin).val() +'"]').remove();
    $('#project_projectUserSelected option[value*="'+ $(selectedProjectAdmin).val() +'"]').remove();
    $('#project_name').attr('disabled', true);
    $('#project_startDate').attr('disabled', true);
    $('#project_endDate').attr('disabled', true);
    $('#project_projectAdmin').attr('disabled', true);
    $('#project_status').attr('disabled', true);
    $('#project_description').attr('disabled', true);
    $('#project_projectUserAll').hide();
    $('label[for="project_projectUserAll"]').hide();
    $('#btnLeft').hide();
    $('#btnRight').hide();
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
            $('#project_projectUserAll').show();
            $('label[for="project_projectUserAll"]').show();
            $('#project_projectUserSelected').removeAttr("disabled");
            $('#btnLeft').show();
            $('#btnRight').show();
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
        selectedProjectAdmin = $('#project_projectAdmin option:selected');
        if($(selectedProjectAdmin).val()!=0){
            $('#project_projectUserAll option[value*="'+ $(selectedProjectAdmin).val() +'"]').remove();
            $('#project_projectUserSelected option[value*="'+ $(selectedProjectAdmin).val() +'"]').remove();
            alert(selectedProjectAdmin.val());
        }
    });
});