$(document).ready(function() {
    $("#dialog").dialog({
        autoOpen: false,
        modal: true

    });
  

    $(".confirmLink").click(function(e) {
        e.preventDefault();
        var targetUrl = $(this).attr("href");

        $("#dialog").dialog({
            buttons : {
                "OK" : function() {
                   
                    window.location.href =  targetUrl+"/"+"deletedDate"+"/"+document.getElementById('deletedDate').value;
                },
                "Cancel" : function() {
                    $(this).dialog("close");
                }
            }
        });

        $("#dialog").dialog("open");
    });
  
  

    synchronizedVariable = true;
    toggleVariable = "Saved";
    dropdownToggleVariable = true;
    var selectionArray = {
        "Backlog":0 ,
        "Design" : 1,
        "Development":2,
        "Development Completed":3,
        "Testing":4,
        "Rework":5,
        "Accepted":6
    };



    $('td.edit').click(function(){
        if(synchronizedVariable){
                
            classNameArray = $(this).attr('class').split( " " );
            if(toggleVariable == "Saved"){
                    
                $(this).html(saveImgUrl);
                synchronizedVariable = false;
                dropdownToggleVariable = true;
            }
            if(toggleVariable == "Edited"){
                $(this).html(editImgUrl);
                toggleVariable = "Saved";
                    
            }
               
            $('.ajaxName').html($('.ajaxName input').val());
            $('.ajaxDate').html($('.ajaxDate input').val());
            $('.ajaxEstimation').html($('.ajaxEstimation input').val());
            $('.ajaxStatusChangedDate').html($('.ajaxStatusChangedDate input').val());
            $('.ajaxStatus').html($('.ajaxStatus select').val());
            
            $('.ajaxName').removeClass('ajaxName');
            $('.ajaxDate').removeClass('ajaxDate');
            $('.ajaxEstimation').removeClass('ajaxEstimation');
            $('.ajaxStatusChangedDate').removeClass('ajaxStatusChangedDate');
            $('.ajaxStatus').removeClass('ajaxStatus');
            
            $(this).parent().children('td.changedName').addClass('ajaxName');
            $(this).parent().children('td.changedName').html('<input id="editboxName" size="37" type="text" value="'+$(this).parent().children('td.changedName').text()+'">');
            $(this).parent().children('td.changedDate').addClass('ajaxDate');
            $(this).parent().children('td.changedDate').html('<input id="editboxDate" size="9" type="text" value="'+$(this).parent().children('td.changedDate').text()+'">');
            $(this).parent().children('td.changedEstimation').addClass('ajaxEstimation');
            $(this).parent().children('td.changedEstimation').html('<input id="editboxEstimation" size="5" type="text" value="'+$(this).parent().children('td.changedEstimation').text()+ '">');
            
            $(this).parent().children('td.changedStatusChangedDate').addClass('ajaxStatusChangedDate');
            $(this).parent().children('td.changedStatusChangedDate').html('<input id="editboxStatusChangedDate" size="9" type="text" value="'+$(this).parent().children('td.changedStatusChangedDate').text()+ '">');

                

            $(this).parent().children('td.changedStatus').addClass('ajaxStatus');
            if(dropdownToggleVariable){
                var previousStatus = jQuery.trim($(this).parent().children('td.changedStatus').text());
                
                $(this).parent().children('td.changedStatus').html('<select name="changedStatus" id="changedStatus" onchange="findSelected()"">'+
                    '<option value=" Backlog">Backlog</option>'+
                    '<option value=" Design">Design</option>'+
                    '<option value=" Development">Development</option>'+
                    '<option value=" Development Completed">Development Completed</option>'+
                    '<option value=" Testing">Testing</option>'+
                    '<option value=" Rework">Rework</option>'+
                    '<option value=" Accepted">Accepted</option>'+
                    '</select> ');

                document.getElementById('changedStatus').selectedIndex = selectionArray[previousStatus];
                if(previousStatus=="Accepted"){
                    document.getElementById('editboxEstimation').disabled=true;
                }
            
                dropdownToggleVariable = false;
            }

              
            $('#saveBtn').click(function(){
                synchronizedVariable = true;
                $currentRow = $('#saveBtn').closest('tr');
                if($('.ajaxName input').val() != '') {                    
                    
                    if($('.ajaxEstimation input').val() != '') {
                        
                        if(!isNaN($('.ajaxEstimation input').val())) {
                    
                            if(ValidateForm($('.ajaxDate input').val())) {

                                removeMainErrorMessage();
                                    
                                    if(($('.ajaxStatus select').val()==" Accepted" && ValidateForm($('.ajaxStatusChangedDate input').val()))||$('.ajaxStatus select').val()!=" Accepted") {
                                        $.ajax({
                                            type: "post",
                                            url: linkUrl,


                                            data: "name="+$('.ajaxName input').val()+"&date="+$('.ajaxDate input').val()+"&estimation="+jQuery.trim($('.ajaxEstimation input').val())+"&id="+classNameArray[2]+"&status="+jQuery.trim($('.ajaxStatus select').val())+
                                                      "&statusChangedDate="+jQuery.trim($('.ajaxStatusChangedDate input').val()),

                                            success: function(){
                        
                                                $('.ajaxName').html($('.ajaxName input').val());
                                                $('.ajaxDate').html($('.ajaxDate input').val());
                                                $('.ajaxEstimation').html($('.ajaxEstimation input').val());

                                                $('.ajaxStatusChangedDate').html($('.ajaxStatusChangedDate input').val());
                                                $('.ajaxStatus').html($('.ajaxStatus select').val());
                                                $('.ajaxStatus').removeClass('ajaxStatus');
                                                if((projectViewUrl != null) && (statusChanged)) {
                                                    window.location.reload();
                                                }
                                            }
                                        });

                                        toggleVariable = "Edited";

                                    }
                            }
                        }
                        else { 
                            setMainErrorMessage('Estimation Effort is not valid');
                        }
                    } else {
                        setMainErrorMessage('Estimation Effort is empty');
                    }
                } else {
                    setMainErrorMessage('Story Name is empty');
                }
            });
            
            $("#editboxDate").datepicker(
            {
                    dateFormat: ' yy-mm-dd',
                    changeMonth: true,
                    changeYear: true,
                    showAnim: "slideDown",
                    onSelect: function() {
                        addedDate = $('.ajaxDate input').val();
                        $("#editboxStatusChangedDate").datepicker('destroy');
	                    $("#editboxStatusChangedDate").datepicker(
	                            {
	                                dateFormat: ' yy-mm-dd',
	                                changeMonth: true,
	                                changeYear: true,
	                                showAnim: "slideDown",
	                                minDate: addedDate
	                    });
                    }
            });
            
            var addedDate = $("#editboxDate").val();
            $("#editboxStatusChangedDate").datepicker(
            {
                dateFormat: ' yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                showAnim: "slideDown",
                minDate: addedDate
            });

            var dtCh= "-";
            var minYear=1900;
            var maxYear=2100;

            function isInteger(s){
                var i;
                for (i = 0; i < s.length; i++){
                    // Check that current character is number.
                    var c = s.charAt(i);
                    if (((c < "0") || (c > "9"))) return false;
                }
                // All characters are numbers.
                return true;
            }

            function stripCharsInBag(s, bag){
                var i;
                var returnString = "";
                // Search through string's characters one by one.
                // If character is not in bag, append to returnString.
                for (i = 0; i < s.length; i++){
                    var c = s.charAt(i);
                    if (bag.indexOf(c) == -1) returnString += c;
                }
                return returnString;
            }

            function daysInFebruary (year){
                // February has 29 days in any year evenly divisible by four,
                // EXCEPT for centurial years which are not also divisible by 400.
                return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
            }
            function DaysArray(n) {
                for (var i = 1; i <= n; i++) {
                    this[i] = 31
                    if (i==4 || i==6 || i==9 || i==11) {
                        this[i] = 30
                    }
                    if (i==2) {
                        this[i] = 29
                    }
                }
                return this
            }

            function isDate(dtStr){
                var daysInMonth = DaysArray(12)
                var pos1=dtStr.indexOf(dtCh)
                var pos2=dtStr.indexOf(dtCh,pos1+1)
            
            
                var strYear=dtStr.substring(0,pos1)
                var strMonth=dtStr.substring(pos1+1,pos2)
                var strDay=dtStr.substring(pos2+1)

                strYr=strYear
                if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
                if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
                for (var i = 1; i <= 3; i++) {
                    if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
                }
                month=parseInt(strMonth)
                day=parseInt(strDay)
                year=parseInt(strYr)
                if (pos1==-1 || pos2==-1){
                    setMainErrorMessage('The date format should be : yyyy-mm-dd');
                    return false
                }
                if (strMonth.length<1 || month<1 || month>12){
                    setMainErrorMessage('Please enter a valid month');
                    return false
                }
                if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
                    setMainErrorMessage('Please enter a valid day');
                    return false
                }
                if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
                    setMainErrorMessage('Please enter a valid 4 digit year between "+minYear+" and "+maxYear');
                    return false
                }
                if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
                    setMainErrorMessage('Please enter a valid date');
                    return false
                }
                return true
            }


            function ValidateForm(date){

                var trimedDate=jQuery.trim(date);
                if (isDate(trimedDate)==false){
                    return false;
                }
                return true;
            }

        }

            
    });
//document.getElementById('#deletedDate').focus= false;
    
   
});

function findSelected(){
    
    var changedStatus = document.getElementById('changedStatus');
    var changedEstimation = document.getElementById('editboxEstimation');
    if(changedStatus.value == " Accepted"){
        changedEstimation.disabled = true;
    }
    else{
        changedEstimation.disabled = false;
    }

    
    
}

function datepicker(){
    $( "#deletedDate" ).datepicker(

    {
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            showAnim: "slideDown"
        });
}

function setMainErrorMessage(message) {
    $('#mainErrorDiv').empty();
    $('#mainErrorDiv').append(message);
}

function removeMainErrorMessage() {
    $('#mainErrorDiv').empty();
}