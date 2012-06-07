<?php echo stylesheet_tag('viewTasks') ?>
<?php use_helper('Pagination'); ?>

<script type="text/javascript">
    var saveImgUrl = '<?php echo image_tag('b_save.gif', 'id=saveBtn') ?>';
    var editImgUrl = '<?php echo image_tag('b_edit.png', 'id=editBtn') ?>';
    var updateTaskUrl = '<?php echo url_for('project/editTask')?>';
    var projectId = '<?php echo $project->getId()?>';
    var statusArray = new Array();
    <?php 
    $tasks = $taskService->getAllTaskStatusArray();
    foreach ($tasks as $key => $val) {?>
        statusArray[<?php echo $key?>] = '<?php echo $val?>';
    <?php } ?>
</script>

<div class="Task">
    <div class="heading">
        <h3> <?php echo link_to(__('Projects'),'project/viewProjects'); ?> > <?php echo link_to(__($project->getName()),"project/viewProjectDetails?projectName={$project->getName()}&projectId={$project->getId()}"); ?> > <?php echo link_to(__('Stories'),"project/viewStories?projectName={$project->getName()}&id={$project->getId()}"); ?> > <?php echo __($story->getName()); ?> > <?php echo __("Tasks"); ?></h3> 
        <span id="message"><?php echo $sf_user->getFlash('addStory') ?></span>
        <span id="noRecordMessage"><?php if(isset($noRecordMessage)) echo $noRecordMessage; ?></span>
    </div>

    <div id="mainErrorDiv"></div>
    <div class="TaskShowForm">
        <table class="tableContent">
            <tr>
                <th class="taskName"><?php echo __('Task Name') ?></th>
                <th class="taskEffort"><?php echo __('Effort')?></th>
                <th class="taskStatus"><?php echo 'Status' ?></th>
                <th class="taskOwnedBy"><?php echo __('Owned By') ?></th>
                <th class="taskDescription"><?php echo __('Description');?></th>
                <th class="taskActions" colspan="2"><?php echo __('Actions') ?></th>
            </tr>
            <?php if(count($taskList) != 0): ?>
            <?php foreach ($taskList as $task): ?>
                <tr id="row">
                    <td class="<?php echo "changedName name " . $task->getId(); ?>"><?php echo $task->getName(); ?></td>
                    <td class="<?php echo "changedEffort effort " . $task->getId(); ?>"><?php echo $task->getEffort(); ?></td>
                    <td class="<?php echo "changedStatus status " . $task->getId(); ?> <?php echo strtolower($taskService->getStatus($task->getStatus())); ?>"> <?php echo $taskService->getStatus($task->getStatus()); ?></td>
                    <td class="<?php echo "changedOwnedBy ownedBy " . $task->getId(); ?>"> <?php echo $task->getOwnedBy(); ?></td>
                    <td class="<?php echo "changedDescription description " . $task->getId(); ?>"> <?php echo $task->getDescription(); ?></td>
                    <td class="<?php echo "edit " . $task->getId(); ?>"><?php echo image_tag('b_edit.png', 'id=editBtn') ?></td>
                    <td class="close"><a class="confirmLink" href="<?php echo url_for("project/deleteTask?id={$task->getId()}&storyId={$story->getId()}&projectId={$project->getId()}"); ?>"><?php echo image_tag('b_drop.png'); ?></a></td>
                </tr>
               
            <?php endforeach; ?>
            <?php else: ?>
                <!-- do not delete the space between <td> tags -->
                <tr><td> </td><td></td><td></td><td></td><td></td><td></td></tr>
            <?php endif; ?>
            </table>
        </div>
        <div class="addButton">
            <form action="<?php echo url_for("project/addTask?storyId={$story->getId()}"); ?>" method="GET">
                <input type="submit" value="<?php echo __('Add') ?>" />
            </form>
        </div>
</div>


<div  id="dialog" title="Confirmation Required">
    <h1><?php echo __('Task will be deleted?')?></h1>
</div>
<?php echo javascript_include_tag('viewTasks'); ?>