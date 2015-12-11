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
                    <div class="row-fluid">
                        <div class="span8">
                            <h4>Convention Store</h4>
                            <h5>Add New Category</h5>
                            <a href="?add_category">add new category</a>
                            <h5>Current Categories</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <?php foreach($categories as $category): ?>
                                <tr>
                                    <td><?php htmlout($category['name']); ?></td>
                                    <td>
                                        <form action="?" method="post">
                                            <input type="hidden" name="categoryID" value="<?php htmlout($category['categoryID']); ?>">
                                            <button class="btn btn-mini" type="submit" value="delete_category" name="action" title="delete">delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                        <div class="span4">
                        </div>
                    </div>
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
