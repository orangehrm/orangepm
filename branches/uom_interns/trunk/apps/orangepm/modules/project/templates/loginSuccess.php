<?php echo stylesheet_tag('login'); ?>

<script type="text/javascript">
    //<![CDATA[
    //we write javascript related stuff here, but if the logic gets lengthy should use a seperate js file
    var lang_usernameRequired = "<?php echo __("Username is required"); ?>";
    var lang_passwordRequired = "<?php echo __("Password is required"); ?>";    
    //]]>
</script>

<div class="login">
    
    <div class="title"><?php echo __('Login') ; ?></div>
    
    <div class="textFields">        
        <form id="login_form" action="<?php echo url_for("project/login"); ?>" method="post" name="login_form" >
            <?php if (isset($errorMessage)): ?>
                <div class="flash_notice">* <?php echo $errorMessage; ?></div>
            <?php endif; ?>

            <div><?php echo $loginForm['username']->renderLabel(); ?>: <?php echo $loginForm['username']->render(); ?><?php echo $loginForm['username']->renderError(); ?><br class="clear" /></div>
            <div><?php echo $loginForm['password']->renderLabel(); ?>: <?php echo $loginForm['password']->render(); ?><?php echo $loginForm['password']->renderError(); ?><br class="clear" /></div>
            <?php echo $loginForm->renderHiddenFields(); ?>
            <div class="submit"><input id="loginButton" class="formButton" type="button" value="<?php echo __('Login'); ?>" /></div>
        </form>        
    </div>
    
</div>

<?php echo javascript_include_tag('login'); ?>
