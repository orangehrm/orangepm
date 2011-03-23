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
                    window.location.href = targetUrl;
                },
                "Cancel" : function() {
                    $(this).dialog("close");
                }
            }
        });

        $("#dialog").dialog("open");
    });
  
  
    a = true;
    nVariable = "Saved";

    $('td.edit').click(function(){
        if(a){
                
            arr = $(this).attr('class').split( " " );
            if(nVariable == "Saved"){
                    
                $(this).html(saveImgUrl);
                a = false;
            }
            if(nVariable == "Edited"){
                $(this).html(editImgUrl);
                nVariable = "Saved";
                    
            }
                

                
            $('.ajaxName').html($('.ajaxName input').val());
            $('.ajaxDate').html($('.ajaxDate input').val());
            $('.ajaxEstimation').html($('.ajaxEstimation input').val());
            $('.ajaxName').removeClass('ajaxName');
            $('.ajaxDate').removeClass('ajaxDate');
            $('.ajaxEstimation').removeClass('ajaxEstimation');
            $(this).parent().children('td.changedName').addClass('ajaxName');
            $(this).parent().children('td.changedName').html('<input id="editboxName" size="13" type="text" value="'+$(this).parent().children('td.changedName').text()+'">');
            $(this).parent().children('td.changedDate').addClass('ajaxDate');
            $(this).parent().children('td.changedDate').html('<input id="editboxDate" size="9" type="text" value="'+$(this).parent().children('td.changedDate').text()+'">');
            $(this).parent().children('td.changedEstimation').addClass('ajaxEstimation');
            $(this).parent().children('td.changedEstimation').html('<input id="editboxEstimation" size="5" type="text" value="'+$(this).parent().children('td.changedEstimation').text()+ '">');

            $('#saveBtn').click(function(){
                a = true;
                if(!isNaN($('.ajaxEstimation input').val())){
                    if(ValidateForm($('.ajaxDate input').val())){
                        $.ajax({
                            type: "post",
                            url: linkUrl,

                            data: "name="+$('.ajaxName input').val()+"&date="+$('.ajaxDate input').val()+"&estimation="+$('.ajaxEstimation input').val()+"&id="+arr[2],

                            success: function(){
                        
                                $('.ajaxName').html($('.ajaxName input').val());
                                $('.ajaxDate').html($('.ajaxDate input').val());
                                $('.ajaxEstimation').html($('.ajaxEstimation input').val());
                            
                         
                            }
                        });

                        nVariable = "Edited";
                
                    }
                }
            else
                alert("Please Input a Valid Number for Estimation Effort");

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
                alert("The date format should be : yyyy-mm-dd")
                return false
            }
            if (strMonth.length<1 || month<1 || month>12){
                alert("Please enter a valid month")
                return false
            }
            if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
                alert("Please enter a valid day")
                return false
            }
            if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
                alert("Please enter a valid 4 digit year between "+minYear+" and "+maxYear)
                return false
            }
            if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
                alert("Please enter a valid date")
                return false
            }
            return true
        }


        function ValidateForm(date){

            var dt=jQuery.trim(date);
            if (isDate(dt)==false){
                return false;
            }
            return true;
        }

   }

            
    });

});
