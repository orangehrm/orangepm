<?php echo stylesheet_tag('viewStories') ?>
<?php use_helper('Pagination'); ?>

<script type="text/javascript">
    var saveImgUrl = '<?php echo image_tag('b_save.gif', 'id=saveBtn') ?>';
    var editImgUrl = '<?php echo image_tag('b_edit.png', 'id=editBtn') ?>';
    var linkUrl = "<?php echo url_for('project/editStory') ?>";
</script>

<div class="Project">
    <div class="heading">
        <h3> <?php echo link_to(__('Projects'),'project/viewProjects'); ?> > <?php echo link_to(__($projectName),"project/viewProjectDetails?projectName={$projectName}&projectId={$id}"); ?> > <?php echo __('Stories'); ?> </h3>
        <span id="message"><?php echo $sf_user->getFlash('addStory') ?></span>
        <span id="noRecordMessage"><?php if(isset($noRecordMessage)) echo $noRecordMessage; ?></span>
    </div>

    <div id="mainErrorDiv"></div>
    <div class="StoryShowForm">
        <table class="tableContent">
            <tr><td class="pageNav" colspan="7"><?php echo pager_navigation($storyList, url_for("project/viewStories") . "?id={$projectId}&projectName={$projectName}") ?></td></tr>
            <tr>
                <th><?php echo __('Story Name') ?></th>
                <th><?php echo __('Effort');?><?php echo "<span class='moreStar'>*</span>"?></th>
                <th><?php echo __('Date Added') ?></th>
                <th><?php echo 'Status' ?></th>
                <th><?php echo 'Accepted Date' ?></th>
                <th colspan="2"><?php echo __('Actions') ?></th>
            </tr>
            <?php if(count($storyList) != 0): ?>
            <?php foreach ($storyList->getResults() as $story): ?>
                <tr id="row">
                    <td class="<?php echo "changedName name " . $story->getId(); ?>"><?php echo $story->getName(); ?></td>
                    <td class="<?php echo "changedEstimation estimation " . $story->getId(); ?>"> <?php echo $story->getEstimation(); ?></td>
                    <td class="<?php echo "changedDate date " . $story->getId(); ?>"> <?php echo $story->getDateAdded(); ?></td>
                    <td class="<?php echo "changedStatus status " . $story->getId(); ?>"> <?php echo $story->getStatus(); ?></td>
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
            <form action="<?php echo url_for("project/viewWeeklyProgress?projectName={$projectName}&projectId={$projectId}"); ?>" method="GET">
                <input type="submit" value="<?php echo "View Weekly Progress" ?>"/>
        </form>
    </div>
    <div id="moreField">Estimated Effort (Engineering Hours) <span class="moreStar">*</span></div>
</div>


<div  id="dialog" title="Confirmation Required">
    <h1><?php echo __('Story will be deleted?')?></h1>
    <label for="name"><?php echo __('Deleted date')?></label>
    <input type="text" name="deletedDate" id="deletedDate" onclick="datepicker()" value="<?php echo Date('Y-m-d')?>" />

</div>
<?php echo javascript_include_tag('viewStories'); ?>
