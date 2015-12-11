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
                    <h4>Confirm User Deletion</h4>
                    <p>
                        Are you sure you want to delete user "<?php htmlout($userinfo['first_name']); echo ' '; htmlout($userinfo['last_name']); ?>"?
                    </p>
                
                    <form action="?" method="post">
                        <input type="hidden" name="id" value="<?php echo $userinfo['id']; ?>">
                        <button class="btn" type="submit" value="delete_user_confirmed" 
                                name="action" title="delete user">delete user</button>
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