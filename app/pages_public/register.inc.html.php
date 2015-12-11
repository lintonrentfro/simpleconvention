<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/pages_public/_head.inc.html'; ?>
        <title>Game Convention Template - Register</title>
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
                    <h4>Register</h4>
                    <p>All fields marked with a * are required.</p>
                    <form action="?" method="post" class="form-horizontal">
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
                        <span class="help-block">Your email address will be your login.</span>
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
                            <label class="control-label" for="home">Home Phone:</label>
                            <div class="controls">
                                <input type="text" name="home" id="home">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="work">Work Phone:</label>
                            <div class="controls">
                                <input type="text" name="work" id="work">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="cell">Cell Phone:</label>
                            <div class="controls">
                                <input type="text" name="cell" id="cell">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="password">*Password:</label>
                            <div class="controls">
                                <input type="password" name="password" id="password">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="password2">*Re-Enter Password:</label>
                            <div class="controls">
                                <input type="password" name="password2" id="password2">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <button class="btn" type="submit" value="registerform" name="action" title="register">register</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="span1"></div>
            </div>
        </div>
    </body>
</html>