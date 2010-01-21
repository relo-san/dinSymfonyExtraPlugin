<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Base asset helper
 * 
 * @package     dinSymfonyExtraPlugin.lib.helper
 * @subpackage  DinAssetHelper
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       january 19, 2010
 * @version     SVN: $Id$
 */
class DinAssetHelper
{

    const DISC_RSS = 'rss';
    const DISC_ATOM = 'atom';


    /**
     * Returns a <link> tag for discovery use to auto-detect a RSS or ATOM feed
     * 
     * @param   string  $type       Type of feed [optional]
     * @param   string  $uri        'module/action' or '@rule' of the action [optional]
     * @param   array   $options    Tag options [optional]
     * @return  string  Formatted xhtml tag
     * @author  relo_san
     * @since   january 19, 2010
     */
    static public function discovery( $type = self::DISC_RSS, $uri = '', $options = array() )
    {

        return DinTagHelper::tag( 'link', array(
            'rel' => isset( $options['rel'] ) ? $options['rel'] : 'alternate',
            'type' => isset( $options['type'] ) ? $options['type'] : 'application/' . $type . '+xml',
            'title' => isset( $options['title'] ) ? $options['title'] : ucfirst( $type ),
            'href' => Url::url( $url, true )
        ) );

    } // DinAssetHelper::discovery()


    /**
     * Adds a stylesheet to the response object
     * 
     * @param   string  $css        Asset name
     * @param   string  $position   loading position (first, last, '') [optional]
     * @param   array   $options    Tag options [optional]
     * @return  void
     * @author  relo_san
     * @since   january 19, 2010
     * @see     sfWebResponse::addStylesheet()
     */
    static public function useCss( $css, $position = '', $options = array() )
    {

        sfContext::getInstance()->getResponse()->addStylesheet( $css, $position, $options );

    } // DinAssetHelper::useCss()


    /**
     * Adds a dynamic stylesheet to the response object
     * 
     * @param   string  $css        Asset name
     * @param   string  $position   loading position (first, last, '') [optional]
     * @param   array   $options    Tag options [optional]
     * @return  void
     * @author  relo_san
     * @since   january 21, 2010
     */
    static public function useDynCss( $css, $position = '', $options = array() )
    {

        $options['raw_name'] = true;
        self::useCss( self::dp( $css, 'css' ), $position, $options );

    } // DinAssetHelper::useDynCss()


    /**
     * Adds a javascript to the response object
     * 
     * @param   string  $js         Asset name
     * @param   string  $position   loading position (first, last, '') [optional]
     * @param   array   $options    Tag options [optional]
     * @return  void
     * @author  relo_san
     * @since   january 19, 2010
     * @see     sfWebResponse::addJavascript()
     */
    static public function useJs( $js, $position = '', $options = array() )
    {

        sfContext::getInstance()->getResponse()->addJavascript( $js, $position, $options );

    } // DinAssetHelper::useJs()


    /**
     * Adds a dynamic javascript to the response object
     * 
     * @param   string  $js         Asset name
     * @param   string  $position   loading position (first, last, '') [optional]
     * @param   array   $options    Tag options [optional]
     * @return  void
     * @author  relo_san
     * @since   january 21, 2010
     * @see     sfWebResponse::addJavascript()
     */
    static public function useDynJs( $js, $position = '', $options = array() )
    {

        $options['raw_name'] = true;
        self::useJs( self::dp( $js, 'js' ), $position, $options );

    } // DinAssetHelper::useDynJs()


    /**
     * Prints a set of <meta> tags according to the response attributes
     * 
     * @return  void
     * @author  relo_san
     * @since   january 19, 2010
     * @see     sfWebResponse::addMeta()
     */
    static public function includeMetas()
    {

        foreach ( sfContext::getInstance()->getResponse()->getMetas() as $name => $content )
        {
            echo DinTagHelper::tag( 'meta', array( 'name' => $name, 'content' => $content ) ) . "\n";
        }

    } // DinAssetHelper::includeMetas()


