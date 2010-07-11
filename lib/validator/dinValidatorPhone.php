<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Validator for phone numbers
 * 
 * @package     dinSymfonyExtraPlugin
 * @subpackage  lib.validator
 * @author      Nicolay N. Zyk <relo.san@gmail.com>
 */
class dinValidatorPhone extends sfValidatorBase
{

    /**
     * Configure validator
     * 
     * @param   array   Validator options [optional]
     * @param   array   Validator messages [optional]
     * @return  void
     */
    protected function configure( $options = array(), $messages = array() )
    {

        $this->addMessage( 'invalid', 'Incorrect phone number ("%value%).' );
        $this->addOption( 'min_length' );
        $this->addOption( 'max_length' );
        $this->addOption( 'int_max_length' );
        $this->addOption( 'allow_internal' );
        $this->setOption( 'min_length', 10 );
        $this->setOption( 'max_length', 13 );
        $this->setOption( 'int_max_length', 3 );
        $this->setOption( 'allow_internal', false );

    } // dinValidatorPhone::configure()


    /**
     * Clean input value
     * 
     * @param   mixed   $value  Input value
     * @return  mixed   Cleaned value
     */
    protected function doClean( $value )
    {

        $clean = (string) $value;
        $int = '';

        if ( $this->getOption( 'allow_internal' ) )
        {
            $clean = preg_replace( '/[^\d*]+/u', '', $clean );
            $parts = explode( '*', $clean );
            if ( count( $parts ) > 2 )
            {
                throw new sfValidatorError( $this, 'invalid', array( 'value' => $value ) );
            }
            if ( isset( $parts[1] ) )
            {
                $int = $parts[1];
                $clean = $parts[0];
                if ( mb_strlen( $int, $this->getCharset() ) > $this->getOption( 'int_max_length' ) )
                {
                    throw new sfValidatorError( $this, 'invalid', array( 'value' => $value ) );
                }
            }
        }

        $clean = preg_replace( '/[^\d]+/u', '', $clean );
        $length = mb_strlen( $clean, $this->getCharset() );

        if ( $length > $this->getOption( 'max_length' ) )
        {
            throw new sfValidatorError( $this, 'invalid', array( 'value' => $value ) );
        }

        if ( $length < $this->getOption( 'min_length' ) )
        {
            throw new sfValidatorError( $this, 'invalid', array( 'value' => $value ) );
        }

        return $clean;

    } // dinValidatorPhone::doClean()

} // dinValidatorPhone

//EOF