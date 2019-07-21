<?php
require_once 'db_config.php';

$hash_value = $_GET['hash'];
if (empty($hash_value)) die("Результат не найден");

$conn = new PDO("{$db_type}:host={$db_host}:{$db_port};dbname={$db_name}", $db_user, $db_passwd);
$sth = $conn->prepare("SELECT t.upload_date, r.words_count, r.uniq_words_count, LEFT(t.text, 64) as `text` FROM `texts` as t, `results` as r WHERE `hash_value` = :hash_value AND t.id = r.text_id");
$sth->execute(array('hash_value' => $hash_value));

$res = $sth->fetch(PDO::FETCH_ASSOC);
if (!count($res)) die("Результат не найден");

$date = $res['upload_date'];
$words_count = $res['words_count'];
$uniq_words_count = $res['uniq_words_count'];
$text = $res['text'];

echo '<table border="1">';

echo '<tr>';
echo '<td>Дата</td>';
echo '<td>'.$date.'</td>';
echo '</tr>';

echo '<tr>';
echo '<td>Текст</td>';
echo '<td>'.$text.'</td>';
echo '</tr>';

echo '<tr>';
echo '<td>Кол-во слов</td>';
echo '<td>'.$words_count.'</td>';
echo '</tr>';

echo '<tr>';
echo '<td>Кол-во уникальных слов</td>';
echo '<td>'.$uniq_words_count.'</td>';
echo '</tr>';

echo '<tr>';
echo '<td>CSV файл с результатами</td>';
echo '<td><a href="'.$hash_value.'.csv">Скачать</a></td>';
echo '</tr>';

echo '<tr>';
echo '<td>Детальный просмотр</td>';
echo '<td><a href="details.php?hash='.$hash_value.'">Перейти</a></td>';
echo '</tr>';

echo '</table>';
?>