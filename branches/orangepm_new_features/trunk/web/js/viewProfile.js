$(document).ready(function() {

    $('#cancel').click(function(){
        location.href="index";
    });
    
    /* form validation */
    $("#editProfile").validate({
        
        rules: {
            'user[email]': { required: true, email: true },
            'user[password]': { required: true }
        },
        messages: {
            'user[email]': { required: lang_emailRequired, email: lang_validEmailRequired },
            'user[oldPassword]': { required: lang_oldPasswordRequired },
            'user[newPassword]': { required: lang_newPasswordRequired },
            'user[confirmPassword]': { required: lang_confirmPasswordRequired }
        },        
        errorElement : 'div',
        errorPlacement: function(error, element) {
            error.insertAfter(element.next(".clear"));            

        }
        
    });
    
});
