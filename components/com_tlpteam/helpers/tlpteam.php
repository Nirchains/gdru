<?php
/**
 * @version     1.0.0
 * @package     com_tlpteam
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      TechLabPro <techlabpro@gmail.com> - http://www.techlabpro.com
 */
defined('_JEXEC') or die;

class TlpTeamHelper {
	
	public function config()
	{
		$db =& JFactory::getDBO();
		$sql = 'SELECT * FROM #__tlpteam_settings WHERE id = 1';
		$db->setQuery($sql);
		$config = $db->loadObject(); 

		return $config;
	}
    
}
