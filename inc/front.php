<?php
/**
 * Front end functionality for advanced posts/page
 *
 * @author      Christopher Davis <http://christopherdavis.me>
 * @copyright   Christopher Davis 2012
 * @since       1.0
 * @package     Advanced Posts/Page
 * @license     GPLv2
 */

class CD_APPP_Front extends CD_APPP_Base
{
    /**
     * Adds actions and such.
     *
     * @since       1.0
     * @access      public
     * @uses        add_action
     * @return      null
     */
    public static function init()
    {
        add_filter(
            'pre_get_posts',
            array(get_class(), 'query'),
            20
        );
    }

    /**
     * Hooked into `parse_query`, changes the posts per page according to the
     * user's settings.
     *
     * @since       1.0
     * @access      public
     * @param       WP_Query $q The WP_Query object
     * @return      null
     */
    public static function query($q)
    {
        if(!$q->is_main_query() || !is_archive())
            return; // bail if this isn't the main query or an archive page

        if($v = intval(self::get_per_page()))
        {
            if($v > 0)
            {
                $q->set('posts_per_page', $v);
            }
            elseif($v == -1)
            {
                $q->set('nopaging', true);
            }
        }
    }

    protected static function get_per_page()
    {
        $rv = self::opt('archives', null);
        $obj = get_queried_object();

        if(is_day())
        {
            $rv = self::opt('date_day', null);
        }
        elseif(is_month())
        {
            $rv = self::opt('date_month', null);
        }
        elseif(is_year())
        {
            $rv = self::opt('date_year', null);
        }
        elseif(is_author())
        {
            $rv = self::opt('author', null);
        }
        elseif(is_search())
        {
            $rv = self::opt('search', null);
        }
        elseif(is_post_type_archive())
        {
            $rv = self::opt(self::prefix_pt($obj->name), null);
        }
        elseif(is_category() || is_tag() || is_tax())
        {
            $rv = self::opt(self::prefix_tax($obj->taxonomy), null);
        }
        return $rv;
    }
} // end class

CD_APPP_Front::init();
