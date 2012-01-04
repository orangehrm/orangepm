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
</script>

<div class="Project">
    <div class="heading">
        <h3> <?php echo link_to(__('Projects'), 'project/viewProjects') ?> > <?php echo __($project->getName()); ?> </h3>
    </div>

    <div class="showForm">
        <div class="headlineField"><?php echo __($project->getName()) ?></div>
        <div class="formField">
        <div class="form_devision_heading">Details</div>
            <form id="showProjectForm" action="<?php echo url_for('project/viewProjectDetails') ?>" method="post">
                <input type="hidden" id="projectId" name="projectId" value="<?php echo $project->getId()?>" />
                <div><?php echo $projectForm['name']->renderLabel() ?><?php echo $projectForm['name']->render(array('value' => $project->getName())) ?><?php echo $projectForm['name']->renderError() ?><br class="clear" /></div>
                <div><?php echo $projectForm['startDate']->renderLabel() ?><?php echo $projectForm['startDate']->render(array('value' => $project->getStartDate())) ?><?php echo $projectForm['startDate']->renderError() ?><br class="clear" /></div>
                <div><?php echo $projectForm['endDate']->renderLabel() ?><?php echo $projectForm['endDate']->render(array('value' => $project->getEndDate())) ?><?php echo $projectForm['endDate']->renderError() ?><br class="clear" /></div>
                
                <?php if($sf_user->hasCredential('superAdmin')): ?>
                <div><?php echo $projectForm['projectAdmin']->renderLabel() ?><?php echo $projectForm['projectAdmin']->render() ?><?php echo $projectForm['projectAdmin']->renderError() ?></div>
                <?php endif; ?>
                <div><?php echo $projectForm['status']->renderLabel() ?><?php echo $projectForm['status']->render() ?><?php echo $projectForm['status']->renderError() ?></div>
                <div><?php echo $projectForm['description']->renderLabel() ?><?php echo $projectForm['description']->render() ?></div>
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
                        </tr>
                        <?php foreach ($statusCountArray  as $status => $percentage): ?>
                        <tr>
                           <td><?php echo $status == 'Pending' ? __("Backlog") : __($status);?></td>
                           <td><div id="progressbar_<?php echo strtolower($status);?>"class="progressbar" value="<?php echo "$percentage"?>"></div></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="break_line"></div>
        <div class='formField'>
            <a name="stories"></a>
            <div class="form_devision_heading">Stories</div>
            <div id="mainErrorDiv"></div>
            <div class='division_content'>
            <?php $urlParm = "project/viewStories?". http_build_query(array('id' => $projectId , 'projectName' => $project->getName()))?>
                <table class='showTable'>
                    <tbody>
                        <tr>
                            <th class='nameColumn'>Story Name</th>
                            <th class="effortColumn">Effort <span class="moreStar">*</span></th>
                            <th class='dateColumn'>Date<br>Added</th>
                            <th class='statusColumn'>Status</th>
                            <th class='statusColumn'>Status<br>Changed<br>Date</th>
                            <th class="actionColumn" colspan="2">Actions</th>
                        </tr>
                        <?php if(count($storyList) != 0): ?>
                        <?php foreach ($storyList->getResults() as $story): ?>
                            <tr id="row">
                                <td class="<?php echo "changedName name " . $story->getId(); ?>"><?php echo $story->getName(); ?></td>
                                <td class="<?php echo "changedEstimation estimation " . $story->getId(); ?>"> <?php echo $story->getEstimation(); ?></td>
                                <td class="<?php echo "changedDate date " . $story->getId(); ?>"> <?php echo $story->getDateAdded(); ?></td>
                                <td class="<?php echo "changedStatus status " . $story->getId(); ?>"> <?php echo $story->getStatus() == "Pending" ? "Backlog" : $story->getStatus(); ?></td>
                                <td class="<?php echo "changedStatusChangedDate statusChangedDate " . $story->getId(); ?>"> <?php echo $story->getStatusChangedDate(); ?></td>
                                <td class="<?php echo "edit edit " . $story->getId(); ?>"><?php echo image_tag('b_edit.png', 'id=editBtn') ?></td>
                                <td class="close"><a class="confirmLink" href="<?php echo url_for("project/deleteStory?fromViewProjectDetails=true&id={$story->getId()}&projectId={$projectId}&projectName={$projectName}"); ?>"><?php echo image_tag('b_drop.png'); ?></a></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php endif;?>
                    </tbody>
                </table>
                <div id="moreFieldProject">Estimated Effort (Engineering Hours) <span class="moreStar">*</span></div>
            </div>
            <div><a href="<?php echo url_for($urlParm)?>">View all stories</a></div>
        </div>
        <div class="break_line"></div>
        <div class='formField'>
            <a name="log"></a>
            <div class="form_devision_heading">Log</div>
            <div class='division_content'>
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
                            <td class="addedBy <?php echo $projectLog->getId();?>" value="<?php echo $projectLog->getAddedBy()?>"><?php echo viewProjectDetailsAction::getUserName($projectLog->getAddedBy());?></td>
                            <td class="description <?php echo $projectLog->getId();?>"><?php echo $projectLog->getDescription();?></td>
                            <td class="logEdit <?php echo $projectLog->getId();?>">
                                <img class="editBtn" src="/orangepm/web/images/b_edit.png">
                            </td>
                            <td class="logDelete <?php echo $projectLog->getId();?>">
                                <img class="deleteBtn" src="/orangepm/web/images/b_drop.png">
                            </td>
                        </tr>
                        <?php }?>
                        <tr>
                    </tbody>
                </table>
            </div>
            <?php $urlParm = "project/addLog?". http_build_query(array('projectId' => $projectId , 'projectName' => $project->getName()))?>
                <div><a href="<?php echo url_for($urlParm)?>">View all Logs</a></div> 
            
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