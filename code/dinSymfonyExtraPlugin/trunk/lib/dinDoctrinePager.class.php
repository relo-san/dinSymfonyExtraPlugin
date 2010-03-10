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
        $cacheKey = null,
        $cacheDriver = null,
        $cache = null;

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
            $this->cacheKey = dinDoctrine::getCacheKey(
                $this->getClass(), dinDoctrine::CACHE_TYPE_PAGE, $this->getPage(), $this->cacheKey
            );
            $this->cacheDriver = dinDoctrine::getCacheDriver(
                $this->getClass(), dinDoctrine::CACHE_TYPE_PAGE, $this->getPage()
            );
            if ( $this->cacheDriver->has( $this->cacheKey ) )
            {
                $this->cache = unserialize( $this->cacheDriver->get( $this->cacheKey ) );
                if ( isset( $this->cache['pager'] ) )
                {
                    return $this->unserialize( $this->cache['pager'] );
                }
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

        if ( $this->useCache && isset( $this->cache['data'] ) )
        {
            return $this->cache['data'];
        }
        $results = parent::getResults( $hydrationMode );
        if ( $this->useCache && isset( $this->cache['pager'] ) )
        {
            $this->cache['data'] = $results;
            $this->cacheDriver->set( $this->cacheKey, serialize( $this->cache ) );
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
        unset( $vars['query'], $vars['cache'], $vars['cacheDriver'], $vars['cacheKey'] );
        return serialize( $vars );

    } // dinDoctrinePager::serialize()


    /**
     * Use cache
     * 
     * @param   string|false    $key    Cache key [if using cache]
     * @return  dinDoctrinePager
     * @author  relo_san
     * @since   march 10, 2010
     */
    public function useCache( $key = false )
    {

        $this->useCache = $key ? true : false;
        $this->cacheKey = $key ? $key : null;
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