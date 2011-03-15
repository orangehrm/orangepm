<form action="<?php echo url_for('project/addStory') ?>" method="POST">
    <table>
        <?php echo $storyForm ?>
        
        <tr>
            <td colspan="2">
                <input type="submit" value="Save" />
                <input type="button" id="cancel" value="Cancel" />
            </td>
        </tr>
    </table>
</form>

<p>
    <?php echo "REGISTRED STORIES"; ?>
        <br/>
    <table border ="1">
        <tr>
            <td><b>Story Id</b></td>
            <td><b>Story Name</b></td>
            <td><b>Effort estimation</b></td>
            <td><b>Date Added</b></td>
        </tr>

    <?php foreach ($storyList as $story): ?>
            <tr>
                <td class="<?php echo "not id " . $story->getId(); ?>"><?php echo $story->getId(); ?></td>
                <td class="<?php echo "change name " . $story->getId(); ?>"><?php echo $story->getName(); ?></td>
                <td> <?php echo $story->getEstimation(); ?></td>
                <td> <?php echo $story->getDateAdded(); ?></td>
            </tr>
    <?php endforeach; ?>
</table>

<script type="text/javascript">
    $(document).ready(
    function() {
        $( "#project_Date_added" ).datepicker({dateFormat: 'yy-mm-dd'});
    });

    $(document).ready(function(){
                $('#cancel').click(function(){
                        location.href="<?php echo url_for('project/viewStories?id=' . $projectId); ?>";
                });
        });
</script>                
