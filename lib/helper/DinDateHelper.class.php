<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Base date helper
 * 
 * @package     dinSymfonyExtraPlugin
 * @subpackage  lib.helper
 * @author      Nicolay N. Zyk <relo.san@gmail.com>
 */
class DinDateHelper
{

    const PRECISION_RELATIVE_DEF = 1;
    const PRECISION_RELATIVE_DOUBLE = 2;
    const PRECISION_STATIC_SECONDS = 3;
    const PRECISION_STATIC_MINUTES = 4;
    const PRECISION_STATIC_HOURS = 5;
    const PRECISION_STATIC_DAYS = 6;

    static protected
        $dateFormats = array();


    /**
     * Format date
     * 
     * @param   integer $date       Unix timestamp
     * @param   string  $format     Format template [optional]
     * @param   string  $culture    Culture identifier [optional]
     * @param   string  $charset    Output charset [optional]
     * @return  string  Formatted string
     */
    static public function formatDate( $date, $format = 'd', $culture = null, $charset = null )
    {

        if ( null === $date )
        {
            return;
        }

        if ( !$culture )
        {
            $culture = sfContext::getInstance()->getUser()->getCulture();
        }

        if ( !$charset )
        {
            $charset = sfConfig::get( 'sf_charset' );
        }

        if ( !isset( self::$dateFormats[$culture] ) )
        {
            self::$dateFormats[$culture] = new sfDateFormat( $culture );
        }

        return self::$dateFormats[$culture]->format( $date, $format, null, $charset );

    } // DinDateHelper::formatDate()


    /**
     * Get time ago as string
     * 
     * @param   integer $fromTime   Start unix timestamp
     * @param   integer $precision  Precision modifier [optional]
     * @return  string  Formatted string
     * @author  relo_san
     * @since   december 25, 2009
     */
    static public function getTimeAgoAsString( $fromTime, $precision = self::PRECISION_RELATIVE_DEF )
    {

        return self::getDistanceOfTimeAsString( $fromTime, time(), $precision );

    } // DinDateHelper::getTimeAgoAsString()


    /**
     * Get distance of time as string
     * 
     * @param   integer $fromTime   Start unix timestamp
     * @param   integer $toTime     End unix timestamp
     * @param   integer $precision  Precision modifier [optional]
     * @return  string  Formatted string
     */
    static public function getDistanceOfTimeAsString( $fromTime, $toTime, $precision = self::PRECISION_RELATIVE_DEF )
    {

        $distInMin = floor( abs( $toTime - $fromTime ) / 60 );
        $distInSec = floor( abs( $toTime - $fromTime ) );

        //TODO: add precision version
        $number = 0;
        if ( $distInMin <= 1 )
        {
            if ( $precision == 1 )
            {
                $string = 'minDef';
                $number = $choice = $distInMin;
            }
            else
            {
                $string = $distInSec <= 40 ? 'secDef' : 'minDef';
                $choice = ( $distInSec <= 40 || $distInSec == 60 ) ? 1 : 0;
                $number = ( $distInSec == 60 ) ? 1 : ceil( $distInSec / 5 ) * 5;
            }
        }
        else if ( $distInMin <= 44 )
        {
            $string = 'minDef';
            $number = $distInMin;
        }
        else if ( $distInMin <= 1439 )
        {
            $string = 'hourDef';
            $number = ( $distInMin <= 89 ) ? 1 : round( $distInMin / 60 );
        }
        else if ( $distInMin <= 43199 )
        {
            $string = 'dayDef';
            $number = ( $distInMin <= 2879 ) ? 1 : round( $distInMin / 1440 );
        }
        else if ( $distInMin <= 525959 )
        {
            $string = 'monDef';
            $number = ( $distInMin <= 86399 ) ? 1 : round( $distInMin / 43200 );
        }
        else
        {
            $string = 'yearDef';
            $number = ( $distInMin <= 1051919 ) ? 0 : round( $distInMin / 525960 );
        }

        if ( sfConfig::get( 'sf_i18n' ) )
        {
            return I18n::__c(
                'datediff.' . $string, isset( $choice ) ? $choice : $number,
                array( '%1%' => $number ), !isset( $choice )
            );
        }
        //TODO: add no i18n way
        return strtr( $string, $parameters );

    } // DinDateHelper::getDistanceOfTimeAsString()

} // DinDateHelper

//EOF