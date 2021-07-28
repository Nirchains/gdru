<?php
/**
 * Bootstrap Details Template
 *
 * @package     Joomla
 * @subpackage  Fabrik
 * @copyright   Copyright (C) 2005-2020  Media A-Team, Inc. - All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @since       3.1
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

$element = $this->element;?>
<div class=" <?php echo $element->containerClass .' '. $element->span; ?>">
	<div class="fabrikLabel">
		<h5><?php echo $element->label_raw;?></h5>
	</div>

	<?php if ($this->tipLocation == 'above') : ?>
		<h4><span class=""><?php echo $element->tipAbove ?></span></h4>
	<?php endif ?>

	<div class="fabrikElement">
		<?php echo $element->element;?>
	</div>

	<?php if ($this->tipLocation == 'side') : ?>
		<span class=""><?php echo $element->tipSide ?></span>
	<?php endif ?>

	<?php if ($this->tipLocation == 'below') :?>
		<span class=""><?php echo $element->tipBelow ?></span>
	<?php endif ?>
</div><!-- end control-group -->


