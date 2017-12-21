<?php
/**
 * 例子，用来测试框架
 * @author zhoupengqian01
 */
class cls_service_map extends sys_serviceabs 
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function startChanllenge($uid,$mapInfo)
	{
		$userDao = $this->daoLocator->getDao('user');
		$userInfo = $userDao->getUser($uid);
		//超过个位数就不能这样判断了
		if($userInfo['bigAreaIndex'].$userInfo['smallAreaIndex'] < $mapInfo['bigAreaIndex'].$mapInfo['smallAreaIndex']){
			return false;
		}
		//更新当前用户的难度等级
		$userInfo['hardLevel'] = $_GET["hardLevel"];
		$userDao->updateUser($uid,$userInfo);
		
		$mapDao = $this->daoLocator->getDao("user_map");
		$mapDetailInfo = $mapDao->getMapByIndex($uid,$mapInfo['bigAreaIndex'],$mapInfo['smallAreaIndex']);
		if(!$mapDetailInfo){
			$mapInfo['uid'] = $uid;
			$mapDao->addMap($uid,$mapInfo);
		}
		return true;
	}
	
	public function endMap($uid,$passTime){
		$userDao = $this->daoLocator->getDao('user');
		$userInfo = $userDao->getUser($uid);
		
		switch($userInfo['hardLevel']){
			case 1:
				$mapInfo['normalTime'] = $passTime;
				break;
			case 2:
				$mapInfo['hardTime'] = $passTime;
				break;
			case 3:
				$mapInfo['veryHardTime'] = $passTime;
				break;
			default:
				return "没有开始此关";
		}
		//清除关卡开始数据。
		$userInfo['hardLevel'] = 0;
		$userDao->updateUser($uid,$userInfo);

		$bigAreaIndex = $_GET['bigAreaIndex'];
		$smallAreaIndex = $_GET['smallAreaIndex'];
		$mapDao = $this->daoLocator->getDao("user_map");

		$pastedInfo = $mapDao->getUserOneRecode($uid,$bigAreaIndex,$smallAreaIndex);

		if($pastedInfo == false)
		{
			$mapInfo['uid'] = $uid;
			$mapInfo['bigAreaIndex'] = $bigAreaIndex;
			$mapInfo['smallAreaIndex'] = $smallAreaIndex;
			$mapDao->insertOneRecode($mapInfo);
		}else
		{

			$mapDao->updateTime($uid,$mapInfo,$bigAreaIndex,$smallAreaIndex);
			
			//下一关
			$ret = $this->getNextIndex($userInfo['bigAreaIndex'],$userInfo['smallAreaIndex']);
			if($ret){
				$userDao->updateUser($uid,$ret);
			}
		
		}
		return true;
	}
	
	private function getNextIndex($bigAreaIndex,$smallAreaIndex)
	{
		$bigMaxIndex = 5;
		$smallMaxIndex = 8;
	
		if($smallAreaIndex+1>$smallMaxIndex){
			if($bigAreaIndex+1>$bigMaxIndex){
				return false;
			}else{
				$bigAreaIndex++;
				$smallAreaIndex=0;
			}
		}else{
			$smallAreaIndex++;
		}
		return array(
				'bigAreaIndex'=>$bigAreaIndex,
				'smallAreaIndex'=>$smallAreaIndex
		);
	}
		
	public function getMap($uid)
	{
		$mapDao = $this->daoLocator->getDao("user_map");
		return $mapDao->getMap($uid);
	}
	
	public function updateMap($uid,$mapInfo){
		$mapDao = $this->daoLocator->getDao("user_map");
		return $mapDao->updateMap($uid,$mapInfo);
	}
	
}

