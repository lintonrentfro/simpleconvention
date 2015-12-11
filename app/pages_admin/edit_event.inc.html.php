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
                    <h4>Edit Event</h4>
                    <form action="?" method="post" class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label" for="name">Name:</label>
                            <div class="controls">
                                <input type="text" name="name" id="name" value="<?php htmlout($result['name']); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="description">Description:</label>
                            <div class="controls">
                                <textarea class="span8" id="description" name="description" rows="6"><?php htmlout($result['description']); ?></textarea>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="building">Building:</label>
                            <div class="controls">
                                <input type="text" name="building" id="building" value="<?php htmlout($result['building']); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="room">Room:</label>
                            <div class="controls">
                                <input type="text" name="room" id="room" value="<?php htmlout($result['room']); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="room">Start:</label>
                            <div class="controls">
                                <input type="text" name="start" id="start" value="<?php htmlout($result['start']); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="room">End:</label>
                            <div class="controls">
                                <input type="text" name="end" id="end" value="<?php htmlout($result['end']); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="type">Type:</label>
                            <div class="controls">
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
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="year_id">Year:</label>
                            <div class="controls">
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
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="contact">Primary Contact:</label>
                            <div class="controls">
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
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="contact_email_displayed">Contact Email Shown to Users?</label>
                            <div class="controls">
                                <select name="contact_email_displayed" id="contact_email_displayed">
                                <option value="">Select one</option>
                                <option value="0"<?php if ($result['contact_email_displayed'] == 
                                        0) {echo ' selected'; } ?>>no</option>
                                <option value="1"<?php if ($result['contact_email_displayed'] == 
                                        1) {echo ' selected'; } ?>>yes</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="can_conflict">Event's Time Can Conflict With Others?</label>
                            <div class="controls">
                                <select name="can_conflict" id="can_conflict">
                                <option value="">Select one</option>
                                <option value="0"<?php if ($result['can_conflict'] == 
                                        0) {echo ' selected'; } ?>>no</option>
                                <option value="1"<?php if ($result['can_conflict'] == 
                                        1) {echo ' selected'; } ?>>yes</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="registration_required">Registration Required?</label>
                            <div class="controls">
                                <select name="registration_required" id="registration_required">
                                <option value="">Select one</option>
                                <option value="1"<?php if ($result['registration_required'] == 
                                        1) {echo ' selected';}?>>yes</option>
                                <option value="0"<?php if ($result['registration_required'] == 
                                        0) {echo ' selected';}?>>no</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="shoutbox">Shoutbox Discussion Allowed?</label>
                            <div class="controls">
                                <select name="shoutbox" id="shoutbox">
                                <option value="">Select one</option>
                                <option value="1"<?php if ($result['shoutbox'] == 
                                        1) {echo ' selected';}?>>yes</option>
                                <option value="0"<?php if ($result['shoutbox'] == 
                                        0) {echo ' selected';}?>>no</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for ="maxusers">Max Attendees:</label>
                            <div class="controls">
                                <input type="text" name="maxusers" id="maxusers" value="<?php htmlout($result['maxusers']); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="status">Status:</label>
                            <div class="controls">
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
                        </div>
                        <input type="hidden" name="id" value="<?php echo $result['id']; ?>">
                        <button type="submit" class="btn" value="update_event" name="action" title="update">update</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>