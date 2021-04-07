<?php
/**
 * @version     1.0.0
 * @package     com_tlpteam
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      TechLabPro <techlabpro@gmail.com> - http://www.techlabpro.com
 */
// no direct access
defined('_JEXEC') or die;
JHtml::_('bootstrap.framework');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
//JHtml::_('bootstrap.tooltip');
//JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canCreate = $user->authorise('core.create', 'com_tlpteam');
$canEdit = $user->authorise('core.edit', 'com_tlpteam');
$canCheckin = $user->authorise('core.manage', 'com_tlpteam');
$canChange = $user->authorise('core.edit.state', 'com_tlpteam');
$canDelete = $user->authorise('core.delete', 'com_tlpteam');

$setting = TlpteamHelper::config();
$image_storiage_path = $setting->imagepath.'/';
$display_no=$setting->display_no;
$grid=(12/$display_no);
?>

<form action="<?php echo JRoute::_('index.php?option=com_tlpteam&view=teams'); ?>" method="post" name="adminForm" id="adminForm">

    <?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
   <?php if (isset($this->items[0]->state)): ?>
        <?php echo JHtml::_('grid.sort', '', 'a.state', $listDirn, $listOrder); ?>
     <?php endif; ?>
     <div class="tlp-team-list clearfix">

     <?php
    $i=0;
	foreach ($this->items as $i => $item) : $i++;

	if($i%$display_no == 1){echo '<div class="row-fluid">'; }
	//echo 'i='.$i%3;
	?>

        <div class="span<?php echo $grid;?> tlp-each-item">
        <?php
		if (!empty($item->profile_image)){ ?>
			<a href="<?php echo JRoute::_('index.php?option=com_tlpteam&view=team&id='.(int) $item->id); ?>"><img src="<?php echo JURI::root().$image_storiage_path.'l_'.$item->profile_image;?>" /></a>
			<?php
		}else{ ?>
        	<img src="<?php echo JURI::root().$image_storiage_path?>noimage.jpg" alt="noimage" />
        <?php }?>
  <div class="tlp-team-name"><h3><a href="<?php echo JRoute::_('index.php?option=com_tlpteam&view=team&id='.(int) $item->id); ?>">
				<?php echo $this->escape($item->name); ?></a></h3></div>
         <div class="tlp-team-position"><h4><?php echo $item->position; ?></h4></div>
         <div class="tlp-team-short-bio-com"><?php echo $item->short_bio; ?></div>
         <div class="tlp-team-social">
                 <ul>
                   <?php if($item->facebook!=''){?><li ><a class="tlp-facebook-icon"  href="<?php echo $item->facebook;?>" target="_new">facebook </a></li><?php }?>
                   <?php if($item->twitter!=''){?><li ><a  class="tlp-twitter-icon" href="<?php echo $item->twitter;?>" target="_new">twitter</a></li><?php }?>
                   <?php if($item->googleplus!=''){?><li ><a  class="tlp-googleplus-icon" href="<?php echo $item->googleplus;?>" target="_new">googleplus </a></li><?php }?>
                   <?php if($item->linkedin!=''){?><li ><a  class="tlp-linkedin-icon" href="<?php echo $item->linkedin;?>" target="_new">linkedin</a></li><?php }?>
                 </ul>

               </div>
        </div>
     <?php if($i%$display_no == 0){ echo '</div>';} ?>
     <?php endforeach; ?>
    </div>
    <?php echo $this->pagination->getListFooter(); ?>



    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
    <?php echo JHtml::_('form.token'); ?>
</form>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
