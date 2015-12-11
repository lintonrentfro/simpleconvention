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
                    <h4>Convention Store</h4>
                    <h5>Add Item</h5>
                    <form action="?" method="post" class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label" for="name">Name:</label>
                            <div class="controls">
                                <input type="text" name="name" id="name">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="type">Category:</label>
                            <div class="controls">
                                <select name="category" id="category">
                                <option value="">Select one</option>
                                <?php foreach ($categories as $category): ?>
                                <option value="<?php htmlout($category['name']); ?>">
                                <?php htmlout($category['name']); ?></option>
                                <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <span class="help-block">(for $20.00, enter "2000")</span>
                        <div class="control-group">
                            <label class="control-label" for="name">Price:</label>
                            <div class="controls">
                                <input type="text" name="price" id="price">
                            </div>
                        </div>
                        <span class="help-block">Example: "/img/store/item1.png"</span>
                        <div class="control-group">
                            <label class="control-label" for="name">Image:</label>
                            <div class="controls">
                                <input type="text" name="image" id="image">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="name">Description:</label>
                            <div class="controls">
                                <textarea class="span8" id="description" name="description" rows="6"></textarea>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <button class="btn" type="submit" value="add_item" name="action" title="add item">add item</button>
                            </div>
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
