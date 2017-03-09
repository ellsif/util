<?php
namespace ellsif\util;


class StringUtil
{
    /**
     * 先頭から文字列を除去する。
     */
    public static function leftRemove(string $str, string $prefix)
    {
        if (($pos = mb_strpos($str, $prefix)) === 0) {
            return mb_substr($str, mb_strlen($prefix));
        }
        return $str;
    }

    /**
     * 末尾から文字列を除去する。
     */
    public static function rightRemove(string $str, string $suffix)
    {
        if (($pos = mb_strpos($str, $suffix)) >= 0) {
            return mb_substr($str, 0, $pos);
        }
        return $str;
    }

    /**
     * キャメルケースに変換する。
     */
    public static function toCamel($str, $lcfirst = false): string
    {
        $str = ucwords($str, '_');
        if ($lcfirst) {
            return lcfirst(str_replace('_', '', $str));
        } else {
            return str_replace('_', '', $str);
        }
    }

    /**
     * スネークケースに変換
     */
    public static function toSnake($str): string
    {
        return ltrim(strtolower(preg_replace('/[A-Z]/', '_\0', $str)), '_');
    }

    public static function startsWith($haystack, $needle): bool
    {
        return $needle === "" || strpos($haystack, $needle) === 0;
    }


    public static function endsWith($haystack, $needle): bool
    {
        return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
    }

    /**
     * 末尾に指定の文字(無ければ)を追加する。
     */
    public static function suffix($str, $suffix)
    {
        if (mb_substr($str, - mb_strlen($suffix)) !== $suffix) {
            $str = "${str}${suffix}";
        }
        return $str;
    }
}