<div id="toolbar">
	<div class="container">
		<div class="row">

			{######################## Logo ########################}
			<div class="span3">
				<a href="/" id="logo"></a>
			</div>

			{######################## Top Menu ########################}
			<div class="span7">
				<ul id="topnav">
					<li><a href="/">Beranda</a></li>
					<li><a href="/">Organisasi</a></li>
					<li><a href="/">Pelatihan</a></li>
					<li><a href="/">Komunitas</a></li>
					<li><a href="/">Karir</a></li>
				</ul>
			</div>
			<div class="span2">
				{% if acl.isLogin == true %}
				<a href="/auth/logout" class="btn btn-primary pull-right alert-block">Keluar</a>
				{% else %}
				<a href="/auth/login" class="btn btn-primary pull-right alert-block">Masuk</a>
				{% endif %}
			</div>
			
		</div>
	</div>
</div>