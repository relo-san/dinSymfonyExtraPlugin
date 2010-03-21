<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Class for managing cache routes
 * 
 * @package     dinSymfonyExtraPlugin.lib.cache
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       march 12, 2010
 * @version     SVN: $Id$
 */
class dinCacheRouting
{

    protected
        $routes = array(),
        $links = array(),
        $trans = array(),
        $defaults = array();


    /**
     * Constructor
     * 
     * @param   sfEventDispatcher   $dispatcher
     * @param   array   $options    Cache routing options [optional]
     * @return  void
     * @author  relo_san
     * @since   march 12, 2010
     */
    public function __construct( sfEventDispatcher $dispatcher, $options = array() )
    {

        $this->dispatcher = $dispatcher;
        $this->options = array_merge( array(
            'defaults'                      => array(),
            'load_configuration'            => false
        ), $options );


        $this->loadConfiguration();

    } // dinCacheRouting::__construct()


    /**
     * Load configuration
     * 
     * @return  void
     * @author  relo_san
     * @since   march 12, 2010
     */
    public function loadConfiguration()
    {

        if ( $this->options['load_configuration'] && $config = $this->getRoutesConfig() )
        {
            include( $config );
        }

        $this->dispatcher->notify( new sfEvent( $this, 'cache_routing.load_configuration' ) );

    } // dinCacheRouting::loadConfiguration()


    /**
     * Get routes config
     * 
     * @return  array   Loaded routes config
     * @author  relo_san
     * @since   march 12, 2010
     */
    protected function getRoutesConfig()
    {

        return sfContext::getInstance()->getConfigCache()
            ->checkConfig( 'config/cache_routing.yml', true );

    } // dinCacheRouting::getRoutesConfig()


    /**
     * Get content for route
     * 
     * @param   string  $route  Route name
     * @param   string  $model  Associated model name
     * @param   array   $params Query params [optional]
     * @return  mixed   Cached content
     * @author  relo_san
     * @since   march 12, 2010
     */
    public function getContent( $route, $model, $params = array() )
    {

        if ( !isset( $this->links[$model] ) || !in_array( $route, $this->links[$model] ) )
        {
            throw new sfConfigurationException( sprintf(
                'Model "%s" not linked to cache route "%s".', $model, $route
            ) );
        }

        $params['_model'] = $model;
        $params['_type'] = $this->routes[$route]['type'];
        $key = $this->getCacheKey( $route, 'get', $params );
        $driver = $this->getCacheDriver( $route, 'get', $params );
        $method = 'get' . ucfirst( $this->routes[$route]['type'] ) . 'Content';
        if ( $driver->has( $key ) )
        {
            $data = unserialize( $driver->get( $key, '' ) );
            if ( $method == 'getDataContent' )
            {
                return isset( $data[$params['id']] ) ? $data[$params['id']] : null;
            }
            return $data;
        }

        if ( $method == 'getPageContent' )
        {
            return array();
        }

        $data = $this->$method( $route, $model, $params );

        $driver->set( $key, serialize( $data ) );

        if ( $method == 'getDataContent' )
        {
            return isset( $data[$params['id']] ) ? $data[$params['id']] : null;
        }
        return $data;

    } // dinCacheRouting::getContent()


    /**
     * Get content for data type
     * 
     * @param   string  $route  Route name
     * @param   string  $model  Associated model name
     * @param   array   $params Query params
     * @return  mixed   Cached result
     * @author  relo_san
     * @since   march 12, 2010
     */
    protected function getDataContent( $route, $model, $params )
    {

        $method = $this->routes[$route]['get']['method'];
        $q = Doctrine::getTable( $model )->$method( $params );
        $ipf = $this->getRouteOption( $route, 'ipf', 1 );
        if ( $ipf > 1 )
        {
            $alias = $q->getRootAlias();
            $q->addWhere( $alias . '.id >= ?', ( floor( $params['id'] / $ipf ) * $ipf ) )
                ->andWhere( $alias . '.id < ?', ( ( floor( $params['id'] / $ipf ) + 1 ) * $ipf ) );
        }
        else
        {
            $q->addWhere( $q->getRootAlias() . '.id = ?', $params['id'] );
        }
        $items = $q->execute( array(), Doctrine::HYDRATE_ARRAY );
        $q->free();
        if ( !isset( $params['_no_prepare_translations'] ) )
        {
            $items = $this->prepareTranslations( $items );
        }

        $data = array();
        foreach ( $items as $item )
        {
            $data[$item['id']] = $item;
        }
        return $data;

    } // dinCacheRouting::getDataContent()


