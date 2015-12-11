<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/pages_public/_head.inc.html'; ?>
        <title>Game Convention Template - My Info</title>
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
                    <h4>My Personal Information</h4>
                    <form action="?" method="post" class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label" for="first_name">First Name:</label>
                            <div class="controls">
                                <input type="text" name="first_name" id="first_name" value="<?php echo $user_info['first_name']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="last_name">Last Name:</label>
                            <div class="controls">
                                <input type="text" name="last_name" id="last_name" value="<?php echo $user_info['last_name']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="email">Email:</label>
                            <div class="controls">
                                <input type="text" name="email" id="email" value="<?php echo $user_info['email']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="company">Company:</label>
                            <div class="controls">
                                <input type="text" name="company" id="company" value="<?php echo $user_info['company']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="address1">Address Line 1:</label>
                            <div class="controls">
                                <input type="text" name="address1" id="address1" value="<?php echo $user_info['address1']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="address2">Address Line 2:</label>
                            <div class="controls">
                                <input type="text" name="address2" id="address2" value="<?php echo $user_info['address2']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="city">City:</label>
                            <div class="controls">
                                <input type="text" name="city" id="city" value="<?php echo $user_info['city']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="state">State:</label>
                            <div class="controls">
                                <input type="text" name="state" id="state" value="<?php echo $user_info['state']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="zip">Zip:</label>
                            <div class="controls">
                                <input type="text" name="zip" id="zip" value="<?php echo $user_info['zip']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="home">Home:</label>
                            <div class="controls">
                                <input type="text" name="home" id="home" value="<?php echo $user_info['home']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="work">Work:</label>
                            <div class="controls">
                                <input type="text" name="work" id="work" value="<?php echo $user_info['work']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="cell">Cell:</label>
                            <div class="controls">
                                <input type="text" name="cell" id="cell" value="<?php echo $user_info['cell']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <input type="hidden" name="id" value="<?php echo $user_info['id']; ?>">
                                <button class="btn"  type="submit" value="update_myuserinfo" name="action" title="update">update</button>
                            </div>
                        </div>
                    </form>
                    <?php 
                        if ($user_is_guest_or_not['COUNT(*)'] > 0) {
                            echo '
                                <h4>Guest Information</h4>
                                <form action="?" method="post" class="form-horizontal">
                                <div class="control-group">
                                    <label class="control-label" for="professional_name">Professional Name:</label>
                                    <div class="controls">
                                        <input type="text" name="professional_name" id="professional_name" value="' . $guest_info['professional_name'] . '">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="short_description">Short Description:</label>
                                    <div class="controls">
                                        <textarea class="span8" id="short_description" name="short_description" rows="6">' . $guest_info['short_description'] . '</textarea>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="full_description">Full Description:</label>
                                    <div class="controls">
                                        <textarea class="span8" id="full_description" name="full_description" rows="6">' . $guest_info['full_description'] . '</textarea>
                                    </div>
                                </div>
                                <span class="help-block">If photo is incorrect, <a href="mailto:' . $con_info['email'] . '">email</a> convention staff.</span>
                                <div class="control-group">
                                    <label class="control-label" for="photo">Photo:</label>
                                    <div class="controls">
                                        <img class="media-object img-rounded" src="' . $guest_info['photo_url'] . '" width="100" height="100">
                                    </div>
                                </div>
                                <div class="control-group">
                                <div class="controls">
                                <input type="hidden" name="guestID" value="' . $guest_info['guestID'] . '">
                                <button type="submit" class="btn" value="update_guest_info" name="action" title="update">update</button>
                                </div></div>                                
                                </form>';
                            } ?>
                </div>
                <div class="span1"></div>
            </div>
        </div>
    </body>
</html>