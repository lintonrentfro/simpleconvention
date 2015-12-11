<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/pages_admin/_head.inc.html'; ?>
        
        <title>Game Convention Template - Advertising</title>
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
                    <div class="row-fluid">
                        <h4>Advertising</h4>
                        <h5>Setup</h5>
                        <form class="form-horizontal" action="?" method="post">
                            <div class="control-group">
                            <label class="control-label" for="type">Ads On or Off? </label>
                                <div class="controls">
                                    <select name="ads_on" id="ads_on">
                                    <option value="1" <?php if ($coninfo['ads_on'] == 1) {echo ' selected';}?>>on</option>
                                    <option value="0" <?php if ($coninfo['ads_on'] == 0) {echo ' selected';}?>>off</option>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <button class="btn" type="submit" value="toggle_ads" name="action" title="update">update</button>
                                </div>
                            </div>
                        </form>
                        <h5>Create New Ad</h5>
                        <form class="form-horizontal" action="?" method="post">
                            <div class="control-group">
                                <label class="control-label" for="sponsor_company">Company:</label>
                                <div class="controls">
                                    <input type="text" name="sponsor_company" id="sponsor_company" value="">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="sponsor_contact">Contact:</label>
                                <div class="controls">
                                    <input type="text" name="sponsor_contact" id="sponsor_contact" value="">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="sponsor_email">Email:</label>
                                <div class="controls">
                                    <input type="text" name="sponsor_email" id="sponsor_email" value="">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="sponsor_phone">Phone:</label>
                                <div class="controls">
                                    <input type="text" name="sponsor_phone" id="sponsor_phone" value="">
                                </div>
                            </div>
                            <span class="help-block">Example: "/img/ads/ad1.gif"</span>
                            <div class="control-group">
                                <label class="control-label" for="image_url">Image Location:</label>
                                <div class="controls">
                                    <input type="text" name="image_url" id="image_url" value="">
                                </div>
                            </div>
                            <span class="help-block">Example: "http://www.arstechnica.com"</span>
                            <div class="control-group">
                                <label class="control-label" for="link_url">Link URL:</label>
                                <div class="controls">
                                    <input type="text" name="link_url" id="link_url" value="">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="on_or_off">On or Off:</label>
                                <div class="controls">
                                    <select name="on_or_off" id="on_or_off">
                                    <option value="1" <?php if ($ad['on_or_off'] == 1) {echo ' selected';}?>>on</option>
                                    <option value="0" <?php if ($ad['on_or_off'] == 0) {echo ' selected';}?>>off</option>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <button class="btn btn-primary" type="submit" value="create_ad" name="action" title="create">create</button>
                                </div>
                            </div>
                        </form>
                        <h5>Manage Ads</h5>
                        <?php foreach($ads as $ad): ?>
                        <form class="form-horizontal" action="?" method="post">
                            <div class="control-group">
                                <label class="control-label" for="sponsor_company">Company:</label>
                                <div class="controls">
                                    <input type="text" name="sponsor_company" id="sponsor_company" value="<?php htmlout($ad['sponsor_company']); ?>">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="sponsor_contact">Contact:</label>
                                <div class="controls">
                                    <input type="text" name="sponsor_contact" id="sponsor_contact" value="<?php htmlout($ad['sponsor_contact']); ?>">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="sponsor_email">Email:</label>
                                <div class="controls">
                                    <input type="text" name="sponsor_email" id="sponsor_email" value="<?php htmlout($ad['sponsor_email']); ?>">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="sponsor_phone">Phone:</label>
                                <div class="controls">
                                    <input type="text" name="sponsor_phone" id="sponsor_phone" value="<?php htmlout($ad['sponsor_phone']); ?>">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="image_url">Image Location:</label>
                                <div class="controls">
                                    <input type="text" name="image_url" id="image_url" value="<?php htmlout($ad['image_url']); ?>">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="link_url">Link URL:</label>
                                <div class="controls">
                                    <input type="text" name="link_url" id="link_url" value="<?php htmlout($ad['link_url']); ?>">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="on_or_off">On or Off:</label>
                                <div class="controls">
                                    <select name="on_or_off" id="on_or_off">
                                    <option value="1" <?php if ($ad['on_or_off'] == 1) {echo ' selected';}?>>on</option>
                                    <option value="0" <?php if ($ad['on_or_off'] == 0) {echo ' selected';}?>>off</option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="adID" value="<?php echo $ad['adID']; ?>">
                            <div class="control-group">
                                <div class="controls">
                                    <button class="btn btn-primary" type="submit" value="update_ad" name="action" title="update">update</button>
                                    <button class="btn btn-danger" type="submit" value="delete_ad" name="action" title="delete">delete</button>
                                </div>
                            </div>
                        </form>
                        <?php endforeach; ?>
                        
                    </div>
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