<?php

$body = "
<html>
<body>
<h4>Store Receipt</h4>
<h5>Purchase Date: <?php echo date('m-d-y'); ?></h5>
<h5>Shipping Information</h5>
<p>" . 
    $user_info['first_name'] . " " . $user_info['last_name'] . "<br>" . 
    $user_info['address1'] . ", " . $user_info['address2'] . "<br>" . 
    $user_info['city'] . ", " . $user_info['state'] . " " . $user_info['zip'] . 
"</p>
<h5>Items</h5>
<table>
    <tr>
        <th>Description</th>
        <th>Price Each</th>
        <th>Quantity</th>
    </tr>";
setlocale(LC_MONETARY, 'en_US');
foreach ($cart_items as $item):
$body .= "<tr><td>" . $item['name'] . "</td>
        <td>";
$body .= money_format('%.2n', $item['price']/100);
$body .= "</td><td>" . $item['quantity'] . "</td></tr>";
endforeach;

$body .= "</table><strong>Total: " . $total . "</strong></body></html>";
