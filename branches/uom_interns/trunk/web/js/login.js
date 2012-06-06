$(document).ready(function() {

    $('#loginButton').click(function(){
        $("#login_form").submit();
    });

    /* form validation */
    $("#login_form").validate({
        
        rules: {
            'login[username]': { required: true },
            'login[password]': { required: true }
        },
        messages: {
            'login[username]': { required: lang_usernameRequired },
            'login[password]': { required: lang_passwordRequired }
        },        
        errorElement : 'div',
        errorPlacement: function(error, element) {
            error.insertAfter(element.next(".clear"));            

        }
        
    });

});
