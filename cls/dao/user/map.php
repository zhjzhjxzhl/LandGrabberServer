<?php
/**
 * 例子，用来测试框架
 * @author zhoupengqian01
 */
class cls_dao_user_map extends sys_daoabs 
{
	const TABLE_NAME = 'user_map';
	const MEMCACHE_KEY_PREFIX = '_user_map';
	
	public function __construct()
	{
		parent::__construct();
		$this->daoHelper = new sys_daohelper(null,self::TABLE_NAME);
	}
	
	public function getMap($uid)
	{
		return $this->daoHelper->fetchAll('uid=:uid',array('uid'=>$uid));	
	}
	
	public function getMapByIndex($uid,$bigAreaIndex,$smallAreaIndex)
	{
		$params = array(
				'uid'=>$uid,
				'bigAreaIndex'=>$bigAreaIndex,
				'smallAreaIndex'=>$smallAreaIndex
				);
		return $this->daoHelper->fetchAll('uid=:uid and bigAreaIndex=:bigAreaIndex and smallAreaIndex=:smallAreaIndex',$params);	
	}
	
	//获取一条记录。
	public function getUserOneRecode($uid,$bigAreaIndex,$smallAreaIndex)
	{
		return $this->daoHelper->fetchSingle('uid=:uid and bigAreaIndex=:bigAreaIndex and smallAreaIndex=:smallAreaIndex',array('uid'=>$uid,'bigAreaIndex'=>$bigAreaIndex,'smallAreaIndex'=>$smallAreaIndex));
	}

	
	public function insertOneRecode($info)
	{
		$fields = array_keys($info);
		return $this->daoHelper->add($fields,$info);
	}

	public function addMap($uid,$mapInfo)
	{
		$fields = array_keys($mapInfo);
		return $this->daoHelper->add($fields, $mapInfo);	
	}
	
	public function updateTime($uid,$mapInfo,$bigAreaIndex,$smallAreaIndex)
	{
		$fields = array_keys($mapInfo);
		return $this->daoHelper->update($fields, $mapInfo, "uid=$uid and bigAreaIndex=$bigAreaIndex and smallAreaIndex=$smallAreaIndex");
	}
}

