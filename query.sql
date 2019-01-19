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
  primary key `id_archived_user` (id_archived_user, time_archived)
) PARTITION BY RANGE ( UNIX_TIMESTAMP(time_archived) ) (
  PARTITION p0 VALUES LESS THAN ( UNIX_TIMESTAMP('2018-01-01 00:00:00') ),
  PARTITION p1 VALUES LESS THAN ( UNIX_TIMESTAMP('2019-01-01 00:00:00') ),
  PARTITION p2 VALUES LESS THAN ( UNIX_TIMESTAMP('2020-01-01 00:00:00') ),
  PARTITION p3 VALUES LESS THAN ( UNIX_TIMESTAMP('2021-01-01 00:00:00') ),
  PARTITION p4 VALUES LESS THAN ( UNIX_TIMESTAMP('2022-01-01 00:00:00') ),
  PARTITION p5 VALUES LESS THAN (MAXVALUE)
  );

delete
from archived_user
where 1 = 1;
insert into archived_user (id, email, roles, password, first_name, last_name, agreed_terms_at, photo_id)
select id,
       email,
       roles,
       password,
       first_name,
       last_name,
       agreed_terms_at,
       photo_id
from user;

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


drop trigger if exists t_archive_user_update;
create trigger t_archive_user_update
  after update
  on user
  for each row insert into archived_user (id, email, roles, password, first_name, last_name, agreed_terms_at, photo_id,
                                          archive_action)
               values (old.id, old.email, old.roles, old.password, old.first_name, old.last_name, old.agreed_terms_at,
                       old.photo_id, 'u');


drop trigger if exists t_archive_user_delete;
create trigger t_archive_user_delete
  after delete
  on user
  for each row insert into archived_user (id, email, roles, password, first_name, last_name, agreed_terms_at, photo_id,
                                          archive_action)
               values (old.id, old.email, old.roles, old.password, old.first_name, old.last_name, old.agreed_terms_at,
                       old.photo_id, 'd');

delimiter |
drop procedure if exists p_add_user_full_data |
create procedure p_add_user_full_data(in mail varchar(180), in pass varchar(255), in roles json,
                                      in first_name varchar(255), in last_name varchar(255),
                                      in agreed_terms_at datetime, in photo_id int(11))

begin
  start transaction
    ;
    insert into user (email, password, roles, first_name, last_name, photo_id, agreed_terms_at)
    values (mail, pass, roles, first_name, last_name, photo_id, agreed_terms_at);
  commit;
end |
delimiter ;

SET @p0 = 'email@email.com';
SET @p1 = 'haselko_ktore_nie_zadziala';
SET @p2 = '[\"ROLE_USER\"]';
SET @p3 = 'Imie';
SET @p4 = 'Nazwisko';
SET @p5 = '2019-01-16 03:05:07.164086';
SET @p6 = 5;
CALL `p_add_user_full_data`(@p0, @p1, @p2, @p3, @p4, @p5, @p6);


DROP FUNCTION `f_user_get_avatar_path`;
CREATE
  DEFINER =`root`@`localhost` FUNCTION `f_user_get_avatar_path`(`v_id_user` INT(11)) RETURNS VARCHAR(255) CHARSET utf8mb4 COMMENT 'get avatar from user_id' DETERMINISTIC NO SQL SQL SECURITY DEFINER
begin
  declare user_photo_path varchar(255);
  select user_photo.path into user_photo_path from `user_photo` where user_photo.id = v_id_user; return user_photo_path;
end
SET @p0='8';
SELECT `f_user_get_avatar_path`(@p0) AS `f_user_get_avatar_path`;

drop table if exists backuped_user;
CREATE TABLE `backuped_user`
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
  `time_backuped`    timestamp                               not null default current_timestamp on update current_timestamp,
  primary key `id_archived_user` (id_archived_user)
);

DROP EVENT `backup_user`;
CREATE DEFINER =`root`@`localhost` EVENT `backup_user` ON SCHEDULE EVERY 3 DAY STARTS '2019-01-20 01:00:00' ON COMPLETION NOT PRESERVE ENABLE COMMENT 'backups every fresh data every day' DO
  BEGIN
    delete from backuped_user where 1;
    insert into backuped_user (id, email, roles, password, first_name, last_name, agreed_terms_at, photo_id)
    select id,
           email,
           roles,
           password,
           first_name,
           last_name,
           agreed_terms_at,
           photo_id
    from user;
  END;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
