$(document).ready(function(){
    $('#cancel').click(function(){  
        location.href="viewProjects";
    
    });
    
    $("#project_projectAdmin option[value='0']").attr('selected', 'selected');
    
    $("#addProjectForm").validate({
        
        rules: {
            'project[name]': { required: true },
            'project[startDate]' : { required: true }
        },
        
        messages: {
            'project[name]': { required: lang_nameRequired },
            'project[startDate]' : { required: lang_startDateRequired }
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
    
});