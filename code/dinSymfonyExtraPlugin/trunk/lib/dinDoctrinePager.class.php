<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009 http://dinecat.com/
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

        parent::init();

        if ( $this->getPage() == $this->getLastPage() )
        {
            $this->getQuery()->offset( ( $this->getLastPage() - 1 ) * $this->getMaxPerPage() );
        }

    } // dinDoctrinePager::init()


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