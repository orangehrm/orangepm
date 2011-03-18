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
                <td class="<?php echo "edit edit " . $project->getId(); ?>"><?php echo image_tag('b_edit.png', 'id=editBtn'); ?><span class="tip">Click here to view the stories</span></td>
                <td class="<?php echo "not close " . $project->getId(); ?>"><a class="confirmLink" href="<?php echo url_for("project/deleteProject?id={$project->getId()}"); ?>" ><?php echo image_tag('b_drop.png'); ?></a></td>
            </tr>
        <?php endforeach; ?>
            
            </table>
        </div>

        <script type="text/javascript">

            $(document).ready(function(){
                nVar = true;
                nVariable = "Saved";

                $('td.edit').click(function(){
                if(nVar){
                    
                    if(nVariable == "Saved"){

                        $(this).html('<?php echo image_tag('b_save.gif', 'id=saveBtn') ?>');
                        nVar = false;
                    }

                    if(nVariable == "Edited"){
                        $(this).html('<?php echo image_tag('b_edit.png', 'id=editBtn') ?>');
                        nVariable = "Saved";
                    }
                    
                    arr = $(this).attr('class').split( " " );

                    $('.ajax').html($('.ajax input').val());
                    $('.ajax').removeClass('ajax');

                    $(this).parent().children('td.change').addClass('ajax');
                    $(this).parent().children('td.change').html('<input id="editbox" size="'+16+'" type="text" value="'+$(this).parent().children('td.change').text()+'">');

                    $('#saveBtn').click(function(){
                    nVar = true;

                        $.ajax({    type: "post",
                            url: "<?php echo url_for('project/editProject') ?>",
                            data: "name="+$('.ajax input').val()+"&id="+arr[2],
                            success: function(){
        
                                var hstring = '<a href=' + '<?php echo url_for("project/viewStories?id={$project->getId()}"); ?>' +' > '+$('.ajax input').val()+'</a>';
                        $('.ajax').html(hstring);

                    }
                });

                nVariable = "Edited";
                
            }
        );}
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




<div id="dialog" title="Confirmation Required">
  Project Will Be Deleted??
</div>



<script type="text/javascript">
  $(document).ready(function() {
    $("#dialog").dialog({
      autoOpen: false,
      modal: true
    });
  });

  $(".confirmLink").click(function(e) {
    e.preventDefault();
    var targetUrl = $(this).attr("href");

    $("#dialog").dialog({
      buttons : {
        "OK" : function() {
          window.location.href = targetUrl;
        },
        "Cancel" : function() {
          $(this).dialog("close");
        }
      }
    });

    $("#dialog").dialog("open");
  });
</script>



