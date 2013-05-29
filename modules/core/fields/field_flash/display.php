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
 * @copyright  Julien Gras et Benoît Lorillot
 * @version  Release: 1.0
 * @category  Parsimony
 * @package core/fields
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 ?>

<object type="application/x-shockwave-flash" data="<?php echo BASE_PATH ?><?php echo PROFILE_PATH.$this->module.'/'.$this->path.'/'.$this; ?>" height="400" width="600">
  <param name="play" value="true">
  <param name="movie" value="<?php echo BASE_PATH ?><?php echo PROFILE_PATH.$this->module.'/'.$this->path.'/'.s($this->value); ?>">
  <param name="menu" value="false">
  <param name="quality" value="high">
  <param name="scalemode" value="noborder">
</object>