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
 * @package core/blocks
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace core\blocks;

/**
 * @title Query
 * @description is a point-and-click interface to build and display SQL Queries
 * @version 1
 * @browsers all
 * @php_version_min 5.3
 * @block_category database
 * @modules_dependencies core:1
 */

class query extends \block {

    protected $pathOfViewFile;

    public function __construct($id) {
        parent::__construct($id);
        $this->setConfig('regenerateview', 0);
        if (isset($_POST['stop_typecont']) && $_POST['stop_typecont'] == 'page') {
            $pathOfView = MODULE . '/views/' . THEMETYPE;
        } else {
            $pathOfView = THEMEMODULE . '/views/' . THEMETYPE;
        }
        $this->setConfig('pathOfViewFile', $pathOfView . '/' . $this->id . '.php');
    }

    public function saveConfigs() {

        $pathOfViewFile = PROFILE_PATH . $this->getConfig('pathOfViewFile');

        $this->setConfig('selected', $_POST['properties']);
        $this->setConfig('pagination', $_POST['pagination']);
        $this->setConfig('filter', $_POST['filter']);
        $this->setConfig('sort', $_POST['sort']);
        $this->setConfig('regenerateview', $_POST['regenerateview']);
        $this->setConfig('nbitem', $_POST['nbitem']);
        if(isset($_POST['tables'])) $this->setConfig('tables', $_POST['tables']);

        $myView = new \view();
        if (isset($_POST['relations']))
            $myView = $myView->initFromArray($_POST['properties'], $_POST['relations']);
        else
            $myView = $myView->initFromArray($_POST['properties']);
        if ($this->getConfig('pagination'))
            $myView->setPagination(TRUE);
        if ($this->getConfig('nbitem') != '')
            $myView->limit($this->getConfig('nbitem'));
        $myView->buildQuery();
        $this->setConfig('view', $myView);
        
        /* Test for errors in view and save */
        \app::addListener('error', array($this, 'catchError'));
        /* Test if new file contains errors */
        $testIfHasError = \tools::testSyntaxError($_POST['editor'], array('_this' => $this, 'view' => $myView));
        /* If new file contains errors */
        if ($testIfHasError === TRUE){
            /* If there's no errors, Save new file */
            if ($this->getConfig('regenerateview') == 1) {
                \tools::file_put_contents($pathOfViewFile, $this->generateViewAction($_POST['properties']));
            } else {
                \tools::file_put_contents($pathOfViewFile, $_POST['editor']);
            }
        }
    }

    public function generateViewAction($properties, $pagination = '', $filter = '', $sort = '') {
        $view_code = '';
        if($this->getConfig('filter') || $this->getConfig('sort') || ($filter == 1)  || ($sort == 1)) $view_code .= '<?php echo $this->getFilters(); ?>' . PHP_EOL.PHP_EOL;
        $view_code .= '<?php if (!$view->isEmpty()) : ?>' . PHP_EOL;
	$view_code .= "\t" . '<?php foreach ($view as $line) : ?>' . PHP_EOL;
        $view_code .= "\t\t" . '<div class="itemscope">' . PHP_EOL;
        $myView = new \view();
        if (!empty($properties)) {
            $myView = $myView->initFromArray($properties);
            foreach ($myView->getFields() AS $sqlName => $field) {
                if (substr($sqlName, 0, 3) !== 'id_')
                    $displayLine = '()';
                else
                    $displayLine = '';
                $view_code .= "\t\t\t" . '<div class="itemprop ' . $sqlName . '"><?php echo $line->' . $sqlName . $displayLine . '; ?></div>' . PHP_EOL;
            }
        } else {
            $view_code .= "\t\t\t<?php //You have to create your query before ?>".PHP_EOL;
        }
        $view_code .= "\t\t" . '</div>' . PHP_EOL;
        $view_code .= "\t" . '<?php endforeach; ?>'.PHP_EOL;
	$view_code .= '<?php else: ?>'.PHP_EOL;
	$view_code .= "\t" . '<div class="noResults"><?php echo t(\'No results\'); ?></div>'.PHP_EOL;
	$view_code .= '<?php endif; ?>'.PHP_EOL;
        if($this->getConfig('pagination') || ($pagination == 1)) $view_code .= PHP_EOL.PHP_EOL.'<?php echo $view->getPagination(); ?>' . PHP_EOL;
        return $view_code;
    }

