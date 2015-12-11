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
                    <h4>Edit Event Properties for: "<?php htmlout($eventinfo['name']); ?>"</h4>
                    <h5>Currently Assigned Event Properties</h5>
                    <table>
                        <tr>
                        <td>properties</td>
                        <td></td>
                        </tr>
                        <?php foreach ($eventprops as $row): ?>
                            <form action="?" method="post">
                            <input type="hidden" name="eventID" 
                                   value="<?php echo $eventinfo['id']; ?>">
                            <input type="hidden" name="eventpropertyID" 
                                   value="<?php echo $row['eventpropertyID']; ?>">
                            <tr>
                                <div>
                                    <td><?php htmlout($row['property']); ?></td>
                                    <td><button class="btn btn-mini" type="submit" value="delete_events_property" 
                                        name="action" title="delete">delete</button></td>
                                </div>
                            </tr>
                            </form>
                            <?php endforeach; ?>
                    </table>
                    <h5>Add Property for This Event</h5>
                    <form action="?" method="post">
                        <input type="hidden" name="eventID" value="<?php echo $eventinfo['id']; ?>">
                        <div>
                            <label for="eventpropertyID">Property:</label>
                            <select name="eventpropertyID" id="eventpropertyID">
                            <option value="">Select one</option>
                            <?php foreach ($props as $prop): ?>
                            <option value="<?php htmlout($prop['id']); ?>">
                            <?php htmlout($prop['property']); ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <button class="btn" type="submit" value="add_events_property" 
                            name="action" title="add">add</button>
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