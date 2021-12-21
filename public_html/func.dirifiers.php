<?php

/**
 * Steffen @innosys
 */
function cleanString($text)
{
    $utf8 = array(
        '/[áàâãªä]/u' => 'a',
        '/[ÁÀÂÃÄ]/u' => 'A',
        '/[ÍÌÎÏ]/u' => 'I',
        '/[íìîï]/u' => 'i',
        '/[éèêë]/u' => 'e',
        '/[ÉÈÊË]/u' => 'E',
        '/[óòôõºö]/u' => 'o',
        '/[\x{1F600}-\x{1F64F}]/u' => '',
        '/[\x{1F300}-\x{1F5FF}]/u' => '',
        '/[\x{1F680}-\x{1F6FF}]/u' => '',
        '/[\x{2600}-\x{26FF}]/u' => '',
        '/[ÓÒÔÕÖ]/u' => 'O',
        '/[úùûü]/u' => 'u',
        '/[ÚÙÛÜ]/u' => 'U',
        '/ç/' => 'c',
        '/Ç/' => 'C',
        '/ñ/' => 'n',
        '/Ñ/' => 'N',
        '/–/' => '-', // UTF-8 hyphen to "normal" hyphen
        '/[\\\]/u' => ' - ', // UTF-8 hyphen to "normal" hyphen
        '/[|’‘‹›‚\']/u' => '', // Literally a single quote
        '/[“”´«»„"¨~^]/u' => '', // Double quote
        '/ /' => ' ', // nonbreaking space (equiv. to 0x160)
        '/_/' => ' ', // nonbreaking space (equiv. to 0x160)
        );
    return preg_replace(array_keys($utf8), array_values($utf8), $text);
}

/**
 * Steinhaug @systemweb
 */
function dirify($s, $delimiter = '-', $pretty = false)
{
    $s = convert_high_ascii($s);              // convert high-ASCII chars to 7bit
  $s = strtolower($s);                      // lower-case
  $s = strip_tags($s);                      // remove HTML tags
  $s = preg_replace('/&[^;\s]+;/', '', $s);   // remove HTML entities
  if ($pretty) {
      if (($delimiter == '-') or ($delimiter == '_')) {
          $s = preg_replace('/[^\w\s' . $delimiter . ']/', '', $s);
      } // remove non-word/space chars but keep delimiter!
      else {
          $s = preg_replace('/[^\w\s]/', '', $s);
      } // remove non-word/space chars but keep delimiter!
    $s = preg_replace('/\s+/', $delimiter, $s); // change space chars to underscores
    $s = utf8_decode($s);
      $s = str_replace('..', '.', $s);
      $s = str_replace("?", "", $s);
      $s = str_replace("__", "_", $s);
      if (($delimiter == '-') or ($delimiter == '_')) {
          $s = preg_replace("/^" . $delimiter . "/", "", $s);
          $s = preg_replace("/" . $delimiter . "$/", "", $s);
      }
  } else {
      if (($delimiter == '-') or ($delimiter == '_')) {
          $s = preg_replace('/[^\w\s' . $delimiter . ']/', $delimiter, $s);
      } // remove non-word/space chars but keep delimiter!
      else {
          $s = preg_replace('/[^\w\s]/', '', $s);
      } // remove non-word/space chars but keep delimiter!
    $s = preg_replace('/\s/', $delimiter, $s); // change space chars to underscores
    $s = utf8_decode($s);
  }
    if ($delimiter == '-') {
        $s = str_replace('_', $delimiter, $s);
    }
    if ($delimiter == '_') {
        $s = str_replace('-', $delimiter, $s);
    }
    return $s;
}
function convert_high_ascii($s)
{
    $high_ascii = array(
    "!\xc0!" => 'A',  "!\xe0!" => 'a',    // A` a`
    "!\xc1!" => 'A',  "!\xe1!" => 'a',    // A' a'
    "!\xc2!" => 'A',  "!\xe2!" => 'a',    // A^ a^
    "!\xc4!" => 'Ae', "!\xe4!" => 'ae',   // A: a:
    "!\xc3!" => 'A',  "!\xe3!" => 'a',    // A~ a~
    "!\xc8!" => 'E',  "!\xe8!" => 'e',    // E` e`
    "!\xc9!" => 'E',  "!\xe9!" => 'e',    // E' e'
    "!\xca!" => 'E',  "!\xea!" => 'e',    // E^ e^
    "!\xcb!" => 'Ee', "!\xeb!" => 'ee',   // E: e:
    "!\xcc!" => 'I',  "!\xec!" => 'i',    // I` i`
    "!\xcd!" => 'I',  "!\xed!" => 'i',    // I' i'
    "!\xce!" => 'I',  "!\xee!" => 'i',    // I^ i^
    "!\xcf!" => 'Ie', "!\xef!" => 'ie',   // I: i:
    "!\xd2!" => 'O',  "!\xf2!" => 'o',    // O` o`
    "!\xd3!" => 'O',  "!\xf3!" => 'o',    // O' o'
    "!\xd4!" => 'O',  "!\xf4!" => 'o',    // O^ o^
    "!\xd6!" => 'Oe', "!\xf6!" => 'oe',   // O: o:
    "!\xd5!" => 'O',  "!\xf5!" => 'o',    // O~ o~
    "!\xd9!" => 'U',  "!\xf9!" => 'u',    // U` u`
    "!\xda!" => 'U',  "!\xfa!" => 'u',    // U' u'
    "!\xdb!" => 'U',  "!\xfb!" => 'u',    // U^ u^
    "!\xdc!" => 'Ue', "!\xfc!" => 'ue',   // U: u:
    "!\xc7!" => 'C',  "!\xe7!" => 'c',    // ,C ,c
    "!\xd1!" => 'N',  "!\xf1!" => 'n',    // N~ n~
    "!\xdf!" => 'ss',                     //
    "!\xc6!" => 'AE', "!\xe6!" => 'ae',   // Æ  æ
    "!\xd8!" => 'OE', "!\xf8!" => 'oe',   // Ø  ø
    "!\xc5!" => 'A',  "!\xe5!" => 'a',    // Å  å
    "!\x8a!" => 'S',  "!\x9a!" => 's',    // S with v over (som å)
    "!\x8c!" => 'CE', "!\x9c!" => 'ce',   // CE symbol
    "!\x8e!" => 'Z',  "!\x9e!" => 'z',    // Z with v over (som å)
    "!\xdd!" => 'Y',  "!\xFd!" => 'Y',    // Y´ Y´
    "!\x9f!" => 'Y',  "!\xde!" => 'Y',    // Y: Y´
    "!\xd0!" => 'D',  "!\xf0!" => 'a',    // -D obelix?
    "!\xb9!" => '1',                      // sup 1
    "!\xb2!" => '2',  "!\xb3!" => '3',    // 2  3
  );
    $find = array_keys($high_ascii);
    $replace = array_values($high_ascii);
    $s = preg_replace($find, $replace, $s);
    return $s;
}
function high_ascii_dirify_check()
{ // No fuction, just save the code
    $hexdec_array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f');
    for ($i = 0;$i < 255;$i++) {
        if (!($i % 16)) {
            echo "\n";
        }
        $digit1 = floor($i / 16);
        $digit2 = $i - ($digit1 * 16);
        $hexdec = $hexdec_array[$digit1] . $hexdec_array[$digit2];
        eval('echo dirify($hexdec . ":\x' . $hexdec . ', ");');
        eval('echo $hexdec . "-> \x' . $hexdec . ', ";');
    }
}


