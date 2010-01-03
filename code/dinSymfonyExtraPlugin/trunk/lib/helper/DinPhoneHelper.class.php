<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Base phone helper
 * 
 * @package     dinSymfonyExtraPlugin.lib.helper
 * @subpackage  DinPhoneHelper
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       december 28, 2009
 * @version     SVN: $Id$
 */
class DinPhoneHelper
{

    const FORMAT_STATIC_M32322 = 1;
    const FORMAT_DYNAMIC_M = 10;


    /**
     * Format phone number
     * 
     * @return  string  Formatted phone number
     * @author  relo_san
     * @since   december 28, 2009
     */
    static public function format( $phone, $format = self::FORMAT_STATIC_M32322, $delimiter = ' ' )
    {

        if ( !$phone )
        {
            return false;
        }

        //TODO: add pre-format check
        $ms = false !== strpos( $phone, '+' );
        $phone = str_replace( '+', '', $phone );

        switch ( $format )
        {
            case self::FORMAT_STATIC_M32322:
                return '+' . substr( $phone, 0, 3 ) . $delimiter . substr( $phone, 3, 2 )
                    . $delimiter . substr( $phone, 5, 3 ) . $delimiter . substr( $phone, 8, 2 )
                    . $delimiter . substr( $phone, 10, 2 );

            default:
                return '+' . substr( $phone, 0, 3 ) . $delimiter . substr( $phone, 3, 2 )
                    . $delimiter . substr( $phone, 5, 3 ) . $delimiter . substr( $phone, 8, 2 )
                    . $delimiter . substr( $phone, 10, 2 );
        }

    } // DinPhoneHelper::format()

} // DinPhoneHelper

//EOF