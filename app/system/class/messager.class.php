<?php

/**
* 
*/
class messager
{
	private static $allTheme=array('default'); //所有主题
	private static $theme;//当前加载的主题
	private static $db;
	private static $key;
	private static $id;

	private static $per=5; //每页包含多少thread

	function __construct()
	{
		self::$db=new messager_db();
		$this->overWriteRouter();
	}
	private function overWriteRouter()
	{
		app::route('\/messager\/(\w+)?',function($theme=null){
			$this->api($theme);
		});

		app::route('\/?',function(){
			$this->home();
		});

	}
	public static function render($data)
	{
		$theme=in_array(self::$theme,self::$allTheme)?self::$theme:'default';
		template('messagerTheme/'.$theme,$data);
	}
	function home()
	{
		$data=array('data'=>$this->data());
		// echo "<pre>";
		// print_r($tree);die;
		template('/messagerTheme/default',$data);
	}

	/**
	 * 返回所有格式化好的数据
	 */
	function data()
	{

		$data=self::$db->getMessageData(0);
		$tree=array(); //最终生成的结果
		$path=array(); //记录如何找到我
		foreach ($data as $item)
		{
			$id=$item['id'];
			$pid=$item['pid'];
			if(isset($path[$pid])) //可以追踪到他的上一级
			{
				$ppath=$path[$pid];///这是他父类所在的路径结构
				eval($ppath.";");
				$p['child'][$id]=$item;
				$path[$id]=$ppath."['child'][{$id}]";
			}
			else
			{
				$path[$id]='$p=&$tree'."[$id]";
				$tree[$id]=$item;
			}
	
		} //end foreach
		
		return array('num'=>count($tree),'data'=>$tree);
	}
	/**
	 * 对格式化好的数据分页处理 
	 */
	function dataPage($page)
	{
		$data=$this->data();
		$num=$data['num'];
		$per=self::$per; //每页的数据
		$pages=ceil($num/$per);
		$offset=($page-1)*$per;
		$ret=array_slice($data['data'],$offset,$per,true);
		// var_dump($page,$offset,$ret);die;
		return array('page'=>$pages,'data'=>$ret);
	}

	############### messager api ######################

	function api($theme='default')
	{
		// usleep(300000);
		header('Access-Control-Allow-Origin:*');
		self::$theme=$theme;
		self::$key=Request::post('key','defaultKey');
		self::$id=Request::post('id','defaultId');
		$event=Request::post('event','errorEvent');
		if(method_exists($this, $event))
		{
			$this->$event();
		}
	}
	function errorEvent()
	{
		json(array('code'=>-1,'msg'=>'error event'));
	}
	function firstInit()
	{
		$page=Request::post('page',1);
		$data=$this->dataPage($page);
		self::render($data);
	}
	function comment()
	{
		json(array('code'=>0,'msg'=>'评论提交成功!'));
	}
	function loadPage()
	{
		$page=Request::post('page',1);
		$data=$this->dataPage($page);
		self::render($data);

	}
	//邮件通知事件
	function notify()
	{

	}

	########## api method end #############

}

/**
* messager db
*/
class messager_db
{
	private static  $table='defaultKey';

	function __construct()
	{

	}
	function init()
	{

	}
	/**
	 * 根据文章表示获取所有评论,然后分页和排列
	 */
	function getMessageData($kid)
	{
		$sql="select * from ".self::$table." where kid='{$kid}' order by id asc";
		$data=db::getData($sql);
		return $data;

	}
}