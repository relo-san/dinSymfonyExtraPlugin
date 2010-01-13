<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Base url helper
 * 
 * @package     dinSymfonyExtraPlugin.lib.helper
 * @subpackage  DinUrlHelper
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       december 26, 2009
 * @version     SVN: $Id$
 */
class DinUrlHelper
{

    /**
     * Returns a routed URL, based on the module/action passed as argument and the routing
     * configuration.
     * 
     * @param   string  $internalUri    'module/action' or '@rule' of the action
     * @param   bool    $isAbsolute     Return absolute path? [optional]
     * @return  string  Generated url
     * @author  relo_san
     * @since   december 26, 2009
     */
    static public function url( $internalUri, $isAbsolute = false )
    {

        return sfContext::getInstance()->getController()->genUrl( $internalUri, $isAbsolute );

    } // DinUrlHelper::url()


    /**
     * Returns a routed URL, based on route name
     * 
     * @param   string          $routeName  Route name
     * @param   array|object    $params     Related object or array with route params [optional]
     * @param   bool            $isAbsolute Return absolute path? [optional]
     * @return  string  Generated url
     * @author  relo_san
     * @since   december 26, 2009
     */
    static public function urlr( $routeName, $params = array(), $isAbsolute = false )
    {

        return sfContext::getInstance()->getController()->genUrl(
            array_merge(
                array( 'sf_route' => $routeName ),
                is_object( $params ) ? array( 'sf_subject' => $params ) : $params
            ),
            $isAbsolute
        );

    } // DinUrlHelper::urlr()

} // DinUrlHelper

//EOF