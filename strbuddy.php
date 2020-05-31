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
 */
class strbuddy {

    const version = '0.5.0';

    private $filesystem_stripper = '/([^\x20-~]+)|([\\/:?"<>|\*]+)/';

    public function __construct(){

    }

    public function sanitize($string){
        $string = $this->remove_4byte($string);
        return $string;
    }

    /**
     * Create a valid directory or filename
     */
    public function dirify($string, $delimiter = '_'){

        $string = $this->aschiify($string);
        $string = mb_strtolower($string);
        $string = strip_tags($string);                          // remove HTML tags
        $string = preg_replace('/&[^;\s]+;/',' ',$string);      // remove HTML entities

        $string = $this->filename_only($string, $delimiter);

        /*
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        $string = preg_replace("/[\s-]+/", " ", $string);
        if(($delimiter == '-') OR ($delimiter == '_'))
            $string = preg_replace('/[^\w\s' . $delimiter . ']/', $delimiter, $string);
            else
            $string = preg_replace('/[^\w\s]/', '', $string);
        */

        // Remove any occurencies of ..
        while( $match = preg_match('/\.\./', $string) ){
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
    public function aschiify($string, $space=' '){

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
            'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', '-'=>'',
        ];
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

        if( $space !== ' ')
            $string = str_replace(' ', $space, $string);

        return $string;
    }

    /**
     * Make filename from string, will try to keep name readable and will remove all illegal characters.
     */
    public function filename_only($string, $delim = '_'){

        $pos = mb_strrpos($string, '.');
        if( $pos !== false ){
            $ext = mb_substr($string, mb_strrpos($string, '.') + 1);
            $filename = mb_substr($string, 0, mb_strrpos($string, '.'));
        } else {
            $ext = null;
            $filename = $string;
        }

        $filename = $this->aschiify($filename, $delim);
        $filename = preg_replace($this->filesystem_stripper, '_', $filename);
        $filename = $this->aschii_only($filename, $delim);
        $filename = trim($filename, $delim . " \t\n\r\0\x0B");

        if( $ext === null )
            return $filename;

        $ext = $this->aschiify($ext, $delim);
        $ext = preg_replace($this->filesystem_stripper, '_', $ext);
        $ext = $this->aschii_only($ext, $delim);

        $ext = trim($ext, $delim . " \t\n\r\0\x0B");

        return $filename . '.' . $ext;
    }

    /**
     * Remove any none-ASCHII character
     */
    public function aschii_only($string, $space=' '){
        $string = trim( preg_replace('/[[:^print:]]/', '', $string) );

        if( $space !== ' ')
            $string = str_replace(' ', $space, $string);

        return $string;
    }

    /**
     * Remove any multibyte character
     */
    function remove_4byte($string, $space=' ') {
        $string = preg_replace('%(?:
          \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
        | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
        | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
        )%xs', ' ', $string);
        $string = trim(preg_replace("/[\s-]+/", " ", $string));

        if( $space !== ' ')
            $string = str_replace(' ', $space, $string);

        return $string;
    }

}
