						<link href="/static/css/default.css" rel="stylesheet">
						<div id="comment-template">
							<ol id="thread-list">

								<?php foreach($data as $item):?>

								<li class="comment-thread">

									<div class="comment-main">
										<section class="comment-avator">
											<img src="https://secure.gravatar.com/avatar/f713666d2485ae4ec6cab07f5dea18ea?s=40" class="avatar avatar-40" height="40" width="40">
											<a class="comment-reply-link" href="javascript:void(0)">回复</a>
										</section>
										<section class="comment-container">
											<div class="comment-meta">
												<cite class="vcard"><a class="linkforavater " href="http://yefengs.com/?home=aHR0cDovL3hwdHQuY29t" title=" 郑永 " target="_blank" rel="external nofollow">郑永</a></cite>
	            								<span class="comment-info">2014/08/26 20:56&nbsp;</span>
											</div>
											<div class="comment-text">
												<p>这里有免费的，你考虑啊这里有费的，你考虑啊这里有免费的，你考虑啊这里有免费的，你考虑啊这里有免费的，你考虑啊这里有免费的，你考虑啊？</p>
											</div>
										</section>
									</div>

									<ul class="comment-children">
										<li>
											<div class="comment-main">
												<section class="comment-avator">
													<img src="https://secure.gravatar.com/avatar/f713666d2485ae4ec6cab07f5dea18ea?s=40" class="avatar avatar-40" height="40" width="40">
													<a class="comment-reply-link" href="javascript:void(0)">回复</a>
												</section>
												<section class="comment-container">
													<div class="comment-meta">
														<cite class="vcard"><a class="linkforavater " href="http://yefengs.com/?home=aHR0cDovL3hwdHQuY29t" title=" 郑永 " target="_blank" rel="external nofollow">郑永</a></cite>
			            								<span class="comment-info">2014/08/26 20:56&nbsp;</span>
													</div>
													<div class="comment-text">
														<p><span class="at">@路易大叔</span>不要酱紫，加班加成狗了，就连周末也
													</div>
												</section>
											</div>
											<ul class="comment-children">
												<li>
													<div class="comment-main">
														<section class="comment-avator">
															<img src="https://secure.gravatar.com/avatar/f713666d2485ae4ec6cab07f5dea18ea?s=40" class="avatar avatar-40" height="40" width="40">
															<a class="comment-reply-link" href="javascript:void(0)">回复</a>
														</section>
														<section class="comment-container admin">
															<div class="comment-meta">
																<cite class="vcard"><a class="linkforavater " href="http://yefengs.com/?home=aHR0cDovL3hwdHQuY29t" title=" 郑永 " target="_blank" rel="external nofollow">郑永</a></cite>
					            								<span class="comment-info">2014/08/26 20:56&nbsp;</span>
															</div>
															<div class="comment-text">
																<p>这里有免费的，你考虑啊这里有免费的，你考虑啊这里有免费的，你考虑啊这里有免费的，你考虑啊？</p>
															</div>
														</section>
													</div>
													<ul class="comment-children">

													</ul>
												</li>
											</ul>
										</li>
									</ul>
								</li>

							<?php endforeach;?>

							</ol>
							<nav id="comment-nav"  role="navigation">
								<a href="javascript:app.messager.prev()">上页</a>
								<a href="javascript:app.messager.page(1)" class="current">1</a>
								<a href="javascript:app.messager.page(3)" rel="3">3</a>
								<a href="javascript:app.messager.page(5)" rel="5">5</a>
								<a href="javascript:app.messager.page(7)" rel="7">7</a>
								<a href="javascript:app.messager.page(9)" rel="9">9</a>
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
