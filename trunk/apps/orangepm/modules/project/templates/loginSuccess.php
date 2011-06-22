<?php echo stylesheet_tag('login'); ?>

<?php echo javascript_include_tag('login'); ?>
<?php echo javascript_include_tag('jquery/jquery.min'); ?>
<?php echo javascript_include_tag('jquery/jquery.validator'); ?>

<div class="login">
    
    <div class="title"><?php echo __('Login') ; ?></div>
    
    <div class="textFields">        
        <form id="login_form" action="<?php echo url_for("project/login"); ?>" method="post" name="login_form" onsubmit="return loginInputValid( );">
            <?php if (isset($errorMessage)): ?>
                <div class="flash_notice">* <?php echo $errorMessage; ?></div>
            <?php endif; ?>

            <div><?php echo $loginForm['username']->renderLabel(); ?>: <?php echo $loginForm['username']->render(); ?><?php echo $loginForm['username']->renderError(); ?><span id="result_username" class="result"></span></div>
            <div><?php echo $loginForm['password']->renderLabel(); ?>: <?php echo $loginForm['password']->render(); ?><?php echo $loginForm['password']->renderError(); ?><span id="result_password" class="result"></span></div>
            <?php echo $loginForm->renderHiddenFields(); ?>
            <div class="submit"><input id="loginButton" class="formButton" type="submit" value="<?php echo __('Login'); ?>" /></div>
        </form>        
    </div>
    
</div>