<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/includes_html/css.inc.html.php'; ?>
        <meta charset="utf-8">
        <title>Game Convention Template - Event Admin</title>
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
                    <?php include '/home/simpleco/demo2/app/includes_html/menu_event_admin.inc.html.php'; ?>
                </div>
                <div class="span10">
                    <h4>Edit Event</h4>
                    <form action="?" method="post">
                        <div>
                            <label for="name">Name:</label><input type="text" name="name"
                            id="name" value="<?php htmlout($result['name']); ?>">
                        </div>
                        <div>
                            <label for="description">Description:</label>
                            <textarea id="description" name="description" rows="10" 
                                cols="80"><?php  htmlout($result['description']); ?></textarea>
                        </div>
                        <div>
                            <label for ="building">Building:</label>
                            <input type="text" name="building" id="building" 
                            value="<?php echo $result['building']; ?>">
                        </div>
                        <div>
                            <label for ="room">Room:</label>
                            <input type="text" name="room" id="room" value="<?php echo $result['room']; ?>">
                            
                        </div>
                        <div>
                            <label for ="start">Start:</label>
                            <input type="text" name="start" id="start" value="<?php echo $result['start']; ?>">
                            
                        </div>
                        <div>
                            <label for ="end">End:</label>
                            <input type="text" name="end" id="end" value="<?php echo $result['end']; ?>">
                            
                        </div>
                        <div>
                            <label for="type">Type:</label>
                            <select name="type" id="type">
                            <option value="">Select one</option>
                            <?php foreach ($types as $type): ?>
                            <option value="<?php echo $type['event_type_desc']; ?>"
                                <?php if ($type['event_type_desc'] == 
                                    $result['type']) {echo ' selected';}?>>
                            <?php htmlout($type['event_type_desc']); ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="year">Year:</label>
                            <select name="year_id" id="year_id">
                            <option value="">Select one</option>
                            <?php foreach ($years as $year): ?>
                            <option value="<?php htmlout($year['id']); ?>"
                                <?php if ($year['id'] == 
                                    $result['year_id']) {echo ' selected';}?>>
                            <?php htmlout($year['id']); ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="contact">Primary Contact:</label>
                            <select name="contact" id="contact">
                            <option value="">Select one</option>
                            <?php foreach ($contacts as $contact): ?>
                            <option value="<?php echo $contact['id']; ?>"
                                    <?php if ($contact['id'] == 
                                    $result['contact']) {echo ' selected';}?>>
                            <?php echo $contact['last_name'] . ', ' .
                                $contact['first_name']; ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="contact_email_displayed">Contact Email Shown to Users?</label>
                            <select name="contact_email_displayed" id="contact_email_displayed">
                            <option value="">Select one</option>
                            <option value="0"<?php if ($result['contact_email_displayed'] == 
                                    0) {echo ' selected'; } ?>>no</option>
                            <option value="1"<?php if ($result['contact_email_displayed'] == 
                                    1) {echo ' selected'; } ?>>yes</option>
                            </select>
                        </div>

                        <div>
                            <label for="can_conflict">Event's Time Can Conflict With Others?</label>
                            <select name="can_conflict" id="can_conflict">
                            <option value="">Select one</option>
                            <option value="0"<?php if ($result['can_conflict'] == 
                                    0) {echo ' selected'; } ?>>no</option>
                            <option value="1"<?php if ($result['can_conflict'] == 
                                    1) {echo ' selected'; } ?>>yes</option>
                            </select>
                        </div>
                        <div>
                            <label for="registration_required">Registration Required?</label>
                            <select name="registration_required" id="registration_required">
                            <option value="">Select one</option>
                            <option value="1"<?php if ($result['registration_required'] == 
                                    1) {echo ' selected';}?>>yes</option>
                            <option value="0"<?php if ($result['registration_required'] == 
                                    0) {echo ' selected';}?>>no</option>
                        </select>
                        </div>
                        <div>
                            <label for="shoutbox">Shoutbox Discussion Allowed?</label>
                            <select name="shoutbox" id="shoutbox">
                            <option value="">Select one</option>
                            <option value="1"<?php if ($result['shoutbox'] == 
                                    1) {echo ' selected';}?>>yes</option>
                            <option value="0"<?php if ($result['shoutbox'] == 
                                    0) {echo ' selected';}?>>no</option>
                        </select>
                        </div>
                        <div>
                            <label for ="maxusers">Max Attendees:</label>
                            <input type="text" name="maxusers" id="maxusers" 
                            value="<?php htmlout($result['maxusers']); ?>">
                        </div>
                        <div>
                            <label for="status">Status:</label>
                            <select name="status" id="status">
                            <option value="">Select one</option>
                            <?php foreach ($statuses as $status): ?>
                            <option value="<?php htmlout($status['statusdescription']); ?>"
                                    <?php if ($status['statusdescription'] == 
                                    $result['status']) {echo ' selected';}?>>
                            <?php htmlout($status['statusdescription']); ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <input type="hidden" name="id" value="<?php echo $result['id']; ?>">
                            <button type="submit" value="update_event" 
                            name="action" title="update">update</button>
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