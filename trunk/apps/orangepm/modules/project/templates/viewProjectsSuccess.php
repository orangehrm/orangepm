<?php echo stylesheet_tag('viewProjects'); ?>
<?php use_helper('Pagination'); ?>

<script type="text/javascript">
    var saveImgUrl = '<?php echo image_tag('b_save.gif', 'id=saveBtn') ?>';
    var editImgUrl = '<?php echo image_tag('b_edit.png', 'id=editBtn') ?>';
    var linkUrl = "<?php echo url_for('project/editProject') ?>";
    var projectAdmins = new Array;
    
    <?php
            echo "projectAdmins[0] = new Array(2);\n";
            echo "projectAdmins[0][0] = 0;\n";
            echo "projectAdmins[0][1] = '--Select--';\n";
        $i = 1;
        foreach ($projectAdmins as $key => $value) {
            echo "projectAdmins[$i] = new Array(2);\n";
            echo "projectAdmins[$i][0] = $key;\n";
            echo "projectAdmins[$i][1] = '$value';\n";
            $i++;
        }  
    
    ?>       
</script>

<div class="Project">
    
    <div class="heading">
        <h3>
            <?php echo __('Projects'); ?>
        </h3>
        <span id="message"><?php echo $sf_user->getFlash('addProject') ?></span>
        <span id="noRecordMessage"><?php if(isset($noRecordMessage)) echo $noRecordMessage; ?></span>
    </div>
    
    <div id="mainErrorDiv"></div>
    
    <table class="tableContent" >
      <!--   <tr><td class="pageNav" colspan="3"><?php // echo pager_navigation($pager, url_for('project/viewProjects')) ?></td></tr>-->
        <tr>
<!--            <th> <?php //echo __('Id') ?></th>-->
            <th class="projectHeader"> <?php echo __('Project Name'); ?> </th>
            <th> <?php echo __('Started<br>Date'); ?> </th>
            <th> <?php echo __('End Date'); ?> </th>
            <th> <?php echo __('Status'); ?> </th>
            <?php if($sf_user->hasCredential('superAdmin')): ?>
                <th> <?php echo __('Project Admin'); ?> </th>
            <?php endif; ?>
            <th colspan="2"><?php echo __('Actions') ?></th>

            <?php $alt = '1' ?>
            <?php if(count($projects) != 0): ?>
            <?php foreach ($projects as $project): ?>
                <?php
                if ($alt == 1) {
                    echo '<tr class="alt">';
                    $alt = 0;
                } else {
                    echo '<tr>';
                    $alt = 1;
                }
                ?>

            <td class="<?php echo "changedName name " . $project->getId(); ?>" ><a class="storyLink" href="<?php echo url_for("project/viewProjectDetails") . "?projectId={$project->getId()}"; ?>" > <?php echo $project->getName(); ?></a></td>
            <td class="<?php echo "changedStartDate startDate " . $project->getId(); ?>" ><?php echo $project->getStartDate(); ?></td>
            <td class="<?php echo "changedEndDate endDate " . $project->getId(); ?>" ><?php echo $project->getEndDate(); ?></td>
            <td class="<?php echo "changedProjectStatus projectStatus " . $project->getId(); ?>" ><?php echo $project->getProjectStatus()->getName(); ?></td>
            <?php if($sf_user->hasCredential('superAdmin')): ?>
                <td class="<?php echo "changedProjectAdmin projectAdmin " . $project->getUserId(); ?>" ><?php if($project->getUser()->getIsActive() != 0) {echo $project->getUser()->getFirstName()." ".$project->getUser()->getLastName();} ?></td>
            <?php endif; ?>
            <td class="<?php echo "edit edit " . $project->getId(); ?>"><?php echo image_tag('b_edit.png', 'id=editBtn'); ?></td>
            <td class="<?php echo "not close " . $project->getId(); ?>"><a class="confirmLink" href="<?php echo url_for("project/deleteProject?id={$project->getId()}"); ?>" ><?php echo image_tag('b_drop.png'); ?></a></td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
            <!-- do not delete the space between <td> tags -->
        <tr><td> </td><td></td><td></td><td></td><td></td><?php if($sf_user->hasCredential('superAdmin')): ?><td></td><?php endif; ?></tr>
        <?php endif; ?>
    </table>
    <?php if (!$sf_user->hasCredential('projectMember')): ?>
        <div class="addButton">        
                <form id="addForm" action="<?php echo url_for('project/addProject') ?>" method="get">
                    <input type="submit" value="<?php echo __('Add') ?>" id="addProject" />
                </form>               
        </div>
    <?php endif; ?>
    
    <div class="searchButton">        
        <?php echo $projectSearchForm ?>
    </div>

</div>

<div id="dialog" title="Confirmation Required">
    Project Will Be Deleted?
</div>

<?php echo javascript_include_tag('viewProjects'); ?>
