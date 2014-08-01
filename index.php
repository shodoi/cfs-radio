<?php
/*
Plugin Name: CFS - Radio-Button Add-on
Plugin URI: http://customfieldsuite.com/
Description: A radio-button field extension for Custom Field Suite
Version: .1
Author: Sho Doi
Author URI: http://show-web.jp/
License: GPL2
*/


$cfs_radio_addon = new cfs_radio_addon();

class cfs_radio_addon
{
    function __construct() {
        add_filter( 'cfs_field_types', array( $this, 'cfs_field_types' ) );
    }

    function cfs_field_types( $field_types )
    {
        $field_types['radio'] = dirname( __FILE__ ) . '/radio.php';
        return $field_types;
    }
}