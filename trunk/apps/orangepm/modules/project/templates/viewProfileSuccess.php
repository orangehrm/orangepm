<?php echo stylesheet_tag('viewProfile'); ?>

<script type="text/javascript">
    //<![CDATA[
    //we write javascript related stuff here, but if the logic gets lengthy should use a seperate js file
    var lang_emailRequired = "<?php echo __("E-mail is required"); ?>";
    var lang_validEmailRequired = "<?php echo __("Valid email required"); ?>";
    var lang_passwordRequired = "<?php echo __("Password is required"); ?>";    
    //]]>
</script>

<div class="Profile">
    
    <div class="heading">
        <h3><?php echo __('Profile'); ?></h3>        
    </div>

    <div class="editProfileForm">
        <div class="headlineField"><?php echo __('Edit Profile') ?></div>
        <div class="formField">
            <form id="editProfile" action="<?php echo url_for('project/viewProfile') ?>" method="post" >
                    <div><span>:</span> <?php echo $userForm['firstName']->renderLabel() ?><?php echo $userForm['firstName']->render() ?><?php echo $userForm['firstName']->renderError() ?></div>
                    <div><span>:</span> <?php echo $userForm['lastName']->renderLabel() ?><?php echo $userForm['lastName']->render() ?><?php echo $userForm['lastName']->renderError() ?></div>
                    <div><span>:</span> <?php echo $userForm['email']->renderLabel() ?><?php echo $userForm['email']->render() ?><?php echo $userForm['email']->renderError() ?><br class="clear" /></div>
                    <div><?php echo $userForm['userType']->renderLabel() ?><input type="hidden" id ="user_userType" name="user[userType]" value ="<?php echo $loggedUser->getUserType(); ?>"><label class="projectAdmin"><?php echo ': '.__('Project Admin'); ?></label></div>
                    <div><?php echo $userForm['username']->renderLabel() ?><input type="hidden" id ="user_username" name="user[username]" value ="<?php echo $loggedUser->getUsername(); ?>"><label class="username"><?php echo ': '.$loggedUser->getUsername(); ?></label></div>
                    <div><span>:</span> <?php echo $userForm['password']->renderLabel() ?><?php echo $userForm['password']->render() ?><?php echo $userForm['password']->renderError() ?><br class="clear" /></div>
                    <div>
                        <input class="formButton" type="submit" value="<?php echo __('Save') ?>" id="saveButton" />
                        <input class="formButton" type="button" id="cancel" value="<?php echo __('Cancel') ?>" />
                    </div>
                    <?php echo $userForm->renderHiddenFields(); ?>

            </form>
        </div>
        
        <!--<div id="requiredField">Fields marked with an asterisk <span class="mandatoryStar">*</span> are required.</div>-->
    </div>
    
</div>
<?php echo javascript_include_tag('viewProfile'); ?>
