<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * dinActions
 * 
 * @package     dinSymfonyExtraPlugin.lib
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       january 7, 2010
 * @version     SVN: $Id$
 */
class dinActions extends sfActions
{

    /**
     * Render partial
     * 
     * @param   string  $template   Partial name
     * @param   array   $vars       Template variables [optional]
     * @return  sfView::NONE
     * @author  relo_san
     * @since   january 6, 2010
     * @see     sfAction::renderPartial()
     */
    public function renderPartial( $template, $vars = null )
    {

        return $this->renderText( Partial::get( $template, $vars ) );

    } // dinActions::renderPartial()


    /**
     * Redirect to referer
     * 
     * @param   string  $defaultUrl Default url
     * @return  void
     * @author  relo_san
     * @since   january 7, 2010
     */
    public function redirectToReferer( $defaultUrl )
    {

        $referer = $this->getRequest()->getReferer();
        $this->redirect( $referer ? $referer : $defaultUrl );

    } // dinActions::redirectToReferer()


    /**
     * Prepare output
     * 
     * @param   string  $partial    Partial name or path (module/partial) or text (@text:*)
     * @param   string  $template   template name [optional]
     * @param   string  $type       Return type [optional]
     * @return  string  sfView name constant or partial
     * @author  relo_san
     * @since   january 7, 2010
     */
    public function prepareOutput( $partial, $template = null, $type = sfView::SUCCESS )
    {

        if ( $this->getRequest()->isXmlHttpRequest() )
        {
            if ( substr( $partial, 0, 6 ) == '@text:' )
            {
                return $this->renderText( substr( $partial, 6 ) );
            }
            if ( strpos( $partial, '/' ) === false )
            {
                $partial = $this->getModuleName() . '/' . $partial;
            }
            return $this->renderPartial( $partial, $this->getVarHolder()->getAll() );
        }

        if ( $template )
        {
            $this->setTemplate( $template );
        }
        return $type;

    } // dinActions::prepareOutput()


    /**
     * Get referer info
     * 
     * @param   string  $param      Request referer parameter [optional]
     * @param   mixed   $default    Default value [optional]
     * @return  mixed   Array of referer parameters or value of request referer parameter
     * @author  relo_san
     * @since   january 7, 2010
     */
    public function getRefInfo( $param = null, $default = null )
    {

        return $this->getUser()->getSIS()->getRefInfo( $param, $default );

    } // dinActions::getRefInfo()


    /**
     * Add referer info to SIS
     * 
     * @param   string  $key        Key for storaging
     * @param   string  $param      Parameter for saving [optional]
     * @param   integer $duration   Time to live storage [optional]
     * @return  void
     * @author  relo_san
     * @since   january 7, 2010
     */
    public function addSISRefInfo( $key, $param = null, $duration = 86400 )
    {

        return $this->getUser()->getSIS()->addRefInfo( $key, $param, $duration );

    } // dinActions::addSISRefInfo()


    /**
     * Get once from SIS (with auto removing)
     * 
     * @param   string  $key        Key for storaging
     * @param   mixed   $default    Default value [optional]
     * @return  string  Value for key
     * @author  relo_san
     * @since   january 7, 2010
     */
    public function getSISOnce( $key, $default = null )
    {

        return $this->getUser()->getSIS()->getOnce( $key, $default );

    } // dinActions::getSISOnce()

} // dinActions

//EOF