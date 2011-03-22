<?php echo stylesheet_tag('viewProjects'); ?>
<?php use_helper('Pagination'); ?>

<script type="text/javascript">
    var saveImgUrl = '<?php echo image_tag('b_save.gif', 'id=saveBtn') ?>';
    var editImgUrl = '<?php echo image_tag('b_edit.png', 'id=editBtn') ?>';
    var linkUrl = "<?php echo url_for('project/editProject') ?>";
</script>

<?php echo javascript_include_tag('viewProjects'); ?>

<div class="Project">
    <div class="addButton">
        <form id="form1" action="<?php echo url_for('project/addProject') ?>" method="get">

            <table>
                <?php echo $projectForm ?>

                <tr>
                    <td colspan="2">
                        <input type="submit" value="<?php echo __('Add') ?>" id="addProject" />
                    </td>
                </tr>
            </table>
        </form>
    </div>


    <table class="tableContent" >
        <tr><td class="pageNav" colspan="4"><?php echo pager_navigation($pager, url_for('project/viewProjects')) ?></td></tr>
        <tr><th> <?php echo __('Id') ?></th>
            <th> <?php echo __('Project Name'); ?> </th>
            <th colspan="2"><?php echo __('Actions') ?></th>

            <?php $alt = '1' ?>
            <?php //foreach ($projectList as $project): ?>
            <?php foreach ($pager->getResults() as $project): ?>
            <?php
                    if ($alt == 1) {
                        echo '<tr class="alt">';
                        $alt = 0;
                    } else {
                        echo '<tr>';
                        $alt = 1;
                    }
            ?>
                    <td class="<?php echo "not id " . $project->getId(); ?>"><?php echo $project->getId(); ?></td>
                    <td class="<?php echo "change name " . $project->getId(); ?>" ><a class="storyLink" href="<?php echo url_for("project/viewStories?id={$project->getId()}"); ?>" > <?php echo $project->getName(); ?></a></td>
                    <td class="<?php echo "edit edit " . $project->getId(); ?>"><?php echo image_tag('b_edit.png', 'id=editBtn'); ?></td>
                    <td class="<?php echo "not close " . $project->getId(); ?>"><a class="confirmLink" href="<?php echo url_for("project/deleteProject?id={$project->getId()}"); ?>" ><?php echo image_tag('b_drop.png'); ?></a></td>
                </tr>
        <?php endforeach; ?>

    </table>
</div>

<div id="dialog" title="Confirmation Required">
    Project Will Be Deleted??
</div>

