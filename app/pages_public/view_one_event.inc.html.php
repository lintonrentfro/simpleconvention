<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/pages_public/_head.inc.html'; ?>
        <title>Game Convention Template - Event Details</title>
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
                    <h4><?php htmlout($event['name']); ?> (<?php htmlout($event['type']); ?>)</h4>
                    <table class="table table-bordered">
                        <tr>
                            <td>Location:</td>
                            <td><?php htmlout($event['building']); 
                                    echo ' ';
                                    htmlout($event['room']);
                                    ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Start:</td>
                            <td><?php htmlout(date("m/d/y g:i A", strtotime($event['start']))); ?></td>
                        </tr>
                        <tr>
                            <td>End:</td>
                            <td><?php htmlout(date("m/d/y g:i A", strtotime($event['end']))); ?></td>
                        </tr><?php if ($event['registration_required'] == 1) {
                                $available = $event['maxusers'] - $event['currentusers'];
                                echo '<tr><td>Open Seats:</td><td>';
                                htmlout($available);
                                echo '</td></tr>'; } ?>
                        <tr>
                            <td>Contact:</td>
                            <td><?php if($event['contact_email_displayed'] == 1) {
                                    echo '<a href="mailto:' . $event['email'] . '">';
                                    echo $event['first_name'] . ' ' . $event['last_name'] . '</a></td></tr>';
                                    }
                                    else {
                                        echo $event['first_name'] . ' ' . $event['last_name'];
                                    } ?></td>
                        <tr>
                            <td>Description:</td>
                            <td><?php htmlout($event['description']); ?></td></tr>
                    </table>
                    <form action="?" method="post">
                        <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                        <?php 
                            if ($event['status'] == 'on schedule - all clear') {
                                if ($event['registration_required'] == 1) {
                                    if (in_array($event['id'], $registered_events)) {
                                            echo 'You are registered for this event.';
                                    }
                                    else {
                                        $seats_left = $event['maxusers'] - $event['currentusers'];
                                        if ($seats_left > 0) {
                                            echo '<button class="btn btn-small"  type="submit" value="register_for_event" 
                                                name="action" title="register">register</button>'; 
                                        }
                                        else {
                                            echo '<button class="btn btn-small"  type="submit" value="overflow_notification" 
                                                name="action" title="overflow_notification">request</button>';
                                        }
                                    }
                                }
                                if ($event['registration_required'] == 0) {
                                    if (in_array($event['id'], $registered_events)) {
                                        echo 'This event is on your itinerary.';
                                    }
                                    else {
                                        echo '<button class="btn btn-small"  type="submit" value="put_event_on_itinerary" 
                                                                name="action" title="put on schedule">put on schedule</button>';
                                    }
                                }
                            }
                            ?>
                    </form>
                    <?php 
                        if ($event['status'] == 'on schedule - all clear') {
                            if ($current_year['event_shoutboxes'] == 1) {
                                if ($event['shoutbox'] == 1) {
                                    echo '<h4>Event Discussion</h4>';
                                    echo shoutboxDisplay($event['id']);
                                    echo shoutboxForm($event['id']);
                                }
                            }
                            if ($current_year['event_shoutboxes'] == 0) {
                                echo '<!--event discussion not enabled-->';
                            }
                        }
                        else {
                            echo '<h4>This event is pending approval.</h4>';
                        } ?>
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