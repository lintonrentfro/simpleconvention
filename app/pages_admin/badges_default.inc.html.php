<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/pages_admin/_head.inc.html'; ?>
        
        <title>Game Convention Template - Admin</title>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12">
                    <img src="/img/logo.png" />
                </div>
            </div>
            <div class="row-fluid">
                <div class="span2">
                    <?php include '/home/simpleco/demo2/app/includes_html/menu_admin.inc.html.php'; ?>
                </div>
                <div class="span10">
                    <h4>Badges</h4>
                    <form action="?" method="post">
                        <button class="btn" type="submit" value="create_pre-reg_badges" name="action" 
                            title="create postcards">create pre-registration badge list as csv file</button><br>
                    </form>
                    <form action="?" method="post">
                        <button class="btn" type="submit" value="show_free_badges" name="action" 
                            title="show users with free badges">show users with free badges</button><br>
                    </form>
                </div>
            </div>
<!--            <div class="row-fluid">
                <div class="span12">
                    <h4>footer</h4>
                </div>
            </div>-->
        </div>
    </body>
</html>