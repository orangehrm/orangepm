<style type="text/css">
    td.edit{
        cursor:pointer;
    }

    a img{
        border: NONE;
    }

</style>
<form action="<?php echo url_for('project/addStory?id=' . $projectId); ?>" method="GET">
    <input type="submit" value="ADD" />
</form>

<?php echo "REGISTRED STORIES"; ?>

<br/>

<table border ="1">
    <tr>
        <th>Story Id</th>
        <th>Story Name</th>
        <th>Effort estimation</th>
        <th>Date Added</th>
    </tr>

    <?php foreach ($storyList as $story): ?>
        <tr id="row">
            <td class="<?php echo "not id " . $story->getId(); ?>"><?php echo $story->getId(); ?></td>
            <td class="<?php echo "change name " . $story->getId(); ?>"><?php echo $story->getName(); ?></td>
            <td> <?php echo $story->getEstimation(); ?></td>
            <td> <?php echo $story->getDateAdded(); ?></td>
            <td class="<?php echo "edit edit " . $story->getId(); ?>"><?php echo image_tag('b_edit.png') ?></td>
            <td>  <a href="<?php echo url_for("project/deleteStory?id={$story->getId()}&projectId={$projectId}"); ?> " > <?php echo image_tag('b_drop.png'); ?></a></td>

        </tr>
    <?php endforeach; ?>
    </table>

    <script type="text/javascript">
        $(document).ready(function(){
            $('td.edit').click(function(){
                $('.ajax').html($('.ajax input').val());
                $('.ajax').removeClass('ajax');

                $(this).parent().children('td.change').addClass('ajax');
                $(this).parent().children('td.change').html('<input id="editbox" size="'+$(this).parent().children('td.change').text().length+'" type="text" value="' + $(this).parent().children('td.change').text() + '">');

                $('#editbox').focus();

            });

            $('td.change').keydown(function(event){
                arr = $(this).attr('class').split( " " );

                if(event.which == 13)
                {
                    $.ajax({    type: "POST",
                        url:"<?php echo url_for('project/editStory') ?>",
                    data: "name="+$('.ajax input').val()+"&id="+arr[2],
                    success: function(data){
                        $('.ajax').html($('.ajax input').val());
                        $('.ajax').removeClass('ajax');
                    }});
            }
        }
    );

        $('#editbox').live('blur',function(){

            $('.ajax').html($('.ajax input').val());
            $('.ajax').removeClass('ajax');
        });

    });
</script>