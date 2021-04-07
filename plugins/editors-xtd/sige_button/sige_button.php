<?php
/**
 * @Copyright
 * @package    Editor Button - SIGE Parameter Button - Editor Plugin for Joomla! 3
 * @author     Viktor Vogel <admin@kubik-rubik.de>
 * @version    3.2.0 - 2016-12-31
 * @link       https://joomla-extensions.kubik-rubik.de/sige-simple-image-gallery-extended
 *
 * @license    GNU/GPL
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
defined('_JEXEC') or die('Restricted access');

class PlgButtonSige_Button extends JPlugin
{
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}

	public function onDisplay($name)
	{
		JPlugin::loadLanguage('plg_editors-xtd_sige_button', JPATH_ADMINISTRATOR);
		$method = $this->params->get('method', 0);

		if($method == 0)
		{
			$params = array();

			$params_bool = array('displaynavtip', 'displayarticle', 'thumbs', 'limit', 'noslim', 'root', 'ratio', 'caption', 'iptc', 'iptcutf8', 'print', 'single_gallery', 'download', 'list', 'crop', 'watermark', 'image_info', 'image_link_new', 'css_image', 'css_image_half', 'copyright', 'calcmaxthumbsize', 'fileinfo', 'resize_images', 'ratio_image', 'images_new');

			foreach($params_bool as $param_bool)
			{
				$this->setParamsBoolean($params, $param_bool);
			}

			$params_value = array('width', 'height', 'gap_v', 'gap_h', 'quality', 'quality_png', 'limit_quantity', 'sort', 'single', 'scaption', 'connect', 'crop_factor', 'thumbdetail', 'watermarkposition', 'watermarkimage', 'encrypt', 'image_link', 'column_quantity', 'word', 'width_image', 'height_image');

			foreach($params_value as $param_value)
			{
				$this->setParamsValue($params, $param_value);
			}

			$params_special = array('salign', 'turbo');

			foreach($params_special as $param_special)
			{
				$this->setParamsSpecial($params, $param_special);
			}

			sort($params);
			$this->getImageFolder($params);
			$params = '{gallery}'.implode(",", $params).'{/gallery}';

			$getContent = $this->_subject->getContent($name);
			$js = "function sige_button(editor) {var content = $getContent; jInsertEditorText('$params', editor);}";
			JFactory::getDocument()->addScriptDeclaration($js);

			$button = new JObject();
			$button->set('modal', false);
			$button->set('class', 'btn');
			$button->set('onclick', 'sige_button(\''.$name.'\');return false;');
			$button->set('text', JText::_('PLG_SIGE_BUTTON_SIGEBUTTONTEXT'));
			$button->set('name', 'camera');
			$button->set('link', '#');

			return $button;
		}

		$lang = JFactory::getLanguage();
		$folder_input = $this->params->get('folder_input');
		$read_in_folder = $this->params->get('read_in_folder');
		$token = md5($this->params->get('token'));
		$link = '';

		if(JFactory::getApplication()->isAdmin())
		{
			$link .= '../';
		}

		$link .= 'plugins/editors-xtd/sige_button/sige_button.html.php?lang='.$lang->getTag().'&amp;e_name='.$name.'&amp;folder_input='.$folder_input.'&amp;read_in_folder='.$read_in_folder.'&amp;token='.$token;

		$button = new JObject();
		$button->set('modal', true);
		$button->set('class', 'btn');
		$button->set('link', $link);
		$button->set('text', JText::_('PLG_SIGE_BUTTON_SIGEBUTTONTEXT'));
		$button->set('name', 'camera');

		$height_modal = 550;

		if($method == 2)
		{
			$height_modal = 150;
		}

		$button->set('options', "{handler: 'iframe', size: {x: 600, y: ".$height_modal."}}");

		return $button;
	}

	/**
	 * Sets the boolean parameters
	 *
	 * @param $params
	 * @param $param_bool
	 */
	private function setParamsBoolean(&$params, $param_bool)
	{
		$value = $this->params->get($param_bool, false);

		if(!empty($value))
		{
			if($value == 1)
			{
				$params[] = $param_bool.'=1';
			}
			elseif($value == 2)
			{
				$params[] = $param_bool.'=0';
			}
		}
	}

	/**
	 * Sets the value parameters
	 *
	 * @param $params
	 * @param $param_value
	 */
	private function setParamsValue(&$params, $param_value)
	{
		$value = $this->params->get($param_value, false);

		if(!empty($value))
		{
			$params[] = $param_value.'='.$value;
		}
	}

	/**
	 * Sets the special parameters
	 *
	 * @param $params
	 * @param $param_special
	 */
	private function setParamsSpecial(&$params, $param_special)
	{
		$value = $this->params->get($param_special, false);

		if($param_special == 'salign')
		{
			if($value == 1)
			{
				$params[] = $param_special.'=left';
			}
			elseif($value == 2)
			{
				$params[] = $param_special.'=right';
			}
			elseif($value == 3)
			{
				$params[] = $param_special.'=center';
			}

			return;
		}

		if($param_special == 'turbo')
		{
			if($value == 1)
			{
				$params[] = $param_special.'=1';
			}
			elseif($value == 2)
			{
				$params[] = $param_special.'=0';
			}
			elseif($value == 3)
			{
				$params[] = $param_special.'=new';
			}

			return;
		}
	}

	/**
	 * Gets the image folder path or set a default name from the language file
	 *
	 * @param $params
	 */
	private function getImageFolder(&$params)
	{
		$read_in_folder = $this->params->get('read_in_folder');

		if(!empty($read_in_folder))
		{
			$root = $this->params->get('root');

			if($root != 1)
			{
				if(stripos($read_in_folder, 'images/') === 0)
				{
					$read_in_folder = substr($read_in_folder, 7);
				}
			}

			array_unshift($params, $read_in_folder);

			return;
		}

		array_unshift($params, JText::_('PLG_SIGE_BUTTON_FOLDER'));
	}
}
