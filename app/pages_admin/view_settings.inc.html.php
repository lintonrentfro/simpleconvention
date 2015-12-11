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
                    <h4>Website Settings</h4>
                    <form class="form-horizontal" action="?" method="post">
                        <h5>Site-Wide Settings</h5>
                        <div class="control-group">
                            <label class="control-label" for="type">Events Viewable?</label>
                            <div class="controls">
                                <select class="span3" name="schedule_shown" id="schedule_shown">
                                <option value="1" <?php if ($con_info['schedule_shown'] == 1) {echo ' selected';}?>>yes</option>
                                <option value="0" <?php if ($con_info['schedule_shown'] == 0) {echo ' selected';}?>>no</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="type">Event Comments On?</label>
                            <div class="controls">
                                <select class="span3" name="event_shoutboxes" id="event_shoutboxes">
                                <option value="1" <?php if ($con_info['event_shoutboxes'] == 1) {echo ' selected';}?>>yes</option>
                                <option value="0" <?php if ($con_info['event_shoutboxes'] == 0) {echo ' selected';}?>>no</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="type">Forums On?</label>
                            <div class="controls">
                                <select class="span3" name="forums_on" id="forums_on">
                                <option value="1" <?php if ($con_info['forums_on'] == 1) {echo ' selected';}?>>yes</option>
                                <option value="0" <?php if ($con_info['forums_on'] == 0) {echo ' selected';}?>>no</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="type">Sponsors Page On?</label>
                            <div class="controls">
                                <select class="span3" name="sponsors_on" id="sponsors_on">
                                <option value="1" <?php if ($con_info['sponsors_on'] == 1) {echo ' selected';}?>>yes</option>
                                <option value="0" <?php if ($con_info['sponsors_on'] == 0) {echo ' selected';}?>>no</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="type">Allow Badge Purchase? </label>
                            <div class="controls">
                                <select class="span3" name="allow_badges" id="allow_badges">
                                <option value="1" <?php if ($con_info['allow_badges'] == 1) {echo ' selected';}?>>yes</option>
                                <option value="0" <?php if ($con_info['allow_badges'] == 0) {echo ' selected';}?>>no</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="type">Store Viewable to Public? </label>
                            <div class="controls">
                                <select class="span3" name="store_on" id="store_on">
                                <option value="1" <?php if ($con_info['store_on'] == 1) {echo ' selected';}?>>yes</option>
                                <option value="0" <?php if ($con_info['store_on'] == 0) {echo ' selected';}?>>no</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="type">Guest Page On? </label>
                            <div class="controls">
                                <select class="span3" name="guests_on" id="guests_on">
                                <option value="1" <?php if ($con_info['guests_on'] == 1) {echo ' selected';}?>>yes</option>
                                <option value="0" <?php if ($con_info['guests_on'] == 0) {echo ' selected';}?>>no</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="type">Vendor Page On? </label>
                            <div class="controls">
                                <select class="span3" name="vendors_on" id="vendors_on">
                                <option value="1" <?php if ($con_info['vendors_on'] == 1) {echo ' selected';}?>>yes</option>
                                <option value="0" <?php if ($con_info['vendors_on'] == 0) {echo ' selected';}?>>no</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="type">Public Can Submit Events? </label>
                            <div class="controls">
                                <select class="span3" name="public_submit_events_on" id="public_submit_events_on">
                                <option value="1" <?php if ($con_info['public_submit_events_on'] == 1) {echo ' selected';}?>>yes</option>
                                <option value="0" <?php if ($con_info['public_submit_events_on'] == 0) {echo ' selected';}?>>no</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="type">Homepage Ads On?</label>
                            <div class="controls">
                                <select class="span3" name="ads_on" id="ads_on">
                                <option value="1" <?php if ($con_info['ads_on'] == 1) {echo ' selected';}?>>on</option>
                                <option value="0" <?php if ($con_info['ads_on'] == 0) {echo ' selected';}?>>off</option>
                                </select>
                            </div>
                        </div>
                        <h5>Badge Settings</h5>
                        <span class="help-block">(for $20.00, enter "2000")</span>
                        <div class="control-group">
                            <label class="control-label" for="badge_price">Badge Price:</label>
                            <div class="controls">
                                <input class="span3" type="text" name="badge_price" id="badge_price" value="<?php echo $con_info['badge_price']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="kid_badge_on">Child Discount On?</label>
                            <div class="controls">
                                <select class="span3" name="kid_badge_on" id="kid_badge_on">
                                <option value="1" <?php if ($con_info['kid_badge_on'] == 1) {echo ' selected';}?>>on</option>
                                <option value="0" <?php if ($con_info['kid_badge_on'] == 0) {echo ' selected';}?>>off</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="kid_badge_price">Child's Badge Price:</label>
                            <div class="controls">
                                <input class="span3" type="text" name="kid_badge_price" id="kid_badge_price" value="<?php echo $con_info['kid_badge_price']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="kid_badge_max_age">Max Age for Child Price:</label>
                            <div class="controls">
                                <input class="span3" type="text" name="kid_badge_max_age" id="kid_badge_max_age" value="<?php echo $con_info['kid_badge_max_age']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="free_badge_on">Free Child Badges On?</label>
                            <div class="controls">
                                <select class="span3" name="free_badge_on" id="free_badge_on">
                                <option value="1" <?php if ($con_info['free_badge_on'] == 1) {echo ' selected';}?>>on</option>
                                <option value="0" <?php if ($con_info['free_badge_on'] == 0) {echo ' selected';}?>>off</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="free_badge_max_age">Max Age for Free Badge:</label>
                            <div class="controls">
                                <input class="span2" type="text" name="free_badge_max_age" id="free_badge_max_age" value="<?php echo $con_info['free_badge_max_age']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <button class="btn" type="submit" value="update_settings" name="action" title="update">update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>