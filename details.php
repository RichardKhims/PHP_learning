<?php
require_once 'db_config.php';

$hash_value = $_GET['hash'];
if (empty($hash_value)) die("Результат не найден");

$conn = new PDO("{$db_type}:host={$db_host}:{$db_port};dbname={$db_name}", $db_user, $db_passwd);
$sth = $conn->prepare("SELECT r.result_csv, r.words_count, r.uniq_words_count, t.text FROM `texts` as t, `results` as r WHERE `hash_value` = :hash_value AND t.id = r.text_id");
$sth->execute(array('hash_value' => $hash_value));

$res = $sth->fetch(PDO::FETCH_ASSOC);
if (!count($res)) die("Результат не найден");

$result_csv = $res['result_csv'];
$text = $res['text'];
$words_count = $res['words_count'];
$uniq_words_count = $res['uniq_words_count'];

$result = explode("\n", $result_csv);

echo "<p>Исходный текст: {$text}</p>".PHP_EOL;
echo "<p>Результат</p>".PHP_EOL;
foreach ($result as $row) {
    $values = explode(';', $row);
    echo "{$values[0]}:{$values[1]}<br>".PHP_EOL;
}
echo "<p>Всего слов в тексте: {$words_count}</p>".PHP_EOL;
echo "<p>Уникальных слов в тексте: {$uniq_words_count}</p>".PHP_EOL;
?>