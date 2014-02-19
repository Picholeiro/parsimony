<?php
/**
 * Parsimony
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@parsimony-cms.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Parsimony to newer
 * versions in the future. If you wish to customize Parsimony for your
 * needs please refer to http://www.parsimony.mobi for more information.
 *
 * @authors Julien Gras et Benoît Lorillot
 * @copyright Julien Gras et Benoît Lorillot
 * 
 * @category Parsimony
 * @package core/blocks
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
?>
<div class="rangefilter <?php echo $property ?>_filter">
	<label for="<?php echo $property ?>_filter_start" class="filtertitle">
		<?php echo $field->label ?>
	</label>
	<input placeholder="Min" type="text" name="filter[<?php echo $property ?>][start]" id="<?php echo $property ?>_filter_start" value="<?php if (isset($_POST['filter'][$property]['start'])) echo s($_POST['filter'][$property]['start']) ?>" />
	<input placeholder="Max" type="text" name="filter[<?php echo $property ?>][end]" id="<?php echo $property ?>_filter_end" value="<?php if (isset($_POST['filter'][$property]['end'])) echo s($_POST['filter'][$property]['end']) ?>" />
</div>