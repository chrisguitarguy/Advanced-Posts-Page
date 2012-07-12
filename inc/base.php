<?php
/**
 * Base class for Advanced Posts/Page.  Provided functionality and class
 * constants used throughout the plugin
 *
 * @author      Christopher Davis <http://christopherdavis.me>
 * @since       1.0
 * @package     Advanced Post/Page
 * @license     GPLv2
 * @copyright   Christopher Davis 2012
 */

class CD_APPP_Base
{
    /**
     * The plugins settings name (used in `register_setting` and such)
     * 
     * @since        1.0
     * @access       public
     */
    const SETTING = 'cd_appp_options';

    /**
     * Container for holding information about the taxonomy posts/page
     * 
     * @since        1.0
     * @access       public
     */
    const TAXOPT = 'cd_appp_tax_settings';

    /**
     * Prefix a post type name for saving in our options
     * 
     * @since       1.0
     * @access      protected
     * @param       string $pt The post name name
     * @return      string The post type with the prefix
     */
    protected static function prefix_pt($pt)
    {
        return sprintf("post_type_%s", esc_attr($pt));
    }

    /**
     * Prefix a taxonomy for saving in our options
     * 
     * @since       1.0
     * @access      protected
     * @param       string $t The taxonomy name to prefix
     * @return      string The taxonomy with the prefix
     */
    protected static function prefix_tax($t)
    {
        return sprintf('taxonomy_%s', esc_attr($t));
    }

    /**
     * Get and option from `self::SETTING`
     * 
     * @since       1.0
     * @access      protected
     * @param       string $key The option key
     * @param       mixed $default The default return value
     * @return      mixed Whatever happens to be in the option
     */
    protected static function opt($key, $default='')
    {
        // fetch the option each time, let WP do the caching
        $opts = get_option(self::SETTING, array());
        return isset($opts[$key]) ? $opts[$key] : $default;
    }

    /**
     * Get an option from `self::TAXOPTS
     * 
     * @since       1.0
     * @access      protected
     * @param       string $key The option key
     * @param       mixed $default Whatever the default value is
     * @return      mixed Whatever happens to be in the setting
     */
    protected static function get_taxopt($key, $default)
    {
        $opts = get_option(self::TAXOPT, array());
        return isset($opts[$key]) ? $opts[$key] : $default;
    }

    /**
     * Fetch all the post types for which this plugin will have settings
     *
     * @since       1.0
     * @access      protected
     * @return      array Prefixed post type as the key and label as the value
     */
    protected static function post_types()
    {
        $types = get_post_types(array(
            'public'      => true,
            '_builtin'    => false, // no default post types
            'has_archive' => true
        ), 'objects');

        if(!$types)
            return array();

        $rv = array();
        foreach($types as $n => $t)
        {
            $rv[self::prefix_pt($n)] = $t->label;
        }
        return apply_filters('cd_appp_post_types', $rv);
    }

    /**
     * Fetch all the taxonomies for which this plugin will have settings
     *
     * @since       1.0
     * @access      protected
     * @return      array Prefixed taxonomy as the key, tax name as the value
     */
    protected static function taxonomies()
    {
        $taxes = get_taxonomies(array(
            'public'  => true
        ), 'objects');
        if(!$taxes)
            return array();

        $rv = array();
        foreach($taxes as $n => $t)
        {
            if('post_format' == $n && !current_theme_supports('post-formats'))
                continue;
            $rv[self::prefix_tax($n)] = $t->label;
        }
        return apply_filters('cd_appp_taxonomies', $rv);
    }

    /**
     * Get the fields for the default section settings
     *
     * @since       1.0
     * @access      protected
     * @return      array Settings name as the key, label as the value
     */
    protected static function archives()
    {
        $rv = array(
            'date_year'   => __('Yearly Archives', 'cdappp'),
            'date_month'  => __('Monthly Archives', 'cdappp'),
            'date_day'    => __('Daily Archives', 'cdappp'),
            'author'      => __('Author Archives', 'cdappp'),
            'search'      => __('Search Results', 'cdappp')
        );
        return apply_filters('cd_appp_archives', $rv);
    }

    /**
     * Gett a settins field name based on the key
     *
     * @since       1.0
     * @access      protected
     * @return      string
     */
    protected static function prefix_opt($key)
    {
        return sprintf('%s[%s]', self::SETTING, esc_attr($key));
    }
}
