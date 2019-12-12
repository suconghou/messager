<?php

/**
 * 可以继承base类或者其他控制器类,也可以继承模型类,也可以什么都不继承
 */
class home
{

	function __construct()
	{
		//可以添加权限控制,保护整个控制器
	}

	public function index1($value = '')
	{
		# code...
		template('console/index');
	}
	/**
	 * home 是默认的控制器
	 * index 时默认的方法
	 */
	function index2()
	{

		$kid = '0';
		$where = ['kid' => $kid];
		$orderLimit = ['id' => 'ASC'];
		$data = db::find($where, 'default', '*', $orderLimit);
		$data = self::data($data);
		// template('test',['data'=>$data,'page'=>4]);
		template('themes/default', ['data' => $data['data'], 'num' => $data['num'], 'page' => 4]);
		// var_dump(self::data($data));
	}

	public static function data($items)
	{
		$items = array_column($items, null, 'id');
		$tree = [];
		foreach ($items as $item) {
			if (isset($items[$item['pid']])) {
				$items[$item['pid']]['child'][] = &$items[$item['id']];
			} else {
				$tree[] = &$items[$item['id']];
			}
		}
		return ['num' => count($tree), 'data' => $tree];
	}

	public static function data2($items)
	{
		$tree = []; //最终生成的结果
		$path = []; //记录如何找到我
		foreach ($items as $item) {
			$id = $item['id'];
			$pid = $item['pid'];
			if (isset($path[$pid])) //可以追踪到他的上一级
			{
				$ppath = $path[$pid]; ///这是他父类所在的路径结构
				eval($ppath . ";");
				$p['child'][$id] = $item;
				$path[$id] = $ppath . "['child'][{$id}]";
			} else {
				$path[$id] = '$p=&$tree' . "[$id]";
				$tree[$id] = $item;
			}
		}
		return ['num' => count($tree), 'data' => $tree];
	}
}
