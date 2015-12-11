<?php

if($con_info['ads_on'] == 1) {
    
    $sql = 'SELECT adID, image_url, link_url 
        FROM ads 
        WHERE on_or_off = 1';
    $s = $pdo->prepare($sql);
    $s->execute();
    $ads = $s->fetchall(PDO::FETCH_ASSOC);
    
    $adID_to_display = array_rand($ads, 1);
    
    echo '<a href="' . $ads[$adID_to_display]['link_url'] . '">' .  '<img src="' . $ads[$adID_to_display]['image_url'] . '" ALT = "' . $ads[$adID_to_display]['link_url'] . '"></a><br>';
}