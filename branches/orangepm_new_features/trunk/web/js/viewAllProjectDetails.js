/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {
    $("#projectSearch_status").change(function(){
            location.href="viewAllProjectDetails?selectedStatusId=" + $("#projectSearch_status").val();
    });
});