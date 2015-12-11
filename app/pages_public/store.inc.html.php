<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/pages_public/_head.inc.html'; ?>
        <title>Game Convention Template - Store</title>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span1"></div>
                <div class="span10">
                    <img src="/img/logo.png" />
                </div>
                <div class="span1"></div>
            </div>
            <div class="row-fluid">
                <div class="span1"></div>
                <div class="span10">
                    <?php include '/home/simpleco/demo2/app/includes_html/menu_public.inc.html.php'; ?>
                </div>
                <div class="span1"></div>
            </div>
            <div class="row-fluid">
                <div class="span1"></div>
                <div class="span10">
                    <h4>Convention Store</h4>
                    <h5>Select Item Category</h5>
                    <form action="?" method="get" class="form-inline">
                        <select name="category" id="category">
                        <option value="">Select One</option>
                        <option value="All">All</option>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?php htmlout($category['name']); ?>">
                        <?php htmlout($category['name']); ?></option>
                        <?php endforeach; ?>
                        </select>
                        <button class="btn" type="submit" value="search_items_by_category" 
                        name="action" title="search items by category">search</button>
                    </form>
                    <?php
                        if (isset($_GET['category'])) {
                            echo '<h5>' . $_GET['category'] . '</h5>' . "\n";
                        } ?>
                    <?php
                        if (isset($items)) {
                            echo '<table class="table">' . "\n";
                            foreach ($items as $item):
                                echo '<tr>' . "\n";
                                echo '<td><a href="/?itemID=' . $item['itemID'] . '&action=item"><img class="img-rounded" src="' . $item['image'] . '" width="50" height="50" alt="item image"></a></td>' . "\n";
                                echo '<td><a href="/?itemID=' . $item['itemID'] . '&action=item">' . $item['name'] . '</a></td>' . "\n";
                                echo '<td><a href="/?itemID=' . $item['itemID'] . '&action=item">';
                                setlocale(LC_MONETARY, 'en_US');
                                echo money_format('%.2n', $item['price']/100);
                                echo '</a></td>' . "\n";
                                echo '</tr>' . "\n";
                            endforeach;
                            echo '</table>' . "\n";
                        } ?>
                </div>
                <div class="span1"></div>
            </div>
        </div>
    </body>
</html>