function dirify2($string)
{
    $table = array(
        'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z',
        'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c',
        'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A',
        'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
        'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I',
        'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
        'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U',
        'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
        'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
        'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i',
        'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o',
        'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u',
        'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b',
        'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r', '-' => '',
    );
    $string = trim($string);
    $string = strtr($string, $table);

    $utf8 = array(
        '/–/' => '-',
        '/[’‘‚‛❛❜❟]/u' => '\'',
        '/[❮❯‹›<>[\]]/u' => '\'',
        '/[‟“”«»„❝❞⹂⹂〝〞〝〟＂]/u' => '"',
    );
    $string = preg_replace(array_keys($utf8), array_values($utf8), $string);
    //$string = str_replace(['"',"'"], [' ',' '], $string);
    $string = preg_replace("/[\s-]+/", " ", $string);

    return $string;
}

/**
 * Reggiecril/Whisky
 */
function cleanString_rewh($string)
{
    $string = trim($string);
    $string = filter_var($string, FILTER_SANITIZE_STRING);
    //or maybe stripslashes($string)
    //or elsewhere mysqli-real-escape_string()
    return $string;
}

/**
 * franz825/test
 */
function cleanString_frte($text)
{
    $utf8 = array(
        '/[áàâãªä]/u' => 'a',
        '/[ÁÀÂÃÄ]/u' => 'A',
        '/[ÍÌÎÏ]/u' => 'I',
        '/[íìîï]/u' => 'i',
        '/[éèêë]/u' => 'e',
        '/[ÉÈÊË]/u' => 'E',
        '/[óòôõºö]/u' => 'o',
        '/[ÓÒÔÕÖ]/u' => 'O',
        '/[úùûü]/u' => 'u',
        '/[ÚÙÛÜ]/u' => 'U',
        '/ç/' => 'c',
        '/Ç/' => 'C',
        '/ñ/' => 'n',
        '/Ñ/' => 'N',
        '/–/' => '-', // UTF-8 hyphen to "normal" hyphen
        '/[’‘‹›‚]/u' => ' ', // Literally a single quote
        '/[“”«»„]/u' => ' ', // Double quote
        '/ /' => ' ', // nonbreaking space (equiv. to 0x160)
        '/[\'"]/u' => ''
    );
    return preg_replace(array_keys($utf8), array_values($utf8), $text);
}


/**
 * EduardoHonorato/funcao-para-url-amigavel
 */
function clean_edfu($string)
{
    $table = array(
        'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z',
        'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c',
        'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A',
        'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
        'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I',
        'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
        'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U',
        'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
        'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
        'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i',
        'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o',
        'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u',
        'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b',
        'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r', '-' => '',
    );
    // Traduz os caracteres em $string, baseado no vetor $table
    $string = trim($string);
    $string = strtr($string, $table);
    // converte para minúsculo
    $string = strtolower($string);
    // remove caracteres indesejáveis (que não estão no padrão)
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    // Remove múltiplas ocorrências de hífens ou espaços
    $string = preg_replace("/[\s-]+/", " ", $string);
    // Transforma espaços e underscores em hífens
    $string = preg_replace("/[\s_]/", "-", $string);
    if (substr($string, -1) == '-') {
        $string = substr($string, 0, -1);
    }

    // retorna a string
    return $string;
}



function filter_1($string)
{
    $string = filter_var(
        $string,
        FILTER_SANITIZE_SPECIAL_CHARS,
        array('flags' => FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_BACKTICK)
    );

    return $string;
}
function filter_2($string)
{
    $string = filter_var(
        $string,
        FILTER_SANITIZE_ENCODED,
        array('flags' => FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_BACKTICK)
    );

    return $string;
}
function filter_3($string)
{
    $string = filter_var(
        $string,
        FILTER_SANITIZE_STRING,
        array('flags' => FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_BACKTICK)
    ); // FILTER_FLAG_NO_ENCODE_QUOTES |

    return $string;
}