    /**
     * Prints a set of <meta http-equiv> tags according to the response attributes
     * 
     * @return  void
     * @author  relo_san
     * @since   january 20, 2010
     */
    static public function includeHttpMetas()
    {

        foreach ( sfContext::getInstance()->getResponse()->getHttpMetas() as $eq => $val )
        {
            echo DinTagHelper::tag( 'meta', array( 'http-equiv' => $eq, 'content' => $val ) ) . "\n";
        }

    } // DinAssetHelper::includeHttpMetas()


    /**
     * Prints title
     * 
     * @return  void
     * @author  relo_san
     * @since   january 20, 2010
     */
    static public function includeTitle()
    {

        echo '<title>' . sfContext::getInstance()->getResponse()->getTitle() . "</title>\n";

    } // DinAssetHelper::includeTitle()


    /**
     * Prints <script> tags for all javascripts configured in view.yml or added to the response object
     * 
     * @return  void
     * @author  relo_san
     * @since   january 20, 2010
     */
    static public function includeJs()
    {

        sfConfig::set( 'symfony.asset.javascripts_included', true );

        foreach ( sfContext::getInstance()->getResponse()->getJavascripts() as $file => $options )
        {
            echo self::jsTag( $file, $options ) . "\n";
        }

    } // DinAssetHelper::includeJs()


    /**
     * Prints <link> tags for all stylesheets configured in view.yml or added to the response object
     * 
     * @return  void
     * @author  relo_san
     * @since   january 21, 2010
     */
    static public function includeCss()
    {

        sfConfig::set( 'symfony.asset.stylesheets_included', true );

        foreach ( sfContext::getInstance()->getResponse()->getStylesheets() as $file => $options )
        {
            echo self::cssTag( $file, $options ) . "\n";
        }

    } // DinAssetHelper::includeCss()


    /**
     * Path for javascript file
     * 
     * @param   string  $source     Asset name
     * @param   boolean $absolute   Is absolute path [optional]
     * @return  string  Computed path
     * @author  relo_san
     * @since   january 20, 2010
     */
    static public function jsPath( $source, $absolute = false )
    {

        return self::cpp( $source, sfConfig::get( 'sf_web_js_dir_name', 'js' ), 'js', $absolute );

    } // DinAssetHelper::jsPath()


    /**
     * Path for stylesheet file
     * 
     * @param   string  $source     Asset name
     * @param   boolean $absolute   Is absolute path [optional]
     * @return  string  Computed path
     * @author  relo_san
     * @since   january 20, 2010
     */
    static public function cssPath( $source, $absolute = false )
    {

        return self::cpp( $source, sfConfig::get( 'sf_web_css_dir_name', 'css' ), 'css', $absolute );

    } // DinAssetHelper::cssPath()


    /**
     * Path for image file
     * 
     * @param   string  $source     Asset name
     * @param   boolean $absolute   Is absolute path [optional]
     * @return  string  Computed path
     * @author  relo_san
     * @since   january 21, 2010
     */
    static public function imgPath( $source, $absolute = false )
    {

        return self::cpp(
            $source, sfConfig::get( 'sf_web_images_dir_name', 'images' ), 'png', $absolute
        );

    } // DinAssetHelper::imgPath()


    /**
     * Tag for include javascript file
     * 
     * @param   string  $source     Asset name
     * @param   array   $options    Tag options
     * @return  Formatted xhtml tag
     * @author  relo_san
     * @since   january 20, 2010
     */
    static public function jsTag( $source, $options )
    {

        if ( !isset( $options['raw_name'] ) )
        {
            $source = self::jsPath( $source, isset( $options['absolute'] ) ? true : false );
        }

        $condition = isset( $options['condition'] ) ? $options['condition'] : false;
        unset( $options['raw_name'], $options['absolute'], $options['condition'] );

        $options = array_merge( array( 'type' => 'text/javascript', 'src' => $source ), $options );
        $tag = DinTagHelper::ctag( 'script', '', $options );
        return $condition ? DinTagHelper::cc( $condition, $tag ) : $tag;

    } // DinAssetHelper::jsTag()


