<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Base i18n helper
 * 
 * @package     dinSymfonyExtraPlugin
 * @subpackage  lib.helper
 * @author      Nicolay N. Zyk <relo.san@gmail.com>
 */
class DinI18nHelper
{

    /**
     * Get translated string
     * 
     * @param   string  $source Source key (catalogue.key) or simply text
     * @param   array   $params Replacements in text [optional]
     * @return  string  Translated string
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
     * Get choice of translated string
     * 
     * @param   string  $source     Source key (catalogue.key) or simply text
     * @param   integer $number     Number of choice
     * @param   array   $params     Replacements in text [optional]
     * @param   boolean $useCase    Using of external number case [optional]
     * @return  string  Translated string
     */
    static public function __c( $source, $number, $params = array(), $useCase = true )
    {

        $trans = self::__( $source, $params );
        $choice = new sfChoiceFormat();
        $retval = $choice->format( $trans, $useCase ? self::ncase( $number ) : $number );
        return $retval === false ? $trans : $retval;

    } // DinI18nHelper::__c()


    /**
     * Get case for numbers
     * 
     * @param   integer $number     Integer number
     * @param   string  $culture    Culture identifier [optional]
     * @return  string  Case code for key
     */
    static public function ncase( $number, $culture = null )
    {

        if ( is_null( $culture ) )
        {
            $culture = sfContext::getInstance()->getUser()->getCulture();
        }
        $n = intval( $number );

        if ( $n == 0 )
        {
            return 0;
        }

        switch ( $culture )
        {
            case 'ru':
            case 'uk':
                $n100 = $n % 100;
                if ( $n100 > 4 && $n100 < 21 )
                {
                    return 3;
                }
                $n10 = $n % 10;
                if ( $n10 == 1 )
                {
                    return 1;
                }
                if ( $n10 > 1 && $n10 < 5 )
                {
                    return 2;
                }
                return 3;
            default:
                if ( $number == 1 )
                {
                    return 1;
                }
                return 2;
        }

    } // DinI18nHelper::ncase()

} // DinI18nHelper

//EOF