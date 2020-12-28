@extends('layouts.home')
@extends('layouts.home_nav')
@section('home_content')
    <section id="container">
        <div class="wrap-container">
            <!-----------------焦点图-------------------->
            <section class="content-box boxstyle-1 box-1">
                <div id="zSlider">
                    <div id="picshow">
                        <div id="picshow_img">
                            <ul>
                                <li><a href="#"><img src="/home/images/slider-1.jpg"></a></li>
                                <li><a href="#"><img src="/home/images/slider-2.jpg"></a></li>
                            </ul>
                        </div>
                        <div id="picshow_tx">
                            <ul>
                                <li>
                                    <h3><a href="#">垂直轮播插件标题信息</a></h3>
                                    <p>上海第一家死飞精品店，由三个外国人与一中国人联合创办，主要经营客订个性单速车，帮助他们得到自己梦想中的车架。</p>
                                </li>
                                <li>
                                    <h3><a href="#">垂直轮播插件标题信息</a></h3>
                                    <p>冰岛有“火山岛”、“雾岛”、“冰封的土地”、“冰与火之岛”之称。有想过在这里骑游吗？下面看看Ovegur的冰岛骑游之旅吧。</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div id="select_btn">
                        <ul>
                            <li><a href="#"><img src="/home/images/slider-1.jpg"><span class="select_text">垂直轮播插件标题信息</span><span class="select_date">2019/01/16</span></a></li>
                            <li><a href="#"><img src="/home/images/slider-2.jpg"><span class="select_text">垂直轮播插件标题信息</span><span class="select_date">2019/01/15</span></a></li>
                        </ul>
                    </div>
                </div>
            </section>
            <!-----------------content-box-4-------------------->
            <section class="content-box boxstyle-1 box-4">
                <div class="zerogrid">
                    <div class="row wrap-box"><!--Start Box-->
                        <div class="header">
                            <div class="wrapper">
                                <h2 class="color-yellow">Team</h2>
                                <hr class="line02">
                                <div class="intro">Meet Our Team</div>
                            </div>
                        </div>
                        <div class="row"><!--Start Box-->
                            <div class="col-1-4">
                                <div class="wrap-col item">
                                    <div class="portfolio-box">
                                        <img src="home/images/ava-4.jpg"alt="">
                                    </div>
                                    <div class="item-content">
                                        <h3><a href="#">Nina Santos</a></h3>
                                        <span>Hairdresser</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-1-4">
                                <div class="wrap-col item">
                                    <div class="portfolio-box">
                                        <img src="home/images/ava-5.jpg"alt="">
                                    </div>
                                    <div class="item-content">
                                        <h3><a href="#">Nina Santos</a></h3>
                                        <span>Stylist</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-1-4">
                                <div class="wrap-col item">
                                    <div class="portfolio-box">
                                        <img src="home/images/ava-6.jpg"alt="">
                                    </div>
                                    <div class="item-content">
                                        <h3><a href="#">Nina Santos</a></h3>
                                        <span>Makeup Artist</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-1-4">
                                <div class="wrap-col item">
                                    <div class="portfolio-box">
                                        <img src="home/images/ava-7.jpg"alt="">
                                    </div>
                                    <div class="item-content">
                                        <h3><a href="#">Nina Santos</a></h3>
                                        <span>Makeup Artist</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-----------------content-box-5-------------------->
            <section class="content-box boxstyle-3 box-5">
                <div class="zerogrid" style="clear:none;">
                    <div class="row wrap-box"><!--Start Box-->
                        <div class="header">
                            <div class="wrapper">
                                <h2 class="color-yellow">From the Blog</h2>
                                <hr class="line03">
                                <div class="intro">We make beauty</div>
                            </div>
                        </div>
                        <div class="row"><!--Blog Box-->
                            <div class="col-1-3">
                                <div class="wrap-col item">
                                    <div class="portfolio-box">
                                        <img src="home/images/portfolio-1.jpg"alt="">
                                    </div>
                                    <div class="item-content">
                                        <h3><a href="single.html">Discover Natural Sources for Hair</a></h3>
                                        <span><i class="fa fa-calendar"></i> August 10, 2016 <i class="fa fa-comments"></i> 1 Comment</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-1-3">
                                <div class="wrap-col item">
                                    <div class="portfolio-box">
                                        <img src="home/images/portfolio-2.jpg"alt="">
                                    </div>
                                    <div class="item-content">
                                        <h3><a href="single.html">Discover Natural Sources for Hair</a></h3>
                                        <span><i class="fa fa-calendar"></i> August 10, 2016 <i class="fa fa-comments"></i> 1 Comment</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-1-3">
                                <div class="wrap-col item">
                                    <div class="portfolio-box">
                                        <img src="home/images/portfolio-3.jpg"alt="">
                                    </div>
                                    <div class="item-content">
                                        <h3><a href="single.html">Discover Natural Sources for Hair</a></h3>
                                        <span><i class="fa fa-calendar"></i> August 10, 2016 <i class="fa fa-comments"></i> 1 Comment</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row"><!--Testimonials Box-->
                            <div id="owl-testimonials" class="owl-carousel t-center">
                                <div class="item testimonials-item">
                                    <img src="home/images/partner3.png" />
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley.</p>
                                    <h5>Catherine Grace - America</h5>
                                </div>
                                <div class="item testimonials-item">
                                    <img src="home/images/partner1.png" />
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley.</p>
                                    <h5>Catherine Grace - America</h5>
                                </div>
                                <div class="item testimonials-item">
                                    <img src="home/images/partner2.png" />
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley.</p>
                                    <h5>Catherine Grace - America</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
@extends('layouts.home_footer')