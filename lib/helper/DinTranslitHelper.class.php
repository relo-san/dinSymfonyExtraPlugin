<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Base translit helper
 * 
 * @package     dinSymfonyExtraPlugin.lib.helper
 * @subpackage  DinTranslitHelper
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       december 10, 2009
 * @version     SVN: $Id$
 */
class DinTranslitHelper
{

    static $base = array(
        'source' => array(
            'ЬЕ', 'ЪЕ', 'АЕ', 'ОЕ', 'УЕ', 'ЕЕ', 'ИЕ', 'ЮЕ', 'ЬЁ', 'ЪЁ', 'АЁ', 'ОЁ', 'УЁ', 'ЕЁ',
            'ИЁ', 'ЮЁ', 'Е', 'Ё', 'ЫЙ', 'ЬЯ', 'Є', 'Ѓ', 'Ї', 'I',
            'А', 'Б', 'В', 'Г', 'Д', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р',
            'С', 'Т', 'У', 'Ф', 'Ы', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ь', 'Э', 'Ю', 'Я',
            'ье', 'ъе', 'ае', 'ое', 'уе', 'ее', 'ие', 'юе', 'ьё', 'ъё', 'аё', 'оё', 'уё', 'её',
            'иё', 'юё', 'е', 'ё', 'ый', 'ья', 'є', 'ѓ', 'ї', 'і',
            'а', 'б', 'в', 'г', 'д', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р',
            'с', 'т', 'у', 'ф', 'ы', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ь', 'э', 'ю', 'я',
            '  ', ' ', '\'', '’', '<', '>', '&', ' ', '`', '"', '.', '«', '»'
        ),
        'replacement' => array(
            'YE', 'YE', 'YE', 'YE', 'YE', 'YE', 'YE', 'YE', 'YE', 'YE', 'YE', 'YE', 'YE', 'YE',
            'YE', 'YE', 'E', 'E', 'IY', 'IA', 'YE', 'G`', 'YI', 'I',
            'A', 'B', 'V', 'G', 'D', 'ZH', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R',
            'S', 'T', 'U', 'F', 'Y', 'X', 'CZ', 'CH', 'SH', 'SHH', '', '`', 'E', 'YU', 'YA',
            'ye', 'ye', 'ye', 'ye', 'ye', 'ye', 'ye', 'ye', 'ye', 'ye', 'ye', 'ye', 'ye', 'ye',
            'ye', 'ye', 'e', 'e', 'iy', 'ia', 'ye', 'g`', 'yi', 'i',
            'a', 'b', 'v', 'g', 'd', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r',
            's', 't', 'u', 'f', 'y', 'x', 'cz', 'ch', 'sh', 'shh', '', '`', 'e', 'yu', 'ya',
            '-', '-', '', '', '', '', '', '', '', '', '', '', ''
        )
    );


    /**
     * Convert string
     * 
     * @param   string  $string Source string
     * @param   integer $length Result max length [optional]
     * @return  string  Converted string
     * @author  relo_san
     * @since   december 10, 2009
     */
    static public function convert( $string, $length = 0 )
    {

        $string = str_replace( self::$base['source'], self::$base['replacement'], $string );
        return $length == 0 ? $string : substr( $string, 0, $length );

    } // DinTranslitHelper::convert()


    /**
     * Revert string
     * 
     * @return  Reverted string
     * @author  relo_san
     * @since   january 4, 2010
     */
    static public function revert( $string )
    {

        //TODO: add correct revert functionality
        return str_replace( self::$base['replacement'], self::$base['source'], $string );

    } // DinTranslitHelper::revert()

} // DinTranslitHelper

//EOF