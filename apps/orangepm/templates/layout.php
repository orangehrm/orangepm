<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <?php include_title() ?>
        <link rel="shortcut icon" href="/favicon.ico" />
        <?php include_stylesheets() ?>
        <?php include_javascripts() ?>
    </head>

    <body>
        <div class="header">
            <a href="<?php echo url_for("project/index"); ?>" id="mainLogo"><?php echo image_tag('orangepmlogo.png', 'id=logo'); ?></a>
            <div class="navigaiton">

                <ul id="menu">
                    <?php if ($sf_user->isAuthenticated()): ?>
                        <li> <?php echo link_to(__('Projects'), 'project/viewProjects', array('id' => 'projects')); ?></li>
                        <?php if ($sf_user->hasCredential('superAdmin')){ ?>
                            <li> <?php echo link_to(__('Users'), 'project/viewUsers', array()); ?></li>
                            <li> <?php echo link_to(__('Projects Summary'), 'project/viewAllProjectDetails', array()); ?></li>
                        <?php } else { ?>
                        
                            <li> <?php echo link_to(__('Profile'), 'project/viewProfile', array()); ?></li>
                        <?php } ?>
                        <li> <a href="http://code.google.com/p/orangepm/issues/list" id="issueTracker" ><?php echo __('Issue Tracker') ?></a></li>

                        <div class="logoutClass" id="logout">
                            <form action="<?php echo url_for("project/logout"); ?>" name="logoutForm" method="post">
                                <input type="submit" name="logoutBotton" value="Logout" />
                            </form>
                        </div>
                     <?php endif; ?>
                </ul> 

            </div>
        </div>
        <?php echo $sf_content ?>
    </body>

</html>
