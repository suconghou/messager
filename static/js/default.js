window.app=window.app||{};
app.messager=
{
	cssPath:'//suconghou.sinaapp.com/static/messager/default.css',
	apiPath:'//suconghou.sinaapp.com/messager/default',
	currentPage:1,
	maxPage:10,
	loadcomment:[],
	kid:null,
	key:null,
	db:{},

	init:function(id)
	{
		
		this.loadcomment=$('#'+id);
		if(this.loadcomment.length)
		{
			this.kid=this.loadcomment.data('kid');
			this.key=this.loadcomment.data('key');
			this.pid=0;
			this.loadCss(this.cssPath);
			this.firstInit();
			this.listener();
		}

	},

	listener:function()
	{
		$(document).on('click','.comment-reply-link',function(e)
		{
			app.messager.showCommentForm(e);
		}).on('keydown','#submit-text',function(e)
		{	
			if((e.ctrlKey)&&(e.keyCode==13))
			{
				app.messager.comment();
			}
		});
	},

	firstInit:function()
	{
		var page=1;
		var data={key:app.messager.key,kid:app.messager.kid,event:'firstInit'};
		var content=app.messager.cache(page);
		if(content)
		{
			app.messager.loadcomment.html(content);
		}
		else
		{

			app.messager.loadcomment.load(app.messager.apiPath,data,function(response,status,xhr)
			{
				//经过firstInit,才知道一共多少页
				if(status=='success')
				{
					app.messager.maxPage=app.messager.loadcomment.find('#comment-nav').data('max')||app.messager.maxPage;
					app.messager.cache(page,response);
				}
				else//不支持cros
				{
					alert(status);
				}
				
			});
		}

	},
	
	loadCss:function(path)
	{
		var link = document.createElement("link");
		link.href = path;
		link.rel="stylesheet";
		var s = document.getElementsByTagName("head")[0]; 
		s.appendChild(link);
	},

	fixPage:function(page)
	{
		app.messager.currentPage=page;
		$('#comment-nav a[rel='+page+']').addClass('current').siblings().removeClass('current');
	},

	scrollInit:function()
	{
	
		var pos=app.messager.loadcomment.offset();
		$('html body').animate({scrollTop:pos.top},800);
			
	},

	cache:function(key,data)
	{
		var key=app.messager.key+'_'+app.messager.kid+'_'+key;
		if(data||data==0)
		{
			app.messager.db[key]=data;
		}
		else
		{
			return app.messager.db[key];
		}

	},

	loadPage:function(page)
	{
		$('#thread-list').css({'opacity': 0.5});
		$('#comment-nav').text('拼命加载中...');
		var content=app.messager.cache(page);
		if(content)
		{
			app.messager.loadcomment.html(content);
			app.messager.scrollInit();
			app.messager.fixPage(page);
		}
		else
		{
			var data={key:app.messager.key,kid:app.messager.kid,event:'loadPage','page':page};
			app.messager.loadcomment.load(app.messager.apiPath,data,function(response,status,xhr)
			{
				app.messager.pid=0;
				app.messager.scrollInit();
				app.messager.fixPage(page);
				app.messager.cache(page,response);

			});	
		}
	},

	page:function(page)
	{
		var page=page||app.messager.currentPage;
		app.messager.loadPage(page);
	},

	prev:function()
	{
		var page=app.messager.currentPage;
		app.messager.currentPage=page<2?page:(page-1);
		app.messager.page(app.messager.currentPage);
	},

	next:function()
	{
	
		var page=app.messager.currentPage;
		app.messager.currentPage=page<app.messager.maxPage?(page+1):page;
		app.messager.page(app.messager.currentPage);
	},

	showCommentForm:function(e)
	{
		var ele=$(e.target);
		pele=ele.parent().parent('.comment-main');
		var form=$('#comment-form');
		pele.append(form);
		app.messager.pid=ele.data('comment');
	},

	formTip:function(msg,delay)
	{
		var delay=delay||2000;
		$('#form-tips').text(msg).fadeIn().delay(delay).fadeOut();
	},

	notify:function()
	{
		$.post(app.messager.apiPath,{event:'notify'},function(data){

		},'json');
	},
	/*提交评论接口,须防止重复点击重复提交*/
	comment:function()
	{
		var submit_name=$('#submit-name').val();
		var submit_email=$('#submit-email').val();
		var submit_site=$('#submit-site').val();
		var submit_text=$('#submit-text').val();
		if(submit_site&&!/^http(s)?:\/\/[\w-]+(\.[\w-]+){1,3}(\/[\w-\.\/]+)?$/.test(submit_site))
		{
			app.messager.formTip('网址格式不正确哦');
			return false;
		}
		if(submit_name&&submit_email)
		{
			if(/^[\w-]+@(\w+\.)+[a-z]+$/.test(submit_email))
			{
				if(submit_text.length>4)
				{
					if(app.messager.posting||submit_text==app.messager.lastmsg)
					{
						return false; ///上次提交的还未完成,或重复的评论内容
					}
					var data=
					{
						event:'comment',
						key:app.messager.key,
						kid:app.messager.kid,
						pid:app.messager.pid||0,
						content:submit_text,
						name:submit_name,
						email:submit_email,
						site:submit_site,
					};
					app.messager.posting=true;
					app.messager.lastmsg=submit_text;
					app.messager.formTip('评论提交中...');
					$.post(app.messager.apiPath,data, function(data){
							app.messager.posting=false;
							console.log(data);
							app.messager.formTip(data.msg);
							if(data.code==0)
							{
								setTimeout(function()
								{
									app.messager.cache(app.messager.currentPage,0);
									app.messager.loadPage(app.messager.currentPage);
								},1000);
							}
					},'json');
				}
				else
				{
					app.messager.formTip('评论至少写五个字吧-_-|||');
				}
			}
			else
			{
				app.messager.formTip('输入的邮箱格式不正确哦');
			}
		}
		else
		{
			app.messager.formTip('昵称和邮箱都要填写的哦');
		}

	}


}
