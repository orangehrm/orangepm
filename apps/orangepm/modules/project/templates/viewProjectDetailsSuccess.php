<?php echo stylesheet_tag('viewProjectDetails') ?>
<?php echo stylesheet_tag('viewStories') ?>

<?php use_helper('Pagination'); ?>
<script type="text/javascript">
    var lang_nameRequired = "<?php echo __('Project name is required'); ?>";
    var saveImgUrl = '<?php echo image_tag('b_save.gif', 'id=saveBtn') ?>';
    var editImgUrl = '<?php echo image_tag('b_edit.png', 'id=editBtn') ?>';
    var delImgUrl = '<?php echo image_tag('b_drop.png') ?>';
    var linkUrl = "<?php echo url_for('project/editStory') ?>";
    var loginUrl = '<?php echo url_for('project/login') ?>';
    var projectViewUrl = "<?php echo url_for("project/viewProjectDetails?projectId={$project->getId()}&projectName={$project->getName()}") ?>";
    var statusChanged = false;
    var userId = "<?php echo $userId; ?>";
    var userName = "<?php echo $userName; ?>";
    var projectId = "<?php echo $projectId; ?>";
    var projectName = "<?php echo $project->getName(); ?>";
    var addLinkUrl = "<?php echo url_for('project/viewProjectDetails') ?>";
    var updateLinkUrl = "<?php echo url_for('project/updateLog') ?>";
    var addProjectLinksUrl = "<?php echo url_for('project/addProjectLinks') ?>";
    var deleteProjectUrl= "<?php echo url_for('project/deleteProjectLinks') ?>";
    var lang_typeHint = '<?php echo __("use link name<space>link format "); ?>';
    var deleteLinkUrl = "<?php echo url_for("project/deleteLog?projectId=$projectId&projectName={$project->getName()}&from=viewDetails"); ?>";
    var viewTaskUrl = "<?php echo url_for('project/viewTasks') ?>";
    var isAllowToEditEffort = "<?php
if (($projectAccessLevel == User::USER_TYPE_PROJECT_ADMIN) || ($projectAccessLevel == User::USER_TYPE_SUPER_ADMIN)) {
    echo '1';
} else {
    echo '0';
}
?>";
    
    var showLinkTable='<?php
if ($projectLinkList != null) {
    echo true;
} else {
    echo false;
}
?>';
    
    function setSelected()
    {
        var list = document.getElementById('project_projectUserSelected');
        var alllList = document.getElementById('project_projectUserAll');
        var vals=list.options[0].value;
        for(var i = 1; i < list.options.length; ++i){
            vals+=','+list.options[i].value;
            list.options[i].selected=false;
        }
        for(var i = 0; i < alllList.options.length; ++i){
            alllList.options[i].selected=false;
        }
        document.getElementById("aaa").value = vals;
    }
    
    var jsArray = [ ];
<?php
foreach ($usersyList as $toJsArray => $convert) {
    ?>    
            jsArray[<?php echo $toJsArray ?>] = '<?php echo $convert; ?>';            
<?php } ?>
    
</script>

