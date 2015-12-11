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
                    <h4>Create New User Account</h4>
                    <p>All fields marked with a * are required.</p>
                    <form action="?" method="post">
                        <label for="firstname">*First Name:</label><input type="text" name="firstname" id="firstname">
                        <label for ="lastname">*Last Name:</label><input type="text" name="lastname" id="lastname">
                        <label for ="Company">Company:</label><input type="text" name="company" id="company">
                        <label for ="email">*Email:</label><input type="text" name="email" id="email">
                        <label for ="address1">*Address (first line):</label><input type="text" name="address1" id="address1">
                        <label for ="address2">Address (second line):</label><input type="text" name="address2" id="address2">
                        <label for ="city">*City:</label><input type="text" name="city" id="city">
                        <label for ="state">*State:</label><input type="text" name="state" id="state">
                        <label for ="zip">*Zip:</label><input type="text" name="zip" id="zip">
                        <label for ="home">Home Phone:</label><input type="text" name="home" id="home">
                        <label for ="work">Work Phone:</label><input type="text" name="work" id="work">
                        <label for ="cell">Cell Phone:</label><input type="text" name="cell" id="cell">
                        <label for ="password">*Password:</label><input type="password" name="password" id="password">
                        <p><button class="btn"  type="submit" value="create_new_user" name="action" title="create">create</button></p>
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