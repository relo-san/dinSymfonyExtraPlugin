<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Base i18n helper
 * 
 * @package     dinSymfonyExtraPlugin.lib.helper
 * @subpackage  DinI18nHelper
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       december 26, 2009
 * @version     SVN: $Id$
 */
class DinI18nHelper
{

    /**
     * Get translated string
     * 
     * @param   string  $source Source key (catalogue.key) or simply text
     * @param   array   $params Replacements in text [optional]
     * @return  string  Translated string
     * @author  relo_san
     * @since   december 26, 2009
     */
    static public function __( $source, $params = array() )
    {

        if ( sfConfig::get( 'sf_i18n' ) )
        {
            $parsed = explode( '.', $source, 2 );
            if ( !isset( $parsed[1] ) )
            {
                array_unshift( $parsed, 'messages' );
            }
            return sfContext::getInstance()->getI18N()->__( $parsed[1], $params, $parsed[0] );
        }

        foreach ( (array) $params as $key => $value )
        {
            if ( is_object( $value ) && method_exists( $value, '__toString' ) )
            {
                $params[$key] = $value->__toString();
            }
        }
        return strtr( $source, $params );

    } // DinI18nHelper::__()


    /**
     * Get case for numbers
     * 
     * @param   integer $number     Integer number
     * @param   string  $culture    Culture identifier [optional]
     * @return  string  Case code for key
     * @author  relo_san
     * @since   december 26, 2009
     */
    static public function ncase( $number, $culture = null )
    {

        if ( is_null( $culture ) )
        {
            $culture = sfContext::getInstance()->getUser()->getCulture();
        }
        $number = intval( $number );

        if ( $number == 0 )
        {
            return 0;
        }

        switch ( $culture )
        {
            case 'ru':
            case 'uk':
                $s1 = substr( (string) $number, -1, 1 );
                $s2 = false;
                if ( strlen( $number ) > 1 )
                {
                    $s2 = substr( (string) $number, -2, 1 );
                }
                if ( $s1 == 1 && $s2 != 1 )
                {
                    return 1;
                }
                if ( $s1 > 1 && $s1 < 5 && $s2 != 1 )
                {
                    return 2;
                }
                return 3;
                break;
            default:
                if ( $number == 1 )
                {
                    return 1;
                }
                return 2;
                break;
        }

    } // DinI18nHelper::ncase()

} // DinI18nHelper

//EOF