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
                    <h4>Register New User</h4>
                    <form class="form-horizontal" action="?" method="post">
                        <div class="control-group">
                            <label class="control-label" for="firstname">*First Name:</label>
                            <div class="controls">
                                <input type="text" name="firstname" id="firstname">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="lastname">*Last Name:</label>
                            <div class="controls">
                                <input type="text" name="lastname" id="lastname">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="Company">Company:</label>
                            <div class="controls">
                                <input type="text" name="company" id="company">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="email">*Email:</label>
                            <div class="controls">
                                <input type="text" name="email" id="email">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="address1">*Address (first line):</label>
                            <div class="controls">
                                <input type="text" name="address1" id="address1">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="address2">Address (second line):</label>
                            <div class="controls">
                                <input type="text" name="address2" id="address2">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="city">*City:</label>
                            <div class="controls">
                                <input type="text" name="city" id="city">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="state">*State:</label>
                            <div class="controls">
                                <input type="text" name="state" id="state">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="zip">*Zip:</label>
                            <div class="controls">
                                <input type="text" name="zip" id="zip">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <button class="btn" type="submit" value="registernew" name="action" title="register new user">register new user</button>
                            </div>
                        </div>
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