<?php
/**
 * 例子，用来测试框架
 * @author zhoupengqian01
 */
class cls_ctrl_chanllenge extends cls_ctrl_base 
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function start()
	{
		$bigAreaIndex  = $_GET['bigAreaIndex'];
		$smallAreaIndex = $_GET['smallAreaIndex'];
		//$hardLevel = $_GET['hardLevel'];
		
		$mapInfo = array(
			'bigAreaIndex'=>$bigAreaIndex,		
			'smallAreaIndex'=>$smallAreaIndex,		
		);
		
		$mapService = $this->serviceLocator->getService("map");
		$ret = $mapService->startChanllenge($this->uid,$mapInfo);
		if($ret){
			return $this->makeReturn(1,array());
		}else{
			return $this->makeReturn(1,array(),'你还不能挑战这一关');
		}
		
	}
	
	public function end()
	{
		$passTime = $_GET['passTime'];
		
		$mapService = $this->serviceLocator->getService("map");
		$result = $mapService->endMap($this->uid,$passTime);
		
		return $this->makeReturn(1,array('data'=>$result));
	}
	
	
}

