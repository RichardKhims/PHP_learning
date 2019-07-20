<!DOCTYPE html>
<html lang="ru">
    <head>
        <title>Счетчик слов</title>
        <meta charset="utf-8">
    </head>
    <body>
        <form action="" method="post" enctype="multipart/form-data">
            <textarea name="text" cols="150" rows="20" placeholder="Введите текст"></textarea>><br/>
            <input type="submit" value="Ok">
        </form>
    </body>
</html>

<?php

function getWordsFromString($string) {
    if (preg_match_all("/\b(\w+)\b/ui", $string, $matches)) {
        return $matches[1];
    }

    return array();
}

function getResult($words)
{
    $result = array();
    foreach ($words as $word) {
        if (!array_key_exists($word, $result)) $result[$word] = 1;
        else $result[$word]++;
    }

    return $result;
}

function saveResult($result, $words_count, $uniq_words_count, $filename) {
    $file = fopen($filename, "w");

    fwrite($file, "Кол-во слов;{$words_count}\n");
    fwrite($file, "Кол-во уникальных слов;{$uniq_words_count}\n");
    foreach ($result as $key => $count) {
        fwrite($file, "$key;{$count}\n");
    }

    fclose($file);
}

function makeCsvFromResult($result, $words_count, $uniq_words_count) {
    $csv_array = array();
    array_push($csv_array, "Кол-во слов;{$words_count}", "Кол-во уникальных слов;{$uniq_words_count}");
    foreach ($result as $key => $count) {
        array_push($csv_array,"$key;{$count}");
    }

    return join('\n', $csv_array);
}


$text = $_POST['text'] ?? '';
if (empty($text)) die("Текст не задан");

$conn = new PDO('mysql:host=localhost:3306;dbname=txt_to_words', 'root', '');
$sth = $conn->prepare("INSERT INTO `texts` SET `text` = :text");
$sth->execute(array('text' => $text));

$words = getWordsFromString($text);
$result = getResult($words);

$words_count = count($words);
$uniq_words_count = count($result);

$last_id = $conn->lastInsertId();
$hash_value = uniqid($last_id);

$sth = $conn->prepare("INSERT INTO `results` SET `text_id` = :text_id, `hash_value` = :hash_value, `result_csv` = :result_csv, `words_count` = :words_count, `uniq_words_count` = :uniq_words_count");
$sth->execute(array(
    'text_id' => $last_id,
    'hash_value' => $hash_value,
    'result_csv' => makeCsvFromResult($result, $words_count, $uniq_words_count),
    'words_count' => $words_count,
    'uniq_words_count' => $uniq_words_count));

echo "<br>";
echo "<a href='result.php?hash={$hash_value}'>Результат</a>";

//$file = $_FILES['file']['tmp_name'] ?? '';
//if (file_exists($file)) {
//    $out_file = makeCsvFromText(file_get_contents($_FILES['file']['tmp_name']) ?? '', 'from_file.csv');
//    if (!empty($out_file)) echo "<a href='{$out_file}'>Результат 2</a>";
//}
?>