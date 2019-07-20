
-- Дамп структуры базы данных txt_to_words
CREATE DATABASE IF NOT EXISTS `txt_to_words` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `txt_to_words`;

-- Дамп структуры для таблица txt_to_words.results
CREATE TABLE IF NOT EXISTS `results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text_id` int(11) NOT NULL,
  `word` varchar(50) NOT NULL,
  `count` int(11) NOT NULL,
  `hash_value` varchar(32) NOT NULL,
  `words_count` int(11) NOT NULL,
  `uniq_words_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_results_texts` (`text_id`),
  KEY `hash_value` (`hash_value`),
  CONSTRAINT `FK_results_texts` FOREIGN KEY (`text_id`) REFERENCES `texts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы txt_to_words.results: ~0 rows (приблизительно)
DELETE FROM `results`;
/*!40000 ALTER TABLE `results` DISABLE KEYS */;
/*!40000 ALTER TABLE `results` ENABLE KEYS */;

-- Дамп структуры для таблица txt_to_words.texts
CREATE TABLE IF NOT EXISTS `texts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(10000) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
