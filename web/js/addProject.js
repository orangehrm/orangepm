$(document).ready(function(){
    var selectedProjectAdmin=$('#project_projectAdmin option:selected');
    $('#cancel').click(function(){  
        location.href="viewProjects";
    
    });
    
    $("#project_projectAdmin option[value='0']").attr('selected', 'selected');
    
    $("#addProjectForm").validate({
        
        rules: {
            'project[name]': {
                required: true
            },
            'project[startDate]' : {
                required: true
            }
        },
        
        messages: {
            'project[name]': {
                required: lang_nameRequired
            },
            'project[startDate]' : {
                required: lang_startDateRequired
            }
        },        
        errorElement : 'div',
        errorPlacement: function(error, element) {
            error.insertAfter(element.next(".clear"));            

        }
        
    });
    
    $("#project_startDate, #project_endDate").datepicker(
    {
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        showAnim: "slideDown"
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
        $('#project_projectUserAll option[value="'+ $(selectedProjectAdmin).val() +'"]').remove();
        $('#project_projectUserSelected option[value="'+ $(selectedProjectAdmin).val() +'"]').remove();
    });


    
    
});
