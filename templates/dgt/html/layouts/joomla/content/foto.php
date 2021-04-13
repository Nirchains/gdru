<?php 
defined('_JEXEC') or die;

// Create a shortcut for params.
$params = $displayData->params;

	if ($params->get('link_titles') && ($params->get('access-view') || $params->get('show_noauth', '0') == '1')) : ?>
		<a href="<?php echo JRoute::_(
			ContentHelperRoute::getArticleRoute($displayData->slug, $displayData->catid, $displayData->language)
		); ?>" itemprop="url">
			<?php 
			$photourl = $displayData->jcfields["15"]->rawvalue;
			if ($photourl == '') {
				$photourl = "/images/miembros/no-photo.png";
			}
			echo '<img src="'. $photourl .'" alt="'. $this->escape($displayData->title) .'" class="foto-miembro-listado">';
			
			?>
		</a>
<?php endif; ?>