<div class="Project">
    <div class="heading">
        <h3> <?php echo link_to(__('Projects'), 'project/viewProjects') ?> > <?php echo __($project->getName()); ?> </h3>
    </div>

    <div class="showForm">
        <div class="headlineField"><?php echo __($project->getName()) ?></div>
        <div class="formField">
            <div class="form_devision_heading">Details</div>
            <form id="showProjectForm" action="<?php echo url_for('project/viewProjectDetails') ?>" method="post" onsubmit="setSelected()">
                <input type="hidden" name="aaa" id="aaa">
                <input type="hidden" id="projectId" name="projectId" value="<?php echo $project->getId() ?>" />
                <div><?php echo $projectForm['name']->renderLabel() ?><?php echo $projectForm['name']->render(array('value' => $project->getName())) ?><?php echo $projectForm['name']->renderError() ?><br class="clear" /></div>
                <div><?php echo $projectForm['startDate']->renderLabel() ?><?php echo $projectForm['startDate']->render(array('value' => $project->getStartDate())) ?><?php echo $projectForm['startDate']->renderError() ?><br class="clear" /></div>
                <div><?php echo $projectForm['endDate']->renderLabel() ?><?php echo $projectForm['endDate']->render(array('value' => $project->getEndDate())) ?><?php echo $projectForm['endDate']->renderError() ?><br class="clear" /></div>
                <div><?php echo $projectForm['estimatedTotalEffort']->renderLabel() ?><?php echo $projectForm['estimatedTotalEffort']->render(array('value' => $project->getTotalEstimatedEffort())) ?><?php echo $projectForm['estimatedTotalEffort']->renderError() ?><br class="clear" /></div>              
                <div><?php echo $projectForm['currentEffort']->renderLabel() ?><?php echo $projectForm['currentEffort']->render(array('value' => $project->getCurrentEffort())) ?><?php echo $projectForm['currentEffort']->renderError() ?><br class="clear" /></div>
                <?php if ($sf_user->hasCredential('superAdmin')): ?>
                    <div><?php echo $projectForm['projectAdmin']->renderLabel() ?><?php echo $projectForm['projectAdmin']->render() ?><?php echo $projectForm['projectAdmin']->renderError() ?></div>
                <?php else: ?>
                    <div><label for="project_projectAdmin"><?php echo __('Project Admin') ?></label><?php echo $project->getUser()->getFirstName(); ?> <?php echo $project->getUser()->getLastName(); ?></div>
                <?php endif; ?>
                <div><?php echo $projectForm['status']->renderLabel() ?><?php echo $projectForm['status']->render() ?><?php echo $projectForm['status']->renderError() ?></div>
                <div><?php echo $projectForm['description']->renderLabel() ?><?php echo $projectForm['description']->render() ?></div>
                <div><?php echo $projectForm['infoLink']->renderLabel() ?><?php echo $projectForm['infoLink']->render() ?><input class="formButton" type="button" value="<?php echo __('Add Link') ?>" id="addLinkButton" name="addLinkButton" /></div>

                <div><table id="linkTable" style="margin-left: 190px; display: none;">
                        <thead><tr><th><?php echo __("Link Name") ?></th><th><?php echo __("Link") ?></th><th></th></tr></thead>
                        <tbody>
                            <?php if ($projectLinkList != null) { ?>
                                <?php foreach ($projectLinkList as $link) { ?>
                                    <tr id="<?php echo "tableR_" . $link->getId(); ?>"><td><?php echo $link->getLinkName(); ?></td><td><?php echo link_to($link->getLink(), $link->getLink(), array('target' => '_blank')) ?></td><td id="<?php echo $link->getId(); ?>"><?php echo image_tag('b_drop.png', 'class=delLink'); ?></td></tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody> 
                    </table>
                </div>

                <table>
                    <tr>
                        <td style="vertical-align: top;padding-top: 10px"><label for="project_assigningUsers">Assign Members</label></td>
                        <td>
                            <div><?php echo $projectForm['projectUserAll']->renderLabel() ?><br/><?php echo $projectForm['projectUserAll']->render(array('class' => 'listbox')) ?></div>
                        </td>                        
                        <td >
                            <div id="btns" style="padding-left: 0px;padding-right: 20px">
                                <input class="formButton" type="button" value="<?php echo __('>') ?>" id="btnRight" />
                                <input class="formButton" type="button"  value="<?php echo __('<') ?>" id="btnLeft"/>   
                            </div>
                        </td>
                        <td>
                            <div><?php echo $projectForm['projectUserSelected']->renderLabel() ?><br/><?php echo $projectForm['projectUserSelected']->render(array('class' => 'listbox')) ?></div>        
                        </td>                        
                    </tr>                    
                </table>


                <!-- buddy --> 
                <?php if ($projectAccessLevel == User::USER_TYPE_SUPER_ADMIN || $projectAccessLevel == User::USER_TYPE_PROJECT_ADMIN): ?>
                    <div>
                        <input class="formButton" type="submit" value="<?php echo __('Edit') ?>" id="saveButton" name="saveButton" />
                        &nbsp;&nbsp;&nbsp;
                        <input class="formButton" type="submit" id="cancel" value="<?php echo __('Cancel') ?>" />
                    </div>
                    <!-- buddy --> 
                <?php endif; ?>

                <?php echo $projectForm->renderHiddenFields(); ?>
            </form>
        </div>
        <div id="requiredField">Field marked with an asterisk <span class="mandatoryStar">*</span> is required.</div>
        <div class="break_line"></div>
        <div class='formField'>
            <div class="form_devision_heading">Status</div>
            <div class='division_content'>
                <table class='showTable'>
                    <tbody>
                        <tr>
                            <th>Project Status</th>
                            <th class="percentageColumn">Percentage</th>
                            <th class="effortColumn"> Effort (<?php echo $storyEstimationCount ?>)</th>
                        </tr>
                        <?php foreach ($statusCountArray as $status => $effort): ?>
                            <tr>
                                <td><?php echo __($status); ?></td>
                                <td><div id="progressbar_<?php echo str_replace(" ", "_", strtolower($status)); ?>"class="progressbar" value="<?php echo $projectService->getPecentage($effort, $storyEstimationCount) ?>"></div></td>
                                <td><?php echo $effort ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class='project_Details' id="projectFacts">
                    <table class='showTable'>
                        <tbody>
                            <tr>
                                <td><label for="Total_Number_Of_Weeks"><?php echo __('Total Number Of Weeks : ') ?></label></td> <td><?php echo $projectDetailList[0] ?> </td>
                            </tr>
                            <tr>
                                <td><label for="Total_Number_Of_Weeks_Completed"><?php echo __('Total Number Of Weeks Completed : ') ?></label></td> <td><?php echo $projectDetailList[1] ?> </td>
                            </tr>
                            <tr>
                                <td><label for="Total_Number_Of_Weeks_Remaining"><?php echo __('Total Number Of Weeks Remaining : ') ?></label></td> <td><?php echo $projectDetailList[2] ?> </td>
                            </tr>
                            <tr>
                                <td><label for="Avg_Weekly_Velocity"><?php echo __('Avg Weekly Velocity : ') ?></label></td> <td><?php echo $projectDetailList[3] ?> </td>
                            </tr>
                            <tr>
                                <td><label for="Required_Weekly_Velocity"><?php echo __('Required Weekly Velocity : ') ?></label></td> <td><?php echo $projectDetailList[4] ?> </td>
                            </tr>
                            <tr>
                                <td><label for="Last_week_Velocity"><?php echo __('Last week Velocity : ') ?></label></td> <td><?php echo $projectDetailList[5] ?> </td>
                            </tr>
                            <tr>
                                <td><label for="Variance_based_on_Known_last_known_velocity"><?php echo __('Variance based on last three weeks velocity : ') ?></label></td> <td><?php echo $projectDetailList[6] ?></td>
                            </tr>
                            <tr>
                                <td><label for="Variance_based_on_avg_weekly_velocity"><?php echo __('Variance based on avg weekly velocity : ') ?></label></td> <td><?php echo $projectDetailList[7] ?></td>
                            </tr>
                            <tr>
                                <td><label for="Estimated_total_effort_used_for_costing"><?php echo __('Estimated total effort used for costing : ') ?></label></td> <td><?php echo $project->getTotalEstimatedEffort(); ?></td>
                            </tr>
                            <tr>
                                <td><label for="Current_total_effort"><?php echo __('Current total effort (based on time sheets) : ') ?></label></td> <td><?php echo $project->getCurrentEffort(); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="break_line"></div>
        <div class='formField'>
            <section id="stoires">
                <a name="stories"></a>
                <?php $urlParm = "project/viewStories?" . http_build_query(array('id' => $projectId, 'projectName' => $project->getName())) ?>
                <div class="form_devision_heading"><a href="<?php echo url_for($urlParm) ?>">Stories</a><a class="divisionExpandColaps show" id="storyExpandColaps" href="">[-]</a>
                    <span id="noStoriesMessage"><?php if (isset($noStoryMessage))
                    echo $noStoryMessage; ?></span>
                </div>
                <div id="mainErrorDiv"></div>
                <div class='division_content' id="storyDivisionContent">
                    <table class='showTable'>
                        <tbody>
                            <tr class="row1">
                                <th><a href='<?php echo url_for("project/viewProjectDetails") . "?projectId={$projectId}&projectName={$project->getName()}&columnname=name&order={$order}&#stoires" ?>'><?php echo __('Story Name') ?></a></th>
                                <th class="estimatedEffort" ><?php echo __('Estimated Effort'); ?></th>
                                <th><?php echo __('Current Effort'); ?></th>
                                <th><a class="order" href='<?php echo url_for("project/viewProjectDetails") . "?projectId={$projectId}&projectName={$project->getName()}&columnname=date_added&order={$order}&#stoires" ?>'><?php echo __('Date Added') ?></a></th>
                                <th><a class="order" href='<?php echo url_for("project/viewProjectDetails") . "?projectId={$projectId}&projectName={$project->getName()}&columnname=estimated_end_date&order={$order}&#stoires" ?>'><?php echo __('Estimated End Date') ?></a></th>
                                <th><?php echo __('Assign To') ?></th>
                                <th><a class="order" href='<?php echo url_for("project/viewProjectDetails") . "?projectId={$projectId}&projectName={$project->getName()}&columnname=status&order={$order}&#stoires" ?>'><?php echo 'Status' ?></a></th>
                                <th><?php echo 'Accepted Date' ?></th>
                                <th colspan="2"><?php echo __('Actions') ?></th>
                            </tr>
                            <?php if (count($storyList) != 0): ?>
                                <?php foreach ($storyList->getResults() as $story): ?>
                                    <?php $status = $story->getStatus() == 'Pending' ? 'Backlog' : $story->getStatus(); ?>
                                    <tr id="row">
                                        <td class="<?php echo "changedName name " . $story->getId(); ?>"><a href="<?php echo url_for("project/viewTasks?storyId={$story->getId()}") ?>"><?php echo $story->getName(); ?></a></td>
                                        <td class="<?php echo "changedEstimation estimation " . $story->getId(); ?>"> <?php echo $story->getEstimation(); ?></td>                
                                        <td class="<?php echo "changedTasksTotal taskTotal " . $story->getId(); ?>"> <?php echo $taskService->getTaskTotalEffortByStoryId($story->getId()) ?></td>
                                        <td class="<?php echo "changedDate date " . $story->getId(); ?>"> <?php echo $story->getDateAdded(); ?></td>
                                        <td class="<?php echo "estimatedEndDate EndDate " . $story->getId(); ?>"> <?php echo $story->getEstimatedEndDate(); ?></td>
                                        <td class="<?php echo "assignTo " . $story->getId(); ?>"> <?php echo $story->getAssignTo(); ?></td>
                                        <td class="<?php echo "changedStatus status " . $story->getId(); ?>"> <?php echo $status; ?></td>
                                        <td class="<?php echo "changedAcceptedDate acceptedDate " . $story->getId(); ?>"> <?php echo $story->getAcceptedDate(); ?></td>
                                        <td class="<?php echo "edit edit " . $story->getId(); ?>"><?php echo image_tag('b_edit.png', 'id=editBtn') ?></td>
                                        <td class="close"><a class="confirmLink" href="<?php echo url_for("project/deleteStory?fromViewProjectDetails=true&id={$story->getId()}&projectId={$projectId}&projectName={$project->getName()}"); ?>"><?php echo image_tag('b_drop.png'); ?></a></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div id="moreFieldProject">"Effort" and "Task Total" are in Engineering Hours</div>
                </div>
                <div class="info">Go to Story page by clicking on "Stories"</div>
            </section>
        </div>
        <div class="break_line"></div>
        <div class='formField'>
            <a name="log"></a>
            <?php $urlParm = "project/addLog?" . http_build_query(array('projectId' => $projectId, 'projectName' => $project->getName())) ?>
            <div class="form_devision_heading"><a href="<?php echo url_for($urlParm) ?>">Log</a><a class="divisionExpandColaps show" id="logExpandColaps" href="">[-]</a>
                <span id="noLogsMessage"><?php if (isset($noLogsMessage))
                echo $noLogsMessage; ?></span>
            </div>
            <div class='division_content' id="logDivisionContent">
                <table class='showTable'>
                    <tbody>
                        <tr>
                            <th>Date</th>
                            <th>Added By</th>
                            <th class="descriptionColumn">Description</th>
                            <th class="listAction" colspan="2">Action</th>
                        </tr>
                        <?php foreach ($projectLogList as $projectLog) { ?>
                            <tr>
                                <?php $loggedDate = explode(" ", $projectLog->getLoggedDate()); ?>
                                <td class="loggedDate <?php echo $projectLog->getId(); ?>"><?php echo $loggedDate[0]; ?></td>
                                <td class="addedBy <?php echo $projectLog->getId(); ?>" value="<?php echo $projectLog->getAddedBy() ?>"><?php echo $projectLogService->getUserName($projectLog->getAddedBy()); ?></td>
                                <td class="description <?php echo $projectLog->getId(); ?>"><?php echo $projectLog->getDescription(); ?></td>
                                <td class="logEdit <?php echo $projectLog->getId(); ?>"><?php echo image_tag('b_edit.png', 'id=editBtn') ?></td>
                                <td class="logDelete <?php echo $projectLog->getId(); ?>"><?php echo image_tag('b_drop.png', 'class=deleteBtn'); ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                    </tbody>
                </table>
            </div>
            <div class="info">Go to Log page by clicking on "Log"</div>
        </div>
    </div>
</div>

<div  id="dialog" title="Confirmation Required">
    <h1><?php echo __('Story will be deleted?') ?></h1>
    <label for="name"><?php echo __('Deleted date') ?></label>
    <input type="text" name="deletedDate" id="deletedDate" onclick="datepicker()" value="<?php echo Date('Y-m-d') ?>" />

</div>
<div  id="dialogLinkDeletion" title="Confirmation Required">
    <?php echo __('Link will be deleted?') ?>

</div>

<?php echo javascript_include_tag('addLog'); ?>
<?php echo javascript_include_tag('viewProjectDetails'); ?>
<?php echo javascript_include_tag('viewStories'); ?>

<script type="text/javascript">
    var saveImgUrl = '<?php echo image_tag('b_save.gif', 'id=saveBtn') ?>';
    var editImgUrl = '<?php echo image_tag('b_edit.png', 'id=editBtn') ?>';
    var linkUrl = "<?php echo url_for('project/editProject') ?>";
</script>