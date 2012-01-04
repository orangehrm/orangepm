$(document).ready(
    function() {
        document.getElementById('project_dateAdded').value = getCurrentDate();
        $( "#project_dateAdded" ).datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            showAnim: "slideDown",
            onSelect: function() {
                addedDate = $('#project_dateAdded').val();
                $("#project_statusChangedDate").datepicker('destroy');
                $("#project_statusChangedDate").datepicker(
                        {
                            dateFormat: 'yy-mm-dd',
                            changeMonth: true,
                            changeYear: true,
                            showAnim: "slideDown",
                            minDate: addedDate
                });
            }
        });
        
        var addedDate = $("#project_dateAdded").val();
        document.getElementById('project_statusChangedDate').value = getCurrentDate();
        $( "#project_statusChangedDate" ).datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            showAnim: "slideDown",
            minDate: addedDate
        });
    }
);

function passProjectId($projectId, $projectName){
    location.href=linkUrl+"?id="+$projectId+"&projectName="+$projectName
}

function getCurrentDate() {
    var currentTime = new Date()
    var month = formatTimeValues(currentTime.getMonth() + 1)
    var day = formatTimeValues(currentTime.getDate())
    var year = formatTimeValues(currentTime.getFullYear())
    return year+"-"+month+"-"+day;
}

function formatTimeValues(value) {
    if (value < 10){
        value = "0" + value;
    }
    return value;
}