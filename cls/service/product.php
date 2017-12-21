<?php
/**
 * 商品
 * @author zhoupengqian01
 */
class cls_service_product extends sys_serviceabs 
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getAllProduct()
	{
		$productDao = $this->daoLocator->getDao("config_product");
		return $productDao->getAllProduct();
	}
	
	public function getProducts($uid)
	{
		$userProductDao = $this->daoLocator->getDao("user_product");
		$userProducts = $userProductDao->getProducts($uid);
		return $userProducts;
	}
	
}

