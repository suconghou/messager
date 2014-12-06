<?php
/**
* 我的 blog
* Single File Site 
* 重写路由
*/
class blog
{
	private static $db;
	public static $pjax;
	private static $theme;

	function __construct($auto=false)
	{
		if($auto)
		{
			if(version_compare(phpversion(),5.4,'>'))
			{
				$this->overWriteRouter();
			}
			else
			{
				$this->php53overWriteRouter();
			}
			self::$db=new blog_db();
			self::$pjax=Request::isPjax();
			
			if(($theme=cookie('theme'))&&in_array($theme,array('default','fresh','orange')))
			{
				self::$theme='blog/'.$theme.'/';
			}
			else
			{
				self::$theme='blog/default/';
			}
			define("SITENAME",'我的博客');
			define("SUBTITLE",'探索永不止步');
			define("KEYWORDS",'开发');
			define("DESCRIPTION",'PHP开发,前端开发,');
			define('COPYRIGHT','COPYRIGHT © 2013-2014 SUCONGHOU\'S BLOG');
			define('POWERED','POWERED BY <a href="http://github.com/suconghou/mvc" target="_blank">MINIMVC</a>');
		}

	}
	/**
	 * 重写一部分路由
	 */
	private function overWriteRouter()
	{
		app::route('\/?',function(){
			$this->index();
		});

		app::route('\/read\/?',function(){
			$this->read();
		});
		app::route('\/page\/?',function(){
			$this->page();
		});
		app::route('\/fm\/?',function(){
			$this->fm();
		});
		app::route('\/about\/?',function(){
			$this->about();
		});
		app::route('\/archive\/?',function(){
			$this->archive();
		});

		app::route('\/mood(\/?|\/(\w+)\/?)',function($a=null,$b=null){
			$this->mood($b);
		});


		app::route('\/read\/(\d{1,9})\.html',function($id){
			$this->readid($id);
		});
		app::route('\/page\/(\d{1,9})\.html',function($id){
			$this->pageid($id);
		});
		app::route('\/fm\/(\d{1,9}).fm',function($id){
			$this->fmid($id);
		});
		app::route('\/(\d{1,9})\.html',function($id){
			$this->indexid($id);
		});
		app::route('\/page\/(\d+)\/?',function($page){
			$this->index($page);
		});
		app::route("\/tags\/([^<>\s#]+)\/?",function($tag){
			$tag=urldecode($tag);
			$this->tags($tag);
		});
	}
	private function php53overWriteRouter()
	{
		$self=$this;

		app::route('\/?',function() use($self){
			$self->index();
		});
		app::route('\/read\/?',function() use($self){
			$self->read();
		});
		app::route('\/page\/?',function() use($self){
			$self->page();
		});
		app::route('\/fm\/?',function() use($self){
			$self->fm();
		});
		app::route('\/about\/?',function() use($self){
			$self->about();
		});
		app::route('\/archive\/?',function() use($self){
			$self->archive();
		});
		app::route('\/mood(\/?|\/(\w+)\/?)',function($a=null,$b=null) use($self){
			$self->mood($b);
		});


		app::route('\/read\/(\d{1,9})\.html',function($id) use($self){
			$self->readid($id);
		});
		app::route('\/page\/(\d{1,9})\.html',function($id) use($self){
			$self->pageid($id);
		});
		app::route('\/fm\/(\d{1,9}).fm',function($id) use($self){
			$self->fmid($id);
		});
		app::route('\/(\d{1,9})\.html',function($id) use($self){
			$self->indexid($id);
		});
		app::route('\/page\/(\d+){1,3}\/?',function() use($self){
			$self->index($page);
		});

		app::route('\/tags\/([^<>\s#]+)\/?',function($tag) use($self){
			$self->tags($tag);
		});

	}
	public static function render($file,$data=null)
	{
		$data['theme']=self::$theme;
		V(self::$theme.$file,$data);
	}