    /**
     * Tag for include stylesheet file
     * 
     * @param   string  $source     Asset name
     * @param   array   $options    Tag options
     * @return  Formatted xhtml tag
     * @author  relo_san
     * @since   january 21, 2010
     */
    static public function cssTag( $source, $options )
    {

        if ( !isset( $options['raw_name'] ) )
        {
            $source = self::cssPath( $source, isset( $options['absolute'] ) ? true : false );
        }

        $condition = isset( $options['condition'] ) ? $options['condition'] : false;
        unset( $options['raw_name'], $options['absolute'], $options['condition'] );

        $options = array_merge( array(
            'type' => 'text/css', 'rel' => 'stylesheet', 'media' => 'screen', 'href' => $source
        ), $options );
        $tag = DinTagHelper::tag( 'link', $options );
        return $condition ? DinTagHelper::cc( $condition, $tag ) : $tag;

    } // DinAssetHelper::cssTag()


    /**
     * Tag for include image file
     * 
     * @param   string  $source     Asset name
     * @param   array   $options    Tag options [optional]
     * @return  Formatted xhtml tag
     * @author  relo_san
     * @since   january 21, 2010
     */
    static public function imgTag( $source, $options = array() )
    {

        if ( !$source )
        {
            return '';
        }

        $options = self::parseAttr( $options );
        $options['src'] = $source;
        if ( !isset( $options['raw_name'] ) )
        {
            $options['src'] = self::imgPath( $source, isset( $options['absolute'] ) ? true : false );
        }
        if ( isset( $options['alt_title'] ) )
        {
            $options['alt'] = isset( $options['alt'] ) ? $options['alt'] : $options['alt_title'];
            $options['title'] = isset( $options['title'] ) ? $options['title'] : $options['alt_title'];
        }
        if ( isset( $options['size'] ) )
        {
            list( $options['width'], $options['height'] ) = explode( 'x', $options['size'], 2 );
        }
        unset( $options['raw_name'], $options['absolute'], $options['alt_title'], $options['size'] );

        return DinTagHelper::tag( 'img', $options );

    } // DinAssetHelper::imgTag()


    /**
     * Compute public path
     * 
     * @param   string  $source     Source file
     * @param   string  $dir        Path to file
     * @param   string  $ext        File extension
     * @param   boolean $absolute   Is absolute path [optional]
     * @return  string  Computed path
     * @author  relo_san
     * @since   january 20, 2010
     */
    static private function cpp( $source, $dir, $ext, $absolute = false )
    {

        if ( strpos( $source, '://' ) )
        {
            return $source;
        }

        $request = sfContext::getInstance()->getRequest();
        $relRoot = $request->getRelativeUrlRoot();

        if ( 0 !== strpos( $source, '/' ) )
        {
            $source = $relRoot . '/' . $dir . '/' . $source;
        }

        if ( false !== $pos = strpos( $source, '?' ) )
        {
            $qString = substr( $source, $pos );
            $source = substr( $source, 0, $pos );
        }

        if ( false === strpos( basename( $source ), '.' ) )
        {
            $source .= '.' . $ext;
        }

        if ( $relRoot && 0 !== strpos( $source, $relRoot ) )
        {
            $source = $relRoot . $source;
        }

        if ( $absolute )
        {
            $source = 'http' . ( $request->isSecure() ? 's' : '' ) . '://' . $request->getHost()
                    . $source;
        }

        return $source . ( isset( $qString ) ? $qString : '' );

    } // DinAssetHelper::cpp()


    /**
     * Dynamic path
     * 
     * @return  string  Computed url
     * @author  relo_san
     * @since   january 21, 2010
     */
    static private function dp( $uri, $format, $absolute = false )
    {

        return Url::url(
            $uri . ( is_bool( strpos( $uri, '?' ) ) ? '?' : '&' ) . 'sf_format=' . $format, $absolute
        );

    } // DinAssetHelper::dp()


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

    } // DinAssetHelper::parseAttr()

} // DinAssetHelper

//EOF