-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Фев 12 2022 г., 13:37
-- Версия сервера: 8.0.24
-- Версия PHP: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `tree_work`
--

-- --------------------------------------------------------

--
-- Структура таблицы `menu`
--

CREATE TABLE `menu` (
  `id` int NOT NULL,
  `titlle` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `parent_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `menu`
--

INSERT INTO `menu` (`id`, `titlle`, `parent_id`) VALUES
(1, 'Автомобили', 0),
(2, 'Самолёты', 0),
(3, 'Трактора', 0),
(4, 'Ремонт', 0),
(5, 'Легковушки', 1),
(6, 'Грузовые', 1),
(7, 'Ремонт', 5),
(8, 'Аренда', 5),
(9, 'Сантехника', 0),
(10, 'Аренда', 2),
(11, 'Грузоперевозки', 2),
(12, 'Ремонт', 3),
(13, 'Ремонт', 2),
(14, 'Шасси', 13),
(16, 'Лёгкомоторных', 10),
(17, 'Большегрузных', 10),
(18, 'Квартир', 4),
(19, 'Частных домов', 4),
(20, 'Демонтаж обоев', 18),
(21, 'Подсчёт работы', 18),
(22, 'Фюзеляжа', 13),
(23, 'Стоимость', 6),
(24, 'Стоимость', 5),
(25, 'За доллары', 24),
(26, 'За тугрики', 24),
(27, 'За евро', 24),
(28, 'За рубли', 24),
(29, 'Велосипеды', 0),
(30, 'Ремонт ходовой', 7),
(31, 'Ремонт электрики', 7),
(32, 'Ремонт двигателя', 7),
(33, 'На сутки', 16),
(34, 'На месяц', 16),
(35, 'На сутки', 17),
(36, 'На месяц', 17),
(38, 'До 2 тонн', 11),
(39, 'Свыше 2х тонн', 11),
(40, 'Замена резины', 14),
(41, 'Накачка шин', 14),
(42, 'Вулканизация', 14),
(44, 'Отключение стояка', 9),
(45, 'Демонтаж стояка', 9),
(46, 'Монтаж стояка', 9),
(47, 'На один час', 44),
(48, 'На сутки', 44),
(49, 'С последствиями', 45),
(50, 'Без последствий', 45),
(51, 'Полипропиленовыми трубами', 46),
(52, 'Металлопласт', 46),
(53, 'Обжимное соединение', 52),
(54, 'Механическое соединение', 52),
(55, 'На горячую воду', 51),
(56, 'На холодную воду', 51),
(57, 'Урегулировать с соседями', 49),
(58, 'Вызвать участкового', 49),
(59, 'Забить на всё', 49),
(60, 'Тихо и аккуратно', 50),
(61, 'Режим ниндзя', 50),
(62, 'Азотом', 41),
(63, 'Воздухом', 41);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
