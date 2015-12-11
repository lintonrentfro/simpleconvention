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
                    <h4>Manage User Roles for: 
                        <?php htmlout($userinfo['first_name']); echo ' '; htmlout($userinfo['last_name']); ?></h4>
                    <h5>Currently Assigned User Roles</h5>
                    <table>
                        <form action="?" method="post">
                            <input type="hidden" name="userID" value="<?php echo $userinfo['id']; ?>">
                            <?php foreach ($result1 as $row): ?>
                            <tr>
                            <td><?php htmlout($row['role']); ?></td>
                            <input type="hidden" name="roleID" value="<?php echo $row['roleID']; ?>">
                            <td><button class="btn btn-mini" type="submit" value="delete_users_role" name="action" title="delete">delete</button></td>
                            </tr>
                            <?php endforeach; ?>
                        </form>
                    </table>

                    <h5>Add Role</h5>
                    <form action="?" method="post">
                        <input type="hidden" name="userID" value="<?php echo $userinfo['id']; ?>">
                        <div>
    <!--                        <label for="roleID">Role:</label>-->
                            <select name="roleID" id="roleID">
                            <option value="">Select one</option>
                            <?php foreach ($roles as $role): ?>
                            <option value="<?php htmlout($role['id']); ?>">
                            <?php htmlout($role['id']); echo ' - '; htmlout($role['role']); ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <button class="btn" type="submit" value="create_new_users_role" 
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