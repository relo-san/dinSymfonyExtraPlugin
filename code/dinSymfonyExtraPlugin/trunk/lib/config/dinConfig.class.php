<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Class for managing configs for components, environments, applications, project
 * 
 * @package     dinSymfonyExtraPlugin.lib.config
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       february 2, 2010
 * @version     SVN: $Id$
 */
class dinConfig
{

    static public $lists = array();

    /**
     * Get active languages
     * 
     * @param   string  $application    Application name [optional]
     * @return  array   Active languages
     * @author  relo_san
     * @since   february 2, 2010
     */
    static public function getActiveLanguages( $application = null )
    {

        $method = sfConfig::get( 'sf_manage_languages', 'config' );
        if ( $method == 'config' )
        {
            return sfConfig::get( 'sf_active_languages', array( 'en' ) );
        }

        //TODO: add languages managing
        return array( 'en', 'ru' );

        return array( 'en' );

    } // dinConfig::getActiveLanguages()


    /**
     * Get default language
     * 
     * @param   string  $application    Application name [optional]
     * @return  string  Default language
     * @author  relo_san
     * @since   february 2, 2010
     */
    static public function getDefaultLanguage( $application = null )
    {

        //TODO: add default language managing
        return sfConfig::get( 'sf_default_culture' );

    } // dinConfig::getDefaultLanguage()


    /**
     * Get list
     * 
     * @param   string  $model      Model name
     * @param   string  $name       List name
     * @param   mixed   $key        Key name [optional]
     * @param   mixed   $default    Default value [optional]
     * @param   boolean $store      Storing loaded data
     * @return  array   List pairs (or one pair, if key provided)
     * @author  relo_san
     * @since   february 7, 2010
     */
    static public function getList( $model, $name, $key = null, $default = null, $store = true )
    {

        if ( !isset( self::$lists[$model][$name] ) )
        {
            self::$lists[$model][$name] = sfContext::getInstance()->get( 'cache_routing' )->getContent(
                'global_list', 'DinList', array( 'model' => $model, 'name' => $name )
            );
            //self::$lists[$model][$name] = Doctrine::getTable( 'DinList' )->getList(
            //    $model, $name, null, $default
            //);
        }

        if ( is_null( $key ) )
        {
            return self::$lists[$model][$name];
        }
        return isset( self::$lists[$model][$name][$key] ) ? self::$lists[$model][$name][$key] : $default;

    } // dinConfig::getList()


    /**
     * Get list for choices
     * 
     * @param   string  $model  Model name
     * @param   string  $name   List name
     * @param   string  $field  Field name [optional]
     * @return  array   List pairs
     * @author  relo_san
     * @since   february 8, 2010
     */
    static public function getChoices( $model, $name, $field = 'title' )
    {

        $list = self::getList( $model, $name );
        $choices = array();
        foreach ( $list as $key => $values )
        {
            $choices[$key] = $values[$field];
        }
        return $choices;

    } // dinConfig::getChoices()

} // dinConfig

//EOF