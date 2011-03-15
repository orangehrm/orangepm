

<div class="Project">
    <form id="form1" action="<?php echo url_for('project/addProject') ?>" method="POST">

        <table>
            <?php echo $projectForm ?>

            <tr>
                <td colspan="2">
                    <input type="submit" value="Save" id="saveButton" />
                </td>
            </tr>
        </table>
    </form>

    <table border="1" bordercolor="red" >
        <tr><th> Id </th>
            <th> Project Name </th>

            <?php foreach ($projectList as $project): ?>
            <tr>
                <td> <?php echo $project->getId(); ?></td>
                <td> <?php echo $project->getName(); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>


