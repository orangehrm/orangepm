$(document).ready(function(){

    $("#addTaskForm").validate({
        
        rules: {
            'task[name]': { required: true },
            'task[effort]' : {number: true}
        },
        
        messages: {
            'task[name]': { required: lang_nameRequired },
            'task[effort]' : { number: lang_effortValid }
            },        
        errorElement : 'div',
        errorPlacement: function(error, element) {
            error.insertAfter(element.next(".clear"));            

        }
        
    });
});