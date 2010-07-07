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
 * @package     dinSymfonyExtraPlugin
 * @subpackage  lib.widget
 * @author      Nicolay N. Zyk <relo.san@gmail.com>
 */
class dinWidgetFormTextareaTinymce extends sfWidgetFormTextarea
{

    /**
     * Configure widget
     * 
     * @param   array   $options    An array of options [optional]
     * @param   array   $attributes An array of default HTML attributes [optional]
     * @return  void
     */
    protected function configure( $options = array(), $attributes = array() )
    {

        $this->addOption( 'theme', 'advanced' );
        $this->addOption( 'width' );
        $this->addOption( 'height' );
        $this->addOption( 'config', '' );
        $this->addOption( 'activate', true );

    } // dinWidgetFormTextareaTinymce::configure()


    /**
     * Render field
     * @param   string  $name       Element name
     * @param   string  $value      Field value [optional]
     * @param   array   $attributes HTML attributes [optional]
     * @param   array   $errors     Field errors [optional]
     * @return  string  XHTML compliant tag
     */
    public function render( $name, $value = null, $attributes = array(), $errors = array() )
    {

        $textarea = parent::render( $name, $value, $attributes, $errors );
        $genId = $this->generateId( $name );

        $init[] = "tinyMCE.init({mode:'none',elements:'" . $genId . "',";
        $init[] = "theme:'" . $this->getOption( 'theme' ) . "',";
        $init[] = $this->getOption( 'width' ) ? "width:'" . $this->getOption( 'width' ) . "px'," : '';
        $init[] = $this->getOption( 'height' ) ? "height:'" . $this->getOption( 'height' ) . "px'," : '';
        $init[] = "theme_advanced_toolbar_location:'top',theme_advanced_toolbar_align:'left',";
        $init[] = "theme_advanced_statusbar_location:'bottom',theme_advanced_resizing:true";
        $init[] = $this->getOption( 'config' ) ? "," . $this->getOption( 'config' ) : '';
        $init[] = "});";

        $fix[] = "if(tinyMCE.get('" . $genId . "'))tinyMCE.remove(tinyMCE.get('" . $genId . "'));";
        $fix[] = "setTimeout('tinyMCE.execCommand(\'mceAddControl\',false,\'" . $genId . "\');', 100);";

        // remove this shit
        if ( !$this->getOption( 'activate' ) )
        {
            $ext[] = '<a id="tmcAct' . $genId . '" class="tinymce_activation" ';
            $ext[] = 'href="javascript:void(0)" onclick="tmcActivation' . $genId . '()" title="';
            $ext[] = I18n::__( 'admin.labels.tmcActivation' ) . '"></a>';
        }

        $ext[] = '<script type="text/javascript">';

        if ( !$this->getOption( 'activate' ) )
        {
            $ext[] = 'if(typeof jQuery!=\'undefined\'){';
            $ext[] = '$(\'#tmcAct' . $genId . "').button({icons:{primary:'ui-icon-contact'},text:false});";
            $ext[] = "function tmcActivation" . $genId . '(){$(\'#tmcAct' . $genId . "').hide();";
            $ext[] = implode( $init ) . implode( $fix ) . '}}';
        }
        else
        {
            $ext[] = implode( $init ) . implode( $fix );
        }

        $ext[] = "</script>";

        return $textarea . implode( $ext );

    } // dinWidgetFormTextareaTinymce::render()

} // dinWidgetFormTextareaTinymce

//EOF