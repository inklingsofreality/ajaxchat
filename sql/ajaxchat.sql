-- phpMyAdmin SQL Dump
-- version 2.6.0-rc2
-- http://www.phpmyadmin.net
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `chat_pages`
-- 

CREATE TABLE `chat_pages` (
  `page_id` tinyint(5) NOT NULL auto_increment,
  `group_id` tinyint(5) default '0',
  `description` tinytext NOT NULL,
  `page` varchar(100) NOT NULL default '',
  `start_time` time NOT NULL default '00:00:00',
  `end_time` time NOT NULL default '00:00:00',
  `days_of_week` set('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') NOT NULL default '',
  PRIMARY KEY  (`page_id`)
) TYPE=MyISAM AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `chat_transcript`
-- 

CREATE TABLE `chat_transcript` (
  `transcript_id` tinyint(5) NOT NULL auto_increment,
  `user_id` tinyint(5) NOT NULL default '0',
  `page_id` tinyint(5) NOT NULL default '0',
  `text` text NOT NULL,
  `timestamp` timestamp(14) NOT NULL,
  PRIMARY KEY  (`transcript_id`)
) TYPE=MyISAM AUTO_INCREMENT=115 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `chat_users`
-- 

CREATE TABLE `chat_users` (
  `user_id` tinyint(5) NOT NULL auto_increment,
  `group_id` tinyint(5) default '0',
  `nickname` varchar(25) NOT NULL default '',
  `email` varchar(75) NOT NULL default '',
  `ipaddress` varchar(20) NOT NULL default '',
  `loggedin` enum('yes','no') NOT NULL default 'no',
  `last_login` timestamp(14) NOT NULL,
  PRIMARY KEY  (`user_id`)
) TYPE=MyISAM AUTO_INCREMENT=19 ;
        