<?php

class cls_dao_user_product extends sys_daoabs {
	const TABLE_NAME = 'user_product';
	const MEMCACH_KEY_PRFIEX = '_user_product';
	
	public function __construct()
	{
		parent::__construct();
		$this->daoHelper = new sys_daohelper(null,self::TABLE_NAME);
	}
	
	public function getProducts($uid)
	{
		$productsList = $this->daoHelper->fetchAll('uid=:uid',array('uid'=>$uid));
		return $productsList;
	}
	
	public function getProductsById($uid,$productTypeId)
	{
		
	}
	
}

?>