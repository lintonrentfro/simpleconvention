<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/pages_public/_head.inc.html'; ?>
        <title>Game Convention Template - Enter New Password</title>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span1"></div>
                <div class="span10">
                    <img src="/img/logo.png" />
                </div>
                <div class="span1"></div>
            </div>
            <div class="row-fluid">
                <div class="span1"></div>
                <div class="span10">
                    <?php include '/home/simpleco/demo2/app/includes_html/menu_public.inc.html.php'; ?>
                </div>
                <div class="span1"></div>
            </div>
            <div class="row-fluid">
                <div class="span1"></div>
                <div class="span10">
                    <h4>Enter New Password</h4>
                    <form action="?" method="post">
                        <label for ="password">New Password: <input type="password" name="password" id="password"></label>
                        <label for ="password2">Re-Enter Password: <input type="password" name="password2" id="password2"></label>
                        <input type="hidden" name="email" value="<?php echo $email; ?>">
                        <input type="hidden" name="recovery_string" value="<?php echo $recovery_string; ?>">
                        <button class="btn"  type="submit" value="reset_password" name="action" title="submit">submit</button>
                    </form>
                </div>
                <div class="span1"></div>
            </div>
<!--            <div class="row-fluid">
                <div class="span12">
                    <h4>footer</h4>
                </div>
            </div>-->
        </div>
    </body>
</html>