/* ~~~~~~~~~~~~~~~~~
   Table Definitions
   ~~~~~~~~~~~~~~~~~ */

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `stack_id_1` int(10) unsigned NOT NULL,
  `path_id_1` int(11) NOT NULL,
  `stack_id_2` int(10) unsigned NOT NULL,
  `path_id_2` int(11) NOT NULL,
  `event` text collate utf8_unicode_ci NOT NULL,
  `time_start` int(10) unsigned default NULL,
  `time_end` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`),
  KEY `stack_id_1` (`stack_id_1`,`stack_id_2`),
  KEY `time_start` (`time_start`),
  KEY `time_end` (`time_end`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `paths` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `stack_id` int(10) unsigned NOT NULL,
  `x1` double unsigned NOT NULL,
  `y1` double unsigned NOT NULL,
  `x2` double unsigned NOT NULL,
  `y2` double unsigned NOT NULL,
  `speed` int(11) NOT NULL,
  `i` double NOT NULL default '0',
  `j` double NOT NULL default '0',
  `i_unit` double NOT NULL default '0',
  `j_unit` double NOT NULL default '0',
  `time_start` int(10) unsigned NOT NULL default '0',
  `time_end` int(10) unsigned NOT NULL default '0',
  `time_check` int(10) unsigned NOT NULL default '0',
  `x_check` double unsigned NOT NULL default '0',
  `y_check` double unsigned NOT NULL default '0',
  `x_min` double NOT NULL default '0',
  `y_min` double NOT NULL default '0',
  `x_max` double unsigned NOT NULL default '0',
  `y_max` double unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `stack_id` (`stack_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `stacks` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `radius` double unsigned NOT NULL default '1',
  `stance` varchar(20) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

# Constraints
ALTER TABLE `paths`
  ADD CONSTRAINT `paths_ibfk_1` FOREIGN KEY (`stack_id`) REFERENCES `stacks` (`id`);
