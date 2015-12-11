<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/pages_public/_head.inc.html'; ?>
        <title>Game Convention Template - My Schedule</title>
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
                    <h4>My Schedule</h4>
                    <?php 
                        if (($user_is_guest_or_not['COUNT(*)'] > 0) and (!empty($events_as_guest))) {
                            include '/home/simpleco/demo2/app/includes_html/htmlsnippets/myschedule_guest.inc.html';
                        }
                        if (($eventsregisterednumber['COUNT(*)'] > 0) and (!empty($events_registered))) {
                            include '/home/simpleco/demo2/app/includes_html/htmlsnippets/myschedule_registered.inc.html';
                        }
                        if (($eventsrunningnumber['COUNT(*)'] > 0) and (!empty($events_running))) {
                            include '/home/simpleco/demo2/app/includes_html/htmlsnippets/myschedule_running.inc.html';
                        }
                        if (($eventshelpernumber['COUNT(*)'] > 0) and (!empty($events_helper))) {
                            include '/home/simpleco/demo2/app/includes_html/htmlsnippets/myschedule_helper.inc.html';
                        }
                    ?>
                </div>
                <div class="span1"></div>
            </div>
        </div>
    </body>
</html>