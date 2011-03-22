<?php echo stylesheet_tag('addStory') ?>
<?php use_helper('Pagination'); ?>

<script type="text/javascript">
    var linkUrl = "<?php echo url_for('project/viewStories') ?>";
</script>



<div class="addForm">
    <div class="headlineField"><?php echo __('Add Story') ?></div>
    <div class="formField">
        <form action="<?php echo url_for('project/addStory?id=' . $projectId); ?>" method="post">
            <table>

                
                <tr><td><?php echo $storyForm['Story_Name']->renderLabel() ?></td><td><?php echo $storyForm['Story_Name']->renderError() ?><?php echo $storyForm['Story_Name']->render() ?></td></tr>
                <tr><td><?php echo $storyForm['Date_Added']->renderLabel() ?></td><td><?php echo $storyForm['Date_Added']->renderError() ?><?php echo $storyForm['Date_Added']->render() ?></td></tr>
                <tr><td><?php echo $storyForm['Estimated_Effort']->renderLabel() ?> &nbsp;&nbsp;</td><td><?php echo $storyForm['Estimated_Effort']->renderError() ?><?php echo $storyForm['Estimated_Effort']->render() ?></td>&nbsp;<td><?php echo __("(Engineering Hours)") ?></td></tr>

                 <?php echo $storyForm->renderHiddenFields();?>


                <tr>
                    <td colspan="2">
                       
                        <input class="formButton" type="submit" value="<?php echo __('Save') ?>" />
                        &nbsp;&nbsp;&nbsp;
                        <input class="formButton" type="button" id="cancel" onclick="passProjectId(<?php echo $projectId?>)" value="<?php echo __('Cancel') ?>" />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>



<p>
    <br/>
<table class="tableContent">
    <tr><td class="pageNav" colspan="4"><?php echo pager_navigation($storyList, url_for('project/addStory') . "?id={$projectId}") ?></td></tr>
    <tr>
        <th><?php echo __('Story Id') ?></th>
        <th><?php echo __('Story Name') ?></th>
        <th><?php echo __('Estimated Effort') ?></th>
        <th><?php echo __('Date Added') ?></th>
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
<?php echo javascript_include_tag('addStory?id='.$projectId); ?>