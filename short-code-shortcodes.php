<?php
/*
Plugin Name: Short Code Shortcodes
Plugin URI: http://joostkiens.com
Description: Adds 2 simple shortcodes for rendering blocklevel [precode] and inline code [code]. converts code to html entites and disables WP's autoformatting. <a href="http://joostkiens.com/">Usage</a>.
The code itself gets converted to utf-8 html entities (not necessary to type &lt;, etc.)
Quotes won't get converted by the WordPress texturizer, so users can copy your code directly.
Version: 0.2.1
Author: Joost Kiens
Author Email: me@joostkiens.com
License:

  Copyright 2011 Joost Kiens (me@joostkiens.com)

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

class shortcodeshortcodes {

	/*--------------------------------------------*
	 * Constants
	 *--------------------------------------------*/
	const name          = 'short code shortcodes';
	const slug          = 'short_code_shortcodes';
	
	/**
	 * Constructor
	 */
	function __construct() {
		// Hook up to the init action
		add_action( 'init', array( &$this, 'init_short_code_shortcodes' ) );

		// List allowed attributes
		$this->core_attributes = array(
			'accesskey',
			'class',
			'contenteditable',
			'contextmenu',
			'dir',
			'draggable',
			'hidden',
			'id',
			'lang',
			'spellcheck',
			'style',
			'tabindex',
			'title',
		);
		$this->scsc_path     = plugin_dir_path( __FILE__ );
		$this->scsc_url      = plugin_dir_url( __FILE__ );
	}

	/**
	 * Runs when the plugin is initialized
	 * @return void
	 */
	function init_short_code_shortcodes() {
		load_plugin_textdomain( self::slug, false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
		add_shortcode( 'code', array( &$this, 'render_code_shortcode' ) );
		add_shortcode( 'precode', array( &$this, 'render_precode_shortcode' ) );
		add_filter( 'no_texturize_shortcodes', array( &$this, 'no_texturized_shortcodes_filter' ) );
		$this->add_buttons();
	}

	/**
	 * Prepare output of [code] shortcode
	 * @param  arr    $atts    Shortcode attributes
	 * @param  str    $content Text content of the shortcode
	 * @return str             Formatted HTML output
	 */
	function render_code_shortcode( $atts, $content = null ) {
		return '<code'. $this->set_html_attributes( $atts ) . '>' . do_shortcode( $this->clean_code_content($content) ) . '</code>';
	}

	/**
	 * Prepare output of [precode] shortcode
	 * @param  arr    $atts    Shortcode attributes
	 * @param  str    $content Text content of the shortcode
	 * @return str             Formatted HTML output
	 */
	function render_precode_shortcode( $atts, $content = null ) {
		return '<pre'. $this->set_html_attributes( $atts ) . '><code>' . do_shortcode( $this->clean_code_content($content) ) . '</code></pre>';
	}

	/**
	 * Excludes shortcodes from running wptexturize
	 * @param  array  $shortcodes Excluded shortcodes
	 * @return array              Excluded shortcodes, with added exclusions
	 */
	function no_texturized_shortcodes_filter( $shortcodes ) {
		$shortcodes[] = 'code';
		$shortcodes[] = 'precode';
		return $shortcodes;
	}

	/**
	 * Add buttons to tinymce Editor & quick tags
	 */
	function add_buttons() {
		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
			return;
		}

		// Add to TinyMCS
		add_filter( 'mce_external_plugins', array( &$this, 'add_plugin' ) );
		add_filter( 'mce_buttons', array( &$this, 'register_buttons' ) );
		
		// Add to quick tags
		add_action('admin_print_scripts', array( &$this, 'custom_quicktags' ) );
	}

	/**
	 * Register code & precode quick tags
	 * @return void
	 */
	function custom_quicktags() {
		wp_enqueue_script( 'scsc_quicktags', $this->scsc_url . 'js/scsc-quicktags.js', array('quicktags') );
	}

	/**
	 * Register buttons to tinymce editor
	 * @param  arr    $buttons Array of tinymce buttons
	 * @return arr             Modified array of tinymce buttons
	 */
	function register_buttons( $buttons ) {
		array_push( $buttons, 'scsc_code', 'scsc_precode' );
		var_dump($buttons);
		return $buttons;
	}

	/**
	 * Add plugin to tinymce
	 * @param  arr   $plugin_array Array of plugins for tinymce
	 * @return arr                 Modified array of plugins for tinymce
	 */
	function add_plugin( $plugin_array ) {
		$plugin_array['scsc_code'] = $this->scsc_url . 'js/scsc-code.js';
		$plugin_array['scsc_precode'] = $this->scsc_url . 'js/scsc-precode.js';
		return $plugin_array;
	}

	/**
	 * Convert to HTML entities
	 * @param  str    $content Text content of the shortcode
	 * @return str             Text content of the shortcode, converted to HTML entities
	 */
	private function clean_code_content ( $content ) {
		return htmlentities( trim( $content ), ENT_NOQUOTES, 'UTF-8', false );
	}

	/**
	 * Prepares list of core HTML attributes and their values
	 * @param    arr    $atts List of shortcode attributes
	 * @return   str          String of HTML attributes with values
	 */
	private function set_html_attributes( $atts ) {
		$html_attributes = '';

		if ( !$atts ) {
			return $html_attributes;
		}

		foreach ( $atts as $att => $val ) {
			if ( in_array( $att, $this->core_attributes ) ) {
				$html_attributes .= ' ' . $att . '="' . $val . '"';
			} 
		}

		return $html_attributes;
	}
} // end class
new shortcodeshortcodes();