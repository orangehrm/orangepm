<?php echo stylesheet_tag('viewProjectDetails') ?>
<?php echo stylesheet_tag('viewStories') ?>
<div class="Project">
    <div class="heading">
        <h2> <?php echo __('Projects'); ?> </h2>
        
    </div>
    <div id="mainErrorDiv"><span id="noRecordMessage"><?php if(isset($noRecordMessage)) echo $noRecordMessage; ?></span></div>
    <div class="searchButtonOnly">        
        <?php echo $projectSearchForm ?>
    </div>
    <br/>
    <?php foreach ($projectProgressList  as $single): ?> 
    <?php  $projectService = new ProjectService();?>
    <?php  $projectDetailList = $projectService->getProjectStatus($single['project']->getId());?>
    <?php  $projectMembersList = $projectService->getUsersByProjectId($single['project']->getId());?>
    
    <div class="showForm">
        <div class="headlineField"><?php echo $single['project']->getName(); ?></div>
        <div class="formField">
            <div class="form_devision_heading">Details</div>
            <div class="projectDatail" id="projectDeatial">    
            <table class='showTable'>
                    <tbody>
                        <tr>
                            <td><label for="project_projectAdmin"><?php echo __('Project Admin : ') ?></label></td> <td><?php echo $single['project']->getUser()->getFirstName().' '.$single['project']->getUser()->getLastName(); ?> </td>
                        </tr>
                        <tr>
                            <td><label for="project_startDate"><?php echo __('Start Date : ') ?></label></td> <td><?php echo $single['project']->getStartDate(); ?> </td>
                        </tr>
                        <tr>
                            <td><label for="project_endDate"><?php echo __('End Date : ') ?></label></td> <td><?php echo $single['project']->getEndDate(); ?> </td>
                        </tr>
                        <tr>
                            <td><label for="project_members"><?php echo __('Project Members : ') ?></label></td> <td><?php  echo implode(',', $projectMembersList); ?> </td>
                        </tr>
                   
                    </tbody>
                </table>
            </div>
        </div>        
    </div>
    <div class="showForm">
        <div class='formField'>
            <div class="form_devision_heading">Status</div>
            <div class='division_content'>
                <table class='showTable'>
                    <tbody>
                        <tr>
                            <th>Project Status</th>
                            <th class="percentageColumn">Percentage</th>
                            <th class="effortColumn"> Effort (<?php echo $single['EstCount']; ?>)</th>
                        </tr>
                        <?php foreach ($single as $status => $effort): ?>
                        <?php if(($status!='project') && ($status!='EstCount') ) { ?>
                        <tr>
                           <td><?php echo __($status);?></td>
                           <td><div id="progressbar_<?php echo str_replace(" ","_",strtolower($status));?>"class="progressbar" value="<?php echo $projectService->getPecentage($single["$status"], $single['EstCount'])?>"></div></td>
                           <td><?php echo $effort?></td>
                        </tr>
                        <?php } ?>                        
                        <?php endforeach; ?>
                    </tbody>
                </table>
                 <div class='project_Details' id="projectFacts">
            <table class='showTable'>
                    <tbody>
                        <tr>
                            <td><label for="Avg_Weekly_Velocity"><?php echo __('Avg Weekly Velocity : ') ?></label></td> <td><?php echo $projectDetailList[0] ?> </td>
                        </tr>
                        <tr>
                            <td><label for="Required_Weekly_Velocity"><?php echo __('Required Weekly Velocity : ') ?></label></td> <td><?php echo $projectDetailList[1] ?> </td>
                        </tr>
                        <tr>
                            <td><label for="Last_week_Velocity"><?php echo __('Last week Velocity : ') ?></label></td> <td><?php echo $projectDetailList[2] ?> </td>
                        </tr>
                        <tr>
                            <td><label for="Variance_based_on_Known_last_known_velocity"><?php echo __('Variance based on Known last known velocity : ') ?></label></td> <td><?php echo $projectDetailList[3] ?></td>
                        </tr>
                        <tr>
                            <td><label for="Variance_based_on_avg_weekly_velocity"><?php echo __('Variance based on avg weekly velocity : ') ?></label></td> <td><?php echo $projectDetailList[4] ?></td>
                        </tr>
                        <tr>
                            <td><label for="Estimated_total_effort_used_for_costing"><?php echo __('Estimated total effort used for costing : ') ?></label></td> <td><?php echo $projectDetailList[5] ?></td>
                        </tr>
                        <tr>
                            <td><label for="Current_total_effort"><?php echo __('Current total effort (based on time sheets) : ') ?></label></td> <td><?php echo $single['project']->getCurrentEffort(); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </div>
        </div>        
    </div>
    <br/>
    <?php endforeach; ?>
</div>
<?php echo javascript_include_tag('viewProjectDetails'); ?>
<?php echo javascript_include_tag('viewStories'); ?>
<?php echo javascript_include_tag('viewAllProjectDetails'); ?>