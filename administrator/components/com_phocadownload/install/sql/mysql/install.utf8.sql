-- -------------------------------------------------------------------- --
-- Phoca Download manual installation                                   --
-- -------------------------------------------------------------------- --
-- See documentation on https://www.phoca.cz/                            --
--                                                                      --
-- Change all prefixes #__ to prefix which is set in your Joomla! site  --
-- (e.g. from #__phocadownload to jos_phocadownload)                    --
-- Run this SQL queries in your database tool, e.g. in phpMyAdmin       --
-- If you have questions, just ask in Phoca Forum                       --
-- https://www.phoca.cz/forum/                                           --
-- -------------------------------------------------------------------- --

CREATE TABLE IF NOT EXISTS `#__phocadownload_categories` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL default 0,
  `section` int(11) NOT NULL default 0,
  `title` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `alias` varchar(255) NOT NULL default '',
  `project_name` varchar(255) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  `image_position` varchar(30) NOT NULL default '',
  `description` text,
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `editor` varchar(50) default NULL,
  `ordering` int(11) NOT NULL default '0',
  `access` int(11) unsigned NOT NULL default '0',
  `uploaduserid` text,
  `accessuserid` text,
  `deleteuserid` text,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `count` int(11) NOT NULL default '0',
  `hits` int(11) NOT NULL default '0',
  `params` text,
  `metakey` text,
  `metadesc` text,
  `metadata` text,
  `language` char(7) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `cat_idx` (`section`,`published`,`access`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`)
) default CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__phocadownload` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `catid` int(11) NOT NULL default '0',
  `sectionid` int(11) NOT NULL default '0',
  `sid` int(11) NOT NULL default '0',
  `owner_id` int(11) NOT NULL default 0,
  `title` varchar(250) NOT NULL default '',
  `alias` varchar(255) NOT NULL default '',
  `project_name` varchar(255) NOT NULL default '',
  `filename` varchar(250) NOT NULL default '',
  `filename_play` varchar(250) NOT NULL default '',
  `filename_preview` varchar(250) NOT NULL default '',
  `filesize` int(11) NOT NULL default 0,
  `author` varchar(255) NOT NULL default '',
  `author_email` varchar(255) NOT NULL default '',
  `author_url` varchar(255) NOT NULL default '',
  `license` varchar(255) NOT NULL default '',
  `license_url` varchar(255) NOT NULL default '',
  `image_filename` varchar(255) NOT NULL default '',
  `image_filename_spec1` varchar(255) NOT NULL default '',
  `image_filename_spec2` varchar(255) NOT NULL default '',
  `image_download` varchar(255) NOT NULL default '',
  `video_filename` varchar(255) NOT NULL default '',
  `link_external` varchar(255) NOT NULL default '',
  `mirror1link` varchar(255) NOT NULL default '',
  `mirror1title` varchar(255) NOT NULL default '',
  `mirror1target` varchar(10) NOT NULL default '',
  `mirror2link` varchar(255) NOT NULL default '',
  `mirror2title` varchar(255) NOT NULL default '',
  `mirror2target` varchar(10) NOT NULL default '',
  `description` text,
  `features` text,
  `changelog` text,
  `notes` text,
  `userid` int(11) NOT NULL default '0',
  `version` varchar(255) NOT NULL default '',
  `directlink` tinyint(1) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL default '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL default '0000-00-00 00:00:00',
  `hits` int(11) NOT NULL default '0',
  `textonly` tinyint(1) NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '0',
  `approved` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',
  `access` int(11) unsigned NOT NULL default '0',
  `confirm_license` int(11) NOT NULL default '0',
  `unaccessible_file` int(11) NOT NULL default '0',
  `token` char(64) default NULL,
  `tokenhits` int(11) NOT NULL default 0,
  `tags_string` varchar(255) NOT NULL default '',
  `params` text,
  `metakey` text,
  `metadesc` text,
  `metadata` text,
  `language` char(7) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `catid` (`catid`,`published`)
) default CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__phocadownload_user_stat` (
  `id` int(11) NOT NULL auto_increment,
  `fileid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  `language` char(7) NOT NULL default '',
  PRIMARY KEY  (`id`)
) default CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__phocadownload_licenses` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `alias` varchar(255) NOT NULL default '',
  `description` text,
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  `language` char(7) NOT NULL default '',
  PRIMARY KEY  (`id`)
) default CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__phocadownload_file_votes` (
  `id` int(11) NOT NULL auto_increment,
  `fileid` int(11) NOT NULL default 0,
  `userid` int(11) NOT NULL default 0,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `rating` tinyint(1) NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',
  `params` text,
  `language` char(7) NOT NULL default '',
  PRIMARY KEY  (`id`)
) default CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__phocadownload_file_votes_statistics` (
  `id` int(11) NOT NULL auto_increment,
  `fileid` int(11) NOT NULL default 0,
  `count` int(11) NOT NULL default '0',
  `average` float(8,6) NOT NULL default '0',
  `language` char(7) NOT NULL default '',
  PRIMARY KEY  (`id`)
) default CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__phocadownload_tags` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `alias` varchar(255) NOT NULL default '',
  `link_ext` varchar(255) NOT NULL default '',
  `link_cat` int(11) unsigned NOT NULL default '0',
  `description` text,
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',
  `params` text,
  `language` char(7) NOT NULL default '',
  PRIMARY KEY  (`id`)
) default CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__phocadownload_tags_ref` (
  `id` SERIAL,
  `fileid` int(11) NOT NULL default 0,
  `tagid` int(11) NOT NULL default 0,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `i_fileid` (`fileid`,`tagid`)
) default CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__phocadownload_layout` (
  `id` int(11) NOT NULL auto_increment,
  `categories` text,
  `category` text,
  `file` text,
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `params` text,
  PRIMARY KEY  (`id`)
) default CHARSET=utf8;

