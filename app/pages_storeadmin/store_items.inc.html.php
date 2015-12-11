<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/pages_storeadmin/_head.inc.html'; ?>
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
                    <?php include '/home/simpleco/demo2/app/includes_html/menu_store_admin.inc.html.php'; ?>
                </div>
                <div class="span10">
                    <h4>Convention Store</h4>
                    <h5>Add New Item</h5>
                    <a href="?add_item">add new item</a>
                    <h5>Current Items</h5>
                    <table class="table table-striped">
                     <thead>
                        <tr>
                            <th>Category</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Image</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <?php foreach($items as $item): ?>
                    <tr>
                        <td><?php htmlout($item['category']); ?></td>
                        <td><?php htmlout($item['name']); ?></td>
                        <td><?php 
                            setlocale(LC_MONETARY, 'en_US');
                            echo money_format('%.2n', $item['price']/100); ?></td>
                        <td><?php htmlout($item['image']); ?></td>
                        <td>
                            <form action="?" method="post">
                                <input type="hidden" name="itemID" value="<?php htmlout($item['itemID']); ?>">
                                <button class="btn btn-small" type="submit" value="view_item" name="action" title="edit">edit</button>
                            </form>
                        </td>
                        <td>
                            <form action="?" method="post">
                                <input type="hidden" name="itemID" value="<?php htmlout($item['itemID']); ?>">
                                <button class="btn btn-small" type="submit" value="delete_item" name="action" title="delete">delete</button>
                            </form>
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
