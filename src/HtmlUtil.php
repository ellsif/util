<?php

namespace ellsif\util;


class HtmlUtil
{
    public static function tag($tagName, $attributes, $text = null) :string
    {
        $tag = "<${tagName}";
        foreach($attributes as $name => $value) {
            $tag .= " ${name}=\"${value}\"";
        }
        if ($text !== null) {
            $tag .= '>' . $text . HtmlUtil::tagEnd($tagName);
        } else {
            $tag .= ' />';
        }
        return $tag;
    }

    public static function tagStart($tagName, $attributes) :string
    {
        $tag = "<${tagName}";
        foreach($attributes as $name => $value) {
            $tag .= " ${name}=\"${value}\"";
        }
        $tag .= '>';
        return $tag;
    }

    public static function tagEnd($tagName) :string
    {
        return "</${tagName}>";
    }

    public static function tagged($tagName, $attributes, $body) :string
    {
        $html = HtmlUtil::tagStart($tagName, $attributes);
        $html .= $body;
        $html .= HtmlUtil::tagEnd($tagName);
        return $html;
    }

}