<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * dinWidgetFormJqueryAutocompleter
 * 
 * @package     dinSymfonyExtraPlugin.lib.widget
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       february 8, 2010
 * @version     SVN: $Id$
 */
class dinWidgetFormJqueryAutocompleter extends sfWidgetFormInput
{

    /**
     * Configure widget
     * 
     * @param   array   $options    An array of options [optional]
     * @param   array   $attributes An array of default HTML attributes [optional]
     * @return  void
     * @author  relo_san
     * @since   february 8, 2010
     * @see     sfWidgetForm
     */
    protected function configure( $options = array(), $attributes = array() )
    {

        $this->addRequiredOption( 'url' );
        $this->addOption( 'value_callback' );
        $this->addOption( 'config', '{ }' );
        $this->addOption( 'choices' );
        parent::configure( $options, $attributes );

    } // dinWidgetFormJqueryAutocompleter::configure()


    /**
     * Get visible value
     * 
     * @param   mixed   $value  Source value
     * @return  string  Visible value
     * @author  relo_san
     * @since   march 7, 2010
     */
    protected function getVisibleValue( $value )
    {

        return $this->getOption( 'value_callback' )
            ? call_user_func( $this->getOption( 'value_callback' ), $value )
            : $value;

    } // dinWidgetFormJqueryAutocompleter::getVisibleValue()


    /**
     * Render field
     * 
     * @param   string  $name       Element name
     * @param   string  $value      Element value
     * @param   array   $attributes HTML attributes [optional]
     * @param   array   $errors     Errors for the field [optional]
     * @return  string  XHTML compliant tag
     * @author  relo_san
     * @since   february 8, 2010
     */
    public function render( $name, $value = null, $attributes = array(), $errors = array() )
    {

        $s[] = '<script type="text/javascript">';
        $s[] = 'jQuery(document).ready(function(){';
        $s[] = "jQuery('#" . $this->generateId( 'autocomplete_' . $name ) . "')";
        $s[] = ".autocomplete('" . $this->getOption( 'url' ) . "',jQuery.extend({},{";
        $s[] = "dataType:'json',scroll:true,resultsClass:'ui-autocomplete-menu ui-menu ui-widget ui-widget-content ui-corner-all',loadingClass:'acpl_loading',";
        $s[] = "inputClass:'acpl_input',parse:function(data){var parsed=[];for(key in data){";
        $s[] = "parsed[parsed.length]={data:[data[key].value,key],value:data[key].value,result:data[key].result};";
        $s[] = "}return parsed;}}, " . $this->getOption( 'config' ) . ")).result(function(event,data){";
        $s[] = "jQuery('#" . $this->generateId( $name ) . "').val(data[1]);});});";
        $s[] = '</script>';

        return $this->renderTag( 'input', array(
                'type' => 'hidden', 'name' => $name, 'value' => $value, 'class' => '' )
            ) . parent::render(
                'autocomplete_' . $name, $this->getVisibleValue( $value ), $attributes, $errors
            ) . implode( $s );

    } // dinWidgetFormJqueryAutocompleter::render()

} // dinWidgetFormJqueryAutocompleter

//EOF