<?php echo stylesheet_tag('addLog') ?>
<script type="text/javascript">
    var saveImgUrl = '<?php echo image_tag('b_save.gif', 'id=saveBtn') ?>';
    var editImgUrl = '<?php echo image_tag('b_edit.png', 'id=editBtn') ?>';
    var logSaveImgUrl = '<?php echo image_tag('b_save.gif', 'id=logSaveBtn') ?>';
    var userId = "<?php echo $userId;?>";
    var userName = "<?php echo $userName;?>";
    var projectId = "<?php echo $projectId;?>";
    var projectName = "<?php echo $projectName;?>";
    var addLinkUrl = "<?php echo url_for('project/addLog') ?>";
    var updateLinkUrl = "<?php echo url_for('project/updateLog') ?>";
    var deleteLinkUrl = "<?php echo url_for("project/deleteLog?projectId=$projectId&projectName=$projectName") ?>";
</script>
<div class="heading">
    <h3> <?php echo link_to(__('Projects'),'project/viewProjects'); ?> ><a class="storyLink" href="<?php echo url_for("project/viewProjectDetails?projectId={$projectId}&projectName={$projectName}"); ?>" > <?php echo $projectName; ?></a> > <?php echo __('Add Log'); ?> </h3>
    <span id="message"><?php echo $sf_user->getFlash('addStory') ?></span>
    <span id="noRecordMessage"><?php if(isset($noRecordMessage)) echo $noRecordMessage; ?></span>
    
</div>
<div class='logList'>
    <input type="button" id="addButton" value="Add">
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
                <td class="logEdit <?php echo $projectLog->getId();?>"><?php echo image_tag('b_edit.png', 'class=editBtn') ?></td>
                <td class="logDelete <?php echo $projectLog->getId();?>"><?php echo image_tag('b_drop.png', 'class=deleteBtn'); ?></td>
            </tr>
            <?php }?>
            <tr>
        </tbody>
    </table>
</div>

<div id="dialog" title="Confirmation Required"></div>
<?php echo javascript_include_tag('addLog'); ?>