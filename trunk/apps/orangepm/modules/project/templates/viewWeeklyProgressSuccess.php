<?php echo stylesheet_tag('viewWeeklyProgress') ?>

<div class="heading">
    <h3> <?php echo link_to(__('Projects'), 'project/viewProjects'); ?> > <a class="storyLink" href="<?php echo url_for("project/viewProjectDetails?projectId={$projectId}&projectName={$projectName}"); ?>" ><?php echo $projectName; ?></a> > <a class="storyLink" href="<?php echo url_for("project/viewStories?id={$projectId}&projectName={$projectName}"); ?>" >Stories</a> > <?php echo __('Weekly Progress'); ?> </h3>
    <span id="noRecordMessage"><?php if(isset($noRecordMessage)) echo $noRecordMessage; ?></span>
</div>

<table class="tableContent">


    <tr><td class="pageNav" colspan="10"><?php //echo pager_navigation($storyList, url_for("project/viewStories") . "?id={$projectId}&projectName={$projectName}")         ?></td></tr>
    <tr>
        <th><?php echo __('Week Starting') ?></th>
        <th><?php echo __('Total<br>Estimated<br>Effort') ?></th>
        <th><?php echo __('Total<br>Work<br>Accepted') ?></th>
        <th><?php echo __('Burn Down') ?></th>
        <th><?php echo __('Backlog') ?></th>
        <th><?php echo __('Development') ?></th>
        <th><?php echo __('Development<br>Completed') ?></th>
        <th><?php echo __('Testing') ?></th>
        <th><?php echo __('Rework') ?></th>
        <th><?php echo __('Velocity') ?></th>
    </tr>
    
    <?php if(count($weekStartingDate) != 0): ?>
    <?php foreach ($weekStartingDate as $array1): ?>
            <tr>
                <td> <?php echo $array1 ?></td> 
                <td> <?php echo $totalEstimation[$array1] ?></td>
                <td><?php echo $workCompleted[$array1] ?></td>
                <td><?php echo $burnDownArray[$array1] ?></td>
                <td><?php echo $statusChangeArray[$array1]['Pending'] ?></td>
                <td><?php echo $statusChangeArray[$array1]['Development'] + $statusChangeArray[$array1]['Design']?></td>
                <td><?php echo $statusChangeArray[$array1]['Development Completed'] ?></td>
                <td><?php echo $statusChangeArray[$array1]['Testing'] ?></td>
                <td><?php echo $statusChangeArray[$array1]['Rework'] ?></td>
                <td><?php echo $statusChangeArray[$array1]['Accepted'] ?></td>
            </tr>
    <?php endforeach; ?>
    <?php else: ?>
        <!-- do not delete the space between <td> tags -->
        <tr><td> </td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <?php endif; ?>
    
</table>