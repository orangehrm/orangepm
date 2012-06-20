<?php echo stylesheet_tag('viewProjectDetails') ?>
<?php echo stylesheet_tag('viewStories') ?>
<div class="Project">
    <div class="heading">
        <h3> </h3>
    </div>
    <?php foreach ($projectProgressList  as $single): ?>
    <div class="showForm">
        <div class="headlineField"><?php echo $single['project']->getName(); ?></div>
        <div class="formField">
            <div class="form_devision_heading">Details</div>
                <table class='showTable'>
                    <tbody>
                        <tr>
                            <td><label for="project_projectAdmin"><?php echo __('Project Admin : ') ?></label></td> <td><?php echo $single['project']->getUser()->getFirstName().' '.$single['project']->getUser()->getLastName(); ?> </td>
                        </tr>
                        <tr>
                            <td><label for="project_startDate"><?php echo __('Start Date : ') ?></label></td> <td><?php echo $single['project']->getStartDate(); ?> </td>
                        </tr>
                        <tr>
                            <td><label for="project_endDate"><?php echo __('Start Date : ') ?></label></td> <td><?php echo $single['project']->getStartDate(); ?> </td>
                        </tr>
                    </tbody>
                </table>
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
            </div>
        </div>        
    </div>
    <br/>
    <?php endforeach; ?>
</div>
<?php echo javascript_include_tag('viewProjectDetails'); ?>
<?php echo javascript_include_tag('viewStories'); ?>