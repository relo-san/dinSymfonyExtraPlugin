<?php

/**
 * DinProjectConfiguration
 * 
 * @package     lib.config
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       december 25, 2009
 * @version     SVN: $Id$
 */
class DinProjectConfiguration extends sfProjectConfiguration
{

    /**
     * Load plugins
     * 
     * @return  void
     * @author  relo_san
     * @since   december 25, 2009
     */
    public function loadPlugins()
    {

        $configDir = $this->getRootDir() . '/lib/config';
        require_once $configDir . '/DinPluginConfiguration.class.php';
        foreach ( $this->getPluginPaths() as $path )
        {

            if ( false === $plugin = array_search( $path, $this->overriddenPluginPaths ) )
            {
                $plugin = basename( $path );
            }
            $class = ucfirst( $oldClass = $plugin . 'Configuration' );

            $file = sprintf( '%s/%s/%s.class.php', $configDir, $plugin, $class );
            $oldFile = sprintf( '%s/config/%s.class.php', $path, $oldClass );
            $pluginFile = sprintf( '%s/config/%s.class.php', $path, 'Plugin' . $class );
            if ( is_readable( $pluginFile ) )
            {
                require_once $pluginFile;
                if ( is_readable( $file ) )
                {
                    require_once $file;
                }
                else
                {
                    $class = 'Plugin' . $class;
                }
                $this->pluginConfigurations[$plugin] = new $class( $this, $path, $plugin );
            }
            else if ( is_readable( $oldFile ) )
            {
                require_once $oldFile;
                $this->pluginConfigurations[$plugin] = new $oldClass( $this, $path, $plugin );
            }
            else
            {
                $this->pluginConfigurations[$plugin] = new sfPluginConfigurationGeneric(
                    $this, $path, $plugin
                );
            }

        }

        $this->pluginsLoaded = true;

    } // DinProjectConfiguration::loadPlugins()

} // DinProjectConfiguration

//EOF