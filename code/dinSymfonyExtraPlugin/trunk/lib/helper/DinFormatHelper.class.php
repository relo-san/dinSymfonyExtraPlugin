<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Base format helper
 * 
 * @package     dinSymfonyExtraPlugin.lib.helper
 * @subpackage  DinFormatHelper
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       january 13, 2010
 * @version     SVN: $Id$
 */
class DinFormatHelper
{

    const CUT_STRATEGY_MIN = 1;
    const CUT_STRATEGY_MAX = 2;

    const ESC_ENTITIES = 1;
    const ESC_SPECIALCHARS = 2;
    const ESC_JS = 3;
    const ESC_JS_NO_ENTITIES = 4;
    const ESC_RAW = 5;


    /**
     * Soft cutting for string
     * 
     * @param   string  $string     Source string
     * @param   integer $soft       Soft cutting position
     * @param   integer $hard       Hard cutting position
     * @param   integer $strategy   Cutting strategy [optional]
     * @param   boolean $strip      Strip tags in source [optional]
     * @param   string  $encoding   Source encoding [optional]
     * @return  string  Cutted string
     * @author  relo_san
     * @since   january 13, 2010
     */
    static public function softCut( $string, $soft, $hard, $strategy = self::CUT_STRATEGY_MIN, $strip = true, $encoding = 'utf-8' )
    {

        if ( $strip )
        {
            $string = strip_tags( $string );
        }
        if ( mb_strlen( $string ) <= $soft )
        {
            return $string;
        }
        $cut = mb_substr( $string, 0, $hard, $encoding );
        $pos = $strategy == self::CUT_STRATEGY_MIN ? mb_stripos( $cut, ' ', $soft, $encoding ) : mb_strripos( $cut, ' ', $soft, $encoding );
        if ( $pos === false )
        {
            $pos = $strategy == self::CUT_STRATEGY_MIN ? mb_stripos( $cut, '&nbsp;', $soft, $encoding ) : mb_strripos( $cut, '&nbsp;', $soft, $encoding );
        }
        if ( $pos === false )
        {
            $pos = $strategy == self::CUT_STRATEGY_MIN ? mb_stripos( $cut, ' ', $soft, $encoding ) : mb_strripos( $cut, ' ', $soft, $encoding );
        }
        if ( $pos === false )
        {
            return $cut;
        }
        return mb_substr( $string, 0, $pos, $encoding );

    } // DinFormatHelper::softCut()


    /**
     * Escape string
     * 
     * @param   string  $string     Source string
     * @param   integer $escaping   Escaping type [optional]
     * @return  string  Escaped string
     * @author  relo_san
     * @since   january 16, 2010
     */
    static public function escape( $string, $escaping = self::ESC_SPECIALCHARS )
    {

        switch ( $escaping )
        {
            case 1:
                return is_string( $string )
                    ? htmlentities( $string, ENT_QUOTES, sfConfig::get( 'sf_charset' ) ) : $string;
                break;
            case 2:
                return is_string( $string )
                    ? htmlspecialchars( $string, ENT_QUOTES, sfConfig::get( 'sf_charset' ) ) : $string;
                break;
            case 3:
                return str_replace( array( "\\", "\n", "\r", "\"", "'" ),
                     array( "\\\\", "\\n", "\\r", "\\\"", "\\'" ), esc_entities( $string ) );
                break;
            case 4:
                return str_replace( array( "\\", "\n", "\r", "\"", "'" ),
                     array( "\\\\", "\\n", "\\r", "\\\"", "\\'" ), $string );
            default:
                return $string;
        }

    } // DinFormatHelper::escape()

} // DinFormatHelper

//EOF