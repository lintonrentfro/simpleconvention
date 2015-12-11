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
                    <h4>Schedule Conflicts for All Guests</h4>
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>Guest</th>
                                <th>Event</th>
                                <th>Location</th>
                                <th>Start</th>
                                <th>End</th>
                            </tr>
                        </thead>
                        <?php
                            foreach ($guests as $guest):
                        
                                foreach($events as $event):
                                    foreach ($guests_events as $row):
                                        if (($row['eventID'] == $event['id']) and ($row['userID'] == $guest['userID'])) {
                                            $on_schedule = 'yes';
                                            } else {
                                                $on_schedule = 'no';
                                        }
                                    endforeach;
                                    $conflict_count = 0;
                                    foreach ($guests_events as $row):
                                        if ($row['userID'] == $guest['userID']) {
                                            if ( (($row['start'] < $event['start']) AND ($row['end'] > $event['start'])) or
                                                (($row['end'] > $event['end']) AND ($row['start'] < $event['end'])) or
                                                (($row['end'] > $event['start']) AND ($row['end'] < $event['end'])) or
                                                (($row['start'] > $event['start']) AND ($row['end'] < $event['end'])) ) {
                                                $conflict_count = $conflict_count + 1;
                                            }
                                        }
                                    endforeach;
                                    if ($conflict_count > 0) {
                                        $conflicts = 'yes';
                                        } else {
                                                $conflicts = 'no';
                                    }
                                    if ($on_schedule == 'yes') {
                                        if ($conflicts == 'yes') {
                                            echo '<tr class="red"><td>';
                                            echo $guest['professional_name'] . ' <a href="/admin/?id=' . $guest['userID'] . '&action=guest_schedule"><i class="icon-edit icon-white"></i></a>';
                                            echo '</td>';
                                            echo '<td>';
                                            htmlout($event['name']);
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
                                            echo '</td></tr>';  
                                        }
                                    }
                                    unset($on_schedule);
                                    unset($conflict_count);
                                    unset($conflicts);
                                endforeach;
                            
                            endforeach;
                            
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>