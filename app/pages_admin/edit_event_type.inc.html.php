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
                    <h4>Edit Event Type</h4>
                    <form action="?" method="post">
                        <div>
                            <label for="event_type_desc">Type: <input type="text" 
                                name="event_type_desc" id="event_type_desc"
                                value="<?php echo $result['event_type_desc']; ?>"></label>
                            <input type="hidden" name="event_type_id" 
                                value="<?php echo $result['event_type_id']; ?>">
                            <button class="btn" type="submit" value="update_event_type" 
                                name="action" title="update">update</button>
                        </div>
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