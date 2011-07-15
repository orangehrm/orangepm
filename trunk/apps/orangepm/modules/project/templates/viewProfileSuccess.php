<?php echo stylesheet_tag('viewProfile'); ?>

<div class="Profile">

    <div class="heading">
        <h3><?php echo __('Profile'); ?></h3>        
    </div>

    <div class="editProfileForm">
        <div class="headlineField"><?php echo __('Edit Profile') ?></div>
        <div class="formField">
            <form id="editProfile" action="<?php echo url_for('project/saveProfile') ?>" method="post" >
                <div id="form">
                    <div><span class="mandatoryStar">*</span> <?php echo $userForm['firstName']->renderLabel() ?><?php echo $userForm['firstName']->render() ?><?php echo $userForm['firstName']->renderError() ?><br class="clear" /></div>
                    <div><span class="mandatoryStar">*</span> <?php echo $userForm['lastName']->renderLabel() ?><?php echo $userForm['lastName']->render() ?><?php echo $userForm['lastName']->renderError() ?><br class="clear" /></div>
                    <div><span class="mandatoryStar">*</span> <?php echo $userForm['email']->renderLabel() ?><?php echo $userForm['email']->render() ?><?php echo $userForm['email']->renderError() ?><br class="clear" /></div>
                    <div> <?php echo $userForm['userType']->renderLabel() ?><label class="projectAdmin"><?php echo __('Project Admin'); ?></label></div>
                    <div> <?php echo $userForm['username']->renderLabel() ?><label class="username"><?php echo $loggedUser->getUsername(); ?></label></div>
                    <div><span class="mandatoryStar">*</span> <?php echo $userForm['password']->renderLabel() ?><?php echo $userForm['password']->render() ?><?php echo $userForm['password']->renderError() ?><br class="clear" /></div>
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

<?php //echo javascript_include_tag('viewProfile'); ?>
