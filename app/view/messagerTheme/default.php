
	<div id="comment-template">
		<div id="thread-list">
			<?php loopData($data);  function loopData($data){ foreach($data as $item){ ?>
				<article class="comment-<?=$item['pid']==0?'thread':'child'?>">

					<div class="comment-main">

						<section class="comment-avator">
							<img src="https://secure.gravatar.com/avatar/f713666d2485ae4ec6cab07f5dea18ea?s=40" class="avatar avatar-40" height="40" width="40">
							<a class="comment-reply-link" href="javascript:void(0)">回复</a>
						</section>
						<section class="comment-container">
							<div class="comment-meta">
								<cite class="vcard"><a class="linkforavater " href="http://yefengs.com/?home=aHR0cDovL3hwdHQuY29t" title=" 郑永 " target="_blank" rel="external nofollow">郑永</a></cite>
								<span class="comment-info"><?=$item['date']?></span>
							</div>
							<div class="comment-text">
								<p><?=$item['content']?></p>
							</div>
						</section>
					
					</div>
					
					<?php if(isset($item['child'])&&$child=$item['child']):?>
						<article>
							<ul class="comment-children">
								<?php loopData($child); ?>
							</ul>
						</article>
					<?php endif;?>

				</article>

			<?php } }?>
		</div>
		<nav id="comment-nav"  role="navigation" data-max="<?=$page?>">
			<a href="javascript:app.messager.prev()">上页</a>
			<a href="javascript:app.messager.page(1)" class="current">1</a>
			<?php for($i=2;$i<=$page;$i++):?>
			<a href="javascript:app.messager.page(<?=$i?>)" rel="<?=$i?>"><?=$i?></a>
			<?php endfor;?>
			<a href="javascript:app.messager.next()">下页</a>
		</nav>
		<div id="comment-form">
			<form>
				<p>
					<input type="text" id="submit-name" placeholder="昵称">
					<input type="email" id="submit-email" placeholder="邮箱">
					<input type="url" id="submit-site" placeholder="网址,没有则留空">
				</p>
				<p>
					<textarea id="submit-text" placeholder="评论..."></textarea>
					<button type="button" id="submit-ok" onclick="app.messager.comment()">评论</button>
				</p>
				<p style="height:20px;"><span id="form-tips"></span></p>
			</form>
		</div>
	</div> <!-- end template -->
