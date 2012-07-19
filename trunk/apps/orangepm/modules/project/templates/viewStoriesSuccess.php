<?php echo stylesheet_tag('viewStories') ?>
<?php use_helper('Pagination'); ?>

<script type="text/javascript">
    var saveImgUrl = '<?php echo image_tag('b_save.gif', 'id=saveBtn') ?>';
    var editImgUrl = '<?php echo image_tag('b_edit.png', 'id=editBtn') ?>';
    var linkUrl = "<?php echo url_for('project/editStory') ?>";
    var loginUrl = '<?php echo url_for('project/login')?>';
    var viewTaskUrl = "<?php echo url_for('project/viewTasks') ?>";
    var isAllowToEditEffort = "<?php    if(($projectAccessLevel == User::USER_TYPE_PROJECT_ADMIN) || ($projectAccessLevel == User::USER_TYPE_SUPER_ADMIN) ) { echo '1';}
                                        else {echo '0'; } ?>";
</script>

<div class="Project">
    <div class="heading">
        <h3> <?php echo link_to(__('Projects'),'project/viewProjects'); ?> > <?php echo link_to(__($projectName),"project/viewProjectDetails?projectId={$id}&projectName={$projectName}"); ?> > <?php echo __('Stories'); ?> </h3>
        <span id="message"><?php echo $sf_user->getFlash('addStory') ?></span>
        <span id="noRecordMessage"><?php if(isset($noRecordMessage)) echo $noRecordMessage; ?></span>
    </div>

    <div id="mainErrorDiv"></div>
    <div class="StoryShowForm">
        <table class="tableContent">
            <tr><td class="pageNav" colspan="12"><?php echo pager_navigation($storyList, url_for("project/viewStories") . "?id={$projectId}&projectName={$projectName}") ?></td></tr>
            <tr>
                <th><?php echo __('Story Name') ?></th>
                <?php if($userType != User::USER_TYPE_PROJECT_MEMBER) { ?>
                <th><?php echo __('Estimated Effort');?></th>
                <?php }  ?>
                <th><?php echo __('Current Effort');?></th>
                <th><?php echo __('Date Added') ?></th>
                <th><?php echo __('Estimated End Date') ?></th>
                <th><?php echo __('Assign To') ?></th>
                <th><?php echo 'Status' ?></th>
                <th><?php echo 'Accepted Date' ?></th>
                <th colspan="4"><?php echo __('Actions') ?></th>
            </tr>
            <?php if(count($storyList) != 0): ?>
            <?php foreach ($storyList->getResults() as $story): ?>
            <?php $status = $story->getStatus() == 'Pending' ? 'Backlog' : $story->getStatus();?>
                <tr id="row">
                    <td class="<?php echo "changedName name " . $story->getId(); ?>"><a href="<?php echo url_for("project/viewTasks?storyId={$story->getId()}")?>"><?php echo $story->getName(); ?></a></td>
                    <?php if($userType != User::USER_TYPE_PROJECT_MEMBER) { ?>
                    <td class="<?php echo "changedEstimation estimation " . $story->getId(); ?>"> <?php echo $story->getEstimation(); ?></td>
                    <?php }  ?>
                    <td class="<?php echo "changedTasksTotal taskTotal " . $story->getId(); ?>"> <?php echo $taskService->getTaskTotalEffortByStoryId($story->getId()) ?></td>
                    <td class="<?php echo "changedDate date " . $story->getId(); ?>"> <?php echo $story->getDateAdded(); ?></td>
                    <td class="<?php echo "estimatedEndDate EndDate " . $story->getId(); ?>"> <?php echo $story->getEstimatedEndDate(); ?></td>
                    <td class="<?php echo "assignTo ".$story->getId(); ?>"> <?php echo $story->getAssignTo(); ?></td>
                    <td class="<?php echo "changedStatus status " . $story->getId(); ?>"> <?php echo $status ?></td>
                    <td class="<?php echo "changedAcceptedDate acceptedDate " . $story->getId(); ?>"> <?php echo $story->getAcceptedDate(); ?></td>
                    <td class="<?php echo "edit edit " . $story->getId(); ?>"><?php echo image_tag('b_edit.png', 'id=editBtn') ?></td>
                    <td class="move"><?php echo'<a class="moveLink" id="'.$story->getId().'" >move</a>'?></td>
                    <td class="copy"><?php                  
                            echo '<a class="copyLink" id="'.$story->getId().'" >copy</a>';
                                              ?> </td>
                    <td class="close"><a class="confirmLink" href="<?php echo url_for("project/deleteStory?id={$story->getId()}&projectId={$projectId}&projectName={$projectName}"); ?>"><?php echo image_tag('b_drop.png'); ?></a></td>
               </tr>
               
            <?php endforeach; ?>
            <?php else: ?>
                <!-- do not delete the space between <td> tags -->
                <tr><td> </td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <?php endif; ?>
            </table>
        </div>
        <div class="addButton">
            <form action="<?php echo url_for("project/addStory?id={$projectId}&projectName={$projectName}"); ?>" method="GET">
                <input type="submit" value="<?php echo __('Add') ?>" />
            </form>
        </div>
        <div class="viewWeeklyProgressButton">
            <form action="<?php echo url_for("project/viewWeeklyProgress?projectId={$projectId}&projectName={$projectName}"); ?>" method="GET">
                <input type="submit" value="<?php echo "View Weekly Progress" ?>"/>
            </form>
    </div>
    <div id="moreField">"Effort" and "Task Total" are in Engineering Hours</div>
