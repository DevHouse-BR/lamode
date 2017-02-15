<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/LICENSE-M1.txt
 *
 * @category   AW
 * @package    AW_All
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-M1.txt
 */ 


class AW_All_Block_System_Config_Form_Fieldset_Awall_Extensions
	extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{
	protected $_dummyElement;
	protected $_fieldRenderer;
	protected $_values;

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
		$html = $this->_getHeaderHtml($element);
		$modules = array_keys((array)Mage::getConfig()->getNode('modules')->children());
		sort($modules);

        foreach ($modules as $moduleName) {
        	if (strstr($moduleName,'AW_') === false) {
        		continue;
        	}
			
			if($moduleName == 'AW_Core' || $moduleName == 'AW_All'){
				continue;
			}
			
        	$html.= $this->_getFieldHtml($element, $moduleName);
        }
        $html .= $this->_getFooterHtml($element);

        return $html;
    }

    protected function _getDummyElement()
    {
    	if (empty($this->_dummyElement)) {
    		$this->_dummyElement = new Varien_Object(array('show_in_default'=>1, 'show_in_website'=>1));
    	}
    	return $this->_dummyElement;
    }

    protected function _getFieldRenderer()
    {
    	if (empty($this->_fieldRenderer)) {
    		$this->_fieldRenderer = Mage::getBlockSingleton('adminhtml/system_config_form_field');
    	}
    	return $this->_fieldRenderer;
    }

	protected function _getFieldHtml($fieldset, $moduleName)
    {
		$configData = $this->getConfigData();
    	$path = 'advanced/modules_disable_output/'.$moduleName; //TODO: move as property of form
    	$data = isset($configData[$path]) ? $configData[$path] : array();

    	$e = $this->_getDummyElement();

		$moduleKey = substr($moduleName, strpos($moduleName,'_')+1);
		$ver = (Mage::getConfig()->getModuleConfig($moduleName)->version);
		$id = $moduleName;
		
		$hasUpdate = false;
		if($displayNames = Mage::app()->loadCache('aw_all_extensions_feed')){
			if($displayNames = unserialize($displayNames)){
				if(isset($displayNames[$moduleName])){
					$url = @$displayNames[$moduleName]['url'];
					$name = @$displayNames[$moduleName]['display_name'];
					$version = (float)@$displayNames[$moduleName]['version'];
				
					$moduleName = '<a href="'.$url.'" target="_blank" title="'.$name.'">'.$name."</a>";
					
					
					if((float)$ver < $version){
						$update = '<a href="'.$url.'" target="_blank"><img src="'.$this->getSkinUrl('aw_all/images/update.gif').'" title="'.$this->__("Update available").'"/></a>';
						$hasUpdate = 1;
						$moduleName ="$update $moduleName";
					}
				}
			}
		}
	
		if(!$hasUpdate){
			$update = '<a  target="_blank"><img src="'.$this->getSkinUrl('aw_all/images/ok.gif').'" title="'.$this->__("Installed").'"/></a>';
			$moduleName ="$update $moduleName";
		}
	
		if($ver){
			
			
			
			$field = $fieldset->addField($id, 'label',
				array(
					'name'          => 'ssssss',
					'label'         => $moduleName,
					'value'         => $ver,
					
				))->setRenderer($this->_getFieldRenderer());
			return $field->toHtml();
		}
		return '';
		
    }
}