$(document).ready(
    function() {
        $( "#project_dateAdded" ).datepicker(

        {
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                showAnim: "slideDown"
            });

    });
    
function passProjectId($projectId, $projectName){

    location.href=linkUrl+"?id="+$projectId+"&projectName="+$projectName
    
}