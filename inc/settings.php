<?php
/**
 * Contains all the functionality for the admin area page
 *
 * @author      Christopher Davis <http://christopherdavis.me>
 * @copyright   Christopher Davis 2012
 * @since       1.0
 * @package     Advanced Posts/Page
 * @license     GPLv2
 */

class CD_APPP_Admin_Page extends CD_APPP_Base
{
    /**
     * The page to which this plugins settings will be added
     * 
     * @since       1.0
     * @access      public
     */
    const PAGE = 'reading';

    /**
     * Initializer.  Called to add actions and such
     * 
     * @since       1.0
     * @access      public
     */
    public static function init()
    {
        add_action(
            'admin_init',
            array(get_class(), 'settings')
        );
    }

    /**
     * Hooked into `admin_init`.  Registers the settings and the settings
     * sections.
     * 
     * @since       1.0
     * @access      public
     */
    public static function settings()
    {
        register_setting(
            self::PAGE,
            self::SETTING,
            array(get_class(), 'cleaner')
        );

        add_settings_section(
            'cd-appp-default',
            __('Advanced Posts Per Page: Archives', 'cdappp'),
            array(get_class(), 'default_cb'),
            self::PAGE
        );

        $defaults = self::archives();
        if($defaults)
        {
            self::add_fields($defaults, 'cd-appp-default');
        }

        $post_types = self::post_types();
        if($post_types)
        {
            add_settings_section(
                'cd-appp-post-type',
                __('Advanced Posts Per Page: Post Type Archives', 'cdappp'),
                array(get_class(), 'type_cb'),
                self::PAGE
            );
            self::add_fields($post_types, 'cd-appp-post-type');
        }

        $taxes = self::taxonomies();
        if($taxes)
        {
            add_settings_section(
                'cd-appp-taxonomies',
                __('Advanced Post Per Page: Taxonomy Archives', 'cdappp'),
                array(get_class(), 'tax_cb'),
                self::PAGE
            );
            self::add_fields($taxes, 'cd-appp-taxonomies');
        }
    }

    /**
     * Callback function for the settings cleaning
     * 
     * @since       1.0
     * @access      public
     */
    public static function cleaner($in)
    {
        $fields = array_merge(
            self::archives(),
            self::post_types(),
            self::taxonomies()
        );

        $out = array();
        foreach($fields as $key => $l)
        {
            $out[$key] = 0;
            if(isset($in[$key]))
            {
                $out[$key] = '-1' == $in[$key] ? -1 : absint($in[$key]);
            }
        }
        return $out;
    }

    /**
     * Field callback function
     * 
     * @since       1.0
     * @access      public
     */
    public static function field_cb($args)
    {
        $key = isset($args['key']) ? $args['key'] : false;
        if(!$key)
            return; // bail if there's no key
        printf(
            '<input type="number" step="1" min="-1" value="%1$s" '. 
            'id="%2$s" name="%2$s" class="small-text" />',
            intval(self::opt($key)),
            self::prefix_opt($key)
        );
    }

    /**
     * Callback for the default settings section
     * 
     * @since       1.0
     * @access      public
     */
    public static function default_cb()
    {
        esc_html_e(
            'Posts per page on date archives, author archives, and search pages',
            'cdappp'
        );
    }

    /**
     * Callback for the post type settings section
     *
     * @since       1.0
     * @access      public
     */
    public static function type_cb()
    {
        esc_html_e('Posts per page on post type archives', 'cdappp');
    }

    /**
     * Callback for the taxonomy settings section
     *
     * @since       1.0
     * @access      public
     */
    public static function tax_cb()
    {
        esc_html_e('Posts per page on taxonomy archives', 'cdappp');
    }

    /**
     * Add settings fields based on `$args` an associative array with the 
     * option key as the first value and the option label as the second
     *
     * @since       1.0
     * @access      protected
     * @uses        add_settings_field
     */
    protected static function add_fields($args, $sect)
    {
        foreach($args as $key => $label)
        {
            $prefixd = self::prefix_opt($key);
            $opt_args = array(
                'label_for'  => $prefixd, 
                'key'        => $key
            );
            add_settings_field(
                $prefixd,
                esc_html($label),
                array(get_class(), 'field_cb'),
                self::PAGE,
                $sect,
                $opt_args
            );
        }
    }
}

CD_APPP_Admin_Page::init();
