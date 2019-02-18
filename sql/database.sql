CREATE TABLE `shows` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique numeric ID for primary key',
  `ip_address` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'User IP address (or hostname)',
  `user_agent` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Raw UserAgent of the user',
  `view_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last time the page was accessed by the user identified by ip and useragent\nhandled automatically by MySQL',
  `page_url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'page url for which banner hits are recorded',
  `views_count` int(10) unsigned NOT NULL COMMENT 'Number of hits per user/page',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `uqUser` (`ip_address`,`user_agent`,`page_url`) COMMENT 'This index disallows addition of user duplicates'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
