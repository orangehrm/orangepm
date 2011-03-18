<?php echo stylesheet_tag('viewProjects') ?>
<?php use_helper('Pagination'); ?>


<div class="Project">
    <div class="addButton">
    <form id="form1" action="<?php echo url_for('project/addProject') ?>" method="get">

        <table>
            <?php echo $projectForm ?>

            <tr>
                <td colspan="2">
                    <input type="submit" value="<?php echo __('Add') ?>" id="addProject" />
                </td>
            </tr>
        </table>
    </form>
    </div>


    <table class="tableContent" >
        <tr><td class="pageNav" colspan="4"><?php  echo pager_navigation($pager, url_for('project/viewProjects')) ?></td></tr>
        <tr><th> <?php echo __('Id') ?></th>
            <th> <?php echo __('Project Name'); ?> </th>
            <th colspan="2"><?php echo __('Actions')?></th>

            <?php $alt = '1' ?>
            <?php //foreach ($projectList as $project): ?>
            <?php foreach ($pager->getResults() as $project): ?>
            <?php
                if ($alt == 1) {
                    echo '<tr class="alt">';
                    $alt = 0;
                } else {
                    echo '<tr>';
                    $alt = 1;
                }
            ?>
                <td class="<?php echo "not id " . $project->getId(); ?>"><?php echo $project->getId(); ?></td>
                <td class="<?php echo "change name " . $project->getId(); ?>" ><a class="storyLink" href="<?php echo url_for("project/viewStories?id={$project->getId()}"); ?>" > <?php echo $project->getName(); ?></a></td>
                <td class="<?php echo "edit edit " . $project->getId() ?>"><?php echo image_tag('b_edit.png'); ?><span class="tip">Click here to view the stories</span></td>
                <td class="<?php echo "not close " . $project->getId(); ?>"><a href="<?php echo url_for("project/deleteProject?id={$project->getId()}"); ?>" ><?php echo image_tag('b_drop.png'); ?><span class="tip">Click here to delete the project</span></a></td>
            </tr>
        <?php endforeach; ?>
            
            </table>
        </div>

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
                            url:"<?php echo url_for('project/editProject') ?>",
                            data: "name="+$('.ajax input').val()+"&id="+arr[2],
                            success: function(data){
                                var hstring = '<a href=' + '<?php echo url_for("project/viewStories?id={$project->getId()}"); ?>' +' > '+$('.ajax input').val()+'</a>';
                                $('.ajax').html(hstring);
                                $('.ajax').removeClass('ajax');
                            }});
                    }

                }


            );


        $('#editbox').live('blur',function(){

            $('.ajax').html($('.ajax input').val());
            $('.ajax').removeClass('ajax');
        });

        $("td.edit,td.close,a.storyLink").hover(function(){
            tip = $(this).find('.tip');
            tip.show(); //Show tooltip
        }, function() {
            tip.hide(); //Hide tooltip
        }).mousemove(function(e) {
            var mousex = e.pageX + 20; //Get X coodrinates
            var mousey = e.pageY + 20; //Get Y coordinates
            var tipWidth = tip.width(); //Find width of tooltip
            var tipHeight = tip.height(); //Find height of tooltip

            //Distance of element from the right edge of viewport
            var tipVisX = $(window).width() - (mousex + tipWidth);
            //Distance of element from the bottom of viewport
            var tipVisY = $(window).height() - (mousey + tipHeight);

            if ( tipVisX < 20 ) { //If tooltip exceeds the X coordinate of viewport
                mousex = e.pageX - tipWidth - 20;
            } if ( tipVisY < 20 ) { //If tooltip exceeds the Y coordinate of viewport
                mousey = e.pageY - tipHeight - 20;
            }
            //Absolute position the tooltip according to mouse position
            tip.css({  top: mousey, left: mousex });
        });

   
    });
</script>






