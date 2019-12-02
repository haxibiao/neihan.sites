<?php

namespace App\Helpers\BadWord;

class BadWordUtils
{
    private static $instance;

    private static $badWords = [];

    private function __construct()
    {
        $file = $this->file();
        if (file_exists($file)) {
            $text           = file_get_contents($file);
            $badWords       = explode("\n", $text);
            self::$badWords = array_map(function ($text) {
                $text = base64_decode($text);
                $text = str_replace(["\n", "\r", "\n\r", '"', ';'], '', $text);
                return $text;
            }, $badWords);

        }
    }

    public function __clone()
    {

    }

    public static function file($filePath = null)
    {
        return $filePath ? $filePath : dirname(__FILE__) . '/' . 'bad-word.txt';
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static;
        }
        return self::$instance;
    }

    public static function check($text, $strict = false)
    {
        if (self::getInstance()) {
            preg_match_all("/[\x{4e00}-\x{9fc2}]/iu", $text, $match);
            $text = implode("", $match[0]);
            foreach (self::$badWords as $badWord) {
                if (strpos($text, $badWord) !== false) {
                    return true;
                }
            }

            // //严格模式 分词匹配
            // if ($strict) {
            //     $wordArr  = (new VicWord(extension_loaded('igbinary') ? 'igb' : 'json'))->getWord($text);
            //     $badWords = [];
            //     foreach ($wordArr as $word) {
            //         if (mb_strlen($word[0], 'utf8') > 1) {
            //             $badWords = $word[0];
            //             break;
            //         }
            //     }

            //     if (count($badWords) > 0) {
            //         foreach ($badWords as $badWord) {

            //         }
            //     }

            // }
        }
    }

    public static function addWord($text)
    {
        if (self::getInstance()) {
            if (!in_array($text, self::$badWords)) {
                $instance = self::getInstance();
                $file     = $instance->file();
                if (file_exists($file)) {
                    $text = PHP_EOL . base64_encode($text);
                    return file_put_contents($file, $text, FILE_APPEND);
                }
            }
        }
    }

    public static function inWords(array $textArr)
    {
        if (self::getInstance()) {
            foreach ($textArr as $text) {
                if (in_array($text, self::$badWords)) {
                    return true;
                }
            }
        }
    }

    public static function getWords()
    {
        if (self::getInstance()) {
            return self::$badWords;
        }
    }

}
