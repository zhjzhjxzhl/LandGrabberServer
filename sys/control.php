<?php
/**
 * 控制类
 * @package sys
 * 
 */

class sys_control {

	/**
	 * 让客户端不缓存页面
	 *
	 */
	public static function noCache()
	{
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0",false);
		header("Pragma: no-cache");
	}

	/**
	 * 国际化
	 * 
	 */
	public static function setLocate()
	{
		/*
		$locale = $_SERVER['lang'].".utf8";
		setlocale(LC_ALL, $locale);
		putenv("LC_ALL=$locale");
		bind_textdomain_codeset(sys_define::PROJECT_LOCALE_NAME, 'UTF-8');
		bindtextdomain(sys_define::PROJECT_LOCALE_NAME, sys_define::PROJECT_LOCALE);
		textdomain(sys_define::PROJECT_LOCALE_NAME);
		*/
	}
	
	public static function makeResult($code,$data=array(),$message="")
	{
		return array(
			'code'=>$code,
			'data'=>$data,
			'message'=>$message,
			'serverTime'=>CURR_TIME,
		);	
	}
	
	public static function makeReturn($code,$data=array(),$message="")
	{
		$ret = array(
			'code'=>$code,
			'data'=>$data,
			'message'=>$message,
			'serverTime'=>CURR_TIME,
		);
		return new cls_view_json($ret);
	}
	
	
	/**
	 * 解析URL，转到对应的控制类去处理
	 * index.php?act=ctl.method
	 */
	public static function performAction()
	{
		$arr = array();
		if (array_key_exists("act",$_POST)) {
			$act = $_POST ['act'];
		} elseif (array_key_exists("act",$_GET)) {
			$act = $_GET ['act'];
		}
		
		if (empty($act) || ! preg_match('/^([a-z_]+)\.([a-z_]+)$/i',$act,$arr)) {
			$arr [0] = 'index.main';
			$arr [1] = 'index';
			$arr [2] = 'main';
		}
		$fname = ROOT_PATH . '/cls/ctrl/' . $arr [1] . '.php';
		if (! file_exists($fname)) {
			sys_log::fatal('control', array('act'=>$act,'msg'=>'unsupport'));
			throw new cls_exception_support("e.ctrl.request.invalid");
			return;
		}
		
		$clsname = 'cls_ctrl_' . $arr [1];
		$ctrl = new $clsname();
		$view = $ctrl->$arr [2]();
		if ($view) {
			$view->display();
		}
	}

	/**
	 * 默认中断处理程序
	 *
	 * @param Exception $exception 中断
	 */
	public static function exceptionHandler(Exception $exception)
	{
	    $logParams = array('message'=>$exception->getMessage(),'code'=>$exception->getCode(),'file'=>$exception->getFile(),'line'=>$exception->getLine());
	    sys_log::fatal('exception', $logParams);
	    
		if (sys_define::DEBUG_MODE) {
			$outputParams = $logParams;
			$outputParams['trace'] = $exception->getTrace();
			$outputParams['traceString'] = $exception->getTraceAsString();
		} else {
			$outputParams = array('message'=>'throw exception, please see log for detail');
		}

		echo '<pre>';
		print_r($outputParams);
		echo '</pre>';
	}

}