    /**
     * Get content for choices type
     * 
     * @param   string  $route  Route name
     * @param   string  $model  Associated model name
     * @param   array   $params Query params
     * @return  mixed   Cached result
     * @author  relo_san
     * @since   march 12, 2010
     */
    protected function getChoicesContent( $route, $model, $params )
    {

        $method = $this->routes[$route]['get']['method'];
        $q = Doctrine::getTable( $model )->$method( $params );
        $items = $q->execute( array(), Doctrine::HYDRATE_ARRAY );
        $q->free();
        if ( !isset( $params['_no_prepare_translations'] ) )
        {
            $items = $this->prepareTranslations( $items );
        }

        $data = array();
        foreach ( $items as $item )
        {
            $data[$item['id']] = $item['title'];
        }
        return $data;

    } // dinCacheRouting::getChoicesContent()


    /**
     * Get content for custom type
     * 
     * @param   string  $route  Route name
     * @param   string  $model  Associated model name
     * @param   array   $params Query params
     * @return  mixed   Cached result
     * @author  relo_san
     * @since   march 12, 2010
     */
    protected function getCustomContent( $route, $model, $params )
    {

        $method = $this->routes[$route]['get']['method'];
        $q = Doctrine::getTable( $model )->$method( $params );
        $data = $q->execute( array(), Doctrine::HYDRATE_ARRAY );
        $q->free();
        if ( !isset( $params['_no_prepare_translations'] ) )
        {
            $data = $this->prepareTranslations( $data );
        }
        return $data;

    } // dinCacheRouting::getCustomContent()


    /**
     * Set content cache
     * 
     * @param   string  $route  Route name
     * @param   mixed   $data   Data for store in cache
     * @param   string  $model  Associated model name
     * @param   array   $params Route params [optional]
     * @return  void
     * @author  relo_san
     * @since   march 12, 2010
     */
    public function setContent( $route, $data, $model, $params = array() )
    {

        $params['_model'] = $model;
        $params['_type'] = $this->routes[$route]['type'];
        if ( !isset( $params['_no_prepare_translations'] ) )
        {
            $data = $this->prepareTranslations( $data );
        }
        $key = $this->getCacheKey( $route, 'get', $params );
        $driver = $this->getCacheDriver( $route, 'get', $params );
        $driver->set( $key, serialize( $data ) );

    } // dinCacheRouting::setContent()


    /**
     * Get query method name for route
     * 
     * @param   string  $route  Route name
     * @return  string  Query method name
     * @author  relo_san
     * @since   march 12, 2010
     */
    public function getQueryMethod( $route )
    {

        return isset( $this->routes[$route]['get']['method'] )
            ? $this->routes[$route]['get']['method'] : null;

    } // dinCacheRouting::getQueryMethod()


    /**
     * Remove cache for model
     * 
     * @param   string  $model  Associated model name
     * @param   array   $params Route params [optional]
     * @return  void
     * @author  relo_san
     * @since   march 12, 2010
     */
    public function removeCacheForModel( $model, array $params = array() )
    {

        if ( !isset( $this->links[$model] ) )
        {
            return;
        }

        $params['_model'] = $model;
        foreach ( $this->links[$model] as $route )
        {
            if ( isset( $this->routes[$route] ) )
            {
                $this->removeCacheForRoute( $route, $params );
            }
        }

    } // dinCacheRouting::removeCacheForModel()


    /**
     * Remove cache for route
     * 
     * @param   string  $route  Route name
     * @param   array   $params Route params
     * @return  void
     * @author  relo_san
     * @since   march 12, 2010
     */
    protected function removeCacheForRoute( $route, $params )
    {

        if ( isset( $this->routes[$route]['remove'] ) )
        {

            $params['_type'] = $this->routes[$route]['type'];
            $this->getCacheDriver( $route, 'remove', $params )->removePattern(
                $this->getCacheKey( $route, 'remove', $params )
            );
        }

    } // dinCacheRouting::removeCacheForRoute()


    /**
     * Get cache driver
     * 
     * @param   string  $route  Route name
     * @param   string  $action Action name
     * @param   array   $params Route params
     * @return  object  Cache driver object (default sfFileCache)
     * @author  relo_san
     * @since   march 12, 2010
     */
    protected function getCacheDriver( $route, $action, $params )
    {

        $params['_root'] = sfConfig::get( 'sf_cache_dir' ) . '/data';
        $class = $this->getRouteOption( $route, 'driver', 'sfFileCache' );
        return new $class( array(
            'lifetime' => $this->getRouteOption( $route, 'ttl', 157680000 ),
            'cache_dir' => $this->getCachePath( $route, $action, $params )
        ) );

    } // dinCacheRouting::getCacheDriver()


    /**
     * Get cache key
     * 
     * @param   string  $route  Route name
     * @param   string  $action Action name
     * @param   array   $params Route params
     * @return  string  Cache key
     * @author  relo_san
     * @since   march 12, 2010
     */
    protected function getCacheKey( $route, $action, $params )
    {

        if ( $action == 'get' )
        {
            $key = $this->routes[$route][$action]['key'];
        }
        else if ( isset( $this->routes[$route][$action][$params['_model']]['key'] ) )
        {
            $key = $this->routes[$route][$action][$params['_model']]['key'];
        }
        else
        {
            $key = $this->routes[$route][$action]['default']['key'];
        }
        $parts = array();
        foreach ( explode( '.', $key ) as $part )
        {
            $parts[] = $this->processKeyPart( $route, $part, $params );
        }
        return implode( '.', $parts );

    } // dinCacheRouting::getCacheKey()


