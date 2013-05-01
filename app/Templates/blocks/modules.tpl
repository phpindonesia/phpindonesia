<div class="row">
	<div class="span8">
		<div class="row">
			<div class="span4">
				<h4><i class="icon-sitemap"></i> Organisasi</h4>
				<p>PHP Indonesia telah menjadi wadah bersama bagi para developer. Saat ini sedang berjalan, sebuah inisiatif pembentukan wadah yang lebih permanen (berupa organisasi).</p><a href="/" class="btn btn-small">Lihat profil dan program</a>
			</div>

			<div class="span4">
				<h4><i class="icon-bar-chart"></i> Pelatihan</h4>
				<p>Dimulai dengan dasar-dasar sampai topik-topik terhangat, serta program sertifikasi, kami berkomitmen untuk memajukan pengetahuan dan keberhasilan rekan-rekan pengembang.</p><a href="/" class="btn btn-small">Lihat jadwal pelatihan dan acara lain</a>
			</div>

			<div class="span4">&nbsp;</div>
			<div class="span4">&nbsp;</div>
			<div class="span4">
				<h4><i class="icon-group"></i> Komunitas</h4>
				<p>Bergabunglah dengan komunitas kami yang terdiri dari ribuan pengembang PHP se-indonesia, dan perluas jaringan anda melalui diskusi dan temu sapa di kota anda.</p><a href="/community" class="btn btn-small">Lihat forum dan tulisan</a>
			</div>

			<div class="span4">
				<h4><i class="icon-briefcase"></i> Karir</h4>
				<p>Cari orang yang tepat dari ribuan pengembang PHP berpengalaman di Indonesia untuk project terbaru anda, atau temukan project/pekerjaan yang cocok untuk anda</p><a href="/" class="btn btn-small">Buat lowongan atau cari kerja</a>
			</div>
		</div>
	</div>

	<div class="span4">
		<div class="well meetup-preview">
			<h4 class="pull-right"><i class="icon-calendar"></i> Event & Meetup</h4>
			<br class="clear"/>
			<hr/>
			<div id="#meetups" class="carousel slide">
			  <!-- Carousel items -->
			  <div class="carousel-inner">
			  	{% for event in events %}
			    <div class="{{ event.state }} item">
					<h4>{{ event.title }}</h4>
					<div class="accordion" id="accordion-meetup">
						<ul class="unstyled">
					        <li><i class="icon-time pull-left"></i>{{ event.time }}</li>
					        <li><i class="icon-map-marker pull-left"></i>{{ event.place }}</li>
					    </ul>
		                <div class="accordion-group">
		                  <div class="accordion-heading">
		                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-meetup" href="#collapseOne">
		                      Deskripsi
		                    </a>
		                  </div>
		                  <div id="collapseOne" class="accordion-body collapse" style="height: 0px; ">
		                    <div class="accordion-inner">
		                    <img src="http://maps.googleapis.com/maps/api/staticmap?zoom=14&size=260x150&maptype=roadmap%20&markers=color:green%7Clabel:X%7C{{ event.lat }},{{ event.lng }}%20&sensor=false" title="Location"/><br/><br/>
		                    <p>{{ event.description|raw }}</p>
		                    </div>
		                  </div>
		                </div>
		                <div class="accordion-group">
		                  <div class="accordion-heading">
		                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-meetup" href="#collapseTwo">
		                    Terdaftar <span class="pull-right">({{ event.confirmed_count }})</span>
		                    </a>
		                  </div>
		                  <div id="collapseTwo" class="accordion-body collapse" style="height: 0px; ">
		                    <div class="accordion-inner">
		                    {{ event.confirmed|raw }}
		                    </div>
		                  </div>
		                </div>
		                <div class="accordion-group">
		                  <div class="accordion-heading">
		                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-meetup" href="#collapseThree">
		                    RSVP (Pending) <span class="pull-right">({{ event.pending_count }})</span>
		                    </a>
		                  </div>
		                  <div id="collapseThree" class="accordion-body collapse">
		                    <div class="accordion-inner">
		                    {{ event.pending|raw }}
		                    </div>
		                  </div>
		                </div>
		            </div>
		            <center>
		            {% if acl.isLogin %}
		            <a href="/wakuwakuw/rsvp/{{ event.eid }}" class="btn btn-small btn-success">RSVP</a>
		            {% else %}
		            <a href="/auth/register" class="btn btn-small btn-primary">Daftar</a>
		            {% endif %}
		            </center>
			    </div>
			    {% endfor %}
			  </div>
			  <!-- Carousel nav -->
			  <br class="clear"/>
			  <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
			  <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
			</div>
		</div>
	</div>
</div>