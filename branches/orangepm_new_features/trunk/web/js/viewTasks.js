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
                   
                    window.location.href =  targetUrl;
                },
                "Cancel" : function() {
                    $(this).dialog("close");
                }
            }
        });

        $("#dialog").dialog("open");
    });
    
    var synchronize = true;
    $("td.edit").click(function() {
        if(synchronize || $(this).hasClass('ajaxEdit')) {
            var name = $.trim($(this).parent().children('td.changedName').find('input').attr('value'));
            var effort = $.trim($(this).parent().children('td.changedEffort').find('input').attr('value'));
            var preestimatedEndDate = $.trim($(this).parent().children('td.changedEstimatedEndDate').find('input').attr('value'));
            var ownedBy = $.trim($(this).parent().children('td.changedOwnedBy').find('input').attr('value'));
            var description = $.trim($(this).parent().children('td.changedDescription').find('textarea').attr('value'));
            if($(this).hasClass('ajaxEdit')) {
                if($.trim($(this).parent().children('td.changedName').find('input').attr('value')) != '') {
                    if(!isNaN($.trim($(this).parent().children('td.changedEffort').find('input').attr('value')))) {
                        removeMainErrorMessage();
                        $(this).removeClass('ajaxEdit');
                        var nameText = $.trim($(this).parent().children('td.changedName').find('input').attr('value'));
                        var effortText = $.trim($(this).parent().children('td.changedEffort').find('input').attr('value'));
                        var estimatedEndDate = $.trim($(this).parent().children('td.changedEstimatedEndDate').find('input').attr('value'));
                        var statusSelect = $(this).parent().children('td.changedStatus').find('select').attr('value');
                        var ownedByText = $.trim($(this).parent().children('td.changedOwnedBy').find('input').attr('value'));
                        var descriptionText = $.trim($(this).parent().children('td.changedDescription').find('textarea').attr('value'));
                        var classString = $(this).attr('class');
                        var id = classString.split(' ')[1];
                        var $parentRow = $(this).parent();
                        $.ajax({
                            type: "post",
                            url: updateTaskUrl,
                            data: {id: id , name: nameText, effort: effortText, estimatedEndDate: estimatedEndDate, status: statusSelect, ownedBy: ownedByText, description: descriptionText, projectId: projectId},                            
                            success: function(responce){
                                if(responce=='notSaved'){
                                    window.location.href = loginUrl+"?noSession=true";
                                }else {
                                    $parentRow.children('td.changedName').html(nameText);
                                    $parentRow.children('td.changedEffort').html(effortText);
                                    $parentRow.children('td.changedEstimatedEndDate').html(estimatedEndDate);
                                    $parentRow.children('td.changedStatus').html(statusArray[statusSelect]);
                                    $parentRow.children('td.changedStatus').addClass(statusArray[statusSelect].toLowerCase());
                                    $parentRow.children('td.changedOwnedBy').html(ownedByText);
                                    $parentRow.children('td.changedDescription').html(descriptionText);                                    
                                }                            
                            },
                            fail: function() {
                                $parentRow.children('td.changedName').html(name);
                                $parentRow.children('td.changedEffort').html(effort);
                                $parentRow.children('td.changedEstimatedEndDate').html(estimatedEndDate);
                                $parentRow.children('td.changedStatus').html(statusArray[status]);
                                $parentRow.children('td.changedOwnedBy').html(ownedBy);
                                $parentRow.children('td.changedDescription').html(description);
                            }
                        });
                        $(this).html(editImgUrl);
                        synchronize = true;
                    } else { 
                        setMainErrorMessage('Task Effort is not valid');
                    }
                } else { 
                    setMainErrorMessage('Task name is empty');
                }
            } else {
                synchronize = false;
                $(this).addClass('ajaxEdit');
                name = $.trim($(this).parent().children('td.changedName').text());
                effort = $.trim($(this).parent().children('td.changedEffort').text());
                estimatedEndDate = $.trim($(this).parent().children('td.changedEstimatedEndDate').text());
                ownedBy = $.trim($(this).parent().children('td.changedOwnedBy').text());
                description = $.trim($(this).parent().children('td.changedDescription').text());
                status = $.trim($(this).parent().children('td.changedStatus').text());
                
                $(this).parent().children('td.changedName').addClass('ajaxName');
                $(this).parent().children('td.changedName').html("<input type='text' id='ajaxNameInput' value='"+escapeQuotes(name)+"'></input>");
                $(this).parent().children('td.changedEffort').addClass('ajaxEffort');
                $(this).parent().children('td.changedEffort').html("<input type='text' id='ajaxEffortInput' value='"+effort+"'></input>");
                $(this).parent().children('td.changedEstimatedEndDate').addClass('ajaxEstimatedEndDate');
                $(this).parent().children('td.changedEstimatedEndDate').html('<input id="ajaxEstimatedEndDateInput" type="text"  value="'+$(this).parent().children('td.changedEstimatedEndDate').text()+'">');
                
                var statusDropDown = '<select name="ajaxStatus" id="ajaxStatus" >';
                for(key in statusArray) {
                    statusDropDown += '<option value="'+key+'">'+statusArray[key]+'</option>';
                }
                statusDropDown += '</select> ';
                $(this).parent().children('td.changedStatus').html(statusDropDown);
                $(this).parent().children('td.changedStatus').removeClass(status.toLowerCase());
                var statusId = getStatusId(status);
                $('#ajaxStatus').find("option[value='"+statusId+"']").attr('selected',true);
                $(this).parent().children('td.changedOwnedBy').addClass('ajaxOwnedBy');
                $(this).parent().children('td.changedOwnedBy').html("<input type='text' id='ajaxOwnedByInput' value='"+escapeQuotes(ownedBy)+"'></input>");
                $(this).parent().children('td.changedDescription').addClass('ajaxDescription');
                $(this).parent().children('td.changedDescription').html("<textarea id='ajaxDescriptionInput'>"+description+"</textarea>");
                $(this).html(saveImgUrl);
                
                $( "#ajaxEstimatedEndDateInput" ).datepicker( {
                    dateFormat: ' yy-mm-dd',
                    changeMonth: true,
                    changeYear: true,
                    showAnim: "slideDown"
                });
            }
            
        }
        
    });
    
    function getStatusId(status) {
        var index = $.inArray(status, statusArray);
        return index;
    }
    
    function setMainErrorMessage(message) {
        $('#mainErrorDiv').empty();
        $('#mainErrorDiv').append(message);
    }

    function removeMainErrorMessage() {
        $('#mainErrorDiv').empty();
    }
    function escapeQuotes(words){
        words=words.replace("&","&amp;");
        words=words.replace('"',"&#34;");
        words=words.replace("'","&#39;");
        return words;
    }
});