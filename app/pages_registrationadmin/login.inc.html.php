<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/includes_html/css.inc.html.php'; ?>
        <meta charset="utf-8">
        <title>Game Convention Template - Registration Admin</title>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12">
                    <img src="/img/logo.png" />
                </div>
            </div>
            <div class="row-fluid">
                <div class="span3">
                    <?php include '/home/simpleco/demo2/app/includes_html/menu_registration_admin.inc.html.php'; ?>
                </div>
                <div class="span9">
                    <h4>Log In</h4>
                    <form action="?loginformdata" method="post">
                        <label for="login">email: <input type="text" name="email" id="email"></label>
                        <label for ="pasword">password: <input type="password" name="password" id="password"></label>
                        <button class="btn" type="submit" name="action" title="submit">submit</button>
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