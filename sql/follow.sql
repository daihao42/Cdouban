create table if not exists `mydouban`.`follow`(
	`id` int(11) not NULL auto_increment,
	`user_id` int(11) default NULL,
	`movie_id` int(11) default NULL,
	`time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	primary key (`id`)
	)engine=myisam character set utf8 collate utf8_unicode_ci;