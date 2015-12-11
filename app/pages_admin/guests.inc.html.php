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
                    <h4>Guest Management</h4>
                    * create new guests or remove existing guests <a href="/admin/?view_users">here</a>
                    <h5>Current Guests and Events</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name - Professional Name</th>
                                <th>Bio</th>
                                <th>Events</th>
                                <th>Photo URL</th>
                            </tr>
                        </thead>
                        <?php foreach ($guests as $guest): ?>
                        <tr>
                            <td><?php 
                                    htmlout($guest['first_name']);
                                    echo ' ';
                                    htmlout($guest['last_name']);
                                    echo ' - ';
                                    htmlout($guest['professional_name']);
                                ?>
                                
                                <!-- Button to trigger modal -->
                                <a href="#professional_name<?php echo $guest['userID']; ?>" role="button" data-toggle="modal"><i class="icon-edit icon-white""></i></a>
                                <!-- Modal -->
                                <div id="professional_name<?php echo $guest['userID']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <form action="?" method="post">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h3 id="myModalLabel">Professional Name</h3>
                                        </div>
                                        <div class="modal-body">
                                            <input type="text" name="field" id="field" value="<?php htmlout($guest['professional_name']); ?>">
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="userID" value="<?php echo $guest['userID']; ?>">
                                            <input type="hidden" name="field_to_change" value="professional_name">
                                            <button class="btn" data-dismiss="modal" aria-hidden="true">close</button>
                                            <button class="btn" type="submit" value="update_guest" name="action" title="save">save</button>
                                        </div>
                                    </form>
                                </div>
                                
                            </td>
                            <td>
                                <!-- Button to trigger modal -->
                                <a href="#short<?php echo $guest['userID']; ?>" role="button" data-toggle="modal">short</a> |
                                <!-- Modal -->
                                <div id="short<?php echo $guest['userID']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <form action="?" method="post">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h3 id="myModalLabel">Short Description</h3>
                                        </div>
                                        <div class="modal-body">
                                            <textarea class="span12" id="field" maxlength="300" name="field" rows="10"><?php  htmlout($guest['short_description']); ?></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="userID" value="<?php echo $guest['userID']; ?>">
                                            <input type="hidden" name="field_to_change" value="short_description">
                                            <button class="btn" data-dismiss="modal" aria-hidden="true">close</button>
                                            <button class="btn" type="submit" value="update_guest" name="action" title="save">save</button>
                                        </div>
                                    </form>
                                </div>
                                
                                <!-- Button to trigger modal -->
                                <a href="#long<?php echo $guest['userID']; ?>" role="button" data-toggle="modal">long</a>
                                <!-- Modal -->
                                <div id="long<?php echo $guest['userID']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <form action="?" method="post">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h3 id="myModalLabel">Long Description</h3>
                                        </div>
                                        <div class="modal-body">
                                            <textarea class="span12" id="field" maxlength="2000" name="field" rows="10"><?php  htmlout($guest['full_description']); ?></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="userID" value="<?php echo $guest['userID']; ?>">
                                            <input type="hidden" name="field_to_change" value="full_description">
                                            <button class="btn" data-dismiss="modal" aria-hidden="true">close</button>
                                            <button class="btn" type="submit" value="update_guest" name="action" title="save">save</button>
                                        </div>
                                    </form>
                                </div>
                            </td>
                            <td>
                                <a href="/admin/?id=<?php echo $guest['userID']; ?>&type=all&action=guest_schedule"><i class="icon-edit icon-white""></i></a>
                                
                            </td>
                            <td>
                                <?php 
                                    htmlout($guest['photo_url']);
                                    echo ' ';
                                ?>
                                <!-- Button to trigger modal -->
                                <a href="#photo_url<?php echo $guest['userID']; ?>" role="button" data-toggle="modal"><i class="icon-edit icon-white""></i></a> 
                                <!-- Modal -->
                                <div id="photo_url<?php echo $guest['userID']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <form action="?" method="post">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h3 id="myModalLabel">Photo URL</h3>
                                        </div>
                                        <div class="modal-body">
                                            <input type="text" name="field" id="field" value="<?php htmlout($guest['photo_url']); ?>">
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="userID" value="<?php echo $guest['userID']; ?>">
                                            <input type="hidden" name="field_to_change" value="photo_url">
                                            <button class="btn" data-dismiss="modal" aria-hidden="true">close</button>
                                            <button class="btn" type="submit" value="update_guest" name="action" title="save">save</button>
                                        </div>
                                    </form>
                                </div>
                                
                                <!-- Button to trigger modal -->
                                <a href="#photo_preview<?php echo $guest['userID']; ?>" role="button" data-toggle="modal"><i class="icon-eye-open icon-white""></i></a>
                                <!-- Modal -->
                                <div id="photo_preview<?php echo $guest['userID']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h3 id="myModalLabel"><?php htmlout($guest['professional_name']); ?></h3>
                                        </div>
                                        <div class="modal-body">
                                            <img src="<?php htmlout($guest['photo_url']); ?>" class="img-rounded">
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn" data-dismiss="modal" aria-hidden="true">close</button>
                                        </div>
                                </div>
                                
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>