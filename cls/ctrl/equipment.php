<?php
/**
 * @author zhoupengqian01
 */
class cls_ctrl_equipment extends cls_ctrl_base 
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getEquipmentInfo()
	{
		$equipmentService = $this->serviceLocator->getService("equipment");
		$equipmentInfo = $equipmentService->getEquipment($this->uid);
		
		return $this->makeReturn(1, array("equipments"=>$equipmentInfo));
	}
	
	public function loadEquipment()
	{
		$cell_id = isset($_GET['cell_id'])?(int)$_GET['cell_id']:0;
		
		$equipmentService = $this->serviceLocator->getService("equipment");
		$result = $equipmentService->loadEquipment($this->uid,$cell_id);
	
		return new cls_view_json($result);
	}
	
	public function unloadEquipment()
	{
		$equipmentId = isset($_GET['equipmentId'])?(int)$_GET['equipmentId']:0;
		
		$equipmentService = $this->serviceLocator->getService("equipment");
		$result = $equipmentService->unloadEquiment($this->uid,$equipmentId);
	
		return new cls_view_json($result);
	}
	
	public function dressEquipment()
	{
		$equipmentId = isset($_GET['equipmentId'])?(int)$_GET['equipmentId']:0;
		$equipmentService = $this->serviceLocator->getService("equipment");
		$result = $equipmentService->dressEquipment($this->uid,$equipmentId);
		
		if(is_numeric($result))
		{
			return $this->makeReturn($result,array());
		}else
		{
			return $result;
		}
	}
	
	public function undressEquipment()
	{
		$equipmentId = isset($_GET['equipmentId'])?(int)$_GET['equipmentId']:0;
		$equipmentService = $this->serviceLocator->getService("equipment");
		$result = $equipmentService->unDressEquipment($this->uid,$equipmentId);
		
		if(is_numeric($result))
		{
			return $this->makeReturn($result,array());
		}else
		{
			return $result;
		}
	}
	
	public function strengthen()
	{
	}
	
}

