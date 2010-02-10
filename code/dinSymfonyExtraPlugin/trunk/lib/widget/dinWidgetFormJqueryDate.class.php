<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Widget for date with jQuery datepicker
 * 
 * @package     dinSymfonyExtraPlugin.lib.widget
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       february 9, 2010
 * @version     SVN: $Id$
 */
class dinWidgetFormJqueryDate extends sfWidgetForm
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

        $this->addOption( 'format', '%month%/%day%/%year%' );

    } // dinWidgetFormJqueryDate::configure()


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

        if ( !is_array( $value ) )
        {
            $value = (string) $value == (string) (integer) $value ? (integer) $value : strtotime( $value );
            if ( false === $value )
            {
                $value = '';
            }
            else
            {
                $value = array( 'year' => date( 'Y', $value ), 'month' => date( 'm', $value ), 'day' => date( 'd', $value ) );
            }
        }

        $dateValue = '';
        if ( is_array( $value ) )
        {
            $value = array_merge( array( 'year' => null, 'month' => null, 'day' => null ), $value );
            $dateValue = strtr( $this->getOption( 'format' ), array(
                '%month%' => $value['month'], '%year%' => $value['year'], '%day%' => $value['day']
            ) );
        }

        $widget = new sfWidgetFormInputText( array(), $attributes );
        return $widget->render( $name, $dateValue )
            . '<script type="text/javascript">$(function(){$("#'
            . $widget->generateId( $name, $dateValue ) . '").datepicker();});</script>';

    } // dinWidgetFormJqueryDate::render()

} // dinWidgetFormJqueryDate

//EOF