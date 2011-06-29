<?php echo stylesheet_tag('addStory') ?>
<?php use_helper('Pagination'); ?>

<script type="text/javascript">
    var linkUrl = "<?php echo url_for('project/viewStories') ?>";
   
</script>


<div class="heading">
    <h3> <?php echo link_to(__('Projects'),'project/viewProjects'); ?> ><a class="storyLink" href="<?php echo url_for("project/viewStories?id={$projectId}&projectName={$projectName}"); ?>" > <?php echo $projectName; ?></a> > <?php echo __('Add Story'); ?> </h3>
</div>

<div class="addForm">
    <div class="headlineField"><?php echo __('Add Story') ?></div>
    <div class="formField">
        <form action="<?php echo url_for("project/addStory?id={$projectId}&projectName={$projectName}"); ?>" method="post">
            <table>


<!--                <tr><td class="labelfield"><?php //echo $storyForm['storyName']->renderLabel() ?></td><td colspan="2"><?php //echo $storyForm['storyName']->renderError() ?><?php //echo $storyForm['storyName']->render() ?></td></tr>-->
                <tr><td  valign="top"><span class="mandatoryStar">*</span> <?php echo $storyForm['storyName']->renderLabel() ?> </td><td colspan="2" class="labelfield" ><?php echo $storyForm['storyName']->render() ?><?php echo $storyForm['storyName']->renderError() ?></td></tr>
                <tr><td><?php echo $storyForm['dateAdded']->renderLabel() ?></td><td><?php echo $storyForm['dateAdded']->renderError() ?><?php echo $storyForm['dateAdded']->render() ?></td></tr>
                <tr><td><?php echo $storyForm['estimatedEffort']->renderLabel() ?>&nbsp;&nbsp; </td><td><?php echo $storyForm['estimatedEffort']->renderError() ?><?php echo $storyForm['estimatedEffort']->render() ?></td>&nbsp;<td><?php echo __("(Engineering Hours)") ?></td></tr>
                <tr><td><?php echo $storyForm['status']->renderLabel() ?></td><td><?php echo $storyForm['status']->render(array('onchange' => 'clicked(project_status)'))?></td>
                    &nbsp;
                    <td><?php echo $storyForm['acceptedDate']->renderLabel() ?></td><td><?php echo $storyForm['acceptedDate']->renderError() ?><?php echo $storyForm['acceptedDate']->render() ?></td></tr>
                <?php echo $storyForm->renderHiddenFields(); ?>


                <tr>
                    <td colspan="2">

                        <input class="formButton" type="submit" value="<?php echo __('Save') ?>" />
                        &nbsp;&nbsp;&nbsp;
                        <input class="formButton" type="button" id="cancel" onclick="passProjectId(<?php echo $projectId ?>, '<?php echo $projectName ?>' )" value="<?php echo __('Cancel') ?>" />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>



<p>
    <br/>
<table class="tableContent">
    <tr><td class="pageNav" colspan="6"><?php echo pager_navigation($storyList, url_for('project/addStory') . "?id={$projectId}&projectName={$projectName}") ?></td></tr>
    <tr>
        
        <th><?php echo __('Story Name') ?></th>
        <th><?php echo __('Estimated Effort'); ?> <br> <?php echo __('(Engineering Hours)'); ?></th>
        <th><?php echo __('Date Added') ?></th>
        <th><?php echo 'Status' ?></th>
        <th><?php echo 'Accepted Date' ?></th>
    </tr>

    <?php foreach ($storyList->getResults() as $story): ?>
                    <tr>
                        
                        <td class="<?php echo "change name " . $story->getId(); ?>"><?php echo $story->getName(); ?></td>
                        <td> <?php echo $story->getEstimation(); ?></td>
                        <td> <?php echo $story->getDateAdded(); ?></td>
                        <td><?php echo $story->getStatus(); ?></td>
                        <td><?php echo $story->getAcceptedDate(); ?></td>


                    </tr>
    <?php endforeach; ?>
                </table>
<?php echo javascript_include_tag("addStory?id={$projectId}&projectName={$projectName}"); ?>
<script type="text/javascript">
    var linkUrl = "<?php echo url_for('project/viewStories') ?>";
    var index = "<?php echo $storyForm->getValue('status')?>";
</script>
