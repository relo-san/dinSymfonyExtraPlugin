<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2009-2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * dinWidgetFormJqueryAutocompleter
 * 
 * @package     dinSymfonyExtraPlugin.lib.widget
 * @signed      5
 * @signer      relo_san
 * @author      relo_san [http://relo-san.com/]
 * @since       february 8, 2010
 * @version     SVN: $Id$
 */
class dinWidgetFormJqueryAutocompleter extends sfWidgetFormInput
{

    /**
     * Configure widget
     * 
     * @param   array   $options    An array of options [optional]
     * @param   array   $attributes An array of default HTML attributes [optional]
     * @return  void
     * @author  relo_san
     * @since   february 8, 2010
     * @see     sfWidgetForm
     */
    protected function configure( $options = array(), $attributes = array() )
    {

        $this->addRequiredOption( 'url' );
        $this->addOption( 'value_callback' );
        $this->addOption( 'config', '{ }' );
        $this->addOption( 'choices' );
        parent::configure( $options, $attributes );

    } // dinWidgetFormJqueryAutocompleter::configure()


    /**
     * Render field
     * 
     * @return  string  XHTML compliant tag
     * @author  relo_san
     * @since   february 8, 2010
     */
    public function render( $name, $value = null, $attributes = array(), $errors = array() )
    {

        $visibleValue = $this->getOption( 'value_callback' )
            ? call_user_func( $this->getOption( 'value_callback' ), $value ) : $value;

        return $this->renderTag('input', array('type' => 'hidden', 'name' => $name, 'value' => $value)).
           parent::render('autocomplete_'.$name, $visibleValue, $attributes, $errors).
           sprintf(<<<EOF
<script type="text/javascript">
  jQuery(document).ready(function() {
    jQuery("#%s")
    .autocomplete('%s', jQuery.extend({}, {
      dataType: 'json',
      parse:    function(data) {
        var parsed = [];
        for (key in data) {
          parsed[parsed.length] = { data: [ data[key].value, key ], value: data[key].value, result: data[key].result };
        }
        return parsed;
      }
    }, %s))
    .result(function(event, data) { jQuery("#%s").val(data[1]); });
  });
</script>
EOF
      ,
      $this->generateId('autocomplete_'.$name),
      $this->getOption('url'),
      $this->getOption('config'),
      $this->generateId($name)
    );

    } // dinWidgetFormJqueryAutocompleter::render()

} // dinWidgetFormJqueryAutocompleter

//EOF