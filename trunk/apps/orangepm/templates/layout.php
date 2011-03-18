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
            <h1> OrangePM </h1>
        </div>
        <div class="navigaiton">
            <ul>
                <li> <?php echo link_to(__('Home'), 'project/index'); ?></li>
                <li> <?php echo link_to(__('Projects'), 'project/viewProjects'); ?></li>
                <li> Help </li>
                <li> Issue Tacker </li>
            </ul>
        </div>
        <?php echo $sf_content ?>        
    </body>
</html>