    /**
     * Get cache path
     * 
     * @param   string  $route  Route name
     * @param   string  $action Action name
     * @param   array   $params Route params
     * @return  string  Cache path
     * @author  relo_san
     * @since   march 12, 2010
     */
    public function getCachePath( $route, $action, $params )
    {

        if ( $action == 'get' )
        {
            $path = $this->routes[$route][$action]['path'];
        }
        else if ( isset( $this->routes[$route][$action][$params['_model']]['path'] ) )
        {
            $path = $this->routes[$route][$action][$params['_model']]['path'];
        }
        else
        {
            $path = $this->routes[$route][$action]['default']['path'];
        }
        $parts = array();
        foreach ( explode( '/', $path ) as $part )
        {
            $parts[] = $this->processPathPart( $route, $part, $params );
        }
        return implode( '/', $parts );

    } // dinCacheRouting::getCachePath()


    /**
     * Get route option
     * 
     * @param   string  $route      Route name
     * @param   string  $optionName Option name
     * @param   mixed   $default    Default value
     * @return  mixed   Option for route
     * @author  relo_san
     * @since   march 12, 2010
     */
    protected function getRouteOption( $route, $optionName, $default = null )
    {

        if ( isset( $this->routes[$route][$optionName] ) )
        {
            return $this->routes[$route][$optionName];
        }
        return isset( $this->defaults[$optionName] ) ? $this->defaults[$optionName] : $default;

    } // dinCacheRouting::getRouteOption()


    /**
     * Process path part
     * 
     * @param   string  $route  Route name
     * @param   string  $part   Mask part of path
     * @param   array   $params Route params
     * @return  string  Part of path
     * @author  relo_san
     * @since   march 12, 2010
     */
    protected function processPathPart( $route, $part, $params )
    {

        if ( substr( $part, 0, 1 ) != ':' )
        {
            return $part;
        }

        $key = substr( $part, 1 );

        if ( $key == '_i18n' )
        {
            return ( isset( $this->trans[$params['_model']] ) && $this->trans[$params['_model']] )
                ? sfContext::getInstance()->getUser()->getCulture() : 'def';
        }

        if ( !isset( $params[$key] ) )
        {
            throw new sfConfigurationException( sprintf(
                'Cache route "%s" requires param "%s".', $route, $part
            ) );
        }

        if ( substr( $part, 1, 1 ) == '_' )
        {
            return $params[$key];
        }

        if ( is_null( $params[$key] ) )
        {
            return 'null';
        }
        if ( !is_numeric( $params[$key] ) )
        {
            $key = md5( $params[$key] );
            return substr( $key, 0, 2 ) . '/' . substr( $key, 2, 2 ) . '/' . substr( $key, 4, 2 );
        }
        return floor( $params[$key] / 1000000000 ) . '/' . floor( $params[$key] / 1000000 )
            . '/' . floor( $params[$key] / 1000 );

    } // dinCacheRouting::processPathPart()


    /**
     * Process key part
     * 
     * @param   string  $route  Route name
     * @param   string  $part   Mask part of key
     * @param   array   $params Route params
     * @return  string  Part of key
     * @author  relo_san
     * @since   march 12, 2010
     */
    protected function processKeyPart( $route, $part, $params )
    {

        if ( substr( $part, 0, 1 ) != ':' )
        {
            return $part;
        }

        $key = substr( $part, 1 );

        if ( $key == '_i18n' )
        {
            return ( isset( $this->trans[$params['_model']] ) && $this->trans[$params['_model']] )
                ? sfContext::getInstance()->getUser()->getCulture() : 'def';
        }

        if ( !isset( $params[$key] ) )
        {
            throw new sfConfigurationException( sprintf(
                'Cache route "%s" requires param "%s".', $route, $part
            ) );
        }

        if ( $key == 'id' && $ipf = $this->getRouteOption( $route, 'ipf', 1 ) )
        {
            return floor( $params[$key] / $ipf );
        }

        return (string)$params[$key];

    } // dinCacheRouting::processKeyPart()


    /**
     * Prepare translations in result array
     * 
     * @param   array   Source array [optional]
     * @return  array   Result array with moved translations
     * @author  relo_san
     * @since   march 18, 2010
     */
    public function prepareTranslations( array $array = array() )
    {

        if ( isset( $array['Translation'] ) )
        {
            foreach ( $array['Translation'] as $translated )
            {
                unset( $translated['id'], $translated['lang'] );
                $array = array_merge( $array, $translated );
            }
            unset( $array['Translation'] );
        }
        foreach ( $array as $key => $value )
        {
            if ( is_array( $value ) )
            {
                $array[$key] = $this->prepareTranslations( $value );
            }
        }
        return $array;

    } // dinCacheRouting::prepareTranslations()

} // dinCacheRouting

//EOF