<?php echo stylesheet_tag('viewUser'); ?>
<?php use_helper('Pagination'); ?>

<script type="text/javascript">
    var saveImgUrl = '<?php echo image_tag('b_save.gif', 'id=saveBtn') ?>';
    var editImgUrl = '<?php echo image_tag('b_edit.png', 'id=editBtn') ?>';
    var linkUrl = "<?php echo url_for('project/editUser') ?>";
</script>

<?php echo javascript_include_tag('viewUsers'); ?>

<div class="User">

    <div class="heading">
        <h3><?php echo __('Users'); ?></h3>
        <span id="message"><?php if (isset($message))
    echo __('The User is added successfully') ?></span>
    </div>

    <table class="tableContent">

        <tr>
            <th><?php echo __('First Name'); ?></th>
            <th><?php echo __('Last Name'); ?></th>
            <th><?php echo __('E-mail'); ?></th>
            <th><?php echo __('User Type'); ?></th>
            <th><?php echo __('Username'); ?></th>
            <th><?php echo __('Password'); ?></th>
            <th colspan="2"><?php echo __('Actions'); ?></th>
        </tr>

            <?php foreach ($pager->getResults() as $user): ?>

            <tr id="row">
                <td class="<?php echo "changedFirstName firstName" . $user->getId(); ?>" ><?php echo $user->getFirstName(); ?></td>
                <td class="<?php echo "changedLastName lastName" . $user->getId(); ?>" ><?php echo $user->getLastName(); ?></td>
                <td class="<?php echo "changedEmail email" . $user->getId(); ?>" ><?php echo $user->getEmail(); ?></td>
                <td class="<?php echo "changedUserType userType" . $user->getId(); ?>" ><?php 
                        if($user->getUserType() == 1)
                            echo 'Super Admin';
                        elseif($user->getUserType() == 2)
                            echo 'Project Admin';
                    ?>
                </td>
                <td class="<?php echo "changedUsername username" . $user->getId(); ?>" ><?php echo $user->getUsername(); ?></td>
                <td id="pass" class="<?php echo "changedPassword password" . $user->getId(); ?>" ><?php echo __('hidden') ?></td>
                <td class="<?php echo "edit edit " . $user->getId(); ?>"><?php echo image_tag('b_edit.png', 'id=editBtn') ?></td>
                <td class="close"><a class="confirmLink" href="<?php echo url_for("project/deleteUser?id={$user->getId()}"); ?>"><?php echo image_tag('b_drop.png'); ?></a></td>

            </tr>

            <?php endforeach; ?>


    </table>

    <div class="addButton">
        <form id="formAddBotton" action="<?php echo url_for('project/addUser') ?>" method="get">
            <table>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="<?php echo __('Add') ?>" id="addProject" />
                    </td>
                </tr>
            </table>
        </form>
    </div>

</div>

<div id="dialog" title="Confirmation Required">
    User Will Be Deleted?
</div>