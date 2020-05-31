<?php

define('BR','<br>');
require '../vendor/autoload.php';

require 'php.libs/emoji-php-static/Emoji.class.php';

//require 'func.dirifiers.php';

//header("Content-Type: text/plain");
echo '<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title></title>
        <style></style>
        <link rel="stylesheet" href="style.css" />
        <link rel="stylesheet" href="php.libs/emoji-php-static/emoji.css" />
    </head>
    <body>
';

$funcs = ['filename'];

$strings = [
    'jv15320' . "\u{1F30F}"  . '-org-01.jv15320 org',
    'Git-2.26.1-64-bit (1).exe',
    'wkhtmltox-0.12.5-1.msvc2015-win64.exe',
    'blåbær.null',
    ' Bl-<å[b]æ>r".?nUll_* '
];



$strbuddy = new strbuddy;
echo '<h1>strbuddy test suit ' . $strbuddy::version . '</h1>';



//$strings[] = "\u{1F30F}" . ' time ' . "\u{23F3}" . ' is up! Jasså?';

/*
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
$clean_html = $purifier->purify($dirty_html);
*/

echo '<table>
<tbody>';

foreach($strings AS $str){

    $new_string = true;
    foreach($funcs AS $func){

        $result = $strbuddy->{$func}($str);

        $line = [];
        $line[] = $func;
        $line[] = mb_detect_encoding($str);
        $line[] = mb_detect_encoding($result);
        $line[] = $result;
        similar_text($result, $str, $percent);
        $line[] = number_format($percent, 1);
        $line[] = levenshtein($result, $str);
        $line[] = soundex($result);
        $line[] = metaphone($result, 100);

        if($new_string){
            $new_string = false;

            echo '<tr class="new_string">';
            echo '<td></td><td></td><td></td><td>' . htmlentities($str, ENT_COMPAT | ENT_HTML401, 'UTF-8') . '</td>';
            echo '<td><span title="similar text">ST</span></td><td>L</td><td>S</td><td>M</td>';
            echo '</tr>';
        }
        echo '<tr>';
        for ($i = 0; $i < count($line); $i++) {
            echo '<td class="td' . str_pad($i, 2, '0', STR_PAD_LEFT) . '">' . htmlentities($line[$i], ENT_COMPAT | ENT_HTML401, 'UTF-8') . '</td>';
        }
        echo '</tr>';

    }


}
echo '</tbody>
</table>';

echo '
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
    </body>
</html>
';
