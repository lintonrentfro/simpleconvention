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
                    <h4>Manage Schedule for: <?php htmlout($guest['professional_name']); ?></h4>
                    <table class="table table-condensed">
                        <tr class="green">
                            <td>on guest's schedule</td>
                        </tr>
                        <tr class="yellow">
                            <td>would conflict with events on guest's schedule</td>
                        </tr>
                        <tr class="red">
                            <td>a conflict exists on the guest's schedule</td>
                        </tr>
                    </table>
                    <p>
                    <div class="btn-group">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                        Filter Events
                        <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/admin/?id=<?php echo $guest['userID']; ?>&type=all&action=guest_schedule">all</a></li>
                            <?php foreach ($event_types as $type): ?>
                            <li><a href="/admin/?id=<?php echo $guest['userID']; ?>&type=<?php echo $type['event_type_desc']; ?>&action=guest_schedule"><?php echo $type['event_type_desc']; ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    </p>
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Start</th>
                                <th>End</th>
                            </tr>
                        </thead>
                        <?php
                            foreach($events as $event):
                                echo '<tr ';
                                foreach ($guest_events as $row):
                                    if (($row['eventID'] == $event['id']) and ($row['userID'] == $guest['userID'])) {
                                        $on_schedule = 'yes';
                                        } else {
                                            $on_schedule = 'no';
                                    }
                                endforeach;
                                $conflict_count = 0;
                                foreach ($guest_events as $row):
                                    if ( (($row['start'] < $event['start']) AND ($row['end'] > $event['start'])) or
                                        (($row['end'] > $event['end']) AND ($row['start'] < $event['end'])) or
                                        (($row['end'] > $event['start']) AND ($row['end'] < $event['end'])) or
                                        (($row['start'] > $event['start']) AND ($row['end'] < $event['end'])) ) {
                                        $conflict_count = $conflict_count + 1;
                                    }
                                endforeach;
                                if ($conflict_count > 0) {
                                    $conflicts = 'yes';
                                    } else {
                                        $conflicts = 'no';
                                }
                                if ($on_schedule == 'yes') {
                                    if ($conflicts == 'yes') {
                                        echo ' class="red" ';
                                    }
                                    if ($conflicts !== 'yes') {
                                        echo ' class="green" ';
                                    }
                                }
                                
                                if ($conflicts == 'yes') {
                                    if ($on_schedule !== 'yes') {
                                        echo ' class="yellow" ';
                                    }
                                }
                                echo '>';
                                echo '<td>';
                                htmlout($event['name']);
                                if ($on_schedule == 'yes') {
                                        echo ' <a href="/admin/?userID=' . $guest["userID"] . '&type=' . $_GET['type'] . '&eventID=' . $event["id"] . '&action=update_guest_events&query=delete"><i class="icon-minus-sign icon-white"></i></a>';
                                }
                                else {
                                    if ($conflicts != 'yes') {
                                        echo ' <a href="/admin/?userID=' . $guest["userID"] . '&type=' . $_GET['type'] . '&eventID=' . $event["id"] . '&action=update_guest_events&query=add"><i class="icon-plus-sign icon-white"></i></a>';
                                    }
                                }
                                echo '</td>';
                                echo '<td>';
                                htmlout($event['building']);
                                echo ' / ';
                                htmlout($event['room']);
                                echo '</td>';
                                echo '<td>';
                                htmlout(date("m/d g:i a", strtotime($event['start'])));
                                echo '</td>';
                                echo '<td>';
                                htmlout(date("m/d g:i a", strtotime($event['end'])));
                                echo '</td>';
                                unset($on_schedule);
                                unset($conflict_count);
                                unset($conflicts);
                            endforeach;
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>