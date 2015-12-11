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
                    <form action="?" method="post">
                        <label for="searchby">Search by:</label>
                        <select name="searchby" id="searchby">
                        <option value="">Select one</option>
                        <option value="email">email</option>
                        <option value="last_name">last name</option>
                        <option value="cell">cell phone</option>
                        </select>
                        <label for="search_text">Containing:</label>
                        <input type="text" name="search_text" id="search_text">
                        <div>
                            <button class="btn" type="submit" value="search_users" name="action" title="search">search</button>
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