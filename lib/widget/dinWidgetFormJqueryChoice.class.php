<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Widget for selects and multiple selects throw Jquery
 * 
 * @package     dinSymfonyExtraPlugin.lib.widget
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       february 21, 2010
 * @version     SVN: $Id$
 */
class dinWidgetFormJqueryChoice extends sfWidgetFormChoiceBase
{

    /**
     * Configure widget
     * 
     * @param   array   $options        Widget options [optional]
     * @param   array   $attributes     Render attributes [optional]
     * @return  void
     * @author  relo_san
     * @since   february 21, 2010
     */
    protected function configure( $options = array(), $attributes = array() )
    {

        parent::configure( $options, $attributes );

        $this->addOption( 'multiple', false );
        $this->addOption( 'expanded', false );
        $this->addOption( 'renderer_class', false );
        $this->addOption( 'renderer_options', array() );
        $this->addOption( 'renderer', false );
        $this->addOption( 'tabbed', false );
        $this->addOption( 'label_unassociated', 'choiceLabels.unassociated' );
        $this->addOption( 'label_associated', 'choiceLabels.associated' );
        $this->addOption(
            'template_dl',
            '<div id="%id%" class="sf-dl-select-list %class%">%tabs%<div id="%id%-1">%associated%</div>'
            . '<div id="%id%-2">%unassociated%</div>%js%</div>'
        );

    } // dinWidgetFormJqueryChoice::configure()


    /**
     * Render widget
     * 
     * @param   string  $name       Element name
     * @param   string  $value      Element value [optional]
     * @param   array   $attributes Render attributes [optional]
     * @param   array   $errors     Errors for the widget [optional]
     * @return  string  Rendered widget
     * @author  relo_san
     * @since   february 21, 2010
     */
    public function render( $name, $value = null, $attributes = array(), $errors = array() )
    {

        if ( is_null( $value ) )
        {
            $value = array();
        }

        $choices = $this->getOption( 'choices' );
        if ( $choices instanceof sfCallable )
        {
            $choices = $choices->call();
        }

        if ( $this->getOption( 'multiple' ) == false )
        {
            return;
        }

        if ( $this->getOption( 'expanded' ) == false )
        {
            $associated = array();
            $unassociated = array();
            foreach ( $choices as $key => $option )
            {
                if ( in_array( strval( $key ), $value ) )
                {
                    $associated[$key] = $option;
                }
                else
                {
                    $unassociated[$key] = $option;
                }
            }

            return strtr( $this->getOption( 'template_dl' ), array(
                '%class%'           => $this->getOption( 'class' ),
                '%id%'              => $this->generateId( $name ),
                '%tabs%'            => $this->renderHeader( $name ),
                '%associated%'      => $this->renderList( $name, 1, $associated, true ),
                '%unassociated%'    => $this->renderList( $name, 2, $unassociated ),
                '%js%'              => $this->getJs( $name )
            ) );
        }

        

    } // dinWidgetFormJqueryChoice::render()


    /**
     * Render header
     * 
     * @param   string  $name   Element name
     * @return  string  HTML code
     * @author  relo_san
     * @since   february 21, 2010
     */
    public function renderHeader( $name )
    {

        if ( $this->getOption( 'tabbed' ) )
        {
            return strtr(
                '<ul><li><a onclick="blur()" href="#%id%-1">%label_associated%</a></li><li>'
                . '<a onclick="blur()" href="#%id%-2">%label_unassociated%</a></li></ul>',
                array(
                    '%id%'                  => $this->generateId( $name ),
                    '%label_associated%'    => I18n::__( $this->parent->getFormFormatter()->getTranslationCatalogue() . '.' . $this->getOption( 'label_associated' ) ),
                    '%label_unassociated%'  => I18n::__( $this->parent->getFormFormatter()->getTranslationCatalogue() . '.' . $this->getOption( 'label_unassociated' ) ),
                )
            );
        }
        return '';

    } // dinWidgetFormJqueryChoice::renderHeader()


    /**
     * Render list
     * 
     * @param   string  $name       Element name
     * @param   integer $sid        List identifier
     * @param   array   $list       List of choices
     * @param   boolean $selected   Selected list?
     * @return  string  HTML code
     * @author  relo_san
     * @since   february 21, 2010
     */
    public function renderList( $name, $sid, $list, $selected = false )
    {

        $tname = $this->generateId( $name );
        $out[] = '<ul id="' . $tname . '_s' . $sid . '" class="connectedSortable ui-helper-reset">';
        foreach ( $list as $key => $value )
        {
            $out[] = '<li class="ui-state-default">' . $value . '<input type="hidden" name="'
                   . $name . '[]" value="' . $key . '"'
                   . ( $selected ? '' : 'disabled' ) . ' /></li>';
        }
        $out[] = '</ul>';
        return implode( $out );

    } // dinWidgetFormJqueryChoice::renderList()


    /**
     * Get javascript code
     * 
     * @param   string  $name   Element name
     * @return  string  HTML container with JS
     * @author  relo_san
     * @since   february 21, 2010
     */
    public function getJs( $name )
    {
        $js[] = '<script type="text/javascript">$(function(){';
        if ( $this->getOption( 'tabbed' ) )
        {
            $tname = $this->generateId( $name );

$s = 'drop:function(ev,ui){var $item=$(this);var $list=$($item.find(\'a\').attr(\'href\')).find(\'.connectedSortable\');
                ui.draggable.hide(\'slow\',function(){$' . $tname . '_tabs.tabs(\'select\',$' . $tname . '_tab_items.index($item));
                    if($list.attr(\'id\')==\'' . $tname . '_s2\'){$(this).find(\'input\').attr(\'disabled\',\'disabled\')}else{$(this).find(\'input\').removeAttr(\'disabled\')}
                    $(this).appendTo($list).show(\'slow\');
                });
            }';

            $js[] = '$("#' . $tname . '_s1, #' . $tname . '_s2").sortable().disableSelection();';
            $js[] = 'var $' . $tname . '_tabs=$("#' . $tname . '").tabs();';
            $js[] = 'var $' . $tname . '_tab_items=$("ul:first li",$' . $tname . '_tabs).droppable({';
            $js[] = 'accept:".connectedSortable li",hoverClass:"ui-state-hover",'.$s.'});';
        }
        $js[] = '});</script>';
        return implode( $js );

    } // dinWidgetFormJqueryChoice::getJs()

} // dinWidgetFormJqueryChoice

//EOF