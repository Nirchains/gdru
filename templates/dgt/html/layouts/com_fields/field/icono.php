<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_fields
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

if (!key_exists('field', $displayData))
{
	return;
}

$field = $displayData['field'];
$label = JText::_($field->label);
$value = $field->rawvalue;
$showLabel = $field->params->get('showlabel');

if ($value == '')
{
	return;
}

?>
<a href="<?php echo $value; ?>" class="btn btn-primary" target="_blank" title="<?php echo $value; ?>"><?php echo $label;?></a>
