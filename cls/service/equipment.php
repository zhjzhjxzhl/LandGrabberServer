<?php
/**
 * 
 * @author zhoupengqian01
 */
class cls_service_equipment extends sys_serviceabs 
{
	
	public function __construct()
	{
		parent::__construct();
	}

	//获取身上的装备
	public function getEquipment($uid)
	{
		$equipmentDao = $this->daoLocator->getDao("user_equipment");
		$equipmentInfo = $equipmentDao->getEquipment($uid);
	
		return $equipmentInfo;
	}
	
	//获取背包里的装备
	public function getPackageEquipment($uid)
	{
		$equipmentDao = $this->daoLocator->getDao("user_equipment");
		$equipmentInfo = $equipmentDao->getUnPutOnEquipment($uid);
	
		return $equipmentInfo;
	}
	
	public function getEquipmentConf()
	{
		$equipmentDao = $this->daoLocator->getDao("config_equipment");
		$equipmentList = $equipmentDao->getAllEquipment();
	
		return $equipmentList;
	}
	
	public function getEquipmentById($uid,$equipmentId)
	{
		$equipmentDao = $this->daoLocator->getDao("user_equipment");
		$equipmentInfo = $equipmentDao->getEquipmentById($uid,$equipmentId);
	
		return $equipmentInfo;
	}
	
	public function addEquipmemnt($uid,$equipmentInfo)
	{
		$equipmentList = $this->getEquipmentConf();
		$equipmentId = $equipmentInfo['equipmentId'];
		$position = $equipmentList[$equipmentId]['position'];
		
		$equipmentDao = $this->daoLocator->getDao("user_equipment");
		$equipmentList = $equipmentDao->getEquipment($uid);
		foreach($equipmentList as $equipment)
		{
			if($equipment['position']==$position)
			{
				return sys_control::makeResult(0,array(),"此位置已经有装备了");
			}
		}
		
		$equipmentInfo['uid'] = $uid;
		$equipmentInfo['position'] = $position;
		$equipmentDao->addEquipment($uid,$equipmentInfo);
		
		return sys_control::makeResult(1);
	}
	
	/**
	 * 卸载
	 */
	public function unloadEquiment($uid,$equipmentId)
	{
		$userEquipmentDao = $this->daoLocator->getDao("user_equipment");
		$equipmentInfo = $userEquipmentDao->getEquipmentById($uid,$equipmentId);
		if(!$equipmentInfo)
		{
			return sys_control::makeResult(0,array(),"身上无此装备");
		}
		
		$ret = $userEquipmentDao->deleteEquipment($uid,$equipmentId);
		
		$storageService = $this->serviceLocator->getService("storage");
		return $storageService->addEquipment($uid,$equipmentInfo);
	}
	
	/**
	 * 安装
	 */
	public function loadEquipment($uid,$cell_id)
	{
		$userStorageDao = $this->daoLocator->getDao("user_storage");
		$stroageInfo = $userStorageDao->getStorage($uid);
		if(empty($stroageInfo['cell_data'][$cell_id]))
		{
			return sys_control::makeResult(0,"没有此格子或此格子是空的");
		}
		$cell_info = $stroageInfo['cell_data'][$cell_id];
		if($cell_info['type']!='equipment')
		{
			return sys_control::makeResult(0,"不是装备");
		}
		$equipmentInfo = $cell_info['data'];
		
		$stroageInfo['cell_data'][$cell_id]=array();
		$userStorageDao->updateStorage($uid,array(
			'cell_data'=>$stroageInfo['cell_data'],
		));
		
		return $this->addEquipmemnt($uid, $equipmentInfo);
	}
	
	//穿上装备
	public function dressEquipment($uid,$equipmentId)
	{
		$userEquipmentDao = $this->daoLocator->getDao('user_equipment');
		$equipmentInfo = $userEquipmentDao->getEquipmentById($uid,$equipmentId);
		
		$userInfo = $this->daoLocator->getDao('user')->getUser($uid);
		$equipmentConfig = $this->daoLocator->getDao('config_equipment')->getAllEquipment();
		if($userInfo['level']<$equipmentConfig[$equipmentInfo['equipmentType']]['equipment_level'])
		{
			return sys_control::makeReturn(0,array(),'人物级别小于装备所需级别');
		}
		
		if(!$equipmentInfo || ($equipmentInfo['isPutOn']!= 0))
		{
			return sys_control::makeResult(0,array(),'背包无此装备或者已经装上');
		}
		return $userEquipmentDao->updateEquipment($uid,$equipmentId,array('isPutOn'=>1));
	}
	
	//脱下装备
	public function unDressEquipment($uid,$equipmentId)
	{
		$userEquipmentDao = $this->daoLocator->getDao('user_equipment');
		$equipmentInfo = $userEquipmentDao->getEquipmentById($uid,$equipmentId);
		if(!$equipmentInfo || ($equipmentInfo['isPutOn']!= 1))
		{
			return sys_control::makeResult(0,array(),'身上无此装备');
		}
		return $userEquipmentDao->updateEquipment($uid,$equipmentId,array('isPutOn'=>0));
		
	}
	
	/**
	 * 强化
	 * @param unknown_type $uid
	 * @param unknown_type $equipmentId
	 * @param unknown_type $stoneLevel
	 * @param unknown_type $stoneNum
	 */
	public function strengthen($uid,$equipmentId,$stoneLevel,$stoneNum)
	{
		$equipmentDao = $this->daoLocator->getDao("user_equipment");
		
//		//检查强化石的级别和数量
//		
//		//强化级别增加
		$equipmentInfo = array();
		$equipmentList = $equipmentDao->getEquipment($uid);
		foreach ($equipmentList as $v){
			if($v['equipmentId']==$equipmentId){
				$equipmentInfo = $v;
				break;
			}
		}
		if(!$equipmentInfo){
			return sys_control::makeReturn(0,array(),"没有此装备");
		}
		
		
		$equipmentStrengthenLevel = $equipmentInfo['equipmentStrengthenLevel']++;
		$equipmentDao->updateEquipment(array('equipmentStrengthenLevel'=>$equipmentStrengthenLevel));
	
		return sys_control::makeReturn(1, array('newEquipment'=>$equipmentInfo));
	}
	
	/**
	 * 
	 * 洗练
	 */
	public function baptize()
	{
		
	}
	
}

