<?php

/*
 * This file is part of the dinSymfonyExtraPlugin package.
 * (c) DineCat, 2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Response class for web interfaces
 * 
 * @package     dinSymfonyExtraPlugin
 * @subpackage  lib.response
 * @author      Nicolay N. Zyk <relo.san@gmail.com>
 */
class dinWebResponse extends sfWebResponse
{

    protected
        $titleParts = array(),
        $titleFormat = '%page%{del}%section%{del}%resource%',
        $titleDelimiter = ' » ';


    /**
     * Initialize
     * 
     * @param   sfEventDispatcher   $dispatcher
     * @param   array               $options    Response options [optional]
     * @return  void
     */
    public function initialize( sfEventDispatcher $dispatcher, $options = array() )
    {

        parent::initialize( $dispatcher, $options );

    } // dinWebResponse::initialize()


    /**
     * Get title
     * 
     * @return  string  Title
     */
    public function getTitle()
    {

        return isset( $this->metas['title'] ) ? $this->metas['title'] : $this->buildTitle();

    } // dinWebResponse::getTitle()


    /**
     * Build title
     * 
     * @return  string  Title
     */
    public function buildTitle()
    {

        $titleConfig = sfConfig::get( 'app_title_params' );
        if ( isset( $titleConfig['resource'] ) )
        {
            $this->setTitlePart( 'resource', $titleConfig['resource'], true );
        }
        if ( isset( $titleConfig['format'] ) )
        {
            $this->setTitleFormat( $titleConfig['format'] );
        }
        if ( isset( $titleConfig['delimiter'] ) )
        {
            $this->setTitleDelimiter( $titleConfig['delimiter'] );
        }

        $parts = array();
        foreach ( explode( '{del}', $this->titleFormat ) as $part )
        {
            if ( isset( $this->titleParts[$part] ) )
            {
                $parts[] = $this->titleParts[$part];
            }
        }
        $this->addMeta(
            'title', implode( $this->titleDelimiter, $parts ), true,
            sfConfig::get( 'app_title_params_escape', true )
        );
        return $this->metas['title'];

    } // dinWebResponse::buildTitle()


    /**
     * Set title format
     * 
     * @param   string  $format Title format
     * @return  dinWebResponse
     */
    public function setTitleFormat( $format )
    {

        $this->titleFormat = $format;
        return $this;

    } // dinWebResponse::setTitleFormat()


    /**
     * Set title delimiter
     * 
     * @param   string  $delimiter  Title delimiter
     * @return  dinWebResponse
     */
    public function setTitleDelimiter( $delimiter )
    {

        $this->titleDelimiter = $delimiter;
        return $this;

    } // dinWebResponse::setTitleDelimiter()


    /**
     * Set title part
     * 
     * @param   string  $name   Name of title part
     * @param   string  $value  Value of title part
     * @param   boolean $isI18n Is part need to translate [optional, default false]
     * @return  dinWebResponse
     */
    public function setTitlePart( $name, $value, $isI18n = false )
    {

        $this->titleParts['%' . $name . '%'] = $isI18n ? I18n::__( $value ) : $value;
        return $this;

    } // dinWebResponse::setTitlePart()

} // dinWebResponse

//EOF