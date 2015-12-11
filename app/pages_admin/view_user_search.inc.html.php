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
                    <h4>Users</h4>
                    <h5>Add New User</h5>
                    <a href="?add_user">add new user</a>
                    <h5>User Search</h5>
                    <form action="?" method="post" class="form-inline">
                        <select name="searchby" id="searchby">
                        <option value="">Search By</option>
                        <?php foreach ($user_columns as $col): ?>
                        <option value="<?php htmlout($col['column_name']); ?>">
                        <?php htmlout($col['column_name']); ?></option>
                        <?php endforeach; ?>
                        </select>
                        <input type="text" name="search_text" id="search_text">
                        <button class="btn" type="submit" value="search_users" name="action" title="search">search</button>
                    </form>
                    <h5>User Filter</h5>
                    <form action="?" method="post" class="form-inline">
                        <select name="searchby" id="searchby">
                        <option value="">User Roles</option>
                        <?php foreach ($user_roles as $role): ?>
                        <option value="<?php htmlout($role['id']); ?>">
                        <?php htmlout($role['role']); ?></option>
                        <?php endforeach; ?>
                        </select>
                        <button class="btn" type="submit" value="search_user_role" name="action" title="search users">search</button>
                    </form>
                <p>Search Results</p>
                <table class="table table-striped">
                    <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Cell</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    <?php foreach ($search_result as $user): ?>
                    <tr>
                        <td><?php htmlout($user['first_name']); echo ' ';
                            htmlout($user['last_name']); ?></td>
                        <td><?php htmlout($user['email']); ?></td>
                        <td><?php htmlout($user['cell']); ?></td>
                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                        <td>
                            <div class="btn-group">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                    actions
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <?php
                                        if (in_array($user['id'], $guests)) {
                                            echo '<li><a href="localhost/admin/?id=' . $user['id'] . '&action=unmake_guest">de-guest</a></li>'; } 
                                        else {
                                            echo '<li><a href="localhost/admin/?id=' . $user['id'] . '&action=make_guest">make guest</a></li>';
                                        }
                                    ?>
                                    <li><a href="localhost/admin/?id=<?php echo $user['id']; ?>&action=edit_user">edit</a></li>
                                    <li><a href="localhost/admin/?id=<?php echo $user['id']; ?>&action=edit_user_roles">roles</a></li>
                                    <li><a href="localhost/admin/?id=<?php echo $user['id']; ?>&action=view_registered_events_for_user">events</a></li>
                                    <li><a href="localhost/admin/?id=<?php echo $user['id']; ?>&action=delete_user">delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </table>
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