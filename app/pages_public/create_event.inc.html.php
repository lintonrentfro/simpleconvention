<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/pages_public/_head.inc.html'; ?>
        <title>Game Convention Template - Create an Event</title>
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
                    <h4>Create an Event</h4>
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
                                <textarea class="span8" id="description" name="description" rows="6"></textarea>
                            </div>
                        </div>
                        <span class="help-block">Event times use the format "08/14/12 2:00 PM"</span>
                        <span class="help-block">Earliest Possible: <?php htmlout(date("m/d/Y", strtotime($con_dates['start']))); ?></span>
                        <div class="control-group">
                            <label class="control-label" for ="start">Start:</label>
                            <div class="controls">
                                <input type="text" name="start" id="start">
                            </div>
                        </div>
                        <span class="help-block">Latest Possible: <?php htmlout(date("m/d/Y", strtotime($con_dates['end']))); ?></span>
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
                                <option value="<?php htmlout($type['event_type_desc']); ?>"><?php htmlout($type['event_type_desc']); ?></option>
                                <?php endforeach; ?></select>
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
                            <label class="control-label" for="contact_email_displayed">Show Your Email?</label>
                            <div class="controls">
                                <select name="contact_email_displayed" id="contact_email_displayed">
                                <option value="">Select one</option>
                                <option value="0">no</option>
                                <option value="1">yes</option></select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for ="maxusers">Maximum Attendees:</label>
                            <div class="controls">
                                <input type="text" name="maxusers" id="maxusers">
                            </div>
                        </div>
                        <span class="help-block">
                            All events are subject to approval by convention staff.
                        </span>
                        <div class="control-group">
                            <div class="controls">
                                <input type="hidden" name="contact" value="<?php echo $id; ?>">
                                <input type="hidden" name="status" value="pending approval">
                                <button class="btn"  type="submit" value="submit_event" name="action" title="submit">submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="span1"></div>
            </div>
<!--            <div class="row-fluid">
                <div class="span12">
                    <h4>footer</h4>
                </div>
            </div>-->
        </div>
    </body>
</html>


