<?php echo stylesheet_tag('viewStories') ?>
<?php use_helper('Pagination'); ?>

<div class="addButton">
<form action="<?php echo url_for('project/addStory?id=' . $projectId); ?>" method="GET">
    <input type="submit" value="<?php echo __('Add') ?>" />
</form>
</div>


<br/>

<table class="tableContent">
    <tr><td class="pageNav" colspan="6"><?php  echo pager_navigation($storyList, url_for("project/viewStories")."?id={$projectId}") ?></td></tr>
    <tr>
        <th><?php echo __('Story Id') ?></th>
        <th><?php echo __('Story Name') ?></th>
        <th><?php echo __('Estimated Effort') ?></th>
        <th><?php echo __('Date Added') ?></th>
        <th colspan="2"><?php echo __('Actions')?></th>
    </tr>

    <?php foreach ($storyList->getResults() as $story): ?>
        <tr id="row">
            <td class="<?php echo "not id " . $story->getId(); ?>"><?php echo $story->getId(); ?></td>
            <td class="<?php echo "changedName name " . $story->getId(); ?>"><?php echo $story->getName(); ?></td>
            <td class="<?php echo "changedEstimation estimation " . $story->getId(); ?>"> <?php echo $story->getEstimation(); ?></td>
            <td class="<?php echo "changedDate date " . $story->getId(); ?>"> <?php echo $story->getDateAdded(); ?></td>
            <td class="<?php echo "edit edit " . $story->getId(); ?>"><?php echo image_tag('b_edit.png', 'id=editBtn') ?></td>
            <td class="close"><a href="<?php echo url_for("project/deleteStory?id={$story->getId()}&projectId={$projectId}"); ?> " > <?php echo image_tag('b_drop.png'); ?></a></td>

        </tr>
    <?php endforeach; ?>
    </table>


    <script type="text/javascript">

        $(document).ready(function(){
            a = true;
            nVariable = "Saved";

            $('td.edit').click(function(){
                if(a){
                
                arr = $(this).attr('class').split( " " );
                if(nVariable == "Saved"){
                    
                    $(this).html('<?php echo image_tag('b_save.gif', 'id=saveBtn') ?>');
                    a = false;
                }
                if(nVariable == "Edited"){
                    $(this).html('<?php echo image_tag('b_edit.png', 'id=editBtn') ?>');
                    nVariable = "Saved";
                    
                }
                

                
                $('.ajaxName').html($('.ajaxName input').val());
                $('.ajaxDate').html($('.ajaxDate input').val());
                $('.ajaxEstimation').html($('.ajaxEstimation input').val());
                $('.ajaxName').removeClass('ajaxName');
                $('.ajaxDate').removeClass('ajaxDate');
                $('.ajaxEstimation').removeClass('ajaxEstimation');
                $(this).parent().children('td.changedName').addClass('ajaxName');
                $(this).parent().children('td.changedName').html('<input id="editboxName" size="13" type="text" value="' + $(this).parent().children('td.changedName').text() + '">');
                $(this).parent().children('td.changedDate').addClass('ajaxDate');
                $(this).parent().children('td.changedDate').html('<input id="editboxDate" size="9" type="text" value="' + $(this).parent().children('td.changedDate').text() + '">');
                $(this).parent().children('td.changedEstimation').addClass('ajaxEstimation');
                $(this).parent().children('td.changedEstimation').html('<input id="editboxEstimation" size="5" type="text" value="' + $(this).parent().children('td.changedEstimation').text() + '">');
                
                $('#saveBtn').click(function(){
                    a = true;
                    $.ajax({    type: "post",
                        url: "<?php echo url_for('project/editStory') ?>",
                        data: "name="+$('.ajaxName input').val()+"&date="+$('.ajaxDate input').val()+"&estimation="+$('.ajaxEstimation input').val()+"&id="+arr[2],
                        success: function(){

                            $('.ajaxName').html($('.ajaxName input').val());
                            $('.ajaxDate').html($('.ajaxDate input').val());
                            $('.ajaxEstimation').html($('.ajaxEstimation input').val());
                            
                         
                        }
                    });

                    nVariable = "Edited";
                

<<<<<<< .mine
            });}
=======
    );

   
    });
>>>>>>> .r18

            });
});
</script>

