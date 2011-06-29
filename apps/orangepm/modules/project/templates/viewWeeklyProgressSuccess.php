<?php echo stylesheet_tag('viewWeeklyProgress') ?>


<div class="heading">
    <h3> <?php echo link_to(__('Projects'), 'project/viewProjects'); ?> > <a class="storyLink" href="<?php echo url_for("project/viewStories?id={$projectId}&projectName={$projectName}"); ?>" ><?php echo $projectName; ?></a> > <?php echo __('Weekly Progress'); ?> </h3>
</div>





<table class="tableContent">


    <tr><td class="pageNav" colspan="6"><?php //echo pager_navigation($storyList, url_for("project/viewStories") . "?id={$projectId}&projectName={$projectName}")         ?></td></tr>
    <tr>
        <th><?php echo __('Week Starting') ?></th>
        <th><?php echo __('Total Estimated Effort') ?></th>
        <th><?php echo __('Weekly Velocity'); ?></th>
        <th><?php echo __('Total Work Accepted') ?></th>
        <th colspan="2"><?php echo __('Burn Down') ?></th>
    </tr>
    
    <?php foreach ($weekStartingDate as $array1): ?>
            <tr><td> <?php echo $array1 ?></td> <td> <?php echo $totalEstimation[$array1] ?></td><td> <?php echo $weeklyVelocity[$array1] ?></td><td><?php echo $workCompleted[$array1] ?></td><td><?php echo $burnDownArray[$array1] ?></td></tr>
    <?php endforeach; ?>
    
</table>