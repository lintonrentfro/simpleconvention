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
                    <h4>Add Event</h4>
                    <form action="?" method="post">
                        <label for="name">Name:</label>
                        <input type="text" name="name" id="name">
                        <label for="description">Description: </label>
                        <textarea id="description" name="description" rows="10" cols="80"></textarea>
                        <label for ="building">Building:</label>
                        <input type="text" name="building" id="building">
                        <label for ="room">Room:</label>
                        <input type="text" name="room" id="room">
                        <label for ="start">Start (in format year:month:day hours:minutes:seconds):</label>
                        <input type="text" name="start" id="start">
                        <label for ="end">End (in format year:month:day hours:minutes:seconds):</label>
                        <input type="text" name="end" id="end">
                        <label for="type">Type:</label>
                            <select name="type" id="type">
                            <option value="">Select one</option>
                            <?php foreach ($types as $type): ?>
                            <option value="<?php htmlout($type['event_type_desc']); ?>">
                            <?php htmlout($type['event_type_desc']); ?></option>
                            <?php endforeach; ?>
                            </select>
                        <label for="year_id">Year:</label>
                            <select name="year_id" id="year_id">
                            <option value="">Select one</option>
                            <?php foreach ($years as $year): ?>
                            <option value="<?php htmlout($year['id']); ?>">
                            <?php htmlout($year['id']); ?></option>
                            <?php endforeach; ?>
                            </select>
                        <label for="contact">Primary Contact:</label>
                            <select name="contact" id="contact">
                            <option value="">Select one</option>
                            <?php foreach ($contacts as $contact): ?>
                            <option value="<?php echo $contact['id']; ?>">
                            <?php echo $contact['last_name'] . ', ' .
                                $contact['first_name']; ?></option>
                            <?php endforeach; ?>
                            </select>
                        <label for ="maxusers">Max Attendees:</label>
                        <input type="text" name="maxusers" id="maxusers">
                        <label for="status">Status:</label>
                            <select name="status" id="status">
                            <option value="">Select one</option>
                            <?php foreach ($statuses as $status): ?>
                            <option value="<?php htmlout($status['statusdescription']); ?>">
                            <?php htmlout($status['statusdescription']); ?></option>
                            <?php endforeach; ?>
                            </select>
                        <label for="registration required">Registration Required:</label>
                            <select name="registration required" id="registration_required">
                            <option value="">Select one</option>
                            <option value="1">yes</option>
                            <option value="0">no</option>
                            </select>
                        <button type="submit" value="create_event" name="action" title="submit">submit</button>
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