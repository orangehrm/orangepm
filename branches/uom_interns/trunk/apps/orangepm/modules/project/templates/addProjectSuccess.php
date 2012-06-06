<?php echo stylesheet_tag('addProject') ?>
<?php use_helper('Pagination'); ?>

<script type="text/javascript">
    var lang_nameRequired = "<?php echo __('Project name is required');?>";
    var lang_startDateRequired = "<?php echo __('Development Start date is required');?>";
</script>

<div class="Project">

    <div class="heading">
        <h3> <?php echo link_to(__('Projects'), 'project/viewProjects') ?> > <?php echo __('Add Project'); ?> </h3>
    </div>

    <div class="addForm">
        <div class="headlineField"><?php echo __('Add Project') ?></div>
        <div class="formField">
            <form id="addProjectForm" action="<?php echo url_for('project/addProject') ?>" method="POST">
                <div><?php echo $projectForm['name']->renderLabel() ?><?php echo $projectForm['name']->render() ?><?php echo $projectForm['name']->renderError() ?><br class="clear" /></div>
                <div><?php echo $projectForm['startDate']->renderLabel() ?><?php echo $projectForm['startDate']->render() ?><?php echo $projectForm['startDate']->renderError() ?><br class="clear" /></div>
                <div><?php echo $projectForm['endDate']->renderLabel() ?><?php echo $projectForm['endDate']->render() ?><?php echo $projectForm['endDate']->renderError() ?><br class="clear" /></div>
                
                <?php if($sf_user->hasCredential('superAdmin')): ?>
                <div><?php echo $projectForm['projectAdmin']->renderLabel() ?><?php echo $projectForm['projectAdmin']->render() ?><?php echo $projectForm['projectAdmin']->renderError() ?></div>
                <?php endif; ?>
                <div><?php echo $projectForm['status']->renderLabel() ?><?php echo $projectForm['status']->render() ?><?php echo $projectForm['status']->renderError() ?></div>
                <div><?php echo $projectForm['description']->renderLabel() ?><?php echo $projectForm['description']->render() ?></div>
                <div>
                    <input class="formButton" type="submit" value="<?php echo __('Save') ?>" id="saveButton" />
                    &nbsp;&nbsp;&nbsp;
                    <input class="formButton" type="button" id="cancel" value="<?php echo __('Cancel') ?>" />
                </div>
                <?php echo $projectForm->renderHiddenFields(); ?>
            </form>
        </div>
        <div id="requiredField">Field marked with an asterisk <span class="mandatoryStar">*</span> is required.</div>
    </div>


    <table class="tableContent" >

        <tr>
            <th><?php echo __('Project Name') ?></th>
            <th><?php echo __('Status') ?></th>
            <?php if($sf_user->hasCredential('superAdmin')): ?>
                <th> <?php echo __('Project Admin'); ?> </th>
            <?php endif; ?>
        </tr>
        <?php if (count($projects) != 0): ?>
            <?php foreach ($projects as $project): ?>
                <tr>                        
                    <td> <?php echo $project->getName(); ?></td>
                    <td> <?php echo $project->getProjectStatus()->getName(); ?></td>
                    <?php if($sf_user->hasCredential('superAdmin')): ?>
                        <td> <?php if($project->getUser()->getIsActive() != 0) { echo $project->getUser()->getFirstName() . ' ' . $project->getUser()->getLastName();} ?></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
        <!-- do not delete the space between <td> tags -->
            <tr><td> </td><td></td><?php if ($sf_user->hasCredential('superAdmin')): ?><td></td><?php endif; ?></tr>
        <?php endif; ?>
    </table>
</div>

<?php echo javascript_include_tag('addProject'); ?>
