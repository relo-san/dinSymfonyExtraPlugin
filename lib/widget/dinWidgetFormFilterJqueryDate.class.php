<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Widget for filter date range with jQuery datepicker
 * 
 * @package     dinSymfonyExtraPlugin
 * @subpackage  lib.widget
 * @author      Nicolay N. Zyk <relo.san@gmail.com>
 */
class dinWidgetFormFilterJqueryDate extends dinWidgetFormJqueryDateRange
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

        parent::configure( $options, $attributes );

        $this->addOption( 'with_empty', true );
        $this->addOption( 'empty_label', 'labels.isEmpty' );
        $this->addOption( 'template', '<span>%from%</span> %from_date% <span>%to%</span> %to_date%' );
        $this->addOption( 'filter_template', '%date_range% <div class="sf-filter-empty">%empty_checkbox% %empty_label%</div>' );

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

        $values = array_merge( array( 'is_empty' => '' ), is_array( $value ) ? $value : array() );

        return strtr( $this->getOption( 'filter_template' ), array(
            '%date_range%'     => parent::render( $name, $value, $attributes, $errors ),
            '%empty_checkbox%' => $this->getOption( 'with_empty' ) ? $this->renderTag( 'input', array( 'type' => 'checkbox', 'name' => $name . '[is_empty]', 'checked' => $values['is_empty'] ? 'checked' : '' ) ) : '',
            '%empty_label%'    => $this->getOption( 'with_empty' ) ? $this->renderContentTag( 'label', $this->translate( $this->getOption( 'empty_label' ) ), array( 'for' => $this->generateId( $name . '[is_empty]' ) ) ) : '',
        ) );

    } // dinWidgetFormTextareaTinymce::render()

} // dinWidgetFormTextareaTinymce

//EOF