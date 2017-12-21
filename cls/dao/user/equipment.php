<?php
/**
 * 装备附加属性
 * @author zhoupengqian01
 */
class cls_dao_user_equipment extends sys_daoabs 
{
	const TABLE_NAME = 'user_equipment';
	const MEMCACHE_KEY_PREFIX = '_user_equipment';
	
	public function __construct()
	{
		parent::__construct();
		$this->daoHelper = new sys_daohelper(null,self::TABLE_NAME);
	}
	
	//获取身上的装备
	public function getEquipment($uid)
	{
		$equipmentList = $this->daoHelper->fetchAll('uid=:uid and isPutOn=:isPutOn',array('uid'=>$uid,'isPutOn'=>1));	
		foreach($equipmentList as $k=>$equipmentInfo)
		{
			if(!empty($equipmentInfo['addPropertys']))
			{
				$equipmentList[$k]['addPropertys'] = json_decode($equipmentInfo['addPropertys'],true);
			}
		}
		return $equipmentList;
	}
	
	public function getUnPutOnEquipment($uid)
	{
		$equipmentList = $this->daoHelper->fetchAll('uid=:uid and isPutOn=:isPutOn',array('uid'=>$uid,'isPutOn'=>0));	
		foreach($equipmentList as $k=>$equipmentInfo)
		{
			if(!empty($equipmentInfo['addPropertys']))
			{
				$equipmentList[$k]['addPropertys'] = json_decode($equipmentInfo['addPropertys'],true);
			}
		}
		return $equipmentList;
	}
	
	public function getEquipmentById($uid,$equipmentId)
	{
		$equipmentInfo = $this->daoHelper->fetchSingle("uid=:uid and equipmentId=:equipmentId",array('uid'=>$uid,'equipmentId'=>$equipmentId));	
		if(!empty($equipmentInfo['addPropertys']))
		{
			$equipmentInfo['addPropertys'] = json_decode($equipmentInfo['addPropertys'],true);
		}
		return $equipmentInfo;	
	}
	
	public function updateEquipment($uid,$equipmentId,$updateInfo)
	{
		$fields = array_keys($updateInfo);
		if(isset($updateInfo['addPropertys']))
		{
			$updateInfo['addPropertys'] = json_encode($updateInfo['addPropertys']);
		}
		return $this->daoHelper->update($fields, $updateInfo, "uid={$uid} and equipmentId={$equipmentId}");
	}
	
	public function addEquipment($uid,$equipmentInfo)
	{
		$fields = array_keys($equipmentInfo);
		if(isset($equipmentInfo['addPropertys']))
		{
			$equipmentInfo['addPropertys'] = json_encode($equipmentInfo['addPropertys']);
		}
		return $this->daoHelper->add($fields, $equipmentInfo);
	}
	
	public function deleteEquipment($uid,$equipmentId)
	{
		return $this->daoHelper->remove("uid=:uid and equipmentId=:equipmentId",array('uid'=>$uid,'equipmentId'=>$equipmentId));
	}
}