	/**
	 * 博客首页
	 */
	function index($page=null)
	{
		
		$page=$page?intval($page):1;
		$ret=self::$db->getPostList($page,8);
		foreach ($ret['list'] as &$item)
		{
			$item['tags']=preg_replace('/#([^<>\s#]+)/', '<li class="article-tag-list-item"><a href="/tags/\1" class="article-tag-list-link">\1</a></li>', $item['tags']);
		}
		$data=&$ret;
		$tagList=self::$db->getAllTags();
		$page=$page>$data['page']?$data['page']:$page;
		$min=max($page-5,1);
		$max=min($page+5,$data['page']);
		$data['current']=$page;
		$data['min']=$min;
		$data['max']=$max;
		$data['tagList']=$tagList;
		// var_dump($data);die;
		self::render('index',$data);
	}
	/**
	 * 阅读 首页
	 */
	function read()
	{
		
		
		$ret=self::$db->getReadList($page=1);
		V('blog/read/index',$ret);

	}
	/**
	 * page 首页
	 */
	function page()
	{
		
		
		$data=self::$db->getPageList('page');
		V('blog/page/index',$data);
	}
	/**
	 * fm 首页
	 */
	function fm()
	{
		
		
		V('blog/fm/index');

	}
	/**
	 * 归档 ,所有文章
	 */
	function archive()
	{
		
		$data=self::$db->getArchive();
		$out=array();
		foreach ($data as $item)
		{
			$key=date('Y-m',$item['date']);
			$out[$key][]=$item;
		}
		$pagearchive=array();
		$data=self::$db->getPageArchive();
		foreach ($data as $item)
		{
			$key=date('Y-m',$item['date']);
			$pagearchive[$key][]=$item;
		}
		$tagList=self::$db->getAllTags();
		$data=array('archive'=>$out,'pagearchive'=>$pagearchive,'tagList'=>$tagList);
		self::render('archive',$data);
	}
	/**
	 * 心情
	 */
	function mood($page)
	{
		$page=$page?$page:'index';
		V('blog/mood/'.$page);
	}
	function tags($tag)
	{
		
		$tagdata=self::$db->getTagInfo($tag);
		foreach ($tagdata as &$item)
		{
			$item['tags']=preg_replace('/#([^<>\s#]+)/', '<li class="article-tag-list-item"><a href="/tags/\1" class="article-tag-list-link">\1</a></li>', $item['tags']);
		}
		$tagList=self::$db->getAllTags();
		$data=array('list'=>$tagdata,'current'=>1,'min'=>1,'max'=>0,'tagList'=>$tagList);
		self::render('index',$data);
	}
	/**
	 * 关于
	 */
	function about()
	{
		$data=self::$db->getAllTags();
		var_dump($data);die;
		self::render('about');
	}
	/**
	 * 文章详情
	 */
	function indexid($id)
	{
		
		$post=self::$db->getPostById($id);
		if($post)
		{
			$data['pjax']=self::$pjax;
			$post['tags']=preg_replace('/#([^<>\s#]+)/', '<li class="article-tag-list-item"><a href="/tags/\1" class="article-tag-list-link">\1</a></li>', $post['tags']);
			$data['post']=$post;
			$tagList=self::$db->getAllTags();
			$data['tagList']=$tagList;
			self::$db->hitIncr(blog_db::postTable,$id);
			self::render('id',$data);
		}
		else
		{
			$this->Error404("Post {$id} Not Found ! ");			
		}
	}
	/**
	 * 数据库取出
	 */
	function pageid($id)
	{

		$content=self::$db->getContentById(blog_db::pageTable,$id);
		if($content)
		{
			
			self::$db->hitIncr(blog_db::pageTable,$id);
			echo $content;
		}
		else
		{
			$this->Error404('Page '.$id.' Not Found !');
		}
	}
	/**
	 * 阅读详情
	 */
	function readid($id)
	{

		$read=self::$db->getReadById($id);
		if($read)
		{
			$data['pjax']=self::$pjax;
			$data['read']=$read;
			V('blog/read/id',$data);
		}
		else
		{
			$this->Error404("Read {$id} Not Found ! ");			
		}
	}
	/**
	 * fm
	 */
	function fmid($id)
	{

		$data=self::$db->getFmById(intval($id));
		var_dump($data);
	}
	/**
	 * 未找到提示
	 */
	function Error404($msg=null)
	{
		echo $msg;
	}

}


/**
* 
*/
class blog_db extends database
{
	const pageSize=20;  //每页个数

	const pageTable='blog_page';
	const postTable='blog_post';
	const fmTable='blog_fm';
	const readTable='blog_read';



	function __construct()
	{

	}
	/**
	 * 获取一个表的content内容
	 */
	function getContentById($table,$id)
	{
		$data=$this->selectById($table,$id);
		return $data?$data['content']:null;
	}
	function getPageList($table)
	{
		// 按id逆序排序的每100个分页的第一页
		$data=$this->getList($table,1,null,'id','desc',100);
		return $data;
	}
	function getPostList($page=1,$pageSize=20)
	{
		return $this->getList(self::postTable,$page,null,'date','desc',$pageSize);
	}
	function getPostById($id)
	{
		return $this->selectById(self::postTable,$id);
	}
	function getReadById($id)
	{
		return $this->selectById(self::readTable,$id);
	
	}
	function getReadList($page=1)
	{
		return $this->getList(self::readTable,$page,null,'date','desc',10);
	}
	function getFmById($id)
	{
		return $this->selectById(self::fmTable,$id);
	}
	/**
	 * 阅读数+1,必须存在hit字段
	 */
	function hitIncr($table,$id)
	{
		return $this->incrById($table,'hit',$id); //点击数加1
	}

	function getArchive()
	{
		$data=$this->selectWhere(self::postTable,null,null,'id,title,hit,date');
		return $data;
	}

	function getPageArchive()
	{
		$data=$this->selectWhere(self::pageTable,null,null,'id,title,hit,date');
		return $data;
	}

	function getTagInfo($tag)
	{
		$data=$this->searchByColumn(self::postTable,'tags',$tag);
		return $data;
	}
	function getAllTags()
	{
		$data=$this->selectWhere(self::postTable,null,null,'tags');
		$tags=array();
		foreach ($data as $tag)
		{
			$tag_arr=explode('#',$tag['tags']);
			foreach($tag_arr as $tag)
			{
				if(!$tag)
				{
					continue;
				}
				if(empty($tags[$tag]))
				{
					$tags[$tag]=1;
				}
				else
				{
					$tags[$tag]++;
				}
			}
		}
		return $tags;
	}
}