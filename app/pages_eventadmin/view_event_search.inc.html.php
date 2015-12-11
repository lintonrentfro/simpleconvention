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
                    <a href="?add_event">add new event</a>
                    <h5>Event Search</h5>
                    <form action="?" method="post" class="form-inline">
                        <select name="searchby" id="searchby">
                        <option value="">Search By</option>
                        <?php foreach ($event_columns as $col): ?>
                        <option value="<?php htmlout($col['column_name']); ?>">
                        <?php htmlout($col['column_name']); ?></option>
                        <?php endforeach; ?>
                        </select>
                        <input type="text" name="search_text" id="search_text">
                        <button class="btn" type="submit" value="search_events" name="action" title="search events">search</button>
                    </form>
                    <h5>Search Results</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name/Location</th>
                                <th>Start/End</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <?php foreach ($search_result as $event): ?>
                        <tr <?php makeyellowif($event['status'], 'pending approval'); ?>>
                            <form action="?" method="post">
                                <div>
                                    <td><?php htmlout($event['name']);
                                        echo '<br>';
                                        htmlout($event['building']); 
                                        echo ' ';
                                        htmlout($event['room']);
                                         ?></td>
                                    <td><?php 
                                        htmlout(date("m/d g:i a", strtotime($event['start'])));
                                        echo '<br>';
                                        htmlout(date("m/d g:i a", strtotime($event['end'])));
                                        ?></td>
                                    <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                                    <td><button class="btn btn-mini" type="submit" value="edit_event" 
                                        name="action" title="edit event">edit</button>
                                    <button class="btn btn-mini" type="submit" value="edit_events_properties" 
                                        name="action" title="edit event's properties">properties</button>
                                    <button class="btn btn-mini" type="submit" value="delete_event" 
                                        name="action" title="delete event"">delete</button>
                                    <?php if ($event['registration_required'] == 1) {
                                            echo '<button class="btn btn-mini" type="submit" value="view_event_attendees" 
                                        name="action" title="attendees">attendees</button>'; } ?></td>
                                </div>
                            </form>
                        </tr>
                        <?php endforeach; ?>
                    </table>
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