<?php  if ( ! defined('EXT')) exit('No direct script access allowed');
/**
 * Extra Head for the Control Panel
 *
 * An ExpressionEngine Extension that allows extra stuff in the cp head
 *
 * @package		ExpressionEngine
 * @author		Karl and Tim
 * @copyright	Copyright (c) 2008, Karl and Tim
 * @license		http://creativecommons.org/licenses/by-sa/3.0/
 * @link		http://example.com/
 * @since		Version 1.0
 * @filesource
 * 
 * This work is licensed under the Creative Commons Attribution-Share Alike 3.0 Unported.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/
 * or send a letter to Creative Commons, 171 Second Street, Suite 300,
 * San Francisco, California, 94105, USA.
 * 
 */
class Ks_extra_head {

	var $settings		= array();
	var $name			= 'Karl\'s Extra Head for the Control Panel';
	var $version		= '1.0';
	var $description	= 'Adds stuff to the end of the head element for control panel.';
	var $settings_exist	= 'y';
  // var $docs_url    = '';

	/**
	 * Constructor
	 */
	function Ks_extra_head($settings = '')
	{
		$this->settings = $settings;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Register hooks by adding them to the database
	 */
	function activate_extension()
	{
		global $DB;

		// default settings
		$settings =	array();
		$settings['extrahead']		= '';
		
		$hook = array(
						'extension_id'	=> '',
						'class'			=> __CLASS__,
						'method'		=> 'add_js',
						'hook'			=> 'show_full_control_panel_end',
						'settings'		=> serialize($settings),
						'priority'		=> 1,
						'version'		=> $this->version,
						'enabled'		=> 'y'
					);
	
		$DB->query($DB->insert_string('exp_extensions',	$hook));
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * No updates yet.
	 * Manual says this function is required.
	 * @param string $current currently installed version
	 */
	function update_extension($current = '')
	{
		global $DB, $EXT;

		if ($current < '1.0')
		{
			$query = $DB->query("SELECT settings FROM exp_extensions WHERE class = '".$DB->escape_str(__CLASS__)."'");
			
			$this->settings = unserialize($query->row['settings']);
			
			$DB->query($DB->update_string('exp_extensions', array('settings' => serialize($this->settings), 'version' => $this->version), array('class' => __CLASS__)));
		}
		
		return TRUE;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Uninstalls extension
	 */
	function disable_extension()
	{
		global $DB;
		$DB->query("DELETE FROM exp_extensions WHERE class = '".__CLASS__."'");
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * EE extension settings
	 * @return array
	 */
	function settings()
	{
    $settings = array();
		
    $settings['extrahead']   = array('t');
		return $settings;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Adds stuff to the head of CP pages
	 * 
	 * We add our stuff to the end of the head tag to ensure that it 
	 * is loaded after any other javascript or css. This way it can 
	 * take advantage of already-loaded libraries and it can trump 
	 * other stylesheets.
	 *
	 * @param string $html Final html of the control panel before display
	 * @return string Modified HTML
	 */
	function add_js($html)
	{
		global $EXT;
	
		$html = ($EXT->last_call !== FALSE) ? $EXT->last_call : $html;
	
		$find = '</head>';
		
		$replace = $this->settings['extrahead'] . "\n";
		$replace .= '</head>';
	
		$html = str_replace($find, $replace, $html);
	
		return $html;
	}
	
	// --------------------------------------------------------------------
	
}
// END CLASS Ks_extra_head

/* End of file ext.Ks_extra_head.php */
/* Location: ./system/extensions/ext.Ks_extra_head.php */