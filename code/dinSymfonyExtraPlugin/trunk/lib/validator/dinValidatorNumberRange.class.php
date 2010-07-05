<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Validator for number range
 * 
 * @package     dinSymfonyExtraPlugin.lib.validator
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       february 14, 2010
 * @version     SVN: $Id$
 */
class dinValidatorNumberRange extends sfValidatorBase
{

    /**
     * Configure validator
     * 
     * @param   array   Validator options [optional]
     * @param   array   Validator messages [optional]
     * @return  void
     * @author  relo_san
     * @since   february 14, 2010
     */
    protected function configure( $options = array(), $messages = array() )
    {

        $this->addMessage( 'invalid', 'The begin number must be lower the end number.' );

        $this->addRequiredOption( 'min' );
        $this->addRequiredOption( 'max' );
        $this->addOption( 'min_field', 'min' );
        $this->addOption( 'max_field', 'max' );

    } // dinValidatorNumberRange::configure()


    /**
     * Clean input value
     * 
     * @param   mixed   $value  Input value
     * @return  mixed   Cleaned value
     * @author  relo_san
     * @since   february 14, 2010
     */
    protected function doClean( $value )
    {

        $minField = $this->getOption( 'min_field' );
        $maxField = $this->getOption( 'max_field' );

        $value[$minField] = $this->getOption( 'min')->clean( isset( $value[$minField] ) ? $value[$minField] : null );
        $value[$maxField] = $this->getOption( 'max')->clean( isset( $value[$maxField] ) ? $value[$maxField] : null );

        if ( $value[$minField] && $value[$maxField] )
        {
            $v = new sfValidatorSchemaCompare(
                $minField, sfValidatorSchemaCompare::LESS_THAN_EQUAL, $maxField,
                array( 'throw_global_error' => true ), array( 'invalid' => $this->getMessage( 'invalid' ) )
            );
            $v->clean( $value );
        }

        return $value;

    } // dinValidatorNumberRange::doClean()

} // dinValidatorNumberRange

//EOF