<?php
/**
 * @author zhoupengqian01
 */
class cls_ctrl_storage extends cls_ctrl_base 
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getStorage()
	{
		$storageService = $this->serviceLocator->getService("storage");
		$storageInfo = $storageService->getStorage($this->uid);
	
		return sys_control::makeReturn(1,array('storageInfo'=>$storageInfo),"");
	}
	
	public function getStorages()
	{
		$equipmentService = $this->serviceLocator->getService('equipment');
		$bagEquipments = $equipmentService->getPackageEquipment($this->uid);
		
		$productService = $this->serviceLocator->getService('product');
		$products = $productService->getProducts($this->uid);
		
		return sys_control::makeReturn(1,array('uid'=>$this->uid,'equipments'=>$bagEquipments,'products'=>$products),"");
	}
	
	public function openCell()
	{
		$storageService = $this->serviceLocator->getService("storage");
		$result = $storageService->openCell($this->uid);
	
		return new cls_view_json($result);
	}
}

