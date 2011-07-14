<?php echo stylesheet_tag('addProject') ?>
<?php use_helper('Pagination'); ?>

<div class="Project">

    <div class="heading">
        <h3> <?php echo link_to(__('Projects'), 'project/viewProjects') ?> > <?php echo __('Add Project'); ?> </h3>
    </div>

    <div class="addForm">
        <div class="headlineField"><?php echo __('Add Project') ?></div>
        <div class="formField">
            <form id="addProjectForm" action="<?php echo url_for('project/addProject') ?>" method="POST">
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

        <tr>
            <th><?php echo __('Project Name') ?></th>
            <th><?php echo __('Status') ?></th>
            <th><?php echo __('Project Admin') ?></th>
        </tr>
        <?php if(count($projects) != 0): ?>
        <?php foreach ($projects as $project): ?>
            <tr>                        
                <td> <?php echo $project->getName(); ?></td>
                <td> <?php echo $project->getProjectStatus()->getName(); ?></td>
                <td> <?php echo $project->getUser()->getFirstName() . ' ' . $project->getUser()->getLastName(); ?></td>
            </tr>
        <?php endforeach; ?>
        <?php else: ?>
            <!-- do not delete the space between <td> tags -->
        <tr><td> </td><td></td><?php if($sf_user->hasCredential('superAdmin')): ?><td></td><?php endif; ?></tr>
        <?php endif; ?>
    </table>
</div>

<?php echo javascript_include_tag('addProject'); ?>