INSERT INTO `#__phocadownload_layout` (

`categories` ,
`category` ,
`file` ,
`checked_out` ,
`checked_out_time` ,
`params`
)
VALUES (
 '<div class="pd-categoriesbox">
<div class="pd-title">{pdtitle}</div>
{pdsubcategories}
{pdclear}
</div>',

'<div class="pd-filebox">
{pdfiledesctop}
{pdfile}
<div class="pd-buttons">{pdbuttondownload}</div>
<div class="pd-buttons">{pdbuttondetails}</div>
<div class="pd-buttons">{pdbuttonpreview}</div>
<div class="pd-buttons">{pdbuttonplay}</div>
<div class="pd-mirrors">{pdmirrorlink2} {pdmirrorlink1}</div>
<div class="pd-rating">{pdrating}</div>
<div class="pd-tags">{pdtags}</div>
{pdfiledescbottom}
<div class="pd-cb"></div>
</div>'

, '<div class="pd-filebox">
{pdimage}
{pdfile}
{pdfilesize}
{pdversion}
{pdlicense}
{pdauthor}
{pdauthoremail}
{pdfiledate}
{pddownloads}
{pddescription}
{pdfeatures}
{pdchangelog}
{pdnotes}
<div class="pd-mirrors">{pdmirrorlink2} {pdmirrorlink1}</div>
<div class="pd-report">{pdreportlink}</div>
<div class="pd-rating">{pdrating}</div>
<div class="pd-tags">{pdtags}</div>
<div class="pd-cb"></div>
</div>' , '0', '0000-00-00 00:00:00', NULL
);

CREATE TABLE IF NOT EXISTS `#__phocadownload_styles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `menulink` text,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `params` text,
  `language` char(7) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 ;

INSERT INTO `#__phocadownload_styles` (`id`, `title`, `alias`, `filename`, `menulink`, `type`, `published`, `checked_out`, `checked_out_time`, `ordering`, `params`, `language`) VALUES
(1, 'Phocadownload', 'phocadownload', 'phocadownload.css', NULL, 1, 1, 0, '0000-00-00 00:00:00', 1, NULL, '*'),
(2, 'Rating', 'rating', 'rating.css', NULL, 1, 1, 0, '0000-00-00 00:00:00', 2, NULL, '*'),
(3, 'Button', 'button', 'button.css', NULL, 1, 0, 0, '0000-00-00 00:00:00', 3, NULL, '*'),
(4, 'Button (Rounded Corners)', '', 'buttonrc.css', NULL, 1, 0, 0, '0000-00-00 00:00:00', 4, NULL, '*'),
(5, 'Default', 'default', 'default.css', NULL, 2, 1, 0, '0000-00-00 00:00:00', 1, NULL, '*'),
(6, 'Bootstrap', '', 'bootstrap.min.css', NULL, 1, 0, 0, '0000-00-00 00:00:00', 5, NULL, '*');

CREATE TABLE IF NOT EXISTS `#__phocadownload_logging` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `fileid` int(11) NOT NULL default '0',
  `catid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  `ip` varchar(20) NOT NULL default '',
  `page` varchar(255) NOT NULL,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `params` text,
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 ;
