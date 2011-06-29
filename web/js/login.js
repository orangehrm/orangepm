/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// global variables
var userValid = false;
var passValid = false;


// login form validation
$(document).ready(function() {    

    // validation for username
    $('#login_form #login_username').validator({
        format: 'alphanumeric',
        invalidEmpty: true,
        correct: function() {
            userValid = true;
            $('#login_form #result_username').text('');            
        },
        error: function(){
            userValid = false;
            $('#login_form #result_username').text("Please fill username field");
        }
    });


    // validation for password
    $('#login_form #login_password').validator({
        format: 'alphanumeric',
        invalidEmpty: true,
        correct: function() {
            passValid = true;
            $('#login_form #result_password').text('');
        },
        error: function(){
            passValid = false;
            $('#login_form #result_password').text("* Please fill password field");
        }
    });   
    
});


// function for check client side validation is there
function loginInputValid( ) {
    if((userValid) && (passValid)){
        return true;
    }
    else{
        return false;
    }            
}    
