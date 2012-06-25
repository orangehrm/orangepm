<?php echo stylesheet_tag('viewStories') ?>
<?php use_helper('Pagination'); ?>

<script type="text/javascript">
    var saveImgUrl = '<?php echo image_tag('b_save.gif', 'id=saveBtn') ?>';
    var editImgUrl = '<?php echo image_tag('b_edit.png', 'id=editBtn') ?>';
    var linkUrl = "<?php echo url_for('project/editStory') ?>";
    var loginUrl = '<?php echo url_for('project/login')?>';
    var viewTaskUrl = "<?php echo url_for('project/viewTasks') ?>";
    var isAllowToEditEffort = "<?php    if(($projectAccessLevel == User::USER_TYPE_PROJECT_ADMIN) || ($projectAccessLevel == User::USER_TYPE_SUPER_ADMIN) ) { echo '1';}
                                        else {echo '0'; } ?>";
</script>

<div class="Project">
    <div class="heading">
        <h3> <?php echo link_to(__('Projects'),'project/viewProjects'); ?> > <?php echo link_to(__($projectName),"project/viewProjectDetails?projectId={$id}&projectName={$projectName}"); ?> > <?php echo __('Stories'); ?> </h3>
        <span id="message"><?php echo $sf_user->getFlash('addStory') ?></span>
        <span id="noRecordMessage"><?php if(isset($noRecordMessage)) echo $noRecordMessage; ?></span>
    </div>

    <div id="mainErrorDiv"></div>
    <div class="StoryShowForm">
        <table class="tableContent">
            <tr><td class="pageNav" colspan="9"><?php echo pager_navigation($storyList, url_for("project/viewStories") . "?id={$projectId}&projectName={$projectName}") ?></td></tr>
            <tr>
                <th><?php echo __('Story Name') ?></th>
                <th><?php echo __('Effort');?></th>
                <th><?php echo __('Tasks Total');?></th>
                <th><?php echo __('Date Added') ?></th>
                <th><?php echo __('Estimated End Date') ?></th>
                <th><?php echo 'Status' ?></th>
                <th><?php echo 'Accepted Date' ?></th>
                <th colspan="2"><?php echo __('Actions') ?></th>
            </tr>
            <?php if(count($storyList) != 0): ?>
            <?php foreach ($storyList->getResults() as $story): ?>
            <?php $status = $story->getStatus() == 'Pending' ? 'Backlog' : $story->getStatus();?>
                <tr id="row">
                    <td class="<?php echo "changedName name " . $story->getId(); ?>"><a href="<?php echo url_for("project/viewTasks?storyId={$story->getId()}")?>"><?php echo $story->getName(); ?></a></td>
                    <td class="<?php echo "changedEstimation estimation " . $story->getId(); ?>"> <?php echo $story->getEstimation(); ?></td>
                    <td class="<?php echo "changedTasksTotal taskTotal " . $story->getId(); ?>"> <?php echo $taskService->getTaskTotalEffortByStoryId($story->getId()) ?></td>
                    <td class="<?php echo "changedDate date " . $story->getId(); ?>"> <?php echo $story->getDateAdded(); ?></td>
                    <td class="<?php echo "estimatedEndDate EndDate " . $story->getId(); ?>"> <?php echo $story->getEstimatedEndDate(); ?></td>
                    <td class="<?php echo "changedStatus status " . $story->getId(); ?>"> <?php echo $status ?></td>
                    <td class="<?php echo "changedAcceptedDate acceptedDate " . $story->getId(); ?>"> <?php echo $story->getAcceptedDate(); ?></td>
                    <td class="<?php echo "edit edit " . $story->getId(); ?>"><?php echo image_tag('b_edit.png', 'id=editBtn') ?></td>
                    <td class="close"><a class="confirmLink" href="<?php echo url_for("project/deleteStory?id={$story->getId()}&projectId={$projectId}&projectName={$projectName}"); ?>"><?php echo image_tag('b_drop.png'); ?></a></td>
    
                </tr>
               
            <?php endforeach; ?>
            <?php else: ?>
                <!-- do not delete the space between <td> tags -->
                <tr><td> </td><td></td><td></td><td></td><td></td><td></td></tr>
            <?php endif; ?>
            </table>
        </div>
        <div class="addButton">
            <form action="<?php echo url_for("project/addStory?id={$projectId}&projectName={$projectName}"); ?>" method="GET">
                <input type="submit" value="<?php echo __('Add') ?>" />
            </form>
        </div>
        <div class="viewWeeklyProgressButton">
            <form action="<?php echo url_for("project/viewWeeklyProgress?projectId={$projectId}&projectName={$projectName}"); ?>" method="GET">
                <input type="submit" value="<?php echo "View Weekly Progress" ?>"/>
            </form>
    </div>
    <div id="moreField">"Effort" and "Task Total" are in Engineering Hours</div>
</div>


<div  id="dialog" title="Confirmation Required">
    <h1><?php echo __('Story will be deleted?')?></h1>
    <label for="name"><?php echo __('Deleted date')?></label>
    <input type="text" name="deletedDate" id="deletedDate" onclick="datepicker()" value="<?php echo Date('Y-m-d')?>" />

</div>
<?php echo javascript_include_tag('viewStories'); ?>
