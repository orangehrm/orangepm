<?php echo stylesheet_tag('viewStories') ?>
<?php use_helper('Pagination'); ?>

<script type="text/javascript">
    var saveImgUrl = '<?php echo image_tag('b_save.gif', 'id=saveBtn') ?>';
    var editImgUrl = '<?php echo image_tag('b_edit.png', 'id=editBtn') ?>';
    var linkUrl = "<?php echo url_for('project/editStory') ?>";
</script>

<?php echo javascript_include_tag('viewStories'); ?>

<div class="Project">
    <div class="heading">
        <h4> <?php echo __('Projects'); ?> > <?php echo $projectName; ?> > <?php echo __('Stories'); ?> </h4>
        <span id="message"><?php if (isset($message))
    echo __('The Story is added successfully') ?></span>
    </div>

    
    <table class="tableContent">
        <tr><td class="pageNav" colspan="8"><?php echo pager_navigation($storyList, url_for("project/viewStories") . "?id={$projectId}&projectName={$projectName}") ?></td></tr>
        <tr>
            <th><?php echo __('Story Id') ?></th>
            <th><?php echo __('Story Name') ?></th>
            <th><?php echo __('Estimated Effort'); ?> <br> <?php echo __('(Engineering Hours)'); ?></th>
            <th><?php echo __('Date Added') ?></th>
            <th><?php echo 'Status' ?></th>
            <th><?php echo 'Accepted Date' ?></th>
            <th colspan="2"><?php echo __('Actions') ?></th>
        </tr>

        <?php foreach ($storyList->getResults() as $story): ?>
            <tr id="row">
                <td class="<?php echo "not id " . $story->getId(); ?>"><?php echo $story->getId(); ?></td>
                <td class="<?php echo "changedName name " . $story->getId(); ?>"><?php echo $story->getName(); ?></td>
                <td class="<?php echo "changedEstimation estimation " . $story->getId(); ?>"> <?php echo $story->getEstimation(); ?></td>
                <td class="<?php echo "changedDate date " . $story->getId(); ?>"> <?php echo $story->getDateAdded(); ?></td>
                <td class="<?php echo "changedStatus status " . $story->getId(); ?>"> <?php echo $story->getStatus(); ?></td>
                <td class="<?php echo "changedAcceptedDate acceptedDate " . $story->getId(); ?>"> <?php echo $story->getAcceptedDate(); ?></td>
                <td class="<?php echo "edit edit " . $story->getId(); ?>"><?php echo image_tag('b_edit.png', 'id=editBtn') ?></td>
                <td class="close"><a class="confirmLink" href="<?php echo url_for("project/deleteStory?id={$story->getId()}&projectId={$projectId}&projectName={$projectName}"); ?>"><?php echo image_tag('b_drop.png'); ?></a></td>

            </tr>
        <?php endforeach; ?>
        </table>
        <div class="addButton">
            <form action="<?php echo url_for("project/addStory?id={$projectId}&projectName={$projectName}"); ?>" method="GET">
                <input type="submit" value="<?php echo __('Add') ?>" />
        </form>
    </div>
</div>


<div  id="dialog" title="Confirmation Required">
    Story Will Be Deleted??
</div>

