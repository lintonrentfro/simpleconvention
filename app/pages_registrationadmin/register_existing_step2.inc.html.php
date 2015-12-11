<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/includes_html/css.inc.html.php'; ?>
        <meta charset="utf-8">
        <title>Game Convention Template - Registration Admin</title>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12">
                    <img src="/img/logo.png" />
                </div>
            </div>
            <div class="row-fluid">
                <div class="span3">
                    <?php include '/home/simpleco/demo2/app/includes_html/menu_registration_admin.inc.html.php'; ?>
                </div>
                <div class="span9">
                    <h4>Register Existing User</h4>
                    <h5>Search Results</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Cell Phone</th>
                                <th>City</th>
                                <th>State</th>
                            </tr>
                        </thead>
                        <?php foreach ($search_result as $user): ?>
                        <tr>
                            <form action="?" method="post">
                                <div>
                                    <td><?php htmlout($user['first_name']); echo ' ';
                                        htmlout($user['last_name']); ?></td>
                                    <td><?php htmlout($user['email']); ?></td>
                                    <td><?php htmlout($user['cell']); ?></td>
                                    <td><?php htmlout($user['city']); ?></td>
                                    <td><?php htmlout($user['state']); ?></td>
                                    <input type="hidden" name="userID" 
                                            value="<?php echo $user['id']; ?>">
                                    <td><button type="submit" value="buy_badge" 
                                        name="action" title="buy badge">buy badge</button></td>
                                </div>
                            </form>
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