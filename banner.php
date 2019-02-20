<?php

/**
 * This is a controller that shows the banner to the user, recording the hit.
 * If the same user visits a page more than once, the hit counter is updated
 */
require_once './conf/db_config.php'; // database credentials saved in separate file, array dbc
require_once './visit.php'; // db model class for page visits

// record banner hit/visit
$visit = new Visit($dbc);
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$ip = $_SERVER['REMOTE_ADDR'];
$page = $_SERVER['HTTP_REFERER']; // 
$visit->save($ip, $userAgent, $page);

// show banner to user - banner is a static image, as banner selection mechanism is not specified.
// select banner to show - just one for demo
// detect banner image type/ content type - hardcode for demo
header('Content-Type:image/jpeg');
$bannerImage = file_get_contents(__DIR__ . '/img/radio.jpg');
echo $bannerImage; // output the banner
exit; // force exit to avoid possible error/warning messages corrupting the image output
