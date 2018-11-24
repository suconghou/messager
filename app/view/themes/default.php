<!-- template begin -->
<?php function loopData($data){ foreach($data as $item): ?>
<article class="comment-<?=$item['pid']?'child':'thread'?>">
	<div class="comment-main comment-<?=$item['id']?>" id="comment-<?=$item['id']?>">
		<section class="comment-avator">
			<img src="https://gravatar.css.network/avatar/<?=$item['avatar']?>?s=80" class="avatar-40">
			<a class="comment-reply-link" href="javascript:void(0)" data-comment="<?=$item['id']?>">回复</a>
		</section>
		<section class="comment-container">
			<div class="comment-meta">
				<cite class="vcard"><a class="linkforavater " href="<?=$item['homepage']?>" title="<?=$item['name']?>" target="_blank" rel="external nofollow"><?=$item['name']?></a></cite>
				<span class="comment-info"><?=date('Y-m-d H:i',$item['time'])?></span>
			</div>
			<div class="comment-text">
				<p><?=$item['content']?></p>
			</div>
		</section>
	</div>
	<?php if(isset($item['child'])&&$child=$item['child']):?>
		<article class="haschild">
			<ul class="comment-children">
				<?php loopData($child); ?>
			</ul>
		</article>
	<?php endif;?>
</article>
<?php endforeach;}?>

<div class="comment-template">
	<div class="thread-list">
		<?php if($data): loopData($data); else: ?>
			<p align="center">还没有评论哦,快来抢沙发!</p>
		<?php endif; ?>
	</div>
	<nav class="comment-nav"  role="navigation" data-max="<?=$page?>">
		<a href="javascript:app.messager.prev()">上页</a>
		<a href="javascript:app.messager.page(1)" class="current">1</a>
		<?php for($i=2;$i<=$page;$i++):?>
		<a href="javascript:app.messager.page(<?=$i?>)" rel="<?=$i?>"><?=$i?></a>
		<?php endfor;?>
		<a href="javascript:app.messager.next()">下页</a>
	</nav>
	<div class="comment-form">
		<form class="form-content">
			<p>
				<input type="text" class="submit-name" placeholder="昵称">
				<input type="email" class="submit-email" placeholder="邮箱">
				<input type="url" class="submit-site" placeholder="网址,没有则留空">
			</p>
			<p>
				<textarea class="submit-text" placeholder="评论..."></textarea>
				<button type="button" class="submit-ok" onclick="app.messager.comment()">评论</button>
			</p>
			<p style="height:20px;"><span class="form-tips"></span></p>
		</form>
	</div>
</div>
<!-- <link rel="stylesheet" type="text/css" href="/static/css/default.css"> -->
<link rel="stylesheet" type="text/css" href="//127.0.0.1:8088/static/css/theme.css">
<!-- end template -->
