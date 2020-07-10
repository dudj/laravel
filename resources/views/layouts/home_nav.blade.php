@section('home_nav')
    <header>
        <div class="wrap-header">
            <!---Main Header--->
            <div class="main-header">
                <div class="zerogrid">
                    <div class="row">
                        <div class="col-1-4">
						<span class="contact-info left">
							<b style="color:#2b2459;font-size:14px;">Mon - Sat 8.00 - 20.00</b>
							<br>
							Sunday CLOSED
						</span>
                        </div>
                        <div class="col-2-4">
                            <div id="logo"><a href="/"><img src="/home/images/logo.png" /></a></div>
                        </div>
                        <div class="col-1-4">
						<span class="contact-info right">
							<b style="color:#2b2459;font-size:14px;">Call us: 123-456-78-90</b>
							<br>
							info@yoursite.com
						</span>
                        </div>
                    </div>
                </div>
            </div>
            <!---Top Menu--->
            <div id="cssmenu" >
                <ul>
                    <li class="active"><a href="{{url('/')}}"><span>首页</span></a></li>
                    <li class="last"><a href="{{url('common/contact')}}"><span>联系我们</span></a></li>
                </ul>
            </div>
        </div>

    </header>
    <script>
        $(document).ready(function(){
            var url = window.location.href;
            console.log(url);
            $("#cssmenu>ul>li").removeClass('active');
            $("#cssmenu>ul>li").each(function(){
                if($(this).children('a').attr('href') == url){
                    $(this).addClass('active');
                }
            });
        })
    </script>
@endsection