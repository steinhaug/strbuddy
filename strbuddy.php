<?php

/**
 * My personal STRING friend, for manipulation and problems related to strings
 *
 * (c) Kim Steinhaug
 * http://steinhaug.no/
 *
 * Function:
 *
 * $strbuddy = new strbuddy;
 * $strbuddy->dirify($string);
 *
 *
 *
 * https://api.cakephp.org/2.2/source-class-Sanitize.html#34-58
 * http://htmlpurifier.org/docs
 * https://packagist.org/packages/iamcal/php-emoji
 * https://packagist.org/packages/elvanto/litemoji
 */
class strbuddy
{
    const version = '0.6.0';

    public static $filesystem_stripper = '/([^\x20-~]+)|([\\/:?"<>|\*]+)/';


    public function __construct()
    {
    }

    public function sanitize($string)
    {
        $string = self::remove_4byte($string);
        return $string;
    }

    /**
     * Create a valid directory or filename
     */
    public static function dirify($string, $delimiter = '_')
    {
        $string = self::aschiify($string);
        $string = mb_strtolower($string);
        $string = strip_tags($string);                          // remove HTML tags
        $string = preg_replace('/&[^;\s]+;/', ' ', $string);      // remove HTML entities

        $string = self::filename_only($string, $delimiter);

        /*
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        $string = preg_replace("/[\s-]+/", " ", $string);
        if(($delimiter == '-') OR ($delimiter == '_'))
            $string = preg_replace('/[^\w\s' . $delimiter . ']/', $delimiter, $string);
            else
            $string = preg_replace('/[^\w\s]/', '', $string);
        */

        // Remove any occurencies of ..
        while ($match = preg_match('/\.\./', $string)) {
            $string = str_replace('..', '.', $string);
        }

        return $string;
    }

    /**
     * Tries to translate most known characters to their equivalent ASCHII version.
     * The reasoning for this is to keep the string readable is plain text when converting to filenames and such.
     *
     * @return A string where known illegal characters are converted into ASCHII, lesser complexity.
     */
    public static function aschiify($string, $space=' ')
    {
        $table = [
            'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z',
            'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
            'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
            'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
            'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
            'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
            'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
            'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
            'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
            'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
            'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
            'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
            'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
        ];
        $string = trim($string);
        $string = strtr($string, $table);

        $utf8 = [
            '/–/' => '-',
            '/[’‘‚‛❛❜❟]/u' => '\'',
            '/[❮❯‹›<>[\]]/u' => '\'',
            '/[‟“”«»„❝❞⹂⹂〝〞〝〟＂]/u' => '"',
        ];
        $string = preg_replace(array_keys($utf8), array_values($utf8), $string);
        //$string = str_replace(['"',"'"], [' ',' '], $string);
        $string = trim(preg_replace("/[\s]+/", " ", $string));

        if ($space !== ' ') {
            $string = str_replace(' ', $space, $string);
        }

        return $string;
    }

    /**
     * Make filename from string, will try to keep name readable and will remove all illegal characters.
     */
    public static function filename_only($string, $delim = '_')
    {
        $pos = mb_strrpos($string, '.');
        if ($pos !== false) {
            $ext = mb_substr($string, mb_strrpos($string, '.') + 1);
            $filename = mb_substr($string, 0, mb_strrpos($string, '.'));
        } else {
            $ext = null;
            $filename = $string;
        }

        $filename = self::aschiify($filename, $delim);
        $filename = preg_replace(self::$filesystem_stripper, '_', $filename);
        $filename = self::aschii_only($filename, $delim);
        $filename = trim($filename, $delim . " \t\n\r\0\x0B");
        if ($delim != '-') {
            $filename = trim($filename, '-');
        }

        if ($ext === null) {
            return $filename;
        }

        $ext = self::aschiify($ext, $delim);
        $ext = preg_replace(self::$filesystem_stripper, '_', $ext);
        $ext = self::aschii_only($ext, $delim);

        $ext = trim($ext, $delim . " \t\n\r\0\x0B");

        return $filename . '.' . $ext;
    }

    /**
     * Remove any none-ASCHII character
     */
    public static function aschii_only($string, $space=' ')
    {
        $string = trim(preg_replace('/[[:^print:]]/', '', $string));

        if ($space !== ' ') {
            $string = str_replace(' ', $space, $string);
        }

        return $string;
    }

    /**
     * Remove any multibyte character
     */
    public static function remove_4byte($string, $space=' ')
    {
        $string = preg_replace('%(?:
          \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
        | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
        | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
        )%xs', ' ', $string);
        $string = trim(preg_replace("/[\s-]+/", " ", $string));

        if ($space !== ' ') {
            $string = str_replace(' ', $space, $string);
        }

        return $string;
    }

    /**
     * Verify if string contains UTF-8 characters
     */
    public static function is_utf8($string)
    {
        return preg_match('%^(?:
            [\x09\x0A\x0D\x20-\x7E]              # ASCII
            | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
            |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
            | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
            |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
            |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
            | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
            |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
        )*$%xs', $string);
    }

    public static function is_utf82($str)
    {
        $strlen = strlen($str);
        for ($i=0; $i<$strlen; $i++) {
            $ord = ord($str[$i]);
            if ($ord < 0x80) {
                continue;
            } // 0bbbbbbb
            elseif (($ord&0xE0)===0xC0 && $ord>0xC1) {
                $n = 1;
            } // 110bbbbb (exkl C0-C1)
            elseif (($ord&0xF0)===0xE0) {
                $n = 2;
            } // 1110bbbb
            elseif (($ord&0xF8)===0xF0 && $ord<0xF5) {
                $n = 3;
            } // 11110bbb (exkl F5-FF)
            else {
                return false;
            } // ungültiges UTF-8-Zeichen

            for ($c=0; $c<$n; $c++) { // $n Folgebytes? // 10bbbbbb
                if (++$i===$strlen || (ord($str[$i])&0xC0)!==0x80) {
                    return false;
                }
            } // ungültiges UTF-8-Zeichen
        }
        return true; // kein ungültiges UTF-8-Zeichen gefunden
    }

    /**
     * Encode string as hexadecimal
     */
    public static function str2hex($data)
    {
        $newData = '';
        $len = strlen($data);
        for ($x=0;$x<$len;$x++) {
            $newData .= str_pad(dechex(ord($data[$x])), 2, '0', STR_PAD_LEFT);
        }
        return $newData;
    }

    /**
     * Decode a hexadecimal string
     */
    public static function hex2str($data)
    {
        $newData = '';
        if (strlen($data) % 2) {
            return '';
        }
        $len = strlen($data);
        for ($x=0;$x<$len;$x+=2) {
            $newData .= chr(hexdec($data[$x].$data[$x+1]));
        }
        return $newData;
    }
}
