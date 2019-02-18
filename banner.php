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
$page = $_SERVER['REQUEST_URI'];
$visit->save($ip, $userAgent, $page);

// show banner to user - banner is a static image, as banner selectio n mechanism is not specified.
