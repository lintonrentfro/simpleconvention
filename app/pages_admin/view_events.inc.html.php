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
                    <h4>Events</h4>
                    <a href="?add_event">add new event</a><br>
                    <a href="?view_event_overflow">view requests for additional events</a>
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
                    <h5>Event Filter</h5>
                    <form action="?" method="post" class="form-inline">
                        <select name="searchbytype" id="searchbytype">
                        <option value="">Types of Events</option>
                        <?php foreach ($event_types as $type): ?>
                        <option value="<?php htmlout($type['event_type_desc']); ?>">
                        <?php htmlout($type['event_type_desc']); ?></option>
                        <?php endforeach; ?>
                        </select>
                        <button class="btn" type="submit" value="search_events_by_type" 
                        name="action" title="search events by type">search</button>
                    </form>
                    <form action="?" method="post" class="form-inline">
                        <select name="eventpropertyID" id="eventpropertyID">
                        <option value="">Event Properties</option>
                        <?php foreach ($event_properties as $property): ?>
                        <option value="<?php htmlout($property['id']); ?>">
                        <?php htmlout($property['property']); ?></option>
                        <?php endforeach; ?>
                        </select>
                        <button class="btn" type="submit" value="search_events_by_property" 
                        name="action" title="search events by property">search</button>
                    </form>
                    <h5>All Events</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name/Location</th>
                                <th>Start/End</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <?php foreach ($events as $event): ?>
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
                                    <button class="btn btn-mini" type="submit" value="add_duty_to_this_event" 
                                        name="action" title="add duty">add duty</button>
                                    <button class="btn btn-mini" type="submit" value="delete_event" 
                                        name="action" title="delete event">delete</button>
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