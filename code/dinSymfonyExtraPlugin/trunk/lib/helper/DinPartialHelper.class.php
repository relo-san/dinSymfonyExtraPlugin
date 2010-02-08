<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Base partial helper
 * 
 * @package     dinSymfonyExtraPlugin.lib.helper
 * @subpackage  DinPartialHelper
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       january 31, 2010
 * @version     SVN: $Id$
 */
class DinPartialHelper
{

    /**
     * get
     * 
     * @return  string  Partial result
     * @author  relo_san
     * @since   january 31, 2010
     */
    static public function get( $templateName, $vars = array() )
    {

        $context = sfContext::getInstance();

        // partial is in another module?
        if ( false !== $sep = strpos( $templateName, '/' ) )
        {
            $moduleName   = substr( $templateName, 0, $sep );
            $templateName = substr( $templateName, $sep + 1 );
        }
        else
        {
            $moduleName = $context->getActionStack()->getLastEntry()->getModuleName();
        }
        $actionName = '_' . $templateName;

        $class = sfConfig::get( 'mod_' . strtolower( $moduleName ) . '_partial_view_class', 'sf' )
            . 'PartialView';
        $view = new $class( $context, $moduleName, $actionName, '' );
        $view->setPartialVars( $vars );

        return $view->render();

    } // DinPartialHelper::get()

} // DinPartialHelper

//EOF