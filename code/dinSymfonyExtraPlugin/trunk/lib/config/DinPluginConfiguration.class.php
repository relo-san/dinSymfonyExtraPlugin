<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * DinPluginConfiguration
 * 
 * @package     lib.config
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       december 25, 2009
 * @version     SVN: $Id$
 */
class DinPluginConfiguration extends sfPluginConfiguration
{

    /**
     * Plugin options.
     * @var array
     */
    protected
        $options = array(
            'activeLanguages'       => array( 'en' ),
            'defaultLanguage'       => 'en',
            'disabledModels'        => array(),
            'disabledColumns'       => array(),
            'disabledBehaviors'     => array(),
            'disabledRelations'     => array(),
            'i18nDisabledColumns'   => array()
        );

    /**
     * Initialize plugin configuration
     * 
     * @return  void
     * @author  relo_san
     * @since   december 25, 2009
     */
    public function initialize()
    {

        $this->options['defaultLanguage'] = sfConfig::get( 'sf_default_culture' );
        $this->options['activeLanguages'] = array( 'ru', 'en' );
        $this->options['cacheData'] = true;
        $this->options['cacheChoices'] = true;
        $this->options['cacheTTL'] = 157680000;
        $this->options['cachePath'] = sfConfig::get( 'sf_cache_dir' ) . '/data';
        $this->setTransColumn( 'uri', false );

    } // DinPluginConfiguration::initialize()


    /**
     * Get disabled columns
     * 
     * @param   string  $model  Model name [optional]
     * @return  array   Disabled columns
     * @author  relo_san
     * @since   january 12, 2010
     */
    public function getDisabledColumns( $model = null )
    {

        if ( !is_null( $model ) && isset( $this->options['models'][$model]['disabledColumns'] ) )
        {
            return array_merge(
                $this->options['disabledColumns'],
                $this->options['models'][$model]['disabledColumns']
            );
        }
        return $this->options['disabledColumns'];

    } // DinPluginConfiguration::getDisabledColumns()


    /**
     * Get disabled models
     * 
     * @return  array   Disabled models
     * @author  relo_san
     * @since   january 12, 2010
     */
    public function getDisabledModels()
    {

        return $this->options['disabledModels'];

    } // DinPluginConfiguration::getDisabledModels()


    /**
     * Get disabled behaviors
     * 
     * @param   string  $model  Model name [optional]
     * @return  array   Disabled behaviors
     * @author  relo_san
     * @since   january 12, 2010
     */
    public function getDisabledBehaviors( $model = null )
    {

        if ( !is_null( $model ) && isset( $this->options['models'][$model]['disabledBehaviors'] ) )
        {
            return array_merge(
                $this->options['disabledBehaviors'],
                $this->options['models'][$model]['disabledBehaviors']
            );
        }
        return $this->options['disabledBehaviors'];

    } // DinPluginConfiguration::getDisabledBehaviors()


    /**
     * Get disabled relations
     * 
     * @param   string  $model  Model name [optional]
     * @return  array   Disabled relations
     * @author  relo_san
     * @since   january 12, 2010
     */
    public function getDisabledRelations( $model = null )
    {

        if ( !is_null( $model ) && isset( $this->options['models'][$model]['disabledRelations'] ) )
        {
            return array_merge(
                $this->options['disabledRelations'],
                $this->options['models'][$model]['disabledRelations']
            );
        }
        return $this->options['disabledRelations'];

    } // DinPluginConfiguration::getDisabledRelations()


    /**
     * Get behavior options
     * 
     * @param   string  $model      Model name
     * @param   string  $behavior   Behavior name
     * @param   string  $options    Behavior options [optional]
     * @return  array   Behavior options
     * @author  relo_san
     * @since   january 12, 2010
     */
    public function getBehaviorOptions( $model, $behavior, $options = array() )
    {

        if ( in_array( $behavior, array( 'I18n', 'I18nMod' ) ) )
        {
            if ( isset( $this->options['models'][$model]['I18nColumns'] ) )
            {
                $options['fields'] = $this->options['models'][$model]['I18nColumns'];
                if ( isset( $options['unique'] ) )
                {
                    foreach ( $options['unique'] as $k => $column )
                    {
                        if ( !in_array( $column, $options['fields'] ) )
                        {
                            unset( $options['unique'][$k] );
                        }
                    }
                }
            }
            else
            {
                $disabled = $this->options['i18nDisabledColumns'];
                if ( isset( $this->options['models'][$model]['i18nDisabledColumns'] ) )
                {
                    $disabled = array_merge(
                        $disabled,
                        $this->options['models'][$model]['i18nDisabledColumns']
                    );
                }
                foreach ( $options['fields'] as $k => $column )
                {
                    if ( isset( $disabled[$column] ) )
                    {
                        unset( $options['fields'][$k] );
                    }
                    if ( isset( $options['unique'][$k] ) )
                    {
                        if ( isset( $disabled[$options['unique'][$k]] ) )
                        {
                            unset( $options['unique'][$k] );
                        }
                    }
                }
            }
        }

        return $options;

    } // DinPluginConfiguration::getBehaviorOptions()


