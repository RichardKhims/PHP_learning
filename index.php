<!DOCTYPE html>
<html lang="ru">
    <head>
        <title>Счетчик слов</title>
        <meta charset="utf-8">
    </head>
    <body>
        <form action="index.php" method="post">
            Введите текст: <input type="text" name="text" size="200"><br/>
            <input type="submit" value="Ok">
        </form>
    </body>
</html>

<?php

function getWordsFromString($string)
{
    if (preg_match_all("/\b(\w+)\b/ui", $string, $matches)) {
        return $matches[1];
    }

    return array();
}

$text = $_POST['text'] ?? '';
$words = getWordsFromString($text);

$result = array();

foreach ($words as $word) {
    if (!array_key_exists($word, $result)) $result[$word] = 1;
    else $result[$word]++;
}

echo "Исходный текст: {$text}".PHP_EOL;
echo "Результат".PHP_EOL;

foreach ($result as $key => $count) {
    echo "{$key}:{$count}".PHP_EOL;
}

$words_count = count($words);
$uniq_words_count = count($result);

echo "Всего слов в тексте: {$words_count}".PHP_EOL;
echo "Уникальных слов в тексте: {$uniq_words_count}".PHP_EOL;
?>