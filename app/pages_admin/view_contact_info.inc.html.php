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
                    <h4>Contact Information</h4>
                    <form class="form-horizontal" action="?" method="post">
                        <div class="control-group">
                            <label class="control-label" for="official_name">Official name:</label>
                            <div class="controls">
                                <input type="text" name="official_name" id="official_name" value="<?php echo $con_info['official_name']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="abbreviated_name">Abbreviated Name:</label>
                            <div class="controls">
                                <input type="text" name="abbreviated_name" id="abbreviated_name" value="<?php echo $con_info['abbreviated_name']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="tagline">Tagline:</label>
                            <div class="controls">
                                <input type="text" name="tagline" id="tagline" value="<?php echo $con_info['tagline']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="email">Email:</label>
                            <div class="controls">
                                <input type="text" name="email" id="email" value="<?php echo $con_info['email']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="address1">Address:</label>
                            <div class="controls">
                                <input type="text" name="address1" id="address1" value="<?php echo $con_info['address1']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="address2">Address:</label>
                            <div class="controls">
                                <input type="text" name="address2" id="address2" value="<?php echo $con_info['address2']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="city">City:</label>
                            <div class="controls">
                                <input type="text" name="city" id="city" value="<?php echo $con_info['city']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="state">State:</label>
                            <div class="controls">
                                <input type="text" name="state" id="state" value="<?php echo $con_info['state']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="zip">Zip:</label>
                            <div class="controls">
                                <input type="text" name="zip" id="zip" value="<?php echo $con_info['zip']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="web">Web:</label>
                            <div class="controls">
                                <input type="text" name="web" id="web" value="<?php echo $con_info['web']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="twitter">Twitter:</label>
                            <div class="controls">
                                <input type="text" name="twitter" id="twitter" value="<?php echo $con_info['twitter']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="facebook">Facebook:</label>
                            <div class="controls">
                                <input type="text" name="facebook" id="facebook" value="<?php echo $con_info['facebook']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <button class="btn" type="submit" value="update_contact_info" name="action" title="update">update</button>
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