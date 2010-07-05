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


    /**
     * Get component
     * 
     * @param   string  $moduleName     Module name
     * @param   string  $componentName  Component name
     * @param   array   $vars           Variables [optional]
     * @return  string  Rendered component
     * @author  relo_san
     * @since   february 16, 2010
     */
    static public function component( $moduleName, $componentName, $vars = array() )
    {

        $context = sfContext::getInstance();
        $actionName = '_' . $componentName;

        $class = sfConfig::get( 'mod_' . strtolower( $moduleName ) . '_partial_view_class', 'sf' ) . 'PartialView';
        $view = new $class( $context, $moduleName, $actionName, '' );
        $view->setPartialVars( $vars );

        if ( $retval = $view->getCache() )
        {
            return $retval;
        }

        $allVars = self::callComponent( $moduleName, $componentName, $vars );

        if ( null !== $allVars )
        {
            $view->getAttributeHolder()->add( $allVars );
            return $view->render();
        }

    } // DinPartialHelper::component()


    /**
     * Call component
     * 
     * @return  mixed   Component controller result
     * @author  relo_san
     * @since   february 16, 2010
     */
    static private function callComponent( $moduleName, $componentName, $vars )
    {

        $context = sfContext::getInstance();

        $controller = $context->getController();

        if ( !$controller->componentExists( $moduleName, $componentName ) )
        {
            throw new sfConfigurationException( sprintf(
                'The component does not exist: "%s", "%s".', $moduleName, $componentName
            ) );
        }

        $componentInstance = $controller->getComponent( $moduleName, $componentName );
        require( $context->getConfigCache()->checkConfig( 'modules/' . $moduleName . '/config/module.yml' ) );

        $componentInstance->getVarHolder()->add(
            true === sfConfig::get( 'sf_escaping_strategy' ) ? sfOutputEscaper::unescape( $vars ) : $vars
        );

        $componentToRun = 'execute' . ucfirst( $componentName );
        if ( !method_exists( $componentInstance, $componentToRun ) )
        {
            if ( !method_exists( $componentInstance, 'execute' ) )
            {
                throw new sfInitializationException( sprintf(
                    'sfComponent initialization failed for module "%s", component "%s".', $moduleName, $componentName
                ) );
            }
            $componentToRun = 'execute';
        }

        if ( sfConfig::get( 'sf_logging_enabled' ) )
        {
            $context->getEventDispatcher()->notify( new sfEvent( null, 'application.log',
                array( sprintf( 'Call "%s->%s()' . '"', $moduleName, $componentToRun ) )
            ) );
        }

        if ( sfConfig::get( 'sf_debug' ) && sfConfig::get( 'sf_logging_enabled' ) )
        {
            $timer = sfTimerManager::getTimer( sprintf( 'Component "%s/%s"', $moduleName, $componentName ) );
        }

        $retval = $componentInstance->$componentToRun( $context->getRequest() );

        if ( sfConfig::get( 'sf_debug' ) && sfConfig::get( 'sf_logging_enabled' ) )
        {
            $timer->addTime();
        }

        return sfView::NONE == $retval ? null : $componentInstance->getVarHolder()->getAll();

    } // DinPartialHelper::callComponent()

} // DinPartialHelper

//EOF