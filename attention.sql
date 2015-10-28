create table if not exists `mydouban`.`attention`(
	`id` int(11) not NULL auto_increment,
	`atten_id` int(11) default NULL,
	`attened_id` int(11) default NULL,
	primary key (`id`)
	)engine=myisam character set utf8 collate utf8_unicode_ci;