    /**
     * Check if model enabled
     * 
     * @param   string  $model  Model name
     * @return  boolean Is model enabled
     * @author  relo_san
     * @since   january 12, 2010
     */
    public function isModel( $model )
    {

        return !isset( $this->options['disabledModels'][$model] );

    } // DinPluginConfiguration::isModel()


    /**
     * Switch model
     * 
     * @param   string  $model      Model name
     * @param   boolean $isEnabled  Is enable model
     * @return  object  Current plugin configuration object
     * @author  relo_san
     * @since   january 12, 2010
     */
    public function setModel( $model, $isEnable )
    {

        if ( $isEnable )
        {
            if ( isset( $this->options['disabledModels'][$model] ) )
            {
                unset( $this->options['disabledModels'][$model] );
            }
            if ( isset( $this->options['disabledRelations'][$model] ) )
            {
                unset( $this->options['disabledRelations'][$model] );
            }
            return $this;
        }
        $this->options['disabledModels'][$model] = true;
        $this->options['disabledRelations'][$model] = true;
        return $this;

    } // DinPluginConfiguration::setModel()


    /**
     * Check if I18n enabled for plugin or model
     * 
     * @param   string  $model  Model name [optional]
     * @return  boolean Is enable I18n
     * @author  relo_san
     * @since   december 25, 2009
     */
    public function isI18n( $model = null )
    {

        if ( !is_null( $model )
            && isset( $this->options['models'][$model]['disabledBehaviors']['I18nMod'] ) )
        {
            return false;
        }
        return !isset( $this->options['disabledBehaviors']['I18nMod'] );

    } // DinPluginConfiguration::isI18n()


    /**
     * Switch I18n extension for plugin or model
     * 
     * @param   boolean $isI18n Is enable I18n
     * @param   string  $model  Model name [optional]
     * @return  object  Current plugin configuration object
     * @author  relo_san
     * @since   december 25, 2009
     */
    public function setI18n( $isI18n, $model = null )
    {

        if ( $isI18n )
        {
            if ( !is_null( $model ) )
            {
                if ( isset( $this->options['models'][$model]['disabledBehaviors']['I18nMod'] ) )
                {
                    unset( $this->options['models'][$model]['disabledBehaviors']['I18nMod'] );
                }
                return $this;
            }
            if ( $this->options['disabledBehaviors']['I18nMod'] )
            {
                unset( $this->options['disabledBehaviors']['I18nMod'] );
            }
            return $this;
        }

        if ( !is_null( $model ) )
        {
            $this->options['models'][$model]['disabledBehaviors']['I18nMod'] = true;
            return $this;
        }
        $this->options['disabledBehaviors']['I18nMod'] = true;
        return $this;

    } // DinPluginConfiguration::setI18n()


    /**
     * Check if column enabled for plugin or model
     * 
     * @param   string  $column Column name
     * @param   string  $model  Model name [optional]
     * @return  boolean Is column enabled
     * @author  relo_san
     * @since   january 12, 2010
     */
    public function isColumn( $column, $model = null )
    {

        if ( !is_null( $model )
            && isset( $this->options['models'][$model]['disabledColumns'][$column] ) )
        {
            return false;
        }
        return !isset( $this->options['disabledColumns'][$column] );

    } // DinPluginConfiguration::isColumn()


    /**
     * Switch column for plugin or model
     * 
     * @param   string  $column     Column name
     * @param   boolean $isColumn   Is enable column
     * @param   string  $model      Model name [optional]
     * @return  object  Current plugin configuration object
     * @author  relo_san
     * @since   january 12, 2010
     */
    public function setColumn( $column, $isColumn, $model = null )
    {

        if ( $isColumn )
        {
            if ( !is_null( $model ) )
            {
                if ( isset( $this->options['models'][$model]['disabledColumns'][$column] ) )
                {
                    unset( $this->options['models'][$model]['disabledColumns'][$column] );
                }
                return $this;
            }
            if ( $this->options['disabledColumns'][$column] )
            {
                unset( $this->options['disabledColumns'][$column] );
            }
            return $this;
        }

        if ( !is_null( $model ) )
        {
            $this->options['models'][$model]['disabledColumns'][$column] = true;
            return $this;
        }
        $this->options['disabledColumns'][$column] = true;
        return $this;

    } // DinPluginConfiguration::setColumn()


