<?php
/**
 * 例子，用来测试框架
 * @author zhoupengqian01
 */
class cls_ctrl_friend extends cls_ctrl_base 
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getFriendList()
	{
		$friendService = $this->serviceLocator->getService("friend");
		$friendList = $friendService->getFriendList($this->uid);
		
		return $this->makeReturn(1, array('friends'=>$friendList));
	}
	
	public function getFriendInfo()
	{
		$fid = isset($_GET['fid'])?$_GET['fid']:0;
		
		$userService = $this->serviceLocator->getService("user");
		$userInfo = $userService->getUser($fid);
		
		$castleService = $this->serviceLocator->getService("castle");
		$castleInfo = $castleService->getCastle($fid);
		
		$equipmentService = $this->serviceLocator->getService("equipment");
		$equipmentInfo = $equipmentService->getEquipment($fid);
		
		return $this->makeReturn(1, array(
			'userInfo'=>$userInfo,
			'castleInfo'=>$castleInfo,
			'equipmentInfo'=>$equipmentInfo
		));
	}
	
	/**
	 * 好友充能
	 */
	public function addFirendCastleEnergy()
	{
		$fid = $_GET['fid'];
		
		$castleService = $this->serviceLocator->getService("castle");
		return $castleService->addFirendCastleEnergy($this->uid,$fid);
	}
}

