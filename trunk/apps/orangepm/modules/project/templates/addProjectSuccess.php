

<div class="Project">
    <form id="form1" action="<?php echo url_for('project/addProject') ?>" method="POST">

        <table>
            <?php echo $projectForm ?>

            <tr>
                <td colspan="2">
                    <input type="submit" value="<?php echo __('Save') ?>" id="saveButton" />
                    <input type="button" id="cancel" value="<?php echo __('Cancel') ?>" />
                </td>
            </tr>
        </table>
    </form>

    <table border="1" bordercolor="red" >
        <tr><th><?php echo __('ID') ?></th>
            <th><?php echo __('Project Name')?></th>

            <?php foreach ($projectList as $project): ?>
            <tr>
                <td> <?php echo $project->getId(); ?></td>
                <td> <?php echo $project->getName(); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>



<script type="text/javascript">

        $(document).ready(function(){
                $('#cancel').click(function(){
                        location.href="<?php echo url_for('project/viewProjects') ?>";
                });
        });

</script> 

