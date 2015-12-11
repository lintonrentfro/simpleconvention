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
                    <h4>Edit User</h4>
                    <form action="?" method="post" class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label" for="first_name">First name:</label>
                            <div class="controls">
                                <input type="text" name="first_name" id="first_name" value="<?php htmlout($user_info['first_name']); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="last_name">Last Name:</label>
                            <div class="controls">
                                <input type="text" name="last_name" id="last_name" value="<?php htmlout($user_info['last_name']); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="email">Email:</label>
                            <div class="controls">
                                <input type="text" name="email" id="email" value="<?php htmlout($user_info['email']); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="company">Company:</label>
                            <div class="controls">
                                <input type="text" name="company" id="company" value="<?php htmlout($user_info['company']); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="address1">Address (first line):</label>
                            <div class="controls">
                                <input type="text" name="address1" id="address1" value="<?php htmlout($user_info['address1']); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="address2">Address (second line):</label>
                            <div class="controls">
                                <input type="text" name="address2" id="address2" value="<?php htmlout($user_info['address2']); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="city">City:</label>
                            <div class="controls">
                                <input type="text" name="city" id="city" value="<?php htmlout($user_info['city']); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="state">State:</label>
                            <div class="controls">
                                <input type="text" name="state" id="state" value="<?php htmlout($user_info['state']); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="zip">Zip:</label>
                            <div class="controls">
                                <input type="text" name="zip" id="zip" value="<?php htmlout($user_info['zip']); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="home">Home:</label>
                            <div class="controls">
                                <input type="text" name="home" id="home" value="<?php htmlout($user_info['home']); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="work">Work:</label>
                            <div class="controls">
                                <input type="text" name="work" id="work" value="<?php htmlout($user_info['work']); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="cell">Cell:</label>
                            <div class="controls">
                                <input type="text" name="cell" id="cell" value="<?php htmlout($user_info['cell']); ?>">
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?php htmlout($user_info['id']); ?>">
                        <button class="btn" type="submit" value="update_user" name="action" title="update">update</button>
                    </form>
                <?php 
                    if ($paidforbadge['COUNT(*)'] < 1) {
                        echo '<form action="?" method="post"><input type="hidden" name="id" value="';
                        echo $user_info['id'] . '">';
                        echo '<button class="btn" type="submit" value="give_badge" name="action" title="give free badge">give badge</button>';
                    }
                    else {
                        echo 'User has badge.';
                    } ?>
                </div>
            </div>
        </div>
    </body>
</html>