$(document).ready(
    function() {
        document.getElementById('project_acceptedDate').disabled=true;
        $( "#project_dateAdded" ).datepicker(

        {
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                showAnim: "slideDown"
            });

    });

    function clicked(dropdown){
        //alert(dropdown.selectedIndex);

    if(dropdown.selectedIndex==6){
        //alert("Came Here");
       document.getElementById('project_acceptedDate').disabled=false;
       var currentTime = new Date()
        var month = currentTime.getMonth() + 1
        var day = currentTime.getDate()
        var year = currentTime.getFullYear()
        //document.write(month + "/" + day + "/" + year)

       document.getElementById('project_acceptedDate').value = year+"-"+month+"-"+day;
       $( " #project_acceptedDate" ).datepicker(

        {
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                showAnim: "slideDown"
            });
    
    }
    else {
       document.getElementById('project_acceptedDate').value = '';
       document.getElementById('project_acceptedDate').disabled=true;

    }
    }
    
function passProjectId($projectId, $projectName){

    location.href=linkUrl+"?id="+$projectId+"&projectName="+$projectName
    
}