-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Дек 12 2023 г., 16:31
-- Версия сервера: 8.0.30
-- Версия PHP: 8.0.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `taskforce`
--

-- --------------------------------------------------------

--
-- Структура таблицы `auth`
--

CREATE TABLE `auth` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `vk_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `auth`
--

INSERT INTO `auth` (`id`, `user_id`, `vk_id`) VALUES
(2, 53, 188464638),
(3, 54, 212818637);

-- --------------------------------------------------------

--
-- Структура таблицы `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `performer_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`, `icon`) VALUES
(1, 'Услуги по уходу', 'quo'),
(2, 'IT и программирование', 'occaecati'),
(3, 'Ремонт и строительство', 'odio'),
(4, 'Творчество и развлечения', 'aspernatur'),
(5, 'Здоровье и фитнес', 'optio'),
(6, 'Фото и видео услуги', 'et'),
(7, 'Маркетинг и реклама', 'autem'),
(8, 'Образование и репетиторство', 'repellat'),
(9, 'Транспорт и перевозки', 'voluptatem'),
(10, 'Дизайн и графика', 'in'),
(11, 'Другое', 'd');

-- --------------------------------------------------------

--
-- Структура таблицы `cities`
--

CREATE TABLE `cities` (
  `name` varchar(255) NOT NULL,
  `id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `cities`
--

INSERT INTO `cities` (`name`, `id`) VALUES
('West Susieside', 10),
('Гюмри', 8),
('Ереван', 4),
('Жуковский', 1),
('Калининград', 11),
('Калуга', 5),
('Москва', 14),
('Реутов', 3),
('Рига', 6),
('Сочи', 9),
('Тбилиси', 7),
('Томск', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `events`
--

CREATE TABLE `events` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `task_id` int UNSIGNED NOT NULL,
  `type` char(12) NOT NULL,
  `dt_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE `files` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `task_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `dt_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `files`
--

INSERT INTO `files` (`id`, `name`, `path`, `task_id`, `user_id`, `dt_add`) VALUES
(12, 'presentation.pdf', '/uploads/presentation_upload6550ec38f1754.pdf', 27, 44, '2023-11-12 18:16:08');

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE `messages` (
  `id` int UNSIGNED NOT NULL,
  `recipient_id` int UNSIGNED NOT NULL,
  `sender_id` int UNSIGNED NOT NULL,
  `dt_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message` text NOT NULL,
  `task_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1698864011),
('m231101_183756_test_migration', 1698864015),
('m231103_175943_create_role_table', 1699034464),
('m231103_180850_add_role_id_to_users', 1699034969),
('m231103_184652_remove_id_column_from_cities_table', 1699037394),
('m231103_184846_remove_foreign_key_from_users', 1699037381),
('m231104_124302_tasksUpdated', 1699102293),
('m231104_125205_tasksUpdated1', 1699102548),
('m231104_181245_taksTableUpdate', 1699121820);

-- --------------------------------------------------------

--
-- Структура таблицы `opinions`
--

CREATE TABLE `opinions` (
  `id` int UNSIGNED NOT NULL,
  `owner_id` int UNSIGNED NOT NULL,
  `performer_id` int UNSIGNED NOT NULL,
  `rate` tinyint UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `dt_add` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `task_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `opinions`
--

INSERT INTO `opinions` (`id`, `owner_id`, `performer_id`, `rate`, `description`, `dt_add`, `task_id`) VALUES
(1, 44, 46, 5, 'Отлично и своевременно выполненная работа!', '2023-12-08 20:28:16', 35),
(2, 44, 46, 1, 'не допил водку, лох', '2023-12-08 20:27:44', 29),
(3, 44, 46, 5, 'Отлично выпили, спасибо!', '2023-12-08 20:28:08', 34),
(4, 44, 46, 5, 'хорошая работа!', '2023-12-08 20:28:34', 37),
(5, 44, 46, 5, 'отлично побарсучатил', '2023-12-08 20:41:25', 45);

-- --------------------------------------------------------

--
-- Структура таблицы `replies`
--

CREATE TABLE `replies` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `dt_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` varchar(255) NOT NULL,
  `task_id` int UNSIGNED NOT NULL,
  `is_approved` tinyint UNSIGNED DEFAULT '0',
  `sum` int DEFAULT NULL,
  `is_denied` tinyint UNSIGNED DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `replies`
--

INSERT INTO `replies` (`id`, `user_id`, `dt_add`, `description`, `task_id`, `is_approved`, `sum`, `is_denied`) VALUES
(1, 37, '2023-10-28 09:56:47', 'Alice thought she might as well say,\' added the Gryphon, sighing in his note-book, cackled out \'Silence!\' and read out from his book, \'Rule Forty-two. ALL PERSONS MORE THAN A MILE HIGH TO LEAVE THE.', 27, 0, 4180, 1),
(2, 20, '2023-10-28 21:46:41', 'Queen!\' and the party were placed along the sea-shore--\' \'Two lines!\' cried the Mock Turtle Soup is made from,\' said the King, and he wasn\'t going to remark myself.\' \'Have you seen the Mock Turtle.', 27, 0, 3540, 1),
(3, 30, '2023-10-07 12:52:16', 'Alice, \'and why it is all the party went back for a moment that it might happen any minute, \'and then,\' thought she, \'if people had all to lie down on one knee. \'I\'m a poor man,\' the Hatter added as.', 27, 0, 9150, 0),
(4, 14, '2023-10-20 10:15:37', 'I the same thing as a drawing of a large canvas bag, which tied up at this moment Alice appeared, she was near enough to drive one crazy!\' The Footman seemed to Alice a little pattering of feet in a.', 27, 0, 7830, 0),
(5, 8, '2023-10-13 03:59:39', 'PRECIOUS nose\'; as an explanation. \'Oh, you\'re sure to kill it in a hoarse growl, \'the world would go anywhere without a moment\'s delay would cost them their lives. All the time they had been.', 8, 0, 3750, 0),
(6, 30, '2023-11-02 01:45:35', 'And I declare it\'s too bad, that it felt quite strange at first; but she ran off at once, and ran the faster, while more and more sounds of broken glass, from which she concluded that it was done.', 4, 0, 2780, 0),
(7, 31, '2023-10-16 10:31:55', 'Alice, who always took a minute or two. \'They couldn\'t have wanted it much,\' said Alice; \'but when you come and join the dance? \"You can really have no idea what to do, and in THAT direction,\'.', 2, 0, 1120, 0),
(8, 14, '2023-10-09 10:47:58', 'March Hare went on. \'I do,\' Alice said very politely, feeling quite pleased to find that she was beginning very angrily, but the Mouse had changed his mind, and was going a journey, I should think.', 9, 0, 4810, 0),
(9, 36, '2023-10-07 22:26:19', 'Gryphon hastily. \'Go on with the lobsters and the turtles all advance! They are waiting on the end of trials, \"There was some attempts at applause, which was full of the goldfish kept running in her.', 1, 0, 8340, 0),
(10, 35, '2023-10-26 19:46:52', 'All the time they were trying which word sounded best. Some of the singers in the window?\' \'Sure, it\'s an arm for all that.\' \'With extras?\' asked the Mock Turtle. \'Certainly not!\' said Alice loudly.', 3, 0, 3800, 0),
(11, 16, '2023-10-12 21:31:51', 'March Hare had just begun to repeat it, but her voice sounded hoarse and strange, and the reason of that?\' \'In my youth,\' said the Duchess, \'and that\'s a fact.\' Alice did not answer, so Alice soon.', 6, 0, 9660, 0),
(12, 24, '2023-10-29 18:50:51', 'What WILL become of it; so, after hunting all about for a minute or two she stood looking at Alice as he spoke. \'UNimportant, of course, Alice could bear: she got used to it in a Little Bill It was.', 7, 0, 9830, 0),
(13, 37, '2023-10-06 09:17:06', 'Dodo, \'the best way you have of putting things!\' \'It\'s a mineral, I THINK,\' said Alice. \'Why, you don\'t know the way YOU manage?\' Alice asked. The Hatter was the Hatter. This piece of evidence we\'ve.', 4, 0, 9670, 0),
(14, 35, '2023-11-01 16:20:07', 'Alice thought this must be shutting up like a steam-engine when she was peering about anxiously among the people that walk with their fur clinging close to her great delight it fitted! Alice opened.', 10, 0, 6960, 0),
(15, 33, '2023-11-05 00:37:14', 'Ann! Mary Ann!\' said the Mouse. \'Of course,\' the Gryphon never learnt it.\' \'Hadn\'t time,\' said the Queen, stamping on the second thing is to do THAT in a great interest in questions of eating and.', 4, 0, 8660, 0),
(16, 8, '2023-10-12 22:48:42', 'I WAS when I was a very pretty dance,\' said Alice to herself, and fanned herself with one eye; \'I seem to see what was on the look-out for serpents night and day! Why, I do wonder what they said.', 6, 0, 9430, 0),
(17, 33, '2023-10-13 16:31:18', 'I find a pleasure in all my life!\' Just as she swam lazily about in the wood,\' continued the Hatter, \'when the Queen said to herself; \'I should have liked teaching it tricks very much, if--if I\'d.', 2, 0, 4790, 0),
(18, 22, '2023-10-31 00:13:30', 'Alice was a long argument with the Dormouse. \'Write that down,\' the King exclaimed, turning to Alice: he had a consultation about this, and she walked on in the kitchen. \'When I\'M a Duchess,\' she.', 4, 0, 5560, 0),
(19, 32, '2023-10-26 15:32:05', 'Let me see--how IS it to her ear. \'You\'re thinking about something, my dear, I think?\' \'I had NOT!\' cried the Mock Turtle. \'And how do you know why it\'s called a whiting?\' \'I never was so ordered.', 3, 0, 9410, 0),
(20, 31, '2023-10-05 17:39:08', 'I said \"What for?\"\' \'She boxed the Queen\'s hedgehog just now, only it ran away when it saw mine coming!\' \'How do you know the way YOU manage?\' Alice asked. \'We called him a fish)--and rapped loudly.', 9, 0, 1130, 0),
(21, 20, '2023-10-17 14:18:08', 'I suppose?\' \'Yes,\' said Alice sharply, for she felt that it signifies much,\' she said to the part about her and to wonder what they\'ll do next! If they had been running half an hour or so, and were.', 3, 0, 5060, 0),
(22, 21, '2023-10-26 22:00:41', 'I\'ll just see what would be wasting our breath.\" \"I\'ll be judge, I\'ll be jury,\" Said cunning old Fury: \"I\'ll try the thing yourself, some winter day, I will tell you how it was quite out of it, and.', 6, 0, 1420, 0),
(23, 31, '2023-10-15 01:12:59', 'Dormouse: \'not in that soup!\' Alice said nothing; she had succeeded in curving it down into its face in some book, but I THINK I can guess that,\' she added in an angry tone, \'Why, Mary Ann, and be.', 6, 0, 8510, 0),
(24, 37, '2023-10-17 20:30:49', 'I can\'t tell you my adventures--beginning from this side of the Lobster; I heard him declare, \"You have baked me too brown, I must sugar my hair.\" As a duck with its mouth open, gazing up into the.', 7, 0, 2490, 0),
(25, 32, '2023-10-16 19:20:53', 'Caterpillar decidedly, and he went on, \'\"--found it advisable to go after that into a cucumber-frame, or something of the tea--\' \'The twinkling of the country is, you ARE a simpleton.\' Alice did not.', 8, 0, 2450, 0),
(26, 16, '2023-10-12 00:00:57', 'Dormouse crossed the court, she said these words her foot as far down the chimney, has he?\' said Alice very humbly: \'you had got burnt, and eaten up by a row of lodging houses, and behind them a.', 3, 0, 9390, 0),
(27, 24, '2023-11-02 04:54:51', 'I only wish people knew that: then they both cried. \'Wake up, Alice dear!\' said her sister; \'Why, what a long sleep you\'ve had!\' \'Oh, I\'ve had such a puzzled expression that she was now about a foot.', 8, 0, 4900, 0),
(28, 32, '2023-10-25 23:06:19', 'Alice quite hungry to look for her, and said, \'So you did, old fellow!\' said the Gryphon went on, \'I must be the right thing to get dry very soon. \'Ahem!\' said the Gryphon. \'I\'ve forgotten the.', 8, 0, 1170, 0),
(29, 30, '2023-10-17 06:53:51', 'Alice\'s shoulder, and it was all dark overhead; before her was another long passage, and the Mock Turtle to the Dormouse, after thinking a minute or two sobs choked his voice. \'Same as if it please.', 5, 0, 2690, 0),
(30, 24, '2023-10-13 02:33:00', 'Gryphon. \'Then, you know,\' said the Hatter. He had been to her, though, as they would call after her: the last concert!\' on which the words all coming different, and then all the things I used to.', 2, 0, 1940, 0),
(31, 46, '2023-11-14 02:12:13', 'Попробую перевести, вроде бы не очень сложно', 27, 1, 5000, 0),
(32, 46, '2023-11-21 00:05:13', 'Да я бесплатно выпью', 34, 1, 1, 0),
(33, 46, '2023-11-21 00:11:48', 'попробую исправиться и выпить с вами водки', 29, 1, 1000, 0),
(34, 46, '2023-11-21 00:12:41', 'готов поговорить', 24, 0, 999, 0),
(35, 46, '2023-11-21 00:33:06', 'сделаю с радостью', 10, 1, 1000, 0),
(36, 46, '2023-11-21 23:14:54', 'вырублю быка!', 35, 1, 7000, 0),
(37, 46, '2023-11-27 18:19:46', 'Выбью в лучшем виде', 37, 1, 1200, 0),
(38, 53, '2023-12-01 03:14:08', 'Попробую снять, если актуально', 41, 0, 2000, 0),
(39, 46, '2023-12-08 01:07:00', 'побарсучатю за бесплатно', 45, 1, 1, 0),
(40, 46, '2023-12-08 19:02:08', 'спилю', 43, 0, 3000, 0),
(41, 46, '2023-12-08 19:15:45', 'приду через 20 минут', 41, 0, 1900, 0),
(42, 46, '2023-12-08 19:16:24', 'приду', 40, 0, 1900, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `role`
--

CREATE TABLE `role` (
  `id` int NOT NULL,
  `role_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `role`
--

INSERT INTO `role` (`id`, `role_name`) VALUES
(1, 'custormer'),
(2, 'doer');

-- --------------------------------------------------------

--
-- Структура таблицы `status`
--

CREATE TABLE `status` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_display` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `status`
--

INSERT INTO `status` (`id`, `name`, `name_display`) VALUES
(1, 'NEW', 'Открыт для откликов'),
(2, 'CANCELED', 'Отменен'),
(3, 'COMPLETED', 'Выполнен'),
(4, 'IN_WORK', 'В работе'),
(5, 'FAILED', 'Провален');

-- --------------------------------------------------------

--
-- Структура таблицы `tasks`
--

CREATE TABLE `tasks` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `budget` int UNSIGNED DEFAULT NULL,
  `expire_dt` datetime DEFAULT NULL,
  `dt_add` datetime DEFAULT CURRENT_TIMESTAMP,
  `client_id` int UNSIGNED NOT NULL,
  `performer_id` int UNSIGNED DEFAULT NULL,
  `status_id` int UNSIGNED NOT NULL,
  `city_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `tasks`
--

INSERT INTO `tasks` (`id`, `name`, `category_id`, `description`, `location`, `budget`, `expire_dt`, `dt_add`, `client_id`, `performer_id`, `status_id`, `city_id`) VALUES
(1, 'Rerum eaque beatae mollitia quam doloremque corrupti.', 3, 'Hatter, and he poured a little bird as soon as she spoke, but no result seemed to be said. At last the Mock Turtle. \'Hold your tongue!\' added the Queen. \'Sentence first--verdict afterwards.\' \'Stuff.', '', 7868, '2023-11-24 00:00:00', '2023-11-03 22:12:41', 1, 10, 1, 8),
(2, 'Nesciunt dolorem omnis eius consectetur.', 6, 'And she\'s such a capital one for catching mice--oh, I beg your pardon!\' she exclaimed in a loud, indignant voice, but she saw them, they were getting so thin--and the twinkling of the busy.', '', 8730, '2023-11-27 00:00:00', '2023-11-04 02:11:29', 1, 10, 1, 8),
(3, 'Beatae velit laudantium modi molestiae saepe qui.', 1, 'Which way?\', holding her hand on the door and went stamping about, and shouting \'Off with her arms round it as she could, for the pool a little hot tea upon its forehead (the position in dancing.\'.', '', 9576, '2023-11-09 00:00:00', '2023-11-04 01:56:14', 1, NULL, 2, 2),
(4, 'Non voluptatem excepturi eum voluptatem delectus corporis voluptas.', 3, 'Dormouse shook its head impatiently, and walked a little queer, won\'t you?\' \'Not a bit,\' she thought at first she would catch a bat, and that\'s all the jurors had a wink of sleep these three little.', '817 Schuppe Drive\nJacquesstad, WV 76254', 6498, '2023-11-01 00:00:00', '2023-11-04 00:00:21', 1, 8, 3, 2),
(5, 'Architecto iusto expedita placeat dignissimos.', 10, 'Tell her to speak first, \'why your cat grins like that?\' \'It\'s a mineral, I THINK,\' said Alice. \'I\'ve tried the roots of trees, and I\'ve tried to fancy what the moral of that is--\"Birds of a.', '', 9185, '2023-11-16 00:00:00', '2023-11-03 19:16:54', 1, NULL, 1, 5),
(6, 'Quae eos dolores nihil enim ex corrupti.', 1, 'I\'ll manage better this time,\' she said to Alice, they all looked so good, that it was too small, but at the March Hare. \'He denies it,\' said the sage, as he spoke, and then dipped suddenly down, so.', '', 4087, '2023-11-15 00:00:00', '2023-11-04 05:40:13', 1, 7, 1, 8),
(7, 'Exercitationem est ratione eligendi velit.', 3, 'ALL PERSONS MORE THAN A MILE HIGH TO LEAVE THE COURT.\' Everybody looked at her rather inquisitively, and seemed not to lie down on her face like the look of it appeared. \'I don\'t believe it,\' said.', '', 7742, '2023-11-28 00:00:00', '2023-11-04 10:35:43', 1, NULL, 2, 8),
(8, 'Quis debitis facere assumenda.', 9, 'ONE respectable person!\' Soon her eye fell upon a neat little house, and found in it about four feet high. \'I wish I hadn\'t drunk quite so much!\' said Alice, \'we learned French and music.\' \'And.', '', 7585, '2023-11-24 00:00:00', '2023-11-04 05:12:37', 1, 7, 3, 7),
(9, 'Nobis ab libero praesentium magnam sed sit.', 6, 'Bill had left off when they hit her; and the reason so many tea-things are put out here?\' she asked. \'Yes, that\'s it,\' said the Gryphon, and the happy summer days. THE.', '', 4547, '2023-11-18 00:00:00', '2023-11-04 09:40:34', 1, NULL, 1, 3),
(10, 'Eum aspernatur accusantium accusamus recusandae.', 5, 'THAN A MILE HIGH TO LEAVE THE COURT.\' Everybody looked at each other for some time with one foot. \'Get up!\' said the Caterpillar decidedly, and he checked himself suddenly: the others looked round.', '', 8504, '2023-11-05 00:00:00', '2023-11-04 08:22:26', 1, 46, 5, 1),
(24, 'Поговорить со мной', 8, 'просто поговорите со мной под бутылочку BEER ', NULL, 999, '2023-11-12 23:00:00', '2023-11-12 18:05:11', 44, NULL, 2, NULL),
(27, 'Перевести презентацию на итальянский', 2, 'посмотрите презентацию, перевести за неделю\r\n', NULL, 5000, '2023-11-17 23:00:00', '2023-11-12 18:16:07', 44, 46, 3, NULL),
(28, 'Выпить со мной пивко', 1, 'посидим, поговорим\r\nпиво за мой счет', NULL, 1000, '2023-11-16 00:00:00', '2023-11-15 22:01:51', 44, NULL, 2, NULL),
(29, 'Выпить со мной водки', 9, 'водка за мой счет + косарь накину', NULL, 1000, '2023-11-16 00:00:00', '2023-11-15 22:06:11', 44, 46, 3, NULL),
(30, 'dasdsa', 1, 'вфывыф', NULL, 1312321, '2023-11-22 00:00:00', '2023-11-15 22:06:54', 44, NULL, 2, NULL),
(31, 'dasdsa', 6, '231321adssa ', NULL, 4323432, '2023-11-16 00:00:00', '2023-11-15 22:11:34', 44, NULL, 2, NULL),
(32, 'dadsa', 1, 'dasdasdas', NULL, 2321321, '2023-11-23 23:00:00', '2023-11-15 22:14:14', 44, NULL, 2, NULL),
(33, 'dasdasdsa', 9, '12312dasdas', NULL, 23112123, '2023-11-16 23:00:00', '2023-11-15 22:14:41', 44, NULL, 2, NULL),
(34, 'попить со мной пивко', 1, 'выпить пивко да и всё', NULL, 13232, '2023-11-18 23:00:00', '2023-11-17 00:50:41', 44, 46, 3, NULL),
(35, 'Надо вырубить одного быка', 1, 'Бык обычный, тяжелый, надо вырубить', NULL, 5999, '2023-11-22 23:00:00', '2023-11-21 22:34:34', 44, 46, 3, NULL),
(36, 'купить помидоров', 1, 'сходить да купить помидоров', NULL, 547, '2023-11-27 23:00:00', '2023-11-27 17:52:57', 44, NULL, 2, NULL),
(37, 'Выбить матрас', 1, 'надо выбить матрас', NULL, 1000, '2023-11-29 23:00:00', '2023-11-27 18:00:12', 44, 46, 4, NULL),
(38, 'Помогите сделать харакири', 1, 'прийти поддержать', '43.841774 40.785273', 10999, '2023-11-30 23:00:00', '2023-11-29 20:25:52', 44, NULL, 2, NULL),
(39, 'Помочь достать кота с дерева', 11, 'Приходите, снимем котенка', '', 1777, '2023-11-30 23:00:00', '2023-11-29 20:45:05', 44, NULL, 2, NULL),
(40, 'Помогите снять кота с дерева', 11, 'приходите, делов на 10 мин', '20.393985 44.803264', 1844, '2023-11-29 23:00:00', '2023-11-29 20:51:59', 44, NULL, 1, NULL),
(41, 'Помогите снять кота с дерева', 11, 'приходите, делов на 10 мин', '20.393985 44.803264', 1844, '2023-11-29 23:00:00', '2023-11-29 20:52:11', 44, NULL, 1, NULL),
(42, 'Привить котенка', 1, 'Отвезти котёнка на прививки', '37.248832 55.448847', 4000, '2023-12-02 23:00:00', '2023-12-01 03:18:33', 54, NULL, 1, NULL),
(43, 'Спилить дерево', 3, 'по бырому', '38.052851 55.611612', 3000, '2023-12-08 23:00:00', '2023-12-03 11:08:11', 44, NULL, 1, NULL),
(44, 'Спилить дерево', 3, 'по бырому', '38.052851 55.611612', 3000, '2023-12-08 23:00:00', '2023-12-03 11:08:20', 44, NULL, 2, NULL),
(45, '  побарсучатить сончика', 1, 'побарсучанить 15 минут', '43.849464 40.787225', 100, '2023-12-08 23:00:00', '2023-12-08 01:06:13', 44, 46, 3, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `city_id` int DEFAULT '0',
  `password` char(64) NOT NULL,
  `dt_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `blocked` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `last_activity` datetime DEFAULT NULL,
  `role_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `name`, `city_id`, `password`, `dt_add`, `blocked`, `last_activity`, `role_id`) VALUES
(1, 'mrunolfsdottir@example.net', 'Brennan Willms', 5, '$2y$13$z5tKHeBcgAYKEOPqkUaNl.0hhysRkTRd106pnOT/R2A0fkvX3Mptm', '2023-07-23 18:12:20', 0, '2023-09-14 22:05:21', 1),
(2, 'gabriella.homenick@example.com', 'Mr. Rick Champlin Sr.', 4, 'qfCrCIjCqMPLr1goG5wA1JFyN2Tvtk9U7yDcSANis9lwLfCt9ru0ExJnC96XFaqc', '2023-09-14 17:03:44', 0, '2023-01-31 07:07:33', 1),
(3, 'rohan.ona@example.com', 'Mrs. Valentine Durgan', 10, '0FQ8mShLZ8HofFLNteTDZWBnRNSX19fK5m4NPtpaYCG64ZTW49lWmcPMb9zECryE', '2023-05-15 23:52:53', 0, '2023-02-25 21:09:31', 1),
(4, 'cschmeler@example.com', 'Shawn Kuhlman', 3, 'k9fBrMIk6JOzqcHGAAY8Vv1Zt1s6QhS6AQFXIIhwAaaFSBLUEyLSA848gHtCVFv9', '2023-05-20 17:12:05', 0, '2023-07-17 23:02:05', 2),
(5, 'gia71@example.com', 'Lucius Gorczany', 3, 'qUHZhAIYagAhVfLARTvaCC2XTYwnOngDxlexxbIL06ksgHGy3OIdnb3ndANjFus7', '2023-10-17 18:34:08', 0, '2023-01-19 03:25:54', 2),
(6, 'murphy.annie@example.org', 'Prof. Elta Eichmann', 3, 'ACupBznr3z6CTNxFhpGnevqJBn4OZpzIW6CeuxSm7cGfEALNODsrmKnjK9ojPgAg', '2023-09-28 20:26:18', 0, '2023-05-31 04:55:18', 1),
(7, 'dhoeger@example.com', 'Edna Lubowitz III', 6, 'XeGHOeFb1p6j19FlJtzqwpqNv6QNwXiKSsat1Sf6lJJAvTIPHjJCKTq0IKI8mLgz', '2023-08-09 20:45:05', 0, '2023-07-19 08:18:13', 1),
(8, 'qcorwin@example.net', 'Mr. Ryleigh Krajcik II', 6, 'ofL1Wh3XFW82m87HL3UQn40GMz9T0ICqTzykGkwGebkuiT3Cv5cZaiIeDEHTgAKj', '2023-06-18 21:19:28', 0, '2023-10-02 23:16:21', 2),
(9, 'tod94@example.net', 'Reva Hodkiewicz', 4, '6c2Zw8F6sNS4lnpSNNcjXys3lQWiorYVqmPo2NXmJ21NAcNt9m3ynPdnVS7c4zAk', '2023-01-15 08:10:22', 0, '2023-04-16 11:47:38', 1),
(10, 'edickens@example.org', 'Dr. Oral Barrows', 2, '6Ja3rITG5IMpyVAcVvJ88jO94wW2UCnejlaeOmVBsTTfFXTwYCjj3bWlXJzamaXt', '2023-05-21 20:35:30', 0, '2023-07-01 11:54:24', 1),
(11, 'kiehn.sadie@example.com', 'Vicenta Trantow', 10, 'jCSG9W1nGH8PbXAyp1duA9eyePQdyQTPbJUVmgpaIa2hzI575Jf51QE3AlpK95Bl', '2023-09-17 02:22:52', 0, '2023-10-30 10:01:18', 1),
(12, 'jerel.reichel@example.com', 'Dr. Alfred Leannon', 6, 'CnEuXxll3turSmzNKBraVLeOqvEZYsffYgX6uFOepHifsPQr59pA4sIOOtA91F2X', '2023-10-07 10:57:57', 0, '2023-01-04 13:21:38', 1),
(13, 'myriam54@example.com', 'Brady Turner V', 1, 'dzOfWzypSdiD2Us92eMzG5tzQCyG8dx2I3uIyhPirElKVVHigOTW5ZLmaax0PXDE', '2023-06-07 16:04:36', 0, '2023-07-04 15:36:48', 1),
(14, 'hamill.libbie@example.com', 'Mrs. Kaylie Lehner PhD', 5, 'ZGVCsWWwoXMg40YhiBgfaKAtyfYrb0FAcyQjSp4XDoPHXFqoUOTSdTtLprfERTH6', '2023-02-08 08:29:10', 0, '2023-08-13 19:09:58', 2),
(15, 'hernser@example.net', 'Charlotte Buckridge', 3, 'qgYSgxbaEvWL44hwRe5j5bwgsaC5j4XnDD9MWIHWWLlL3t6BOY8HYbKQesGoLd1j', '2023-02-08 20:05:30', 0, '2023-08-20 12:28:39', 2),
(16, 'vita65@example.net', 'Gennaro Paucek', 9, 'RjfhR0DE2d0br0cYcerPtgZMcQJ79lcm4BxsJ9VpmFYAUUcyfkbpQngAQpVrH5GB', '2023-06-04 05:38:42', 0, '2023-06-25 09:13:30', 2),
(17, 'fisher.lou@example.net', 'Melody Reilly DVM', 6, '2g0I34lTGJmaaxfjMHn802fcfiMOQT78qBt0rYVjd6OgtT7bA8h0AKZPKLQ4foo0', '2023-09-13 13:17:05', 0, '2023-08-07 13:57:22', 1),
(18, 'alaina.schneider@example.org', 'Miss Sydni Luettgen', 10, 'TzpWRK5Kdr7MjXScF5J3gc3c5A0Am0B9ec55TgsqOiHaw3My3qvhH59AlLyp9Gli', '2023-09-22 22:07:00', 0, '2023-01-24 20:39:06', 1),
(19, 'dickens.durward@example.org', 'Zachery Bosco', 10, 'nACHHLOfPP1i5kDob7uTHrX8FgU4tFYknF5kwzyNLIcqTX9zWOqm5MpxHvonM3NH', '2023-08-22 16:03:41', 0, '2023-04-18 17:03:21', 1),
(20, 'amani80@example.com', 'Prof. Americo Willms DVM', 7, 'gxPDTO5Gw9UnrzIBTjexRevnifTwACpX4zi2D8w41GKMItvKUUgjQpyoCxTGxKGb', '2023-03-08 03:48:14', 0, '2023-06-10 07:16:57', 2),
(21, 'damore.pierce@example.com', 'Dorris Muller', 7, 'L4NLHMBp8K0YFHNp6I8qcx2r6W4tLkXnxGZ6xLc6tGVJsgpe6ubDk5t1oMkvKh51', '2023-08-15 05:18:46', 0, '2023-07-18 15:31:30', 2),
(22, 'hane.michaela@example.com', 'Bailee Lindgren', 8, '20GNPgEBoC91JuvOce2ZRVXnlYl7TrYDSUTvHQVkEfSFk7K1g9RipMYG89Ax7im2', '2023-02-08 00:47:29', 0, '2023-06-18 03:14:30', 2),
(23, 'edwina10@example.net', 'Cielo Lemke', 4, 'SFAKyZRWcBci3tPIk3EOxbeJloieYetxnkZwDkwoohkhfJsmr6nhHuLpF8v7mx7V', '2023-03-24 17:24:40', 0, '2023-01-04 15:46:49', 1),
(24, 'hilpert.wilfrid@example.org', 'Karina Abshire', 7, '21fGFjWghhnZ0M31Juz4Gnpwdv6Qz3viIqENFL7VNyCQFvi25BVKFzTVGaxiJFiA', '2023-10-22 09:49:22', 0, '2023-10-07 15:36:52', 2),
(25, 'nelda.sporer@example.org', 'Mr. Halle Luettgen', 8, 'HzFZgLPIy1FVbhMk5yV0afnqj20o1FZvKTw65HnteBo36btS18ctGM0p7bYJqntv', '2023-09-08 09:24:46', 0, '2023-08-06 06:12:19', 1),
(26, 'qdouglas@example.org', 'Laila Ankunding', 10, 'GIgwKvD292zZBDWgAj6zLyxtWsJiQCY3C65T9dzD1J2k4XiHT6vF6JJLd9QyAQCk', '2023-09-06 17:02:38', 0, '2023-01-12 00:35:51', 1),
(27, 'macy.conn@example.com', 'Darby Hoppe', 4, 'FUVqq9fpjEmffZG9w9b9k5y62fJQkCcipsNR4MQ2FrLrG20PQBAs65MeAPAqKODk', '2023-01-09 19:31:20', 0, '2023-10-17 15:17:50', 2),
(28, 'jaron.berge@example.org', 'Lamont Stoltenberg V', 6, 'SXYy1EjMbL32se6h5uVBjn3ph8ntbw6h3tHEUTihwAgIhpiu8UAmThomlFStpxWj', '2023-07-02 02:22:21', 0, '2023-07-28 04:34:37', 1),
(29, 'hiram.friesen@example.net', 'Miss Maymie Robel II', 7, '1CF9HmwkYzicrPVhjOKIU6jl7wFN7bwJbETvzySi2TfrjW4BoBewYvzGkEYLh57I', '2023-02-07 23:52:08', 0, '2023-04-03 02:37:45', 1),
(30, 'lehner.victoria@example.com', 'Leonel Bogisich', 5, 'iTzptzmWjqqVsA79rCevGIi34tU1fxM55ThBVXwkkbsSHthifP2GgV6wrfTBlZfF', '2023-04-10 08:38:32', 0, '2023-07-20 14:17:50', 2),
(31, 'jtorp@example.com', 'Mr. Jonathon Purdy MD', 9, 'ntOYERcRxrF3TlCJDatwaUKIdSQ5LwnSAbmTy55BvUh78vkQZJDIWLEnmr8tQQwz', '2023-07-03 22:44:30', 0, '2023-06-04 22:01:23', 2),
(32, 'julie.dubuque@example.net', 'Hayden Bergstrom Jr.', 1, 'ETg60iVmG8TYHKLEQqqslUPsKFVygspqMAqYaTGCmAfm8ui3J5uYG5wGpArernFV', '2023-08-30 18:37:49', 0, '2023-10-31 22:40:58', 2),
(33, 'khaley@example.org', 'Angeline Price', 3, 'taCQfhZsniDjbcB9Cit1Iilg8gwtubuTjV4SCkrPxpKiyiezwP9JpQn61pk5gtbp', '2023-06-08 06:31:10', 0, '2023-09-24 08:37:31', 2),
(34, 'enid.ohara@example.net', 'Prof. Eladio Parisian', 1, 'S2IjODfhuBqOswnPmqHq3fNVnLEdLYxJHbyBWUS11H9jAM60iWBXzvRSXrTWh67A', '2023-05-31 03:27:06', 0, '2023-03-26 13:28:55', 2),
(35, 'schamberger.emelia@example.org', 'Regan Will', 3, 'DQQVSWhDFHkqB5HSvq4VDB2xL43MvADCEmmY6IcNLc6KCs92uxxwYrPop4YARQc7', '2023-05-13 02:22:49', 0, '2023-03-25 07:11:27', 2),
(36, 'denis.keebler@example.net', 'Colleen Morissette', 7, 'm7tCKlBQ4cXOz5lGl86eUTSVz0Ky035UVLGks3kxdhfBZzKGHvT56q3JhFweMne4', '2023-05-27 03:54:18', 0, '2023-08-15 12:04:44', 2),
(37, 'zachery.monahan@example.com', 'Maryjane Predovic', 2, 'w17CM5qiay4b1oumgnNOQ3wJlfY7mKNWLzzBHugWzpPkwUVkJ6bT07S901Rqh69L', '2023-06-28 04:54:36', 0, '2023-01-13 15:16:17', 2),
(38, 'tanya86@example.net', 'Paul Swaniawski', 2, 'QoseeAVlBRWtAKfSBvTCRx6mIbjyNrOC2sejfNmC3eJCG2tHXn4XfQfU3phky5ph', '2023-10-26 14:37:39', 0, '2023-07-07 01:35:19', 2),
(39, 'gerlach.lavonne@example.com', 'Michel Dietrich DVM', 10, 'MYEAzkeD5VihjnNYuR5NLmujjHmAONFbKKuuB8uawshPgu37D1iHQdNx6X41D7ZK', '2023-02-23 16:48:29', 0, '2023-03-08 14:39:46', 1),
(40, 'vrice@example.org', 'Eldon Little', 2, 'NZj9VlpBC4RDzvrfUIAw37chZzzuq4RkNJ1FmGxT7Jl0a4PYb4QTKqoxoFrNfpko', '2023-07-31 01:01:36', 0, '2023-10-12 00:42:37', 1),
(42, 'ben@mail.ru', 'borhpeus', 7, '$2y$13$gv4R9d9QuVv0ATRt8DcxSen3fFwelGYzAr5j5DJD1brYcYbdWO1hK', '2023-11-06 13:28:01', 0, NULL, 1),
(43, 'ben4@mail.ru', 'borhpeus', 7, '$2y$13$Rofrnavu29B.lX6sT//pWu733abKZSS90Ri3fcS6/phTxojgGqhOG', '2023-11-06 14:13:13', 0, NULL, 2),
(44, 'peter@mail.ru', 'peter', 3, '$2y$13$NdzYwQqDfESQNwz88lAbIeWoCIEKpo8LWrKx8t/lKXSEMW4Uek/ce', '2023-11-08 19:11:05', 0, NULL, 1),
(46, 'b2@mail.ru', 'Костик Хвостик', 4, '$2y$13$qvfpIv24VXdoCIhaSkINf./ar2mhIoWSW83WVpNgx9i7fU4OCZHeG', '2023-11-14 00:30:36', 0, NULL, 2),
(47, 'b1@mail.ru', 'Игорь Ли', 1, '$2y$13$jwL6d96BO33idwgM1YcoO.hmr4s3XcIijEoYno4n.sfBIH4dQies2', '2023-11-27 18:20:26', 0, NULL, 2),
(48, 'b4@mail.ru', 'Алексей Борисович', 1, '$2y$13$bpgfZT06DLSjmM0BL5GfN.cIZns.tY1obrx9HKP6.75Lb7Rsj7CoK', '2023-11-27 18:23:00', 0, NULL, 1),
(49, 'b5@mail.ru', 'Хуго Уго', 4, '$2y$13$gvAx4V6JwF5hBdHIEEfXWeauX/25etpR/QaboqU9XyiKKcc8hedSm', '2023-11-30 21:35:11', 0, NULL, 1),
(53, 'b141414146@mail.ru', 'Костя', 14, '1aca134907e37fec1187257077b80ea9', '2023-12-01 02:05:25', 0, NULL, 2),
(54, NULL, 'Соня', 0, '6dd6111551ef2699ae53c445c11c81f7', '2023-12-01 03:17:23', 0, NULL, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `user_categories`
--

CREATE TABLE `user_categories` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `category_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `user_categories`
--

INSERT INTO `user_categories` (`id`, `user_id`, `category_id`) VALUES
(31, 44, 2),
(32, 44, 3),
(33, 44, 6),
(53, 53, 1),
(54, 53, 2),
(55, 53, 4),
(57, 46, 6),
(58, 46, 1),
(59, 46, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `user_settings`
--

CREATE TABLE `user_settings` (
  `id` int UNSIGNED NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `bd` date DEFAULT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `about` text,
  `phone` char(11) DEFAULT NULL,
  `skype` char(32) DEFAULT NULL,
  `messenger` char(32) DEFAULT NULL,
  `notify_new_msg` tinyint UNSIGNED DEFAULT '0',
  `notify_new_action` tinyint UNSIGNED DEFAULT '0',
  `notify_new_reply` tinyint UNSIGNED DEFAULT '0',
  `opt_hide_contacts` tinyint UNSIGNED DEFAULT '0',
  `opt_hide_me` tinyint UNSIGNED DEFAULT '0',
  `user_id` int UNSIGNED NOT NULL,
  `refused_counter` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `user_settings`
--

INSERT INTO `user_settings` (`id`, `address`, `bd`, `avatar_path`, `about`, `phone`, `skype`, `messenger`, `notify_new_msg`, `notify_new_action`, `notify_new_reply`, `opt_hide_contacts`, `opt_hide_me`, `user_id`, `refused_counter`) VALUES
(1, '678 Julien Flat\nPort Curtfort, NJ 30125-8872', '1979-08-20', '', 'Dolor deleniti possimus ut aut repudiandae ut. Nesciunt sed ratione deleniti ut aut quas officiis. Eum consequatur illo itaque non vel omnis et rerum. Mollitia provident voluptatem suscipit est dolores nam fugiat nam.', '8724437656', 'etNisi', 'omnisProvident', 0, 0, 0, 0, 0, 1, 0),
(2, '291 Schmidt Mission Apt. 921\nYundtmouth, DC 84138-5240', '1998-03-24', '', 'Veritatis iure nobis dolorum architecto at dolorem. Cumque delectus velit ipsa natus officiis eos maiores. Eligendi sit quo maxime veniam. Accusantium atque pariatur quibusdam ipsum quis vero eum. Saepe nisi facere qui quisquam temporibus asperiores veniam.', '15024024027', 'quamNeque', 'laboriosamExpedita', 0, 0, 0, 0, 0, 2, 0),
(3, '498 Henry Gardens\nGerholdshire, SD 91053', '2003-01-14', '', 'Amet eius voluptatem deleniti aut exercitationem ut. Et ea omnis et. Totam illum illo id nihil ut. Velit necessitatibus fuga quod porro.', '9383498558', 'maximeVel', 'doloresIn', 0, 0, 0, 0, 0, 3, 0),
(4, '97503 Jennings Green Apt. 300\nMacktown, CT 22084-6671', '1989-12-24', '', 'Voluptas ab vel delectus ut reprehenderit sequi. Placeat illo repellendus dicta similique asperiores ab quas velit.', '2024273008', 'rationeVoluptatem', 'etAdipisci', 0, 0, 0, 0, 0, 4, 0),
(5, '7413 Garry Rapid\nRoselynland, NY 34759-7839', '1992-07-30', '', 'Et reiciendis corrupti enim. Itaque qui dignissimos soluta et. Dolorem facilis molestias est hic vel animi.', '4056368966', 'voluptatumSapiente', 'evenietEt', 0, 0, 0, 0, 1, 5, 0),
(6, '15961 McLaughlin Falls\nDarechester, AL 41516', '1984-08-17', '', 'Fugit facere earum et odit sunt inventore dolores. Sunt iusto quas optio aut quam. Sint sint eaque dolor reprehenderit.', '9093671107', 'velPerferendis', 'delectusIpsum', 0, 0, 0, 0, 0, 6, 0),
(7, '46818 Israel Crescent\nLake Alberta, WY 88619', '1985-09-20', '', 'Mollitia iste deserunt reiciendis quis qui natus quia. Aperiam illum distinctio suscipit est. Magnam maiores soluta aut temporibus itaque totam. Et delectus numquam non quia a earum qui. Excepturi quasi similique provident placeat sunt.', '6613912178', 'eosId', 'repudiandaeNon', 0, 0, 0, 0, 0, 7, 0),
(8, '7330 Misty Ports\nKassandramouth, OK 67425-8442', '2001-11-09', '', 'Aliquid beatae quaerat quisquam possimus. Pariatur enim quia sint qui. Amet qui vel et sed molestiae dolorem aspernatur at. Ex temporibus provident ea minima voluptatem sapiente quo.', '18305164078', 'rerumNatus', 'magnamEt', 0, 0, 0, 0, 0, 8, 0),
(9, '26450 Flo Ports Suite 041\nPort Lucindahaven, TN 28638', '1981-09-15', '', 'Qui numquam et veniam autem aut. Laboriosam eum non esse corporis sint deserunt. Voluptate quisquam reprehenderit exercitationem aut.', '13465506912', 'estSed', 'cumSapiente', 0, 0, 0, 0, 0, 9, 0),
(10, '149 Rippin Courts Suite 596\nWilfordchester, MT 05557-6364', '2005-10-05', '', 'Et sapiente sit sed aliquam dolorem. Sed quaerat totam numquam.', '7868509125', 'sedAut', 'inNesciunt', 0, 0, 0, 0, 1, 10, 0),
(11, '1278 Evie Ramp Suite 746\nBartville, NY 41743-8882', '1994-01-13', '', 'Dicta dolor perferendis dolor fugit saepe. Exercitationem asperiores dolor ut et minus et iste. In illo voluptatem impedit amet dolores. Perspiciatis alias explicabo omnis ea sed ea et aut.', '3647441330', 'occaecatiEt', 'temporeMaxime', 0, 0, 0, 0, 0, 11, 0),
(12, '33235 Garrick Plain\nOkunevaburgh, OR 92458-4516', '2004-02-06', '', 'Rerum ad et sunt aut. Ipsum omnis ut non facilis in id. Magnam temporibus distinctio praesentium beatae laborum.', '17795988427', 'voluptatibusSunt', 'occaecatiQuaerat', 0, 0, 0, 0, 0, 12, 0),
(13, '1356 Leslie Rest\nRamiroview, PA 74453-6174', '1995-01-13', '', 'Velit optio aut vitae minus provident eos dolor. Explicabo beatae consequatur et sit. Pariatur neque ut sunt et.', '13517177012', 'estEst', 'dictaUt', 0, 0, 0, 0, 0, 13, 0),
(14, '154 Friesen Forge Suite 772\nGabeside, DE 16520-5180', '2003-09-27', '', 'Nihil sed aspernatur sit in unde. Praesentium animi itaque consequatur a possimus accusamus.', '8624625914', 'sedNihil', 'iurePerferendis', 0, 0, 0, 0, 0, 14, 0),
(15, '947 Stokes Drive\nNorth Alva, CT 46846-6084', '2003-06-18', '', 'Culpa reprehenderit sunt non ab omnis assumenda. Id consequatur sit deleniti mollitia. Quo dolores sint et accusamus sed ipsa. In perferendis quis repudiandae.', '4702532454', 'isteAdipisci', 'quisquamA', 0, 0, 0, 0, 1, 15, 0),
(16, '735 Grace Manors Suite 160\nWest Garnetview, TN 47093', '2004-11-12', '', 'Odio laboriosam quod deleniti quia eos qui et. Minus qui nostrum aut suscipit reiciendis saepe.', '9479970222', 'etQuia', 'quisFuga', 0, 0, 0, 0, 0, 16, 0),
(17, '2023 Kim Heights\nEsperanzaton, ID 12329-0982', '1998-07-16', '', 'Temporibus saepe deleniti ab. Tempora est corporis aut voluptas quod. Culpa aut saepe et neque perspiciatis quasi.', '14249571199', 'providentSoluta', 'delenitiEst', 0, 0, 0, 0, 0, 17, 0),
(18, '1460 Haylie Street Apt. 623\nMarvinshire, HI 47891-7194', '1996-08-11', '', 'In fugit repellendus ab dolore velit omnis et. Perferendis quam deleniti aut expedita. Cumque non est est tempore. Molestias et quod et error ut. Excepturi molestias maxime consequatur maiores pariatur maiores.', '9493106561', 'pariaturQui', 'dolorHic', 0, 0, 0, 0, 0, 18, 0),
(19, '8622 Robbie Union Suite 433\nO\'Connerside, PA 66440', '1985-08-24', '', 'Aspernatur quis deleniti vero sed alias mollitia. Sunt ullam nulla fugiat magni qui.', '18054965377', 'solutaConsequatur', 'idDeserunt', 0, 0, 0, 0, 0, 19, 0),
(20, '34089 Alek Fort Apt. 950\nSouth Fanny, DE 83514-2426', '2000-08-17', '', 'Quo rerum maiores eligendi officiis. Earum voluptatem ut quod sed sunt. Exercitationem odio labore pariatur officiis tempora nam.', '3322715111', 'eosOmnis', 'nonQui', 0, 0, 0, 0, 1, 20, 0),
(21, '7414 Strosin Harbor\nWaldoburgh, MA 79642', '1995-01-19', '', 'Sed ab odio odit quam hic itaque consectetur. Eum explicabo voluptatem assumenda sed tempore natus beatae. Quod rerum sint sunt reprehenderit.', '6195213122', 'expeditaQui', 'consecteturConsequuntur', 0, 0, 0, 0, 0, 21, 0),
(22, '7888 Karley Unions Apt. 785\nEast Kaycee, PA 59166', '1997-10-28', '', 'Dolorem nihil eius corporis sed accusantium. Possimus sit numquam dolor rerum.', '16627539952', 'quaeratVoluptatibus', 'quiIusto', 0, 0, 0, 0, 0, 22, 0),
(23, '440 Doyle Burg Suite 462\nEast Hassieport, GA 11445', '1996-08-02', '', 'Quia qui libero enim sed ex laboriosam quidem. Doloribus quisquam sed non.', '8047211551', 'sedIn', 'dignissimosQuam', 0, 0, 0, 0, 0, 23, 0),
(24, '575 Dannie Pine Suite 232\nCleorabury, RI 96841', '2000-04-01', '', 'Eius iste praesentium non. Voluptatem id vel explicabo debitis sed aut recusandae. Ratione quisquam eum excepturi ipsum veritatis itaque sunt. Animi aut optio et.', '3867219343', 'oditSit', 'porroEt', 0, 0, 0, 0, 0, 24, 0),
(25, '9332 Stroman Land Apt. 913\nPort Jocelyn, GA 60021', '2000-04-03', '', 'Sunt suscipit assumenda dolores ullam minima. Nobis tempore omnis possimus accusantium minima. Dolorum consequatur maxime nisi est.', '5122143014', 'delectusConsequuntur', 'voluptasEt', 0, 0, 0, 0, 1, 25, 0),
(26, '4384 Jett Manors Suite 033\nFranzberg, KS 05892-7962', '1991-06-01', '', 'Voluptatem et voluptates aliquid libero est. In qui perspiciatis quasi dicta facilis adipisci. Dolores ullam adipisci adipisci quos quis incidunt sit.', '19349474310', 'etMagni', 'estAut', 0, 0, 0, 0, 0, 26, 0),
(27, '364 Roscoe Turnpike Apt. 041\nPort Georgeville, OR 17107-4780', '1986-12-11', '', 'Ducimus occaecati cum voluptatem et occaecati aspernatur animi. Beatae et officiis tempore aliquam. Sed aliquam quis hic est doloremque quia. Iusto voluptatem nihil odit cupiditate sit dolorum in. Dolor consequatur optio quo ut odit voluptatum est blanditiis.', '2709730986', 'voluptasAut', 'quiPerspiciatis', 0, 0, 0, 0, 0, 27, 0),
(28, '381 Quitzon Forks\nMohrtown, AK 70549', '2001-01-19', '', 'Rerum asperiores veniam quis velit. Voluptates magni ad minima quibusdam corporis. Commodi provident et ea similique nesciunt.', '4109339733', 'sedEarum', 'iustoAnimi', 0, 0, 0, 0, 0, 28, 0),
(29, '1730 Littel Harbors\nHaleyside, MS 93863-0583', '1995-10-04', '', 'Dolor quo sed ab distinctio beatae voluptate. Accusantium minima libero doloribus corrupti esse et aliquam explicabo. Aut laborum aut reprehenderit veritatis voluptatum est atque. Ea ab maiores beatae. Cupiditate ut architecto atque possimus commodi unde eaque.', '2832641084', 'velitNobis', 'nullaEt', 0, 0, 0, 0, 0, 29, 0),
(30, '44266 Parisian Courts\nDejahaven, AZ 20018-7314', '1993-03-17', '', 'Aut dolores et est voluptate velit natus qui. Dolorem voluptatum ut dolores et cupiditate. Vitae odio sint facilis dolorem mollitia qui.', '17433839564', 'deseruntAccusamus', 'velLaudantium', 0, 0, 0, 0, 1, 30, 0),
(31, '1844 Sawayn Manors\nLake Cydneyshire, LA 19822', '1991-12-06', '', 'Vel qui sit sit quis nisi. Doloribus ipsum ut deserunt voluptates in est. Minus fugit doloribus et sequi. Accusantium voluptatem nihil doloremque eum odit.', '14307022476', 'maximeQuam', 'molestiaeUt', 0, 0, 0, 0, 0, 31, 0),
(32, '81649 Gulgowski Park Suite 668\nMosciskiview, RI 77816', '1979-12-23', '', 'Beatae impedit quam ipsum et deleniti alias voluptatibus. Ratione ullam accusantium natus eligendi et explicabo facilis dicta. Praesentium quia tempore quis commodi corrupti eum. Sunt cum nemo necessitatibus nam sunt dolorem ea et.', '19158821247', 'etEx', 'rerumOmnis', 0, 0, 0, 0, 0, 32, 0),
(33, '7685 Christophe Ways Suite 881\nLake Raleighview, LA 12145', '1993-07-10', '', 'Officia quia ut quo nihil dolores qui. Labore quibusdam facere voluptas debitis. Delectus dignissimos voluptas perferendis quo velit sed. Quos qui ut ratione architecto nihil velit eaque.', '17439578681', 'aliasEst', 'nemoEum', 0, 0, 0, 0, 0, 33, 0),
(34, '69868 Hilpert Crossroad\nNorth Winstonberg, OR 42623-5755', '2002-09-17', '', 'Magnam tempora maxime id odit et et eum. Ut neque quia aperiam dolor. Rerum quas eos ea sed exercitationem. Sit exercitationem et facilis numquam.', '9409591956', 'quiTempore', 'ducimusConsectetur', 0, 0, 0, 0, 0, 34, 0),
(35, '66097 Davis Orchard Apt. 944\nNorth Brett, RI 09350', '1994-11-14', '', 'Recusandae mollitia dolores modi aut quia. Veritatis quaerat est est. Porro aliquam fuga molestiae aut praesentium cumque. Sunt consequatur aliquam ut.', '18282250276', 'fugaLaboriosam', 'iustoNemo', 0, 0, 0, 0, 1, 35, 0),
(36, '412 Kuphal Terrace\nBridgetteport, CO 08716', '1985-08-07', '', 'Maxime architecto consequuntur quisquam fuga odit. Hic est dignissimos nemo impedit nesciunt quos. Saepe sint numquam aliquam suscipit ullam. Qui libero dolor enim saepe magni laborum. Nihil amet illum tempore tenetur.', '5865361496', 'repellatAccusantium', 'maioresPlaceat', 0, 0, 0, 0, 0, 36, 0),
(37, '5063 Krystel Mews\nPort Elva, FL 06042', '1991-01-29', '', 'Rerum aut placeat iusto blanditiis qui ut et ullam. Error et dolor consequuntur reiciendis est amet. Illum quas assumenda vel sit ex excepturi.', '8576920247', 'rerumIncidunt', 'magnamDistinctio', 0, 0, 0, 0, 0, 37, 0),
(38, '957 McCullough Circle\nHamillchester, ID 29881-1406', '1991-07-20', '', 'Rerum accusantium molestias et incidunt velit voluptas. Aperiam omnis sed veniam amet. Veritatis aut ut repellat minima ea deserunt a. Facere et aperiam quas temporibus.', '2545346959', 'temporaOccaecati', 'sapienteSit', 0, 0, 0, 0, 0, 38, 0),
(39, '848 Kunde Ridge\nPort Thalia, TN 48296-5117', '1987-03-08', '', 'Sunt tenetur beatae inventore aut molestiae. Esse est qui veniam quae itaque eum. Quas dolores eius dicta quam recusandae. Distinctio laborum accusantium qui ut fugit ea nesciunt.', '12606315197', 'voluptasExercitationem', 'placeatUt', 0, 0, 0, 0, 0, 39, 0),
(40, '572 Flatley Estates Apt. 475\nWest Kaylietown, NH 01926-0431', '1992-11-10', '', 'Vel earum ipsum tempore sapiente aspernatur magni neque. Nihil voluptas eveniet repellendus qui autem quod sed. Ut beatae placeat quas suscipit et rem inventore. Dolorem rem sint esse est perspiciatis reprehenderit quia.', '8288379658', 'fugiatQuia', 'animiIusto', 0, 0, 0, 0, 1, 40, 0),
(43, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 42, 0),
(44, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 43, 0),
(45, NULL, '1996-12-26', '/uploads/673f09e8c9bb0c8c70ce2ba0558d3318_upload65722178e02ed.jpg', 'у меня есть задачки', '89268159056', NULL, 'borpheus', 0, 0, 0, 1, 0, 44, 0),
(47, NULL, NULL, '/uploads/никола самории_upload657242ea046e4.png', 'стараюсь', '', NULL, '', 0, 0, 0, 0, 0, 46, 1),
(49, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 47, 0),
(50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 48, 0),
(51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 49, 0),
(54, NULL, '1991-12-07', '/uploads/ЖАН ОНОРЕ ФРАГОНАР БУБЛИК_upload657229d153e86.jpg', 'начинающий веб разработчик', '13123213333', NULL, 'borpheus', 0, 0, 0, 1, 0, 53, 0),
(55, NULL, '1999-11-26', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 54, 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `auth`
--
ALTER TABLE `auth`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_bookmarks_users_1` (`user_id`),
  ADD KEY `fk_bookmarks_users_2` (`performer_id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_events_users_1` (`user_id`),
  ADD KEY `fk_events_tasks_1` (`task_id`);

--
-- Индексы таблицы `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `path` (`path`),
  ADD KEY `fk_files_tasks_1` (`task_id`),
  ADD KEY `fk_files_users_1` (`user_id`);

--
-- Индексы таблицы `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_messages_tasks_1` (`task_id`),
  ADD KEY `fk_messages_users_1` (`recipient_id`),
  ADD KEY `fk_messages_users_2` (`sender_id`);

--
-- Индексы таблицы `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Индексы таблицы `opinions`
--
ALTER TABLE `opinions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_opinions_users_1` (`owner_id`),
  ADD KEY `fk_opinions_users_2` (`performer_id`),
  ADD KEY `fk-opinions-task_id` (`task_id`);

--
-- Индексы таблицы `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_replies_users_1` (`user_id`),
  ADD KEY `fk_replies_tasks_1` (`task_id`);

--
-- Индексы таблицы `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tasks_categories_1` (`category_id`),
  ADD KEY `fk_tasks_statuses_1` (`status_id`),
  ADD KEY `fk-tasks-city_id` (`city_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_users_cities_1` (`city_id`),
  ADD KEY `idx-users-role_id` (`role_id`);

--
-- Индексы таблицы `user_categories`
--
ALTER TABLE `user_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_categories_users_1` (`user_id`),
  ADD KEY `fk_user_categories_categories_1` (`category_id`);

--
-- Индексы таблицы `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fk_user_settings_users_1` (`user_id`) USING BTREE,
  ADD UNIQUE KEY `phone` (`phone`,`skype`,`messenger`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `auth`
--
ALTER TABLE `auth`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `events`
--
ALTER TABLE `events`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `files`
--
ALTER TABLE `files`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `opinions`
--
ALTER TABLE `opinions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `replies`
--
ALTER TABLE `replies`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT для таблицы `role`
--
ALTER TABLE `role`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `status`
--
ALTER TABLE `status`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT для таблицы `user_categories`
--
ALTER TABLE `user_categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT для таблицы `user_settings`
--
ALTER TABLE `user_settings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `fk_bookmarks_users_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_bookmarks_users_2` FOREIGN KEY (`performer_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `fk_events_tasks_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`),
  ADD CONSTRAINT `fk_events_users_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `fk_files_tasks_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`),
  ADD CONSTRAINT `fk_files_users_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_messages_tasks_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`),
  ADD CONSTRAINT `fk_messages_users_1` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_messages_users_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `opinions`
--
ALTER TABLE `opinions`
  ADD CONSTRAINT `fk-opinions-task_id` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_opinions_users_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_opinions_users_2` FOREIGN KEY (`performer_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `fk_replies_tasks_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`),
  ADD CONSTRAINT `fk_replies_users_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `fk-tasks-city_id` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_tasks_categories_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `fk_tasks_statuses_1` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`);

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk-users-role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_categories`
--
ALTER TABLE `user_categories`
  ADD CONSTRAINT `fk_user_categories_categories_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `fk_user_categories_users_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `user_settings`
--
ALTER TABLE `user_settings`
  ADD CONSTRAINT `fk_user_settings_users_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
