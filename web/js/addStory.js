$(document).ready(
    function() {
        document.getElementById('project_acceptedDate').disabled=true;
        document.getElementById('project_dateAdded').value = getCurrentDate();
        $( "#project_dateAdded" ).datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            showAnim: "slideDown"
        });
    }
);

function clicked(dropdown){
    if(dropdown.selectedIndex==6){
       document.getElementById('project_acceptedDate').disabled=false;


       document.getElementById('project_acceptedDate').value = getCurrentDate();
       $( " #project_acceptedDate" ).datepicker({
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

function getCurrentDate() {
    var currentTime = new Date()
    var month = currentTime.getMonth() + 1
    var day = currentTime.getDate()
    var year = currentTime.getFullYear()
    return year+"-"+month+"-"+day;
}