    /**
     * Check if translations for column enabled for plugin or model
     * 
     * @param   string  $column     Column name
     * @param   string  $model  Model name [optional]
     * @return  boolean Is translation for column enabled
     * @author  relo_san
     * @since   january 12, 2010
     */
    public function isTransColumn( $column, $model = null )
    {

        if ( !is_null( $model )
            && isset( $this->options['models'][$model]['i18nDisabledColumns'][$column] ) )
        {
            return false;
        }
        return !isset( $this->options['i18nDisabledColumns'][$column] )
            && $this->isI18n( $model ) && $this->isColumn( $column, $model );

    } // DinPluginConfiguration::isTransColumn()


    /**
     * Switch translations for column for plugin or model
     * 
     * @param   string  $column         Column name
     * @param   boolean $isTransColumn  Is enable translations for column
     * @param   string  $model          Model name [optional]
     * @return  object  Current plugin configuration object
     * @author  relo_san
     * @since   january 12, 2010
     */
    public function setTransColumn( $column, $isTransColumn, $model = null )
    {

        if ( $isTransColumn )
        {
            if ( !is_null( $model ) )
            {
                if ( isset( $this->options['models'][$model]['i18nDisabledColumns'][$column] ) )
                {
                    unset( $this->options['models'][$model]['i18nDisabledColumns'][$column] );
                }
                return $this;
            }
            if ( $this->options['i18nDisabledColumns'][$column] )
            {
                unset( $this->options['i18nDisabledColumns'][$column] );
            }
            return $this;
        }

        if ( !is_null( $model ) )
        {
            $this->options['models'][$model]['i18nDisabledColumns'][$column] = true;
            return $this;
        }
        $this->options['i18nDisabledColumns'][$column] = true;
        return $this;

    } // DinPluginConfiguration::setTransColumn()


    /**
     * Get enabled languages for plugin
     * 
     * @return  array   Enabled languages
     * @author  relo_san
     * @since   december 25, 2009
     */
    public function getActiveLanguages()
    {

        return $this->options['activeLanguages'];

    } // DinPluginConfiguration::getActiveLanguages()


    /**
     * Set enabled languages for plugin
     * 
     * @param   array   $languages  Array of languages
     * @return  object  Current plugin configuration object
     * @author  relo_san
     * @since   december 25, 2009
     */
    public function setActiveLanguages( array $languages )
    {

        $this->options['activeLanguages'] = $languages;
        return $this;

    } // DinPluginConfiguration::setActiveLanguages


    /**
     * Check if language enabled for plugin
     * 
     * @param   string  $language   Language
     * @return  boolean Is language enabled
     * @author  relo_san
     * @since   december 25, 2009
     */
    public function hasActiveLanguage( $language )
    {

        return in_array( $language, $this->options['activeLanguages'] ) ? true : false;

    } // DinPluginConfiguration::hasActiveLanguage()


    /**
     * Enable language for plugin
     * 
     * @param   string  $language   Language
     * @return  object  Current plugin configuration object
     * @author  relo_san
     * @since   december 25, 2009
     */
    public function addActiveLanguage( $language )
    {

        if( !in_array( $language, $this->options['activeLanguages'] ) )
        {
            $this->options['activeLanguages'][] = $language;
        }
        return $this;

    } // DinPluginConfiguration::addActiveLanguage()


    /**
     * Disable language for plugin
     * 
     * @param   string  $language   Language
     * @return  object  Current plugin configuration object
     * @author  relo_san
     * @since   december 25, 2009
     */
    public function removeActiveLanguage( $language )
    {

        $key = array_search( $language, $this->options['activeLanguages'] );
        if ( $key !== false )
        {
            unset( $this->options['activeLanguages'][$key] );
        }
        return $this;

    } // DinPluginConfiguration::removeActiveLanguage()


    /**
     * Get default language for plugin
     * 
     * @return  string  Default language
     * @author  relo_san
     * @since   december 25, 2009
     */
    public function getDefaultLanguage()
    {

        return $this->options['defaultLanguage'];

    } // DinPluginConfiguration::getDefaultLanguage()


    /**
     * Check if data cache enabled for plugin or model
     * 
     * @param   string  $model  Model name [optional]
     * @return  boolean Is enable cache for data
     * @author  relo_san
     * @since   december 26, 2009
     */
    public function isCacheData( $model = null )
    {

        if ( !is_null( $model ) && isset( $this->options['models'][$model]['cacheData'] ) )
        {
            return $this->options['models'][$model]['cacheData'];
        }
        return $this->options['cacheData'];

    } // DinPluginConfiguration::isCacheData()


