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
                    <h4>Edit Event Property</h4>
                    <form action="?" method="post">
                        <div>
                            <label for="property">Property:</label><input type="text" 
                                name="property" id="property"
                                value="<?php echo $result['property']; ?>">
                            <input type="hidden" name="id" 
                                value="<?php echo $result['id']; ?>">
                            <p><button class="btn" type="submit" value="update_event_property" 
                                name="action" title="edit">update</button></p>
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