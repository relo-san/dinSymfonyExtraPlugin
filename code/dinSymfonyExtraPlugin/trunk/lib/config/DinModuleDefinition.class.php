<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Module definition base class
 * 
 * @package     dinSymfonyExtraPlugin.lib.config
 * @subpackage  DinModuleDefinition
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       february 9, 2010
 * @version     SVN: $Id$
 */
class DinModuleDefinition
{

    protected
        $pluginConfig = null;


    /**
     * Get plugin config
     * 
     * @return  object  Plugin config
     * @author  relo_san
     * @since   february 9, 2010
     */
    private function getPluginConfig()
    {

        if ( is_null( $this->pluginConfig ) )
        {
            $config = sfConfig::get( 'sf_lib_dir' ) . '/config/' . $this->definitions['plugin']
                 . '/' . ucfirst( $this->definitions['plugin'] ) . 'Configuration.class.php';
            $pluginConfig = sfConfig::get( 'sf_plugins_dir' ) . '/' . $this->definitions['plugin']
                 . '/config/' . 'Plugin' . ucfirst( $this->definitions['plugin'] )
                 . 'Configuration.class.php';
            require_once $pluginConfig;
            if ( is_readable( $config ) )
            {
                require_once $config;
            }
            $class = ucfirst( $this->definitions['plugin'] ) . 'Configuration';
            if ( !class_exists( $class ) )
            {
                $class = 'Plugin' . $class;
            }
            $this->pluginConfig = new $class( new ProjectConfiguration() );
            unset( $this->definitions['plugin'] );
        }
        return $this->pluginConfig;

    } // DinModuleDefinition::getPluginConfig()


    /**
     * Configure definitions
     * 
     * @return  void
     * @author  relo_san
     * @since   february 9, 2010
     */
    public function configure()
    {

        $this->configureI18nFormFields();

    } // DinModuleDefinition::configure()


    /**
     * Configure translated form fields
     * 
     * @return  void
     * @author  relo_san
     * @since   february 9, 2010
     */
    public function configureI18nFormFields()
    {

        if ( $this->getPluginConfig()->isI18n( $this->definitions['generator']['param']['model_class'] ) )
        {
            if ( isset( $this->definitions['generator']['param']['config']['form']['display']['translated'] ) )
            {
                foreach ( dinConfig::getActiveLanguages() as $lang )
                {
                    $this->definitions['generator']['param']['config']['form']['display']['fieldsets.' . $lang]
                        = $this->definitions['generator']['param']['config']['form']['display']['translated'];
                }
                unset( $this->definitions['generator']['param']['config']['form']['display']['translated'] );
            }
        }

    } // DinModuleDefinition::configureI18nFormFields()


    /**
     * Get rendered module config
     * 
     * @return  string  Yaml config part
     * @author  relo_san
     * @since   february 9, 2010
     */
    public function getAsYml()
    {

        $data[] = '';
        $data[] = 'generator:';
        $data[] = $this->getParam( $this->definitions['generator'], 1 );
        return implode( "\n", $data );

    } // DinModuleDefinition::getAsYml()


    /**
     * Get rendered parameter
     * 
     * @param   mixed   $param  Parameter for rendering
     * @param   integer $indent Indentation [optional]
     * @return  string  Yaml config part
     * @author  relo_san
     * @since   february 9, 2010
     */
    private function getParam( $param, $indent = 1 )
    {

        if ( is_null( $param ) )
        {
            return '    ~';
        }
        if ( is_array( $param ) && count( $param ) == 0 )
        {
            return '    []';
        }
        if ( !is_array( $param ) )
        {
            return '    '
                . ( !is_bool( $param ) ? "'" . $param . "'" : ( $param ? 'true' : 'false' ) );
        }
        if ( isset( $param[0] ) )
        {
            $repr = '';
            foreach ( $param as $val )
            {
                $repr .= ( $repr ? ', ' : '' ) . "'" . $val . "'";
            }
            return '    [' . $repr . ']';
        }
        $data[] = '';
        foreach ( $param as $key => $val )
        {
            $data[] = str_repeat( ' ', ( $indent * 4 ) )
                . $key . ':' . $this->getParam( $val, $indent + 1 );
        }
        return implode( "\n", $data );

    } // DinModuleDefinition::getParam()

} // DinModuleDefinition

//EOF