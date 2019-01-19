-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2019 at 09:56 PM
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

drop table if exists archived_user;
CREATE TABLE `archived_user`
(
  `id_archived_user` int(11)                                 NOT NULL auto_increment,
  `id`               int(11)                                 NOT NULL,
  `email`            varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles`            json                                    NOT NULL,
  `password`         varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name`       varchar(255) COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
  `last_name`        varchar(255) COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
  `agreed_terms_at`  datetime                                NOT NULL,
  `photo_id`         int(11)                                          DEFAULT NULL,
  `archive_action`   char(1)                                 not null default 'u',
  `time_archived`    timestamp                               not null default current_timestamp on update current_timestamp,
  unique key `id_archived_user` (id_archived_user, time_archived)
) PARTITION BY RANGE ( UNIX_TIMESTAMP(time_archived) ) (
  PARTITION p0 VALUES LESS THAN ( UNIX_TIMESTAMP('2018-01-01 00:00:00') ),
  PARTITION p1 VALUES LESS THAN ( UNIX_TIMESTAMP('2019-01-01 00:00:00') ),
  PARTITION p2 VALUES LESS THAN ( UNIX_TIMESTAMP('2020-01-01 00:00:00') ),
  PARTITION p3 VALUES LESS THAN ( UNIX_TIMESTAMP('2021-01-01 00:00:00') ),
  PARTITION p4 VALUES LESS THAN ( UNIX_TIMESTAMP('2022-01-01 00:00:00') ),
  PARTITION p5 VALUES LESS THAN (MAXVALUE)
  );

select *
from INFORMATION_SCHEMA.PARTITIONS
where table_schema = 'wulkanizator_test'
  and table_name = 'archived_user';

delete
from archived_user
where 1 = 1;
insert into archived_user (select * from user);

drop view if exists v_user_comments;
create view v_user_comments as
select DISTINCT user.email,
                user.first_name,
                user.last_name,
                user.roles,
                user_photo.path,
                count(comment.user_id) as liczba_komentarzy
FROM user
       left join user_photo on user_photo.id = user.photo_id
       left join comment on comment.user_id = user.id
GROUP by user.id;

drop view if exists v_user_articles;
create view v_user_articles as
select DISTINCT user.email,
                user.first_name,
                user.last_name,
                user.roles,
                user_photo.path,
                count(article.author_id) as liczba_artykulow
FROM user
       left join user_photo on user_photo.id = user.photo_id
       left join article on article.author_id = user.id
GROUP by user.id;


/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
