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
                    <h4>Create a New Event</h4>
                    <form action="?" method="post" class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label" for="name">Name:</label>
                            <div class="controls">
                                <input type="text" name="name" id="name">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="description">Description:</label>
                            <div class="controls">
                                <textarea id="description" name="description" rows="6"></textarea>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for ="building">Building:</label>
                            <div class="controls">
                                <input type="text" name="building" id="building">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for ="room">Room:</label>
                            <div class="controls">
                                <input type="text" name="room" id="room">
                            </div>
                        </div>
                        <span class="help-block">Event times use the format "08/14/12 2:00 PM"</span>
                        <div class="control-group">
                            <label class="control-label" for ="start">Start:</label>
                            <div class="controls">
                                <input type="text" name="start" id="start">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for ="end">End:</label>
                            <div class="controls">
                                <input type="text" name="end" id="end">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="type">Type:</label>
                            <div class="controls">
                                <select name="type" id="type">
                                <option value="">Select one</option>
                                <?php foreach ($types as $type): ?>
                                <option value="<?php htmlout($type['event_type_desc']); ?>">
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
                                <option value="<?php htmlout($year['id']); ?>">
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
                                <option value="<?php echo $contact['id']; ?>">
                                <?php echo $contact['last_name'] . ', ' . $contact['first_name']; ?></option>
                                <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="registration_required">Registration Required?</label>
                            <div class="controls">
                                <select name="registration_required" id="registration_required">
                                <option value="">Select one</option>
                                <option value="1">yes</option>
                                <option value="0">no</option></select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for ="maxusers">Max Attendees:</label>
                            <div class="controls">
                                <input type="text" name="maxusers" id="maxusers">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="status">Status:</label>
                            <div class="controls">
                                <select name="status" id="status">
                                <option value="">Select one</option>
                                <?php foreach ($statuses as $status): ?>
                                <option value="<?php htmlout($status['statusdescription']); ?>">
                                <?php htmlout($status['statusdescription']); ?></option>
                                <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <button class="btn" type="submit" value="create_event" name="action" title="submit">submit</button>
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