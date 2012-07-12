<?php
/*
Plugin Name: Advanced Posts/Page
Plugin URI: https://github.com/chrisguitarguy/Advanced-Posts-Page
Description: Set how many posts are each page of all your WordPress archives
Version: 1.0
Text Domain: cdappp
Domain Path: /lang
Author: Christopher Davis
Author URI: http://christopherdavis.me
License: GPL2

    Copyright 2012 Christopher Davis

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('CD_APPP_PATH', plugin_dir_path(__FILE__));

require_once(CD_APPP_PATH . 'inc/base.php');
if(is_admin())
{
    require_once(CD_APPP_PATH . 'inc/settings.php');
}
else
{
    require_once(CD_APPP_PATH . 'inc/front.php');
}
