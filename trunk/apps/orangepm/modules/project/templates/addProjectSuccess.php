<?php echo stylesheet_tag('addProject') ?>
<?php use_helper('Pagination'); ?>
<?php echo javascript_include_tag('addProject'); ?>

<div class="Project">

    <div class="heading">
        <h4> <?php echo __('Projects > Add Project'); ?> </h4>
    </div>

    <div class="addForm">
        <div class="headlineField"><?php echo __('Add Project') ?></div>
        <div class="formField">
            <form id="form1" action="<?php echo url_for('project/addProject') ?>" method="POST">

                <table>
                    <?php echo $projectForm ?>

                    <tr>
                        <td colspan="2">
                            <input class="formButton" type="submit" value="<?php echo __('Save') ?>" id="saveButton" />
                            <input class="formButton" type="button" id="cancel" value="<?php echo __('Cancel') ?>" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>


    <table class="tableContent" >
        <tr><td class="pageNav" colspan="4"><?php echo pager_navigation($pager, url_for('project/addProject')) ?></td></tr>
        <tr><th><?php echo __('Id') ?></th>
            <th><?php echo __('Project Name') ?></th>

            <?php foreach ($pager->getResults() as $project): ?>
                    <tr>
                        <td> <?php echo $project->getId(); ?></td>
                        <td> <?php echo $project->getName(); ?></td>
                    </tr>
        <?php endforeach; ?>
    </table>
</div>
