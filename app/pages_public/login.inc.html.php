<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/pages_public/_head.inc.html'; ?>
        <title>Game Convention Template - Log In</title>
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
                    <h4>Log In</h4>
                    <form action="?loginformdata" method="post" class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label" for="login">login:</label>
                            <div class="controls">
                                <input type="text" name="email" id="email">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for ="pasword">password:</label>
                            <div class="controls">
                                <input type="password" name="password" id="password">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <button class="btn" type="submit" name="action" title="submit">submit</button>
                                <p><a href="?forgot_password">reset password</a></p>
                            </div>
                        </div>
                    </form>
                    
                </div>
                <div class="span1"></div>
            </div>
        </div>
    </body>
</html>






