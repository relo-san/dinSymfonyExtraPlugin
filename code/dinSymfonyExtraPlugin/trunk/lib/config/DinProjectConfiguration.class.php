<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * DinProjectConfiguration
 * 
 * @package     dinSymfonyExtraPlugin.lib.config
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       december 25, 2009
 * @version     SVN: $Id$
 */
class DinProjectConfiguration extends sfProjectConfiguration
{

    /**
     * __construct
     * 
     * @return  void
     * @author  relo_san
     * @since   march 12, 2010
     */
    public function __construct( $rootDir = null, sfEventDispatcher $dispatcher = null )
    {

        parent::__construct( $rootDir, $dispatcher );

        $pldir = sfConfig::get( 'sf_plugins_dir' );
        require_once( $pldir . '/dinSymfonyExtraPlugin/lib/config/dinRoutingConfigHandler.class.php' );
        require_once( $pldir . '/dinSymfonyExtraPlugin/lib/config/dinCacheRoutingConfigHandler.class.php' );
        require_once( $pldir . '/dinSymfonyExtraPlugin/lib/config/dinFactoryConfigHandler.class.php' );

    } // DinProjectConfiguration::__construct()


    /**
     * Setup configuration
     * 
     * @return  
     * @author  relo_san
     * @since   june 27, 2010
     */
    public function preConfigureDoctrinePlugin( $options = array() )
    {

        sfConfig::set( 'doctrine_model_builder_options', array_merge(
            array( 'baseTableClassName' => 'DinDoctrineTable', 'baseClassName' => 'DinDoctrineRecord' ),
            $options
        ) );

    } // DinProjectConfiguration::setup()


    /**
     * Load plugins
     * 
     * @return  void
     * @author  relo_san
     * @since   december 25, 2009
     */
    public function loadPlugins()
    {

        $rootDir = $this->getRootDir();
        require_once $rootDir . '/plugins/dinSymfonyExtraPlugin/lib/config/DinPluginConfiguration.class.php';
        foreach ( $this->getPluginPaths() as $path )
        {

            if ( false === $plugin = array_search( $path, $this->overriddenPluginPaths ) )
            {
                $plugin = basename( $path );
            }
            $class = ucfirst( $oldClass = $plugin . 'Configuration' );

            $file = sprintf( '%s/%s/%s.class.php', $rootDir . '/lib/config', $plugin, $class );
            $oldFile = sprintf( '%s/config/%s.class.php', $path, $oldClass );
            $pluginFile = sprintf( '%s/config/%s.class.php', $path, 'Plugin' . $class );

            $isPlugin = false;
            if ( is_readable( $pluginFile ) )
            {
                require_once $pluginFile;
                $isPlugin = true;
            }
            if ( is_readable( $file ) )
            {
                require_once $file;
            }
            else if ( $isPlugin )
            {
                $class = 'Plugin' . $class;
            }
            else if ( is_readable( $oldFile ) )
            {
                require_once $oldFile;
                $class = $oldClass;
                $this->pluginConfigurations[$plugin] = new $oldClass( $this, $path, $plugin );
            }
            else
            {
                $class = 'sfPluginConfigurationGeneric';
            }
            $this->pluginConfigurations[$plugin] = new $class( $this, $path, $plugin );

        }

        $this->pluginsLoaded = true;

    } // DinProjectConfiguration::loadPlugins()

} // DinProjectConfiguration

//EOF