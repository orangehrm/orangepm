/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

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
    
        
    synchronizedVariable = true;
    toggleVariable = "Saved";
    dropdownToggleVariable = true;
    
    
    $('td.edit').click(function() {
        
        if(synchronizedVariable) {
                
            classNameArray = $(this).attr('class').split( " " );
            
            if(toggleVariable == "Saved") {                    
                $(this).html(saveImgUrl);
                synchronizedVariable = false;
                dropdownToggleVariable = true;
            }
            if(toggleVariable == "Edited") {
                $(this).html(editImgUrl);
                toggleVariable = "Saved";                    
            }
               
            $('.ajaxFirstName').html($('.ajaxFirstName input').val());
            $('.ajaxLastName').html($('.ajaxLastName input').val());
            $('.ajaxEmail').html($('.ajaxEmail input').val());
            $('.ajaxUserType').html($('.ajaxUserType select').val());
            $('.ajaxUsername').html($('.ajaxUsername input').val());
            $('.ajaxPassword').html($('.ajaxPassword input').val());
            
            $('.ajaxFirstName').removeClass('ajaxFirstName');
            $('.ajaxLastName').removeClass('ajaxLastName');
            $('.ajaxEmail').removeClass('ajaxEmail');
            $('.ajaxUserType').removeClass('ajaxUserType');
            $('.ajaxUsername').removeClass('ajaxUsername');
            $('.ajaxPassword').removeClass('ajaxPassword');
            
            $(this).parent().children('td.changedFirstName').addClass('ajaxFirstName');
            $(this).parent().children('td.changedFirstName').html('<input id="editboxFirstName" size="15" type="text" maxlength="15" value="'+escapeQuotes($(this).parent().children('td.changedFirstName').text())+'">');
            $(this).parent().children('td.changedLastName').addClass('ajaxLastName');
            $(this).parent().children('td.changedLastName').html('<input id="editboxLastName" size="15" type="text" maxlength="15" value="'+escapeQuotes($(this).parent().children('td.changedLastName').text())+'">');
            $(this).parent().children('td.changedEmail').addClass('ajaxEmail');
            $(this).parent().children('td.changedEmail').html('<input id="editboxEmail" size="30" type="text" maxlength="30" value="'+escapeQuotes($(this).parent().children('td.changedEmail').text())+ '">');            
            
            $(this).parent().children('td.changedUserType').addClass('ajaxUserType');
            if(dropdownToggleVariable){
                
                var previousUserType = jQuery.trim($(this).parent().children('td.changedUserType').text());
                                
                $(this).parent().children('td.changedUserType').html('<select name="changedUserType" id="changedUserType">'+
                    '<option value="Project Admin">Project Admin</option>'+
                    '<option value="Super Admin">Super Admin</option>'+
                    '<option value="Project Member">Project Member</option>'+
                    '</select> ');
                
                $("#changedUserType").val(previousUserType);
                
                dropdownToggleVariable = false;
            }
            
            $(this).parent().children('td.changedUsername').addClass('ajaxUsername');
            $(this).parent().children('td.changedUsername').html('<input id="editboxUsername" size="14" type="text" maxlength="15" value="'+escapeQuotes($(this).parent().children('td.changedUsername').text())+'">');
            $(this).parent().children('td.changedPassword').addClass('ajaxPassword');
            $(this).parent().children('td.changedPassword').html('<input id="editboxPassword" size="14" type="text" maxlength="15" value="double click to reset" readonly="readonly" >');
                
            var el = document.getElementById('editboxPassword');
            el.ondblclick = function(){
                this.removeAttribute('readonly');
                this.type = 'password';
            };
 
             
            $('#saveBtn').click(function() {
                
                synchronizedVariable = true;
                isValidEmail = true;
                
                if(!($('.ajaxFirstName input').val()=='')) {
                    
                    if(!($('.ajaxLastName input').val()=='')) {    

                        var email=$('.ajaxEmail input').val();
                        if(email == '') {
                            setMainErrorMessage('Email is empty');
                            isValidEmail = false;
                        }
                        var atpos=email.indexOf("@");
                        var dotpos=email.lastIndexOf(".");
                        if (isValidEmail && (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length)) {
                            setMainErrorMessage('Email is not valid');
                            isValidEmail=false;
                        }

                        if(isValidEmail) {
                            
                            if(!($('.ajaxUsername input').val()=='')) {
                                    
                                if(!($('.ajaxPassword input').val()=='')) {
                                    
                                    removeMainErrorMessage();

                                    $.ajax({
                                        type: "post",
                                        url: linkUrl,
                                        data: {
                                            firstName : $('.ajaxFirstName input').val() , 
                                            lastName : $('.ajaxLastName input').val() , 
                                            email : jQuery.trim($('.ajaxEmail input').val()) , 
                                            id : classNameArray[2] , 
                                            userType : jQuery.trim($('.ajaxUserType select').val()) , 
                                            username : jQuery.trim($('.ajaxUsername input').val()) , 
                                            password : jQuery.trim($('.ajaxPassword input').val())
                                            },

                                        success: function(){

                                            if($('.ajaxPassword input').val() == "double click to reset") {
                                                $('.ajaxPassword').html("hidden");
                                            }

                                        /*$('.ajaxFirstName').html($('.ajaxFirstName input').val());
                                        $('.ajaxLastName').html($('.ajaxLastName input').val());
                                        $('.ajaxEmail').html($('.ajaxEmail input').val());
                                        $('.ajaxUserType').html($('.ajaxUserType select').text());
                                        $('.ajaxUsername').html($('.ajaxUsername input').val());
                                        $('.ajaxPassword').html($('.ajaxPassword input').val());

                                        $('.ajaxUserType').removeClass('ajaxUserType');*/
                                        location.href="viewUsers";

                                        }

                                    });

                                    dropdownToggleVariable = false;
                                    toggleVariable = "Edited";
                                } else {
                                    setMainErrorMessage('Password is empty');
                                }
                            } else {
                                setMainErrorMessage('Username is empty');
                            }
                        }
                        
                    } else {
                        setMainErrorMessage('Last Name is empty');
                    }
                    
                } else {
                    setMainErrorMessage('First Name is empty');
                }

            });

        }
            
    });
        
});

function setMainErrorMessage(message) {
    $('#mainErrorDiv').empty();
    $('#mainErrorDiv').append(message);
}

function removeMainErrorMessage() {
    $('#mainErrorDiv').empty();
}
function escapeQuotes(words){
    return words
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");

}
