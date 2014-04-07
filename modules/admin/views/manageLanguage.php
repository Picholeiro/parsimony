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
 * @package admin
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
?>

<div class="content">
	<div id="conf_box_title"><?php echo t('Current Language') ?></div>
	<select onchange="parent.ParsimonyAdmin.changeLocale(this.value)" id="languages" style="width:300px;max-width:350px">
		<?php
		foreach (\request::$locales AS $code => $locale) {
			if (isset($_COOKIE['locale']) && isset(\request::$locales[$_COOKIE['locale']]) && $_COOKIE['locale'] == $code)
				echo '<option value="' . $code . '" selected="selected">' . $locale . '</option>';
			else
				echo '<option value="' . $code . '">' . $locale . '</option>';
		}
		?>
	</select>
</div>