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
                    <h4>Event Properties</h4>
                    <h5>Current Event Properties</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Property</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <?php foreach ($eventproperties as $property): ?>
                        <tr>
                            <form action="?" method="post">
                                <div>
                                    <td><?php htmlout($property['property']); ?></td>
                                    <input type="hidden" name="id" value="<?php echo $property['id']; ?>">
                                    <td><button class="btn btn-mini" type="submit" value="edit_event_property" 
                                        name="action" title="edit">edit</button></td>
                                    <td><button class="btn btn-mini" type="submit" value="delete_event_property" 
                                        name="action" title="edit">delete</button></td>
                                </div>
                            </form>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                    <h5>Add New Event Property</h5>
                    <form class="form-inline" action="?" method="post">
                        <div>
                            <input type="text" name="property" id="property"></label>
                            <button class="btn" type="submit" value="create_event_property" name="action" title="create">create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>