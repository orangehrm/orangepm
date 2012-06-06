/* 
 * Events & validations
 */

//// global variables
//var allValid = true;
//var thisInput = false;
//
//$(document).ready(function(){
//    
//    $('#cancel').click(function(){
//        location.href="viewUsers";
//    });
//    
//    // validation for first name
//    $('#addUser #user_firstName').validator({
//        format: 'alphanumeric',
//        invalidEmpty: true,
//        correct: function() {
//            allValid = true;
//            thisInput = true;
//            allValid = allValid && thisInput;
//            $('#addUser #result_firstName').text('');            
//        },
//        error: function(){
//            thisInput = false;
//            allValid = allValid && thisInput;
//            $('#addUser #result_firstName').text(lang_firstNameRequired);
//        }
//    });   
//    
//    //allValid = allValid && thisInput;
//    
//    // validation for last name
//    $('#addUser #user_lastName').validator({
//        format: 'alphanumeric',
//        invalidEmpty: true,
//        correct: function() {
//            thisInput = true;
//            allValid = allValid && thisInput;
//            $('#addUser #result_lastName').text('');            
//        },
//        error: function(){
//            thisInput = false;
//            allValid = allValid && thisInput;
//            $('#addUser #result_lastName').text(lang_lastNameRequired);
//        }
//    });
//    
//    //allValid = allValid && thisInput;
//    
//    // validation for email
//    $('#addUser #user_email').validator({
//        format: 'email',
//        invalidEmpty: true,
//        correct: function() {
//            thisInput = true;
//            allValid = allValid && thisInput;
//            $('#addUser #result_email').text('');            
//        },
//        error: function(){
//            thisInput = false;
//            allValid = allValid && thisInput;
//            $('#addUser #result_email').text(lang_emailRequired);
//        }
//    });
//    
//    //allValid = allValid && thisInput;
//    
//    // validation for username
//    $('#addUser #user_username').validator({
//        format: 'alphanumeric',
//        invalidEmpty: true,
//        correct: function() {
//            thisInput = true;
//            allValid = allValid && thisInput;
//            $('#addUser #result_username').text('');            
//        },
//        error: function(){
//            thisInput = false;
//            allValid = allValid && thisInput;
//            $('#addUser #result_username').text(lang_usernameRequired);
//        }
//    });
//    
//    //allValid = allValid && thisInput;
//    
//    // validation for password
//    $('#addUser #user_password').validator({
//        format: 'alphanumeric',
//        invalidEmpty: true,
//        correct: function() {
//            thisInput = true;
//            allValid = allValid && thisInput;
//            $('#addUser #result_password').text('');            
//        },
//        error: function(){
//            thisInput = false;
//            allValid = allValid && thisInput;
//            $('#addUser #result_password').text(lang_passwordRequired);
//        }
//    });
//        
//    //allValid = allValid && thisInput;
//    
//});
//
//// function for check client side validation is there
//function addUserInputsValid( ) {
//    if(allValid){
//        return true;
//    }
//    else{
//        return false;
//    }            
//}   

$(document).ready(function() {

    $('#cancel').click(function(){
        location.href="viewUsers";
    });

    /* form validation */
    $("#addUser").validate({
        
        rules: {
            'user[firstName]': { required: true },
            'user[lastName]': { required: true },
            'user[email]': {required: true, email: true},
            'user[username]': {required: true },
            'user[password]': {required: true}
            
        },
        messages: {
            'user[firstName]': { required: lang_firstNameRequired },
            'user[lastName]': { required: lang_lastNameRequired },
            'user[email]': { required: lang_emailRequired, email: lang_validEmailRequired },
            'user[username]': { required: lang_usernameRequired },
            'user[password]': { required: lang_passwordRequired }
        },        
        errorElement : 'div',
        errorPlacement: function(error, element) {
            error.insertAfter(element.next(".clear"));            

        }
        
    });

});