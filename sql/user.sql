create table if not exists `mydouban`.`users`(
	`user_id` int(11) not NULL auto_increment,
	`user_name` varchar(80) default NULL,
	`user_pass` varchar(47) default NULL,
	`user_email` varchar(80) default NULL,
	`user_city` varchar(80) default NULL,
	`user_img` varchar(80) default NULL,
	`user_about` varchar(200) default NULL,
	`time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	primary key (`user_id`),
	unique (`user_name`)
	)engine=myisam character set utf8 collate utf8_unicode_ci;