    /**
     * Switch data caching for plugin or model
     * 
     * @param   boolean $isCacheData    Is enable cache for data
     * @param   string  $model          Model name [optional]
     * @return  object  Current plugin configuration object
     * @author  relo_san
     * @since   december 26, 2009
     */
    public function setCacheData( $isCacheData, $model = null )
    {

        if ( !is_null( $model ) )
        {
            $this->options['models'][$model]['cacheData'] = (boolean) $isCacheData;
            return $this;
        }
        $this->options['cacheData'] = (boolean) $isCacheData;
        return $this;

    } // DinPluginConfiguration::setCacheData()


    /**
     * Check if choices cache enabled for plugin or model
     * 
     * @param   string  $model  Model name [optional]
     * @return  boolean Is enable cache for choices
     * @author  relo_san
     * @since   december 26, 2009
     */
    public function isCacheChoices( $model = null )
    {

        if ( !is_null( $model ) && isset( $this->options['models'][$model]['cacheChoices'] ) )
        {
            return $this->options['models'][$model]['cacheChoices'];
        }
        return $this->options['cacheChoices'];

    } // DinPluginConfiguration::isCacheChoices()


    /**
     * Switch choices caching for plugin or model
     * 
     * @param   boolean $isCacheChoices     Is enable cache for choices
     * @param   string  $model              Model name [optional]
     * @return  object  Current plugin configuration object
     * @author  relo_san
     * @since   december 26, 2009
     */
    public function setCacheChoices( $isCacheChoices, $model = null )
    {

        if ( !is_null( $model ) )
        {
            $this->options['models'][$model]['cacheChoices'] = (boolean) $isCacheChoices;
            return $this;
        }
        $this->options['cacheChoices'] = (boolean) $isCacheChoices;
        return $this;

    } // DinPluginConfiguration::setCacheChoices()


    /**
     * Get cache time-to-live for plugin or model
     * 
     * @param   string  $model  Model name [optional]
     * @return  integer Cache TTL in seconds
     * @author  relo_san
     * @since   december 26, 2009
     */
    public function getCacheTTL( $model = null )
    {

        if ( !is_null( $model ) && isset( $this->options['models'][$model]['cacheTTL'] ) )
        {
            return $this->options['models'][$model]['cacheTTL'];
        }
        return $this->options['cacheTTL'];

    } // DinPluginConfiguration::getCacheTTL()


    /**
     * Set cache time-to-live for plugin or model
     * 
     * @param   boolean $cacheTTL   Cache TTL
     * @param   string  $model      Model name [optional]
     * @return  object  Current plugin configuration object
     * @author  relo_san
     * @since   december 26, 2009
     */
    public function setCacheTTL( $cacheTTL, $model = null )
    {

        if ( !is_null( $model ) )
        {
            $this->options['models'][$model]['cacheTTL'] = (integer) $cacheTTL;
            return $this;
        }
        $this->options['cacheTTL'] = (integer) $cacheTTL;
        return $this;

    } // DinPluginConfiguration::setCacheTTL()


    /**
     * Get cache path for plugin or model
     * 
     * @param   string  $model      Model name [optional]
     * @param   string  $default    Default path part for model [optional]
     * @return  integer Cache path
     * @author  relo_san
     * @since   december 26, 2009
     */
    public function getCachePath( $model = null, $default = null )
    {

        if ( !is_null( $model ) )
        {
            if ( isset( $this->options['models'][$model]['cachePath'] ) )
            {
                return $this->options['models'][$model]['cachePath'];
            }
            return $this->options['cachePath'] . ( $default ? '/' . $default : '' );
        }
        return $this->options['cachePath'];

    } // DinPluginConfiguration::getCachePath()


    /**
     * Set cache path for plugin or model
     * 
     * @param   boolean $cachePath  Cache path
     * @param   string  $model      Model name [optional]
     * @return  object  Current plugin configuration object
     * @author  relo_san
     * @since   december 26, 2009
     */
    public function setCachePath( $cachePath, $model = null )
    {

        if ( !is_null( $model ) )
        {
            $this->options['models'][$model]['cachePath'] = (string) $cachePath;
            return $this;
        }
        $this->options['cachePath'] = (string) $cachePath;
        return $this;

    } // DinPluginConfiguration::setCachePath()


    /**
     * Get items per file cache
     * 
     * @param   string  $model      Model name [optional]
     * @param   string  $default    Default items per file cache [optional]
     * @return  integer Items per file
     * @author  relo_san
     * @since   december 27, 2009
     */
    public function getCacheIPF( $model = null, $default = 1 )
    {

        if ( !is_null( $model ) && isset( $this->options['models'][$model]['cacheIPF'] ) )
        {
            return $this->options['models'][$model]['cacheIPF'];
        }

        return isset( $this->options['cacheIPF'] ) ? $this->options['cacheIPF'] : $default;

    } // DinPluginConfiguration::getCacheIPF()

} // DinPluginConfiguration

//EOF