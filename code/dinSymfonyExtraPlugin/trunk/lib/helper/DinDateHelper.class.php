<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Base date helper
 * 
 * @package     dinSymfonyExtraPlugin.lib.helper
 * @subpackage  DinDateHelper
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       december 25, 2009
 * @version     SVN: $Id$
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
     * @author  relo_san
     * @since   december 29, 2009
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
     * @author  relo_san
     * @since   december 25, 2009
     */
    static public function getDistanceOfTimeAsString( $fromTime, $toTime, $precision = self::PRECISION_RELATIVE_DEF )
    {

        $distInMin = floor( abs( $toTime - $fromTime ) / 60 );
        $distInSec = floor( abs( $toTime - $fromTime ) );

        //TODO: add precision version
        $string = '';
        $params = array();
        if ( $distInMin <= 1 )
        {
            if ( $precision == 1 )
            {
                $string = $distInMin == 0 ? 'less than a minute' : '1 minute';
            }
            else
            {
                if ( $distInSec <= 5 )
                {
                    $string = 'less than %seconds% seconds';
                    $params['%seconds%'] = 5;
                }
                else if ( $distInSec <= 10 )
                {
                    $string = 'less than %seconds% seconds';
                    $params['%seconds%'] = 10;
                }
                else if ( $distInSec <= 20 )
                {
                    $string = 'less than %seconds% seconds';
                    $params['%seconds%'] = 20;
                }
                else if ( $distInSec <= 40 )
                {
                    $string = 'half a minute';
                }
                else if ( $distInSec <= 59 )
                {
                    $string = 'less than a minute';
                }
                else
                {
                    $string = '1 minute';
                }
            }
        }
        else if ( $distInMin <= 44 )
        {
            $string = '%minutes% minutes';
            $params['%minutes%'] = $distInMin;
        }
        else if ( $distInMin <= 89 )
        {
            $string = 'about %hours% hour';
            $params['%hours%'] = 1;
        }
        else if ( $distInMin <= 1439 )
        {
            $string = 'about %hours% hours';
            $params['%hours%'] = round( $distInMin / 60 );
        }
        else if ( $distInMin <= 2879 )
        {
            $string = '%days% day';
            $params['%days%'] = 1;
        }
        else if ( $distInMin <= 43199 )
        {
            $string = '%days% days';
            $params['%days%'] = round( $distInMin / 1440 );
        }
        else if ( $distInMin <= 86399 )
        {
            $string = 'about %months% month';
            $params['%months%'] = 1;
        }
        else if ( $distInMin <= 525959 )
        {
            $string = '%months% months';
            $params['%months%'] = round( $distInMin / 43200 );
        }
        else if ( $distInMin <= 1051919 )
        {
            $string = 'about %years% year';
            $params['%years%'] = 1;
        }
        else
        {
            $string = 'over %years% years';
            $params['%years%'] = floor( $distInMin / 525960 );
        }

        if ( sfConfig::get( 'sf_i18n' ) )
        {
            return __( 'datediff.' . $string, $params );
        }
        return strtr( $string, $parameters );

    } // DinDateHelper::getDistanceOfTimeAsString()

} // DinDateHelper

//EOF