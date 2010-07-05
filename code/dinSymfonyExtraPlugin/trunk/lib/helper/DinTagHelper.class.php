<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Base tag helper
 * 
 * @package     dinSymfonyExtraPlugin.lib.helper
 * @subpackage  DinTagHelper
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       january 21, 2010
 * @version     SVN: $Id$
 */
class DinTagHelper
{

    /**
     * Constructs an html tag
     * 
     * @param   string  $name       Tag name
     * @param   array   $options    Tag options [optional]
     * @param   boolean $open       True to leave tag open [optional]
     * @return  string  Formatted xhtml tag
     * @author  relo_san
     * @since   january 21, 2010
     */
    static public function tag( $name, $options = array(), $open = false )
    {

        if ( !$name )
        {
            return '';
        }

        return '<' . $name . self::to( $options ) . ( ( $open ) ? '>' : ' />' );

    } // DinTagHelper::tag()


    /**
     * Constructs an html content tag
     * 
     * @param   string  $name       Tag name
     * @param   string  $content    Tag content [optional]
     * @param   array   $options    Tag options [optional]
     * @return  string  Formatted xhtml tag
     * @author  relo_san
     * @since   january 21, 2010
     */
    static public function ctag( $name, $content = '', $options = array() )
    {

        if ( !$name )
        {
            return '';
        }

        return '<' . $name . self::to( $options ) . '>' . $content . '</' . $name . '>';

    } // DinTagHelper::ctag()


    /**
     * Wraps the content in CDATA
     * 
     * @param   string  $content    Internal content
     * @return  string  Formatted xhtml tag
     * @author  relo_san
     * @since   january 21, 2010
     */
    static public function cdata( $content )
    {

        return '<![CDATA[' . $content . ']]>';

    } // DinTagHelper::cdata()


    /**
     * Wraps the content in conditional comments
     * 
     * @param   string  $condition  Condition
     * @param   string  $content    Internal content
     * @return  string  Formatted xhtml tag
     * @author  relo_san
     * @since   january 21, 2010
     */
    static public function cc( $condition, $content )
    {

        return '<!--[if ' . $condition . ']>' . $content . '<![endif]-->';

    } // DinTagHelper::cc()


    /**
     * Escape carrier returns and single and double quotes for Javascript segments
     * 
     * @param   string  $js Javascript code [optional]
     * @return  string  Escaped code
     * @author  relo_san
     * @since   january 21, 2010
     */
    static public function escapeJs( $js = '' )
    {

        return preg_replace( '/(["\'])/', '\\\\\1', preg_replace( '/\r\n|\n|\r/', "\\n", $js ) );

    } // DinTagHelper::escapeJs()


    /**
     * Escape string (once)
     * 
     * @param   string  $html   String for escaping
     * @return  string  Escaped string
     * @author  relo_san
     * @since   january 21, 2010
     */
    static public function escape( $html )
    {

        return self::fixEscape(
            htmlspecialchars( $html, ENT_COMPAT, sfConfig::get( 'sf_charset' ) )
        );

    } // DinTagHelper::escape()


    /**
     * Fix double escape
     * 
     * @param   string  $escaped    Escaped string
     * @return  string  Fixed escaped string
     * @author  relo_san
     * @since   january 21, 2010
     */
    static public function fixEscape( $escaped )
    {

        return preg_replace( '/&amp;([a-z]+|(#\d+)|(#x[\da-f]+));/i', '&$1;', $escaped );

    } // DinTagHelper::fixEscape()


    /**
     * Prepare tag options
     * 
     * @param   array   $options    Tag options [optional]
     * @return  string  Options for tag
     * @author  relo_san
     * @since   january 21, 2010
     */
    static private function to( $options = array() )
    {

        $options = self::parseAttr( $options );
        $html = '';
        foreach ( $options as $key => $value )
        {
            $html .= ' ' . $key . '="' . self::escape( $value ) . '"';
        }
        return $html;

    } // DinTagHelper::to()


    /**
     * Get option value (with removing)
     * 
     * @param   array   $options    Tag options
     * @param   string  $name       Option name
     * @param   string  $default    Default value [optional]
     * @return  string  Option value
     * @author  relo_san
     * @since   january 21, 2010
     */
    static private function go( &$options, $name, $default = null )
    {

        if ( array_key_exists( $name, $options ) )
        {
            $value = $options[$name];
            unset($options[$name]);
        }
        else
        {
            $value = $default;
        }

        return $value;

    } // DinTagHelper::go()


    /**
     * Converts specific options to their correct HTML format
     * 
     * @param   array   $options    Tag options
     * @return  array   Formatted options
     * @author  relo_san
     * @since   january 21, 2010
     */
    static private function co( $options )
    {

        $options = self::parseAttr( $options );
        foreach ( array( 'disabled', 'readonly', 'multiple' ) as $attribute )
        {
            if ( array_key_exists( $attribute, $options ) )
            {
                if ( $options[$attribute] )
                {
                    $options[$attribute] = $attribute;
                }
                else
                {
                    unset( $options[$attribute] );
                }
            }
        }

        return $options;

    } // DinTagHelper::co()


    /**
     * Parse attributes
     * 
     * @param   string  $string Attributes in string
     * @return  array   Attributes
     * @author  relo_san
     * @since   january 20, 2010
     */
    static private function parseAttr( $string )
    {

        return is_array( $string ) ? $string : sfToolkit::stringToArray( $string );

    } // DinTagHelper::parseAttr()

} // DinTagHelper

//EOF