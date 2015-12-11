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
                    <h4>Free Badges</h4>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Revoke Badge</th>
                            </tr>
                        </thead>
                    <?php foreach($users_with_free_badges as $row): ?>
                        <tr>
                            <td><?php htmlout($row['first_name']);
                                      echo ' ';
                                      htmlout($row['last_name']); ?></td>
                            <td><?php htmlout($row['email']); ?></td>
                            <td><form action="?" method="post">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button class="btn btn-mini" type="submit" value="revoke_free_badge" 
                                    name="action" title="revoke free badge">revoke free badge</button>
                                </form></td>
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