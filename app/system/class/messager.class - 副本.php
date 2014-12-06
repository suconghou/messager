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

	function data()
	{

		$data=self::$db->getMessageData(0);
		$tree=array(); //最终生成的结果
		$level=array();///记录次记录所在的各级
		$path=array(); //记录如何找到我
		foreach ($data as $item)
		{
			$id=$item['id'];
			$pid=$item['pid'];
			
			if($pid==0) //it's parent
			{
				$tree[$id]=$item;
				$level[0][]=$id; //记录此ID是顶级ID
				$path[$id]='$p=&$tree'."[$id]";//记录怎样找到我
				// echo "我是父类,这样可以找到我{$path[$id]}<br>";
			}
			else//not the parent
			{
					if(isset($level[0])) //已存储顶级的数据
					{
						if(in_array($pid,$level[0])) //他的父类在这一层级的列表中
						{
							$level[1][]=$id;//他是他的父级所在的下一级
							$ppath=$path[$pid];///这是他父类所在的路径结构
							// echo "我经过{$i}此遍历找到我的父类了,他是第{$i}层级:",$ppath,'<br>';
							eval($ppath.";");///找到他父类在哪,执行后,即得到他父类的引用$p
							$p['child'][$id]=$item;//在他父类中,讲自己插入进去
							$path[$id]=$ppath.'[\'child\']'."[{$id}]";//记录怎样找到我,即他插入到父类的路径
							// echo "我要想办法插入到他的内部,下面是我插入后的结果";
							// var_dump($p);
							// echo "我在{$path[$id]}<br><br>";
							//break; //既然我已找到我的父类,并且插入到父类里,就不用继续循环了,退出while循环
						}
						else
						{
							// echo "我{$id}循环了{$i}次也没找到我的父类,啊啊啊,<br>";
							//尝试插入到他的父类中
							if(isset($path[$pid]))
							{
								$ppath=$path[$pid];///这是他父类所在的路径结构
								eval($ppath.";");
								$p['child'][$id]=$item;
								$path[$id]=$ppath.'[\'child\']'."[{$id}]";
							}
							else
							{
								$path[$id]='$p=&$tree'."[$id]";
								$tree[$id]=$item;
							}

						}
					}
					else
					{
						// echo "我{$id}循环了{$i}次也没找到我的父类,啊啊啊,<br>";
							//尝试插入到他的父类中
							if(isset($path[$pid]))
							{
								$ppath=$path[$pid];///这是他父类所在的路径结构
								eval($ppath.";");
								$p['child'][$id]=$item;
								$path[$id]=$ppath.'[\'child\']'."[{$id}]";
							}
							else
							{
								$path[$id]='$p=&$tree'."[$id]";
								$tree[$id]=$item;
							}

					}
				

		
			}
		} //end foreach
		
		return $tree;
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
		$data['num']=Request::post('page',1);
		$data['data']=$this->data();
		self::render($data);
	}
	function comment()
	{
		json(array('code'=>0,'msg'=>'评论提交成功!'));
	}
	function loadPage()
	{
		$data['num']=Request::post('page',1);
		$data['data']=$this->data();
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
		$sql="select * from ".self::$table." where kid='{$kid}'";
		$data=db::getData($sql);
		return $data;

	}
}