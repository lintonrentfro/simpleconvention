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
                    <h4>Event List: <?php htmlout($event_type_shown) ?></h4>
                    <?php
                        if((isset($events)) and (!empty($events))) {
                            include '/home/simpleco/demo2/app/includes_html/htmlsnippets/view_type_of_event_table.inc.html';
                        } else {
                            echo '<p>Nothing yet ... check back later.</p>';
                        }
                    ?>
                </div>
                <div class="span1"></div>
            </div>
        </div>
    </body>
</html>