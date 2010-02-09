<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Widget for TinyMCE editor
 * 
 * @package     dinSymfonyExtraPlugin.lib.widget
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       february 9, 2010
 * @version     SVN: $Id$
 */
class dinWidgetFormTextareaTinymce extends sfWidgetFormTextarea
{

    /**
     * Configure widget
     * 
     * @param   array   $options    An array of options [optional]
     * @param   array   $attributes An array of default HTML attributes [optional]
     * @return  void
     * @author  relo_san
     * @since   february 9, 2010
     * @see     sfWidgetForm
     */
    protected function configure( $options = array(), $attributes = array() )
    {

        $this->addOption( 'theme', 'advanced' );
        $this->addOption( 'width' );
        $this->addOption( 'height' );
        $this->addOption( 'config', '' );

    } // dinWidgetFormTextareaTinymce::configure()


    /**
     * Render field
     * @param   string  $name       Element name
     * @param   string  $value      Field value [optional]
     * @param   array   $attributes HTML attributes [optional]
     * @param   array   $errors     Field errors [optional]
     * @return  string  XHTML compliant tag
     * @author  relo_san
     * @since   february 9, 2010
     * @see     sfWidgetForm
     */
    public function render( $name, $value = null, $attributes = array(), $errors = array() )
    {

        $textarea = parent::render( $name, $value, $attributes, $errors );

        $js[] = '<script type="text/javascript">';
        $js[] = "tinyMCE.init({mode:'exact',elements:'" . $this->generateId( $name ) . "',";
        $js[] = "theme:'" . $this->getOption( 'theme' ) . "',";
        if ( $this->getOption( 'width' ) )
        {
            $js[] = "width:'" . $this->getOption( 'width' ) . "px',";
        }
        if ( $this->getOption( 'height' ) )
        {
            $js[] = "height:'" . $this->getOption( 'height' ) . "px',";
        }
        $js[] = "theme_advanced_toolbar_location:'top',theme_advanced_toolbar_align:'left',";
        $js[] = "theme_advanced_statusbar_location:'bottom',theme_advanced_resizing:true";
        if ( $this->getOption( 'config' ) )
        {
            $js[] = "," . $this->getOption( 'config' );
        }
        $js[] = "});</script>";

        return $textarea . implode( $js );

    } // dinWidgetFormTextareaTinymce::render()

} // dinWidgetFormTextareaTinymce

//EOF