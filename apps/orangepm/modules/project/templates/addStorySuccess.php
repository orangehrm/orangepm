<?php echo stylesheet_tag('addStory') ?>
<?php echo stylesheet_tag('jquery-ui-1.8.10.custom.css') ?>
<?php use_helper('Pagination'); ?>

<div class="addForm">
    <div class="headlineField"><?php echo __('Add Project')?></div>
    <div class="formField">
        <form action="<?php echo url_for('project/addStory') ?>" method="post">
            <table>
                <?php echo $storyForm ?>
                <tr>
                    <td colspan="2">
                        <input class="formButton" type="submit" value="<?php echo __('Save') ?>" />
                        &nbsp;&nbsp;&nbsp;
                        <input class="formButton" type="button" id="cancel" value="<?php echo __('Cancel') ?>" />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>


<p>
    <br/>
<table class="tableContent">
    <tr><td class="pageNav" colspan="4"><?php  echo pager_navigation($storyList, url_for('project/addStory')."?id={$projectId}") ?></td></tr>
    <tr>
        <th><?php echo __('Story Id')?></th>
        <th><?php echo __('Story Name')?></th>
        <th><?php echo __('Estimated Effort')?></th>
        <th><?php echo __('Date Added')?></th>
    </tr>

    <?php foreach ($storyList->getResults() as $story): ?>
                    <tr>
                        <td class="<?php echo "not id " . $story->getId(); ?>"><?php echo $story->getId(); ?></td>
                        <td class="<?php echo "change name " . $story->getId(); ?>"><?php echo $story->getName(); ?></td>
                        <td> <?php echo $story->getEstimation(); ?></td>
                        <td> <?php echo $story->getDateAdded(); ?></td>
                    </tr>
    <?php endforeach; ?>
                </table>

                <script type="text/javascript">
                    $(document).ready(
                    function() {
                        $( "#project_Date_added" ).datepicker(
                        {
                            dateFormat: 'yy-mm-dd',
                            changeMonth: true,
                            changeYear: true,
                            showAnim: "slideDown"

                        });



                    });

                    $(document).ready(function(){
                        $('#cancel').click(function(){
                            location.href="<?php echo url_for('project/viewStories?id=' . $projectId); ?>";
        });
    });
</script>                
