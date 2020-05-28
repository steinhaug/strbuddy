<?php


/**
 * My personal STRING friend, for manipulation and problems related to strings
 * 
 * (c) Kim Steinhaug
 * http://steinhaug.no/
 * 
 */
class strbuddy {

    const version = '0.0.0';

    public function __construct(){

    }

    public function sanitize($string){

        $table = array(
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
            'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', '-'=>'',
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
        $string = trim(preg_replace("/[\s-]+/", " ", $string));

        return $string;

    }

    public function dirify($string){

        $string = mb_strtolower($string);
        $string = $this->sanitize($string);
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        $string = preg_replace("/[\s-]+/", " ", $string);

        return $string;

    }


}
