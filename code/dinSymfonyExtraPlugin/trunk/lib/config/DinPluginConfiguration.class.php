<?php

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
        $options = array();

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
        $this->options['enableI18n'] = true;
        $this->options['enableUri'] = true;
        $this->options['enableI18nUri'] = false;
        $this->options['cacheData'] = true;
        $this->options['cacheChoices'] = true;
        $this->options['cacheTTL'] = 157680000;
        $this->options['cachePath'] = sfConfig::get( 'sf_cache_dir' ) . '/data';

    } // DinPluginConfiguration::initialize()


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

        if ( !is_null( $model ) && isset( $this->options['models'][$model]['enableI18n'] ) )
        {
            return $this->options['models'][$model]['enableI18n'];
        }
        return $this->options['enableI18n'];

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

        if ( !is_null( $model ) )
        {
            $this->options['models'][$model]['enableI18n'] = (boolean) $isI18n;
            return $this;
        }
        $this->options['enableI18n'] = (boolean) $isI18n;
        return $this;

    } // DinPluginConfiguration::setI18n()


    /**
     * Check if uri field enabled for plugin or model
     * 
     * @param   string  $model  Model name [optional]
     * @return  boolean Is uri field enabled
     * @author  relo_san
     * @since   december 25, 2009
     */
    public function isUri( $model = null )
    {

        if ( !is_null( $model ) && isset( $this->options['models'][$model]['enableUri'] ) )
        {
            return $this->options['models'][$model]['enableUri'];
        }
        return $this->options['enableUri'];

    } // DinPluginConfiguration::isUri()


    /**
     * Switch uri field for plugin or model
     * 
     * @param   boolean $isUri  Is enable uri field
     * @param   string  $model  Model name [optional]
     * @return  object  Current plugin configuration object
     * @author  relo_san
     * @since   december 25, 2009
     */
    public function setUri( $isUri, $model = null )
    {

        if ( !is_null( $model ) )
        {
            $this->options['models'][$model]['enableUri'] = (boolean) $isUri;
            return $this;
        }
        $this->options['enableUri'] = (boolean) $isUri;
        return $this;

    } // DinPluginConfiguration::setUri()


    /**
     * Check if translations for uri field enabled for plugin or model
     * 
     * @param   string  $model  Model name [optional]
     * @return  boolean Is uri field enabled
     * @author  relo_san
     * @since   december 25, 2009
     */
    public function isTransUri( $model = null )
    {

        if ( !is_null( $model ) )
        {
            return $this->isI18n( $model )
                && $this->isUri( $model )
                && ( isset( $this->options['models'][$model]['enableI18nUri'] )
                    ? $this->options['models'][$model]['enableI18nUri']
                    : $this->options['enableI18nUri'] );
        }
        return $this->options['enableI18n']
            && $this->options['enableUri']
            && $this->options['enableI18nUri'];

    } // DinPluginConfiguration::isTransUri()


    /**
     * Switch translations for uri field for plugin or model
     * 
     * @param   boolean $isTransUri Is enable translations for uri field
     * @param   string  $model  Model name [optional]
     * @return  object  Current plugin configuration object
     * @author  relo_san
     * @since   december 25, 2009
     */
    public function setTransUri( $isTransUri, $model = null )
    {

        if ( !is_null( $model ) )
        {
            $this->options['models'][$model]['enableI18nUri'] = (boolean) $isTransUri;
            return $this;
        }
        $this->options['enableI18nUri'] = (boolean) $isTransUri;
        return $this;

    } // DinPluginConfiguration::setTransUri()


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