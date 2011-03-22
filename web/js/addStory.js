$(document).ready(
    function() {
        $( "#project_Date_Added" ).datepicker(

        {
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                showAnim: "slideDown"
            });

    });
    
   function passProjectId($projectId){
    location.href=linkUrl+"?id="+$projectId
    }