    public function catchError($code, $file, $line, $message) {
        $mess = $message.' '.t('in line').' '. $line ;
        if($code == 0 || $code == 2 || $code == 8 || $code == 256 || $code == 512 || $code == 1024 || $code == 2048 || $code == 4096 || $code == 8192 || $code == 16384){
            /* If it's a low level error, we save but we notice the dev */
            if ($this->getConfig('regenerateview') == 1) {
                \tools::file_put_contents(PROFILE_PATH . $this->getConfig('pathOfViewFile'), $this->generateViewAction($_POST['properties']));
            } else {
                \tools::file_put_contents(PROFILE_PATH . $this->getConfig('pathOfViewFile'), $_POST['editor']);
            }
            $return = array('eval' => '$("#' . $this->getId() . '",ParsimonyAdmin.currentBody).html("' . $mess . '");', 'notification' => t('Saved but', FALSE) . ' : ' . $mess, 'notificationType' => 'normal');
        }else{
            $return = array('eval' => '$("#' . $this->getId() . '",ParsimonyAdmin.currentBody).html("' . $mess . '");', 'notification' => t('Error', FALSE) . ' : ' . $mess, 'notificationType' => 'negative');
        }
        if (ob_get_level()) ob_clean();
	echo json_encode($return);
        exit;
    }
    
    public function getView() {
	ob_start();
	\app::addListener('beforeBuildQuery', array($this, 'process'));
        $view = $this->getConfig('view');
        if ($view != FALSE) {
            include($this->getConfig('pathOfViewFile'));
        } else {
            echo t('Please check the query configuration');
        }
	return ob_get_clean();
    }


    public function getFilters() {
        $view = $this->getConfig('view');
        $selected = $this->getConfig('selected');
        if (is_object($view)) {
            $filter = $this->getConfig('filter');
            $sort = $this->getConfig('sort');
            if ($filter || $sort) {
                ?>
                <form method="POST" action="" class="filter sort">
                    <?php
                    if ($filter) {
                        foreach ($view->getFields() AS $field) {
                            $name = $field->module . '_' . $field->entity . '_' . $field->name; 
                            if(isset($selected[$name]['filter']) && $selected[$name]['filter']) echo $field->displayFilter();
                        }
                    }
                    if ($sort) {
                        ?>
                        <select name="tri"><option></option>
                            <?php
                            foreach ($view->getFields() AS $field) {
                                $name = $field->module . '_' . $field->entity . '_' . $field->name; 
                                if(isset($selected[$name]['sort']) && $selected[$name]['sort']){
                                    ?>
                                    <option value="<?php echo $field->name ?>_asc" <?php if (isset($_POST['tri']) && $_POST['tri'] == $field->name . '_asc') echo ' selected="selected"' ?>><?php echo $field->label ?> ASC</option>
                                    <option value="<?php echo $field->name ?>_desc" <?php if (isset($_POST['tri']) && $_POST['tri'] == $field->name . '_desc') echo ' selected="selected"' ?>><?php echo $field->label ?> DESC</option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    <?php } ?>
                    <input type="submit">
                </form>
                <?php
            }
        }
    }

    public function process() {
        $view = $this->getConfig('view');
        if (is_object($view)) {
            $filter = $this->getConfig('filter');
            $sort = $this->getConfig('sort');
            if ($filter || $sort) {
                foreach ($view->getFields() AS $field) {
                    if ($filter && isset($_POST['filter'][$field->name]) && !empty($_POST['filter'][$field->name])) {
                        $view->where($field->module . '_' . $field->entity . '.' . $field->name . ' '.$field->sqlFilter($_POST['filter'][$field->name]));
                    }
                    if ($sort && isset($_POST['tri']) && !empty($_POST['tri'])) {
                        $cut = strrpos($_POST['tri'], '_');
                        $sort = substr($_POST['tri'], $cut + 1);
                        if ($sort == 'asc' || $sort == 'desc')
                            $view->order($field->module . '_' . $field->entity . '.' . substr($_POST['tri'], 0, $cut), $sort);
                    }
                }
            }
        }
    }
    
    public function destruct() {
        $path = PROFILE_PATH . $this->getConfig('pathOfViewFile');
        if(is_file($path) === TRUE){
            rename($path, $path . '.back');
        }
    }

}
?>
