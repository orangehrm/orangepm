<?php echo stylesheet_tag('addUser') ?>
<?php use_helper('Pagination'); ?>

<script type="text/javascript">
    var lang_firstNameRequired = "<?php echo __('First name is required');?>";
    var lang_lastNameRequired = "<?php echo __('Last name is required');?>";
    var lang_emailRequired = "<?php echo __('Email is required');?>";
    var lang_usernameRequired = "<?php echo __('Username is required');?>";
    var lang_passwordRequired = "<?php echo __('Password is required');?>";
</script>

<div class="User">

    <div class="heading">
        <h3> <?php echo link_to(__('Users'), 'project/viewUsers') ?> > <?php echo __('Add User'); ?> </h3>
    </div>

    <div class="addForm">
        <div class="headlineField"><?php echo __('Add User') ?></div>
        <div class="formField">
            <form id="addUser" action="<?php echo url_for('project/addUser') ?>" method="post" onsubmit="return addUserInputsValid( );" >
                <div id="form">
                    <div><span class="mandatoryStar">*</span> <?php echo $userForm['firstName']->renderLabel() ?><?php echo $userForm['firstName']->render() ?><?php echo $userForm['firstName']->renderError() ?><br/><span id="result_firstName" class="result"></span></div>
                    <div><span class="mandatoryStar">*</span> <?php echo $userForm['lastName']->renderLabel() ?><?php echo $userForm['lastName']->render() ?><?php echo $userForm['lastName']->renderError() ?><br/><span id="result_lastName" class="result"></span></div>
                    <div><span class="mandatoryStar">*</span> <?php echo $userForm['email']->renderLabel() ?><?php echo $userForm['email']->render() ?><?php echo $userForm['email']->renderError() ?><br/><span id="result_email" class="result"></span></div>
                    <div><span class="mandatoryStar">*</span> <?php echo $userForm['userType']->renderLabel() ?><?php echo $userForm['userType']->render() ?><?php echo $userForm['userType']->renderError() ?><br/><span id="result_userType" class="result"></span></div>
                    <div><span class="mandatoryStar">*</span> <?php echo $userForm['username']->renderLabel() ?><?php echo $userForm['username']->render() ?><?php echo $userForm['username']->renderError() ?><br/><span id="result_username" class="result"></span></div>
                    <div><span class="mandatoryStar">*</span> <?php echo $userForm['password']->renderLabel() ?><?php echo $userForm['password']->render() ?><?php echo $userForm['password']->renderError() ?><br/><span id="result_password" class="result"></span></div>
                    <div>
                        <input class="formButton" type="submit" value="<?php echo __('Save') ?>" id="saveButton" />
                        <input class="formButton" type="button" id="cancel" value="<?php echo __('Cancel') ?>" />
                    </div>
                    <?php echo $userForm->renderHiddenFields(); ?>
                </div>
            </form>
        </div>
        <div id="requiredField">Fields marked with an asterisk <span class="mandatoryStar">*</span> are required.</div>
    </div>



    <table class="tableContent" >
          <!--<tr><td class="pageNav" colspan="4"><?php //echo pager_navigation($pager, url_for('project/addUser'))  ?></td></tr>-->
        <tr>
            <th><?php echo __('First Name') ?></th>
            <th><?php echo __('Last Name') ?></th>
            <th><?php echo __('User Type') ?></th>
            <th><?php echo __('Email') ?></th>
            <th><?php echo __('Username') ?></th>
        </tr>
        <?php foreach ($pager->getResults() as $user): ?>
            <tr>
                <td> <?php echo $user->getFirstName(); ?></td>
                <td> <?php echo $user->getLastName(); ?></td>
                <td> <?php
        if ($user->getUserType() == 1)
            echo 'Super admin';
        elseif ($user->getUserType() == 2)
            echo 'Project admin';
        ?></td>
                <td> <?php echo $user->getEmail(); ?></td>
                <td> <?php echo $user->getUsername(); ?></td>
            </tr>
<?php endforeach; ?>
    </table>
</div>

<?php echo javascript_include_tag('jquery/jquery.min'); ?>
<?php echo javascript_include_tag('jquery/jquery.validator'); ?>
<?php echo javascript_include_tag('addUser'); ?>