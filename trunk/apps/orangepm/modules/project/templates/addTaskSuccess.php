<?php echo stylesheet_tag('addTask') ?>
<?php use_helper('Pagination'); ?>

<script type="text/javascript">
    var lang_nameRequired = "<?php echo __('Task name is required');?>";
    var lang_effortValid = "<?php echo __('Task effort is inValid');?>";
    var storyUrl = "<?php echo url_for("project/viewTasks?storyId={$story->getId()}")?>";
</script>

<div class="Task">
    <div class="heading">
        <h3> <?php echo link_to(__('Projects'),'project/viewProjects'); ?> > <?php echo link_to(__($project->getName()),"project/viewProjectDetails?projectName={$project->getName()}&projectId={$project->getId()}"); ?> >
            <?php echo link_to(__('Stories'),"project/viewStories?projectName={$project->getName()}&id={$project->getId()}"); ?> > <?php echo link_to(__($story->getName()), "project/viewTasks?storyId={$story->getId()}");?> > <?php echo __('Add Task'); ?></h3>
        <span id="message"><?php echo $sf_user->getFlash('addStory') ?></span>
        <span id="noRecordMessage"><?php if(isset($noRecordMessage)) echo $noRecordMessage; ?></span>
    </div>
    
    <div class="addForm">
        <div class="headlineField"><?php echo __('Add Task') ?></div>
        <div class="formField">
            <form id="addTaskForm" action="<?php echo url_for("project/addTask?storyId={$story->getId()}"); ?>" method="post">
                <div><?php echo $taskForm['name']->renderLabel() ?><?php echo $taskForm['name']->render() ?><?php echo $taskForm['name']->renderError() ?><br class="clear"/></div>
                <div><?php echo $taskForm['effort']->renderLabel() ?><?php echo $taskForm['effort']->render() ?><?php echo $taskForm['effort']->renderError() ?><br class="clear"/></div>
                <div><?php echo $taskForm['status']->renderLabel() ?><?php echo $taskForm['status']->render() ?><?php echo $taskForm['status']->renderError() ?></div>
                <div><?php echo $taskForm['ownedBy']->renderLabel() ?><?php echo $taskForm['ownedBy']->render() ?><?php echo $taskForm['ownedBy']->renderError() ?></div>
                <div><?php echo $taskForm['description']->renderLabel() ?><?php echo $taskForm['description']->render() ?></div>
                <div>
                <?php echo $taskForm->renderHiddenFields(); ?>
                    <input class="formButton" type="submit" value="<?php echo __('Save') ?>" id="saveButton" />
                    &nbsp;&nbsp;&nbsp;
                    <input class="formButton" type="button" id="cancel" value="<?php echo __('Cancel') ?>" />
                </div>
                
            </form>
        </div>
        <div id="requiredField">Field marked with an asterisk <span class="mandatoryStar">*</span> is required.</div>
    </div>
    <table class="tableContent">
            <tr>
                <th class="taskName"><?php echo __('Task Name') ?></th>
                <th class="taskEffort"><?php echo __('Effort')?></th>
                <th class="taskStatus"><?php echo 'Status' ?></th>
                <th class="taskOwnedBy"><?php echo __('Owned By') ?></th>
                <th class="taskDescription"><?php echo __('Description');?></th>
            </tr>
            <?php if(count($taskList) != 0): ?>
            <?php foreach ($taskList as $task): ?>
                <tr id="row">
                    <td class="<?php echo "changedName name " . $task->getId(); ?>"><?php echo $task->getName(); ?></td>
                    <td class="<?php echo "changedEffort effort " . $task->getId(); ?>"><?php echo $task->getEffort(); ?></td>
                    <td class="<?php echo "changedStatus status " . $task->getId(); ?> <?php echo strtolower($taskService->getStatus($task->getStatus())); ?>"> <?php echo $taskService->getStatus($task->getStatus()); ?></td>
                    <td class="<?php echo "changedOwnedBy ownedBy " . $task->getId(); ?>"> <?php echo $task->getOwnedBy(); ?></td>
                    <td class="<?php echo "changedDescription description " . $task->getId(); ?>"> <?php echo $task->getDescription(); ?></td>
                </tr>
            <?php endforeach; ?>
            <?php else: ?>
                <!-- do not delete the space between <td> tags -->
                <tr><td> </td><td></td><td></td><td></td><td></td></tr>
            <?php endif; ?>
            </table>
</div>

<?php echo javascript_include_tag('addTask'); ?>