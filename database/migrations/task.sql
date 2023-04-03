CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_creatorid` int(11) DEFAULT NULL,
  `task_clientid` int(11) DEFAULT NULL COMMENT 'optional',
  `task_projectid` int(11) DEFAULT NULL COMMENT 'project_id',
  `task_date_start` date DEFAULT NULL,
  `task_date_due` date DEFAULT NULL,
  `task_title` varchar(250) DEFAULT NULL,
  `task_description` text,
  `task_client_visibility` varchar(100) DEFAULT 'yes',
  `task_milestoneid` int(11) DEFAULT NULL COMMENT 'new tasks must be set to the [uncategorised] milestone',
  `task_previous_status` varchar(100) DEFAULT 'new',
  `task_priority` varchar(100) NOT NULL DEFAULT 'normal' COMMENT 'normal | high | urgent',
  `task_status` varchar(100) NOT NULL DEFAULT 'new' COMMENT 'new | in_progress | testing | awaiting_feedback | completed',
  `task_active_state` varchar(100) DEFAULT 'active' COMMENT 'active|archived',
  `task_visibility` varchar(40) DEFAULT 'visible' COMMENT 'visible|hidden (used to prevent tasks that are still being cloned from showing in tasks list)',

  KEY `task_creatorid` (`task_creatorid`),
  KEY `task_clientid` (`task_clientid`),
  KEY `task_billable` (`task_billable`),
  KEY `task_milestoneid` (`task_milestoneid`),
  KEY `task_status` (`task_status`),
  KEY `task_priority` (`task_priority`),
  KEY `taskresource_id` (`task_projectid`),
  KEY `task_visibility` (`task_visibility`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='[truncate]';


DROP TABLE IF EXISTS `tasks_assigned`;
CREATE TABLE `tasks_assigned` (
  `tasksassigned_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[truncate]',
  `tasksassigned_taskid` int(11) NOT NULL,
  `tasksassigned_userid` int(11) DEFAULT NULL,
  `tasksassigned_created` datetime DEFAULT NULL,
  `tasksassigned_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`tasksassigned_id`),
  KEY `tasksassigned_taskid` (`tasksassigned_taskid`),
  KEY `tasksassigned_userid` (`tasksassigned_userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='[truncate]';

