<?php

define('BR','<br>');
require '../vendor/autoload.php';

require 'func.dirifiers.php';

//header("Content-Type: text/plain");
echo '<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title></title>
        <style>
            table {
                border-collapse: collapse;
            }
            table {
                border: none;
            }
            th, td {
                border: 1px solid #ccc;
                border-top: none;
                border-bottom: none;
            }
            td {
                padding: 0 1em;
                text-align: center;
            }
            .td00 {
                text-align: left;
            }
            td:first-child {
                padding-left: 0;
                border-left: 0;
            }
            td:last-child {
                padding-right: 0;
                border-right: 0;
            }
            td:nth-child(4) {
                text-align: left;
            }
            tr.new_string td {
                border-top: 1px solid #888;
                background-color: #eee;
                padding-top: 5px;
                padding-bottom: 5px;
            }
        </style>
    </head>
    <body>
';

$funcs = ['dirify','sanitize'];

$strings = [
    'æ/Æ ø/Ø å/Å ä/Ä ö/Ö blåbær frøya ØLBÅTSØK',
    '? « out ‹ inside › out » <'
];
// &laquo; &lsaquo; &rsaquo; &raquo;


echo '<h1>strbuddy test suit v0.0.0</h1>';

$strbuddy = new strbuddy;


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
