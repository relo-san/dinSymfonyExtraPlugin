<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Class for formatting form validators messages
 * 
 * @package     dinSymfonyExtraPlugin
 * @subpackage  lib.form
 * @author      Nicolay N. Zyk <relo.san@gmail.com>
 */
class dinFormValidatorMessageFormatter
{

    /**
     * I18n catalogue name
     * @var     string
     */
    protected $catalogue = null;


    /**
     * I18n instance
     * @var     object
     */
    protected $i18n = null;


    /**
     * Message patterns
     * @var     array
     */
    protected $pattern = array(
        'baseString'    => 'formMessages.',
        'empty'         => 'required',
        'bad'           => 'invalid',
        'short'         => 'min_length',
        'long'          => 'max_length',
        'format'        => 'bad_format',
        'notUnique'     => 'invalid',
        '*'             => 'invalid'
    );


    /**
     * Constructor
     * 
     * @param   string  $catalogue  I18n catalogue name [optional]
     * @param   boolean $i18n       I18n replacing [optional]
     * @return  void
     */
    public function __construct( $catalogue = null, $i18n = false )
    {

        $this->catalogue = $catalogue;
        if ( !is_null( $catalogue ) && $i18n )
        {
            $this->i18n = sfContext::getInstance()->getI18N();
        }

    } // dinFormValidatorMessageFormatter::__construct()


    /**
     * Formatting array with messages
     * 
     * @param   array   $messageTypes   Types of errors messages
     * @param   string  $fieldname      Field name
     * @param   array   $args           Arguments for replacing [optional]
     * @return  array   Formatted messages
     */
    public function format( $messageTypes, $fieldname, $args = array() )
    {

        $array = array();
        foreach ( $messageTypes as $value )
        {

            $string = $this->pattern['baseString'] . $value . ucfirst( $fieldname );
            $key = isset( $this->pattern[$value] ) ? $this->pattern[$value] : $this->pattern['*'];
            if ( $this->i18n )
            {
                $array[$key] = $this->i18n->__( $string, $args, $this->catalogue );
            }
            else
            {
                $array[$key] = $this->catalogue ? $this->catalogue . '.' . $string : $string;
            }

        }

        return $array;

    } // dinFormValidatorMessageFormatter::format()

} // dinFormValidatorMessageFormatter

//EOF