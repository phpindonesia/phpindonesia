<div id="header">
    <div class="container">
        <div class="row">
            <!-- SLIDER -->
            <div class="span4">
                <div id="header-left">
                    <h1>PHP Indonesia</h1>
                    <p>
                        Bersama. Berkarya. Berjaya
                    </p>
                    {% if acl.isLogin == false %}
                    <br/>
                    <p>
                        <a href="/auth/loginfb" class="btn btn-large"><i class="icon-facebook-sign"></i> Masuk melalui Facebook</a>
                    </p>
                    {% endif %}
                </div>
            </div>

            <!-- SLIDER -->
            <div class="span8">
                {% if sliders is not empty %}
                <div id="slider" class="carousel slide">
                    <div class="carousel-inner">
                        <div class="active item"><img src="asset/img/slider/surabaya.png" alt=""></div>
                        <div class="item"><img src="/asset/img/slider/manado.png" alt=""></div>
                        <div class="item"><img src="/asset/img/slider/malang.png" alt=""></div>
                        <div class="item"><img src="/asset/img/slider/bandung.png" alt=""></div>
                    </div>
                    
                    <a class="carousel-control right" href="#slider" data-slide="next">&rsaquo;</a>
                    <a class="carousel-control left" href="#slider" data-slide="prev">&lsaquo;</a>
                </div>
                {% endif %}
            </div>
        </div>
    </div>
</div>