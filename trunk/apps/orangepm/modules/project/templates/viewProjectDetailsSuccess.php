<?php echo stylesheet_tag('viewProjectDetails') ?>
<?php echo stylesheet_tag('viewStories') ?>

<?php use_helper('Pagination'); ?>

<script type="text/javascript">
    var lang_nameRequired = "<?php echo __('Project name is required');?>";
    var saveImgUrl = '<?php echo image_tag('b_save.gif', 'id=saveBtn') ?>';
    var editImgUrl = '<?php echo image_tag('b_edit.png', 'id=editBtn') ?>';
    var linkUrl = "<?php echo url_for('project/editStory') ?>";
    var projectViewUrl = "<?php echo url_for("project/viewProjectDetails?projectId={$project->getId()}&projectName={$project->getName()}")?>";
    var statusChanged = false;
    var userId = "<?php echo $userId;?>";
    var userName = "<?php echo $userName;?>";
    var projectId = "<?php echo $projectId;?>";
    var projectName = "<?php echo $project->getName();?>";
    var addLinkUrl = "<?php echo url_for('project/viewProjectDetails') ?>";
    var updateLinkUrl = "<?php echo url_for('project/updateLog') ?>";
    var deleteLinkUrl = "<?php echo url_for("project/deleteLog?projectId=$projectId&projectName={$project->getName()}&from=viewDetails"); ?>";
    var viewTaskUrl = "<?php echo url_for('project/viewTasks') ?>";
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
                <input type="hidden" id="projectId" name="projectId" value="<?php echo $project->getId()?>" />
                <div><?php echo $projectForm['name']->renderLabel() ?><?php echo $projectForm['name']->render(array('value' => $project->getName())) ?><?php echo $projectForm['name']->renderError() ?><br class="clear" /></div>
                <div><?php echo $projectForm['startDate']->renderLabel() ?><?php echo $projectForm['startDate']->render(array('value' => $project->getStartDate())) ?><?php echo $projectForm['startDate']->renderError() ?><br class="clear" /></div>
                <div><?php echo $projectForm['endDate']->renderLabel() ?><?php echo $projectForm['endDate']->render(array('value' => $project->getEndDate())) ?><?php echo $projectForm['endDate']->renderError() ?><br class="clear" /></div>
                
                <?php if($sf_user->hasCredential('superAdmin')): ?>
                <div><?php echo $projectForm['projectAdmin']->renderLabel() ?><?php echo $projectForm['projectAdmin']->render() ?><?php echo $projectForm['projectAdmin']->renderError() ?></div>
                <?php endif; ?>
                <div><?php echo $projectForm['status']->renderLabel() ?><?php echo $projectForm['status']->render() ?><?php echo $projectForm['status']->renderError() ?></div>
                <div><?php echo $projectForm['description']->renderLabel() ?><?php echo $projectForm['description']->render() ?></div>
                <table>
                    <tr>
                        <td>Assign users&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>
                            <div><?php echo $projectForm['projectUserAll']->renderLabel() ?><br/><?php echo $projectForm['projectUserAll']->render() ?></div>
                        </td>
                        <td>
                            <input class="formButton" type="button" value="<?php echo __('>') ?>" id="btnRight" />
                            <input class="formButton" type="button"  value="<?php echo __('<') ?>" id="btnLeft"/>                          
                        </td>
                        <td>
                            <div><?php echo $projectForm['projectUserSelected']->renderLabel() ?><br/><?php echo $projectForm['projectUserSelected']->render() ?></div>        
                        </td>                        
                    </tr>                    
                </table>
                <div>
                    <input class="formButton" type="submit" value="<?php echo __('Edit') ?>" id="saveButton" name="saveButton" />
                    &nbsp;&nbsp;&nbsp;
                    <input class="formButton" type="submit" id="cancel" value="<?php echo __('Cancel') ?>" />
                </div>
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
                            <th class="effortColumn"> Effort (<?php echo $storyEstimationCount?>)</th>
                        </tr>
                        <?php foreach ($statusCountArray  as $status => $effort): ?>
                        <tr>
                           <td><?php echo __($status);?></td>
                           <td><div id="progressbar_<?php echo str_replace(" ","_",strtolower($status));?>"class="progressbar" value="<?php echo $projectService->getPecentage($effort, $storyEstimationCount)?>"></div></td>
                           <td><?php echo $effort?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="break_line"></div>
        <div class='formField'>
            <a name="stories"></a>
            <?php $urlParm = "project/viewStories?". http_build_query(array('id' => $projectId , 'projectName' => $project->getName()))?>
            <div class="form_devision_heading"><a href="<?php echo url_for($urlParm)?>">Stories</a><a class="divisionExpandColaps show" id="storyExpandColaps" href="">[-]</a>
                <span id="noStoriesMessage"><?php if(isset($noStoryMessage)) echo $noStoryMessage; ?></span>
            </div>
            <div id="mainErrorDiv"></div>
            <div class='division_content' id="storyDivisionContent">
                <table class='showTable'>
                    <tbody>
                        <tr>
                            <th><?php echo __('Story Name') ?></th>
                            <th><?php echo __('Effort');?></th>
                            <th><?php echo __('Tasks Total');?></th>
                            <th><?php echo __('Date Added') ?></th>
                            <th><?php echo 'Status' ?></th>
                            <th><?php echo 'Accepted Date' ?></th>
                            <th colspan="2"><?php echo __('Actions') ?></th>
                        </tr>
                        <?php if(count($storyList) != 0): ?>
                        <?php foreach ($storyList->getResults() as $story): ?>
                        <?php $status = $story->getStatus() == 'Pending' ? 'Backlog' : $story->getStatus();?>
                            <tr id="row">
                                <td class="<?php echo "changedName name " . $story->getId(); ?>"><a href="<?php echo url_for("project/viewTasks?storyId={$story->getId()}")?>"><?php echo $story->getName(); ?></a></td>
                                <td class="<?php echo "changedEstimation estimation " . $story->getId(); ?>"> <?php echo $story->getEstimation(); ?></td>
                                <td class="<?php echo "changedTasksTotal taskTotal " . $story->getId(); ?>"> <?php echo $taskService->getTaskTotalEffortByStoryId($story->getId()) ?></td>
                                <td class="<?php echo "changedDate date " . $story->getId(); ?>"> <?php echo $story->getDateAdded(); ?></td>
                                <td class="<?php echo "changedStatus status " . $story->getId(); ?>"> <?php echo $status; ?></td>
                                <td class="<?php echo "changedAcceptedDate acceptedDate " . $story->getId(); ?>"> <?php echo $story->getAcceptedDate(); ?></td>
                                <td class="<?php echo "edit edit " . $story->getId(); ?>"><?php echo image_tag('b_edit.png', 'id=editBtn') ?></td>
                                <td class="close"><a class="confirmLink" href="<?php echo url_for("project/deleteStory?fromViewProjectDetails=true&id={$story->getId()}&projectId={$projectId}&projectName={$project->getName()}"); ?>"><?php echo image_tag('b_drop.png'); ?></a></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php endif;?>
                    </tbody>
                </table>
                <div id="moreFieldProject">"Effort" and "Task Total" are in Engineering Hours</div>
            </div>
            <div class="info">Go to Story page by clicking on "Stories"</div>
        </div>
        <div class="break_line"></div>
        <div class='formField'>
            <a name="log"></a>
             <?php $urlParm = "project/addLog?". http_build_query(array('projectId' => $projectId , 'projectName' => $project->getName()))?>
            <div class="form_devision_heading"><a href="<?php echo url_for($urlParm)?>">Log</a><a class="divisionExpandColaps show" id="logExpandColaps" href="">[-]</a>
                <span id="noLogsMessage"><?php if(isset($noLogsMessage)) echo $noLogsMessage; ?></span>
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
                        <?php foreach ($projectLogList as $projectLog) {?>
                        <tr>
                            <?php $loggedDate = explode(" ", $projectLog->getLoggedDate());?>
                            <td class="loggedDate <?php echo $projectLog->getId();?>"><?php echo $loggedDate[0];?></td>
                            <td class="addedBy <?php echo $projectLog->getId();?>" value="<?php echo $projectLog->getAddedBy()?>"><?php echo $projectLogService->getUserName($projectLog->getAddedBy());?></td>
                            <td class="description <?php echo $projectLog->getId();?>"><?php echo $projectLog->getDescription();?></td>
                            <td class="logEdit <?php echo $projectLog->getId();?>"><?php echo image_tag('b_edit.png', 'id=editBtn') ?></td>
                            <td class="logDelete <?php echo $projectLog->getId();?>"><?php echo image_tag('b_drop.png', 'class=deleteBtn'); ?></td>
                        </tr>
                        <?php }?>
                        <tr>
                    </tbody>
                </table>
            </div>
            <div class="info">Go to Log page by clicking on "Log"</div>
        </div>
    </div>
</div>

<div  id="dialog" title="Confirmation Required">
    <h1><?php echo __('Story will be deleted?')?></h1>
    <label for="name"><?php echo __('Deleted date')?></label>
    <input type="text" name="deletedDate" id="deletedDate" onclick="datepicker()" value="<?php echo Date('Y-m-d')?>" />

</div>
<?php echo javascript_include_tag('addLog'); ?>
<?php echo javascript_include_tag('viewProjectDetails'); ?>
<?php echo javascript_include_tag('viewStories'); ?>