<div id="sidebar">
	<ul class="nav nav-list">
		<li><br/><a href="http://en.gravatar.com/" target="_blank"><img src="{{ item.Avatar }}?s=150&d=retro" class="img-polaroid"/></a></li>
		<li><h3>{{ item.Name }}</h3></li>
		<li><h4 class="subtitle">{{ item.Fullname }}</h4></li>
		<li class="divider"></li>
		<li class="nav-header">Informasi</li>
		<li><i class="icon icon-time"></i> {{ item.Date }}</li>
		<li><i class="icon icon-quote-left"></i><blockquote><small>{{ item.Signature|striptags }}</small></blockquote></li>
	</ul>
</div>