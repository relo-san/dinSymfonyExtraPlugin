<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Widget for date range with jQuery datepicker
 * 
 * @package     dinSymfonyExtraPlugin.lib.widget
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       09.02.2010
 * @version     SVN: $Id$
 */
class dinWidgetFormJqueryDateRange extends sfWidgetForm
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

        $this->addRequiredOption( 'from_date' );
        $this->addRequiredOption( 'to_date' );

        $this->addOption( 'template', '<span>%from%</span> %from_date% <span>%to%</span> %to_date%' );

    } // dinWidgetFormJqueryDateRange::configure()


    /**
     * Render field
     * 
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

        $values = array_merge( array( 'from' => '', 'to' => '', 'is_empty' => '' ), is_array( $value ) ? $value : array() );
        $dict = $this->parent->getFormFormatter()->getTranslationCatalogue();

        return strtr( $this->getOption( 'template'), array(
            '%from%'        => I18n::__( $dict . '.labels.fromDate' ),
            '%to%'          => I18n::__( $dict . '.labels.toDate' ),
            '%from_date%'   => $this->getOption( 'from_date' )->render( $name . '[from]', $value['from'], $attributes ),
            '%to_date%'     => $this->getOption( 'to_date' )->render( $name . '[to]', $value['to'], $attributes )
        ) );

    } // dinWidgetFormJqueryDateRange::render()

} // dinWidgetFormJqueryDateRange

//EOF