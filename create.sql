CREATE TABLE `cr_cache` (
`id` VARCHAR( 255 ) NOT NULL ,
`information` VARCHAR( 30000 ) NOT NULL ,
PRIMARY KEY ( `id` ),
INDEX (`id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;