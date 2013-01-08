<?php
/*
Plugin Name: Short Code Shortcodes
Plugin URI: http://joostkiens.com
Description: Adds 2 simple shortcodes for rendering blocklevel [precode] and inline code [code]. converts code to html entites and disables WP's autoformatting. <a href="http://joostkiens.com/">Usage</a>.
The code itself gets converted to utf-8 html entities (not necessary to type &lt;, etc.)
Quotes won't get converted by the WordPress texturizer, so users can copy your code directly.
Version: 0.1
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
	const name = 'short code shortcodes';
	const slug = 'short_code_shortcodes';

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
	}

	/**
	 * Runs when the plugin is initialized
	 * @return void
	 */
	function init_short_code_shortcodes() {

		// Allow translations
		load_plugin_textdomain( self::slug, false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

		// Register the shortcode [code]
		add_shortcode( 'code', array( &$this, 'render_code_shortcode' ) );
		add_shortcode( 'precode', array( &$this, 'render_precode_shortcode' ) );

		// Excludes shortcodes from running wptexturize
		add_filter( 'no_texturize_shortcodes', array( &$this, 'no_texturized_shortcodes_filter' ) );
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
	 * Convert to HTML entities
	 * @param  str    $content Text content of the shortcode
	 * @return str             Text content of the shortcode, converted to HTML entities
	 */
	private function clean_code_content ( $content ) {
		return htmlentities( $content, ENT_NOQUOTES, "UTF-8", false );
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