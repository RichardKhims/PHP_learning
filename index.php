<!DOCTYPE html>
<html lang="ru">
    <head>
        <title>Счетчик слов</title>
        <meta charset="utf-8">
    </head>
    <body>
        <form action="" method="post" enctype="multipart/form-data">
            <textarea name="text" cols="150" rows="20" placeholder="Введите текст"></textarea>><br/>
            Укажите файл: <input type="file" name="file"><br/>
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

function makeCsvFromText($text, $filename) {
    if (empty($text)) return '';

    $words = getWordsFromString($text);
    $result = getResult($words);

    $words_count = count($words);
    $uniq_words_count = count($result);

    saveResult($result, $words_count, $uniq_words_count, $filename);

    return $filename;
}

echo "<br>";
$out_file = makeCsvFromText($_POST['text'] ?? '', 'from_text.csv');
if (!empty($out_file)) echo "<a href='{$out_file}'>Результат 1</a>";

echo "<br>";
$file = $_FILES['file']['tmp_name'] ?? '';
if (file_exists($file)) {
    $out_file = makeCsvFromText(file_get_contents($_FILES['file']['tmp_name']) ?? '', 'from_file.csv');
    if (!empty($out_file)) echo "<a href='{$out_file}'>Результат 2</a>";
}
?>