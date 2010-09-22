<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Widget for number range
 * 
 * @package     dinSymfonyExtraPlugin
 * @subpackage  lib.widget
 * @author      Nicolay N. Zyk <relo.san@gmail.com>
 */
class dinWidgetFormNumberRange extends sfWidgetForm
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

        $this->addOption( 'with_empty', true );
        $this->addOption( 'empty_label', 'labels.isEmpty' );
        $this->addRequiredOption( 'min' );
        $this->addRequiredOption( 'max' );

        $this->addOption( 'template', '<span>%min_label%</span> %min% <span>%max_label%</span> %max%' );
        $this->addOption( 'filter_template', '%number_range% <div class="sf-filter-empty">%empty_checkbox% %empty_label%</div>' );

    } // dinWidgetFormNumberRange::configure()


    /**
     * Render field
     * 
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
            '%number_range%'   => $this->renderRange( $name, $value, $attributes, $errors ),
            '%empty_checkbox%' => $this->getOption( 'with_empty' ) ? $this->renderTag( 'input', array( 'type' => 'checkbox', 'name' => $name . '[is_empty]', 'checked' => $values['is_empty'] ? 'checked' : '' ) ) : '',
            '%empty_label%'    => $this->getOption( 'with_empty' ) ? $this->renderContentTag( 'label', $this->translate( $this->getOption( 'empty_label' ) ), array( 'for' => $this->generateId( $name . '[is_empty]' ) ) ) : '',
        ) );

    } // dinWidgetFormNumberRange::render()


    /**
     * Render range block
     * 
     * @return  string  XHTML compliant
     */
    protected function renderRange( $name, $value = null, $attributes = array(), $errors = array() )
    {

        //TODO: add no i18n way
        $values = array_merge( array( 'min' => '', 'max' => '' ), is_array( $value ) ? $value : array() );
        $dict = $this->parent->getFormFormatter()->getTranslationCatalogue();

        return strtr( $this->getOption( 'template'), array(
            '%min_label%'   => I18n::__( $dict . '.labels.min' ),
            '%max_label%'   => I18n::__( $dict . '.labels.max' ),
            '%min%'         => $this->getOption( 'min' )->render( $name . '[min]', $value['min'], $attributes ),
            '%max%'         => $this->getOption( 'max' )->render( $name . '[max]', $value['max'], $attributes )
        ) );

    } // dinWidgetFormNumberRange::renderRange()

} // dinWidgetFormNumberRange

//EOF