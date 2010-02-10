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


    /**
     * urlf
     * 
     * @return  
     * @author  relo_san
     * @since   january 31, 2010
     */
    static public function urlf( sfForm $form, $routePrefix )
    {

        $format = '%s/%s';
        if ( '@' == $routePrefix[0] )
        {
            $format = '%s_%s';
            $routePrefix = substr( $routePrefix, 1 );
        }

        $uri = sprintf( $format, $routePrefix, $form->getObject()->isNew() ? 'create' : 'update' );

        return self::urlr( $uri, $form->getObject() );

    } // DinUrlHelper::urlf()


    /**
     * Returns link for routed URL, based on the module/action
     * 
     * @param   string  $name           Link title
     * @param   string  $internalUri    'module/action' or '@rule' of the action
     * @param   array   $options        Additional HTML compliant <a> tag parameters [optional]
     * @return  Generated XHTML <a> tag
     * @author  relo_san
     * @since   january 30, 2010
     */
    static public function link( $name, $internalUri, $options = array() )
    {

        $options = self::parseAttr( $options );

        $options['href'] = Url::url(
            $internalUri,
            ( isset( $options['absolute'] ) || isset( $options['absolute_url'] ) ) ? true : false
        );
        $options['href'] .= isset( $options['query_string'] ) ? '?' . $options['query_string'] : '';
        $options['href'] .= isset( $options['anchor'] ) ? '#' . $options['anchor'] : '';

        $options = self::c2js( $options );


        if ( is_object( $name ) )
        {
            if ( method_exists( $name, '__toString' ) )
            {
                $name = $name->__toString();
            }
            else
            {
                throw new sfException( sprintf(
                    'Object of class "%s" cannot be converted to string (Please create a'
                    .   ' __toString() method).', get_class( $name )
                ) );
            }
        }

        if ( !strlen( $name ) )
        {
            $name = $options['href'];
        }

        unset(
            $options['absolute_url'], $options['absolute'], $options['query_string'],
            $options['anchor']
        );

        return Tag::ctag( 'a', $name, $options );

    } // DinUrlHelper::link()


    /**
     * Returns link for routed URL, based on route name
     * 
     * @param   string          $name       Link title
     * @param   string          $routeName  Route name
     * @param   array|object    $params     Related object or array with route params
     * @param   array           $options    Additional HTML compliant <a> tag parameters [optional]
     * @return  string          Generated XHTML <a> tag
     * @author  relo_san
     * @since   january 30, 2010
     */
    static public function linkr( $name, $routeName, $params, $options = array() )
    {

        $params = array_merge(
            array( 'sf_route' => $routeName ),
            is_object( $params ) ? array( 'sf_subject' => $params ) : $params
        );
        return self::link( $name, $params, $options );

    } // DinUrlHelper::linkr()


    /**
     * Convert options to javascript
     * 
     * @param   array   $options    Source options
     * @param   string  $url        Url [optional]
     * @return  array   Converted options
     * @author  relo_san
     * @since   january 30, 2010
     */
    static private function c2js( $options, $url = 'this.href' )
    {

        $confirm = isset( $options['confirm'])
            ? "confirm('" . Tag::escapeJs( $options['confirm'] ) . "')" : '';
        $onclick = isset( $options['onclick']) ? $options['onclick'] : '';

        if ( isset( $options['ajax'] ) )
        {
            $action = $options['ajax'] . "Ajax('"
                . ( isset( $options['dest'] ) ? $options['dest'] : 'actionPartial' )
                . "','" . $options['href'] . "'" . ( isset( $options['data'] ) ? ",'"
                . $options['data'] . "'" : '' ) . ");";
            if ( isset( $options['jconfirm'] ) )
            {
                $action = strtr( $options['jconfirm'], array( '%%action%%' => $action ) );
            }
        }
        else
        {
            $action = isset( $options['popup'] ) ? self::pjf( $options['popup'], $url ) : '';
            $action = $action ? $action : isset( $options['method'] ) ? $options['method'] :
                ( isset( $options['post'] ) && $options['post'] ? 'post' : false );
            $action = $action ? self::mjf( $action ) : '';
        }

        unset(
            $options['confirm'], $options['popup'], $options['method'], $options['post'],
            $options['ajax'], $options['dest'], $options['data'], $options['jconfirm']
        );

        if ( $confirm )
        {
            if ( $action )
            {
                $options['onclick'] = $onclick . 'if(' . $confirm . '){' . $action . '};return false;';
                return $options;
            }
            if ( $onclick )
            {
                $options['onclick'] = 'if(' . $confirm . '){return ' . $onclick . '}else return false;';
                return $options;
            }
            $options['onclick'] = 'return ' . $confirm . ';';
            return $options;
        }
        if ( $action )
        {
            $options['onclick'] = $onclick . $action . 'return false;';
        }

        return $options;

    } // DinUrlHelper::c2js()


    /**
     * Popup javascript function
     * 
     * @param   array   $popup  Popup attributes
     * @param   string  $url    Url [optional]
     * @return  string  Generated js code
     * @author  relo_san
     * @since   january 30, 2010
     */
    static private function pjf( $popup, $url = '' )
    {

        if ( !is_array( $popup ) )
        {
            return 'var w=window.open(' . $url . ');w.focus();';
        }

        if ( !isset( $popup[1] ) )
        {
            return "var w=window.open(" . $url . ",'" . $popup[0] . "');w.focus();";
        }
        return "var w=window.open(" . $url . ",'" . $popup[0] . "','" . $popup[1] . "');w.focus();";

    } // DinUrlHelper::pjf()


    /**
     * Method javascript function
     * 
     * @param   string  $method     Http method
     * @return  Generated js code
     * @author  relo_san
     * @since   january 30, 2010
     */
    static private function mjf( $method )
    {

        $function = "var f=document.createElement('form');f.style.display='none';"
            . "this.parentNode.appendChild(f);f.method='post';f.action=this.href;";

        if ( 'post' != strtolower( $method ) )
        {
            $function .= "var m=document.createElement('input');m.setAttribute('type','hidden'); ";
            $function .= sprintf(
                "m.setAttribute('name','sf_method');m.setAttribute('value','%s');f.appendChild(m);",
                strtolower( $method )
            );
        }

        // CSRF protection
        $form = new BaseForm();
        if ( $form->isCSRFProtected() )
        {
            $function .= "var m=document.createElement('input');m.setAttribute('type','hidden'); ";
            $function .= sprintf(
                "m.setAttribute('name','%s');m.setAttribute('value','%s');f.appendChild(m);",
                $form->getCSRFFieldName(), $form->getCSRFToken()
            );
        }

        $function .= "f.submit();";

        return $function;

    } // DinUrlHelper::mjf()


    /**
     * Parse attributes
     * 
     * @param   string  $string Attributes in string
     * @return  array   Attributes
     * @author  relo_san
     * @since   january 30, 2010
     */
    static private function parseAttr( $string )
    {

        return is_array( $string ) ? $string : sfToolkit::stringToArray( $string );

    } // DinUrlHelper::parseAttr()

} // DinUrlHelper

//EOF