</div>


<div  id="dialog" title="Confirmation Required">
    <h1><?php echo __('Story will be deleted?')?></h1>
    <label for="name"><?php echo __('Deleted date')?></label>
    <input type="text" name="deletedDate" id="deletedDate" onclick="datepicker()" value="<?php echo Date('Y-m-d')?>" />

</div>
<div  id="mydialog" title="Move Story">
    <form id="moveId" action='<?php echo url_for("project/moveStory"); ?>' method='post'>
        
        <div> <?php echo $moveForm->renderHiddenFields();?></div>
        <div><?php echo $moveForm['project']->renderLabel(); ?>
        <?php echo $moveForm['project']->render(); ?></div>
        <div><?php echo $moveForm['project']->renderError(); ?></div>
    </form>
    <br>
</div>
<div  id="copyDialog" title="Copy Story">
    <form id="copyId" action='<?php echo url_for("project/copyStory"); ?>' method='post'>
        
        <div> <?php echo $copyForm->renderHiddenFields();?></div>
        <div><?php echo $copyForm['project']->renderLabel(); ?>
        <?php echo $copyForm['project']->render(); ?></div>
        <div><?php echo $copyForm['project']->renderError(); ?></div>
    </form>
    <br>
</div>
<?php echo javascript_include_tag('viewStories'); ?>

<script type="text/javascript">
   
    if(isAllowToEditEffort == '0'){
                $('.tableContent').find('.copy').hide();
                $('.tableContent').find('.move').hide();
            }   
   
    $(document).ready(function() {
        $("#mydialog").dialog({
            autoOpen: false,
            height: 250,
            width: 1000,
            modal: true,
            position: 'middle'
    });
       
     var userType = <?php echo $userType ?> ;
     if(userType != 3) {
        $(".moveLink").click(function(e) { 
         
        /*
         * In move link get the story Id and set it to the id element
         * set values to the storyId hidden form using jquery after setting
         * those values can get or set in action
         */ 
        
        $('#moveForm_storyId').val($(this).attr('id'));
        $('#moveForm_projectId').val(<?php echo $projectId ?>);
        e.preventDefault();
       
        $("#mydialog").dialog({
            buttons : {
                
                "Submit" : function() {
                   
                    $('#moveId').submit();
                                                
                },
                
                "Cancel" : function() {
                    
                    $(this).dialog("close");
                    
                }
            }
        });
       
        $("#mydialog").dialog("open");
               
                     
    });
     }
     
     $("#copyDialog").dialog({
            autoOpen: false,
            height: 250,
            width: 1000,
            modal: true,
            position: 'middle'
    });
       
     var userType = <?php echo $userType ?> ;
     if(userType != 3) {
        $(".copyLink").click(function(e) { 
         
        /*
         * In move link get the story Id and set it to the id element
         * set values to the storyId hidden form using jquery after setting those values can get or set in action
         */ 
        
        $('#copyForm_storyId').val($(this).attr('id'));
        $('#copyForm_projectId').val(<?php echo $projectId ?>);
        e.preventDefault();
       
        $("#copyDialog").dialog({
            buttons : {
                
                "Submit" : function() {
                   
                    $('#copyId').submit();
                                                
                },
                
                "Cancel" : function() {
                    
                    $(this).dialog("close");
                    
                }
            }
        });
       
        $("#copyDialog").dialog("open");
               
                     
    });
     }  
     
  });
  var jsArray = [ ];
<?php

        foreach( $userList as $toJsArray => $convert ) {
       ?>    
            jsArray[<?php echo $toJsArray ?>] = '<?php echo $convert;?>';            
  <?php } ?>
</script>