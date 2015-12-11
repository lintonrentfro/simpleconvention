<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/pages_public/_head.inc.html'; ?>
        <title>Game Convention Template - Convention Schedule</title>
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
                    <h4>Convention Schedule</h4>
                    <p>
                        This is where static information about the events/schedule can be placed
                        when the live schedule is not available to the public yet.
                    </p>
                    <?php 
                        if ($current_year['public_submit_events_on'] == 1) {
                            echo '<p><form action="?" method="post">
                            <button class="btn btn-small"  type="submit" value="create_event" name="action" title="create event">submit an event for the upcoming convention</button>
                        </form></p>';
                        }
                    ?>
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