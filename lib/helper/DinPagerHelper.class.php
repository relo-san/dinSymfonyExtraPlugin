<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Base pager helper
 * 
 * @package     dinSymfonyExtraPlugin
 * @subpackage  lib.helper
 * @author      Nicolay N. Zyk <relo.san@gmail.com>
 */
class DinPagerHelper
{

    /**
     * Preparing customized pagination template vars
     * 
     * @param   object  $pager  Instance of sfPropelPager|sfDoctrinePager
     * @param   string  $uri    Uri
     * @param   array   $params Custom params [optional]
     * @return  array   Pager template vars
     */
    static public function prepare( $pager, $uri, $params = array() )
    {

        $paging = array();
        if ( $pager->haveToPaginate() )
        {

            // set page to uri
            $uri .= ( mb_strpos( $uri, '?' ) ? '&' : '?' ) . 'page=';

            // set pagination range
            if ( isset( $params['range'] ) )
            {
                $range = intval( $params['range'] );
            }
            else
            {
                $range = intval( sfConfig::get( 'app_pager_range', 5 ) );
            }
            $current = $pager->getPage();
            $total = $pager->getLastPage();
            $minPage = ( $current <= $range || $range == 0 ) ? 1 : $current - $range;
            $maxPage = ( ( $current + $range ) < $total && $range != 0 ) ? $current + $range : $total;

            // generating links
            for ( $i = $minPage; $i <= $maxPage; $i++ )
            {
                $paging['links'][$i]['uri'] = ( $i == 1 && isset( $params['first_uri'] ) ) ? $params['first_uri'] : $uri . $i;
                $paging['links'][$i]['name'] = $i;
                $paging['links'][$i]['current'] = $i == $current ? true : false;
            }
            if ( !isset( $paging['links'][1] ) )
            {
                $paging['haveFirstPage'] = true;
                $paging['firstPageUri'] = isset( $params['first_uri'] ) ? $params['first_uri'] : $uri . '1';
                $paging['firstPageName'] = '1';
            }
            if ( !isset( $paging['links'][2] ) )
            {
                $paging['haveFirstInterval'] = true;
            }
            if ( !isset( $paging['links'][$total] ) )
            {
                $paging['haveLastPage'] = true;
                $paging['lastPageUri'] = $uri . $total;
                $paging['lastPageName'] = $total;
            }
            if ( !isset( $paging['links'][$total - 1] ) )
            {
                $paging['haveLastInterval'] = true;
            }
            if ( isset( $params['ajaxFunc'] ) )
            {
                if ( isset( $params['ajaxDest'] ) )
                {
                    $paging['ajaxDest'] = $params['ajaxDest'];
                }
                $paging['ajaxFunc'] = $params['ajaxFunc'];
                $paging['isAjax'] = true;
            }
            else
            {
                $paging['isAjax'] = false;
            }

            $paging['haveToPaginate'] = true;
        }

        return $paging;

    } // DinPagerHelper::prepare()

} // DinPagerHelper

//EOF