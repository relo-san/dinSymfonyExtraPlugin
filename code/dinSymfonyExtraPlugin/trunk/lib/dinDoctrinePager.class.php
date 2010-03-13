<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Pager for Doctrine
 * 
 * @package     dinSymfonyExtraPlugin.lib
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       december 20, 2009
 * @version     SVN: $Id$
 */
class dinDoctrinePager extends sfDoctrinePager
{


    protected
        $useCache = false,
        $cacheRoute = null,
        $cacheManager = null,
        $cache = null,
        $queryParams = array();

    /**
     * Initializing pager
     * 
     * @return  void
     * @author  relo_san
     * @since   december 20, 2009
     * @see     sfDoctrinePager
     */
    public function init()
    {

        if ( $this->useCache )
        {
            $this->cacheManager = sfContext::getInstance()->get( 'cache_routing' );
            $data = $this->cacheManager->getContent(
                $this->cacheRoute, $this->getClass(),
                array_merge( array( 'page' => $this->getPage() ), $this->queryParams )
            );
            if ( isset( $data['pager'] ) && isset( $data['data'] ) )
            {
                $this->cache = $data;
                return $this->unserialize( $this->cache['pager'] );
            }
        }
        parent::init();

        if ( $this->getPage() == $this->getLastPage() )
        {
            $this->getQuery()->offset( ( $this->getLastPage() - 1 ) * $this->getMaxPerPage() );
        }

        if ( $this->useCache )
        {
            $this->cache['pager'] = $this->serialize();
        }

    } // dinDoctrinePager::init()


    /**
     * Get results
     * 
     * @param   mixed   $hydrationMode  Doctrine hydration mode
     * @return  array|Doctrine_Collection   Results
     * @author  relo_san
     * @since   march 10, 2010
     */
    public function getResults( $hydrationMode = null )
    {

        if ( $this->useCache )
        {
            if ( isset( $this->cache['data'] ) )
            {
                return $this->cache['data'];
            }
            $this->setTableMethod( $this->cacheManager->getQueryMethod( $this->cacheRoute ) );
            $hydrationMode = Doctrine::HYDRATE_ARRAY;
        }

        $results = parent::getResults( $hydrationMode );
        if ( $this->useCache && isset( $this->cache['pager'] ) )
        {
            $this->cache['data'] = $results;
            $this->cacheManager->setContent(
                $this->cacheRoute, $this->cache, $this->getClass(),
                array_merge( array( 'page' => $this->getPage() ), $this->queryParams )
            );
        }
        return $results;

    } // dinDoctrinePager::getResults()


    /**
     * Serialize pager object
     * 
     * @return  string  Serialized vars
     * @author  relo_san
     * @since   march 10, 2010
     */
    public function serialize()
    {

        $vars = get_object_vars( $this );
        unset( $vars['query'], $vars['cache'], $vars['cacheManager'], $vars['cacheRoute'] );
        return serialize( $vars );

    } // dinDoctrinePager::serialize()


    /**
     * Use cache
     * 
     * @param   string|false    $route  Cache route name [if using cache]
     * @return  dinDoctrinePager
     * @author  relo_san
     * @since   march 10, 2010
     */
    public function useCache( $route = false )
    {

        $this->useCache = $route ? true : false;
        $this->cacheRoute = $route ? $route : null;
        return $this;

    } // dinDoctrinePager::useCache()


    /**
     * Set query
     * 
     * @param   Doctrine_Query object
     * @return  dinDoctrinePager object
     * @author  relo_san
     * @since   december 29, 2009
     * @see     sfDoctrinePager::setQuery()
     */
    public function setQuery( $query )
    {

        parent::setQuery( $query );
        return $this;

    } // dinDoctrinePager::setQuery()


    /**
     * setQueryParams
     * 
     * @return  
     * @author  relo_san
     * @since   13.03.2010
     */
    public function setQueryParams( array $params = array() )
    {

        $this->queryParams = $params;

    } // dinDoctrinePager::setQueryParams()


    /**
     * Set table method
     * 
     * @param   string  $tableMethodName    Table method
     * @return  dinDoctrinePager
     * @author  relo_san
     * @since   march 10, 2010
     */
    public function setTableMethod( $tableMethodName )
    {

        $this->tableMethodName = $tableMethodName;
        return $this;

    } // dinDoctrinePager::setTableMethod()


    /**
     * Set page
     * 
     * @param   integer $page   Current page
     * @return  dinDoctrinePager object
     * @author  relo_san [http://relo-san.com/]
     * @since   december 29, 2009
     * @see     sfPager::setQuery()
     */
    public function setPage( $page )
    {

        parent::setPage( $page );
        return $this;

    } // dinDoctrinePager::setPage()

} // dinDoctrinePager

//EOF