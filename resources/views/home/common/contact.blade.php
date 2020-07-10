@extends('layouts.home')
@extends('layouts.home_nav')
@section('home_content')
    <section id="container">
        <div class="wrap-container zerogrid">
            <div class="crumbs">
                <ul>
                    <li><a href="{{url('/')}}">首页</a></li>
                    <li><a href="{{url('common/contact')}}">联系我们</a></li>
                </ul>
            </div>
            <div id="main-content">
                <div class="wrap-content" style="border-right: none">
                    <div class="row">
                        <div class="col-1-3">
                            <div class="wrap-col">
                                <h3 style="margin: 20px 0">联系方式</h3>
                                <p>JL.Kemacetan timur no.23. block.Q3<br>
                                    Jakarta-Indonesia</p>
                                <p>+6221 888 888 90 <br>
                                    +6221 888 88891</p>
                                <p>info@yourdomain.com</p>
                            </div>
                        </div>
                        <div class="col-2-3">
                            <div class="wrap-col">
                                <div class="contact">
                                    <h3 style="margin: 20px 0 20px 30px">联系信息</h3>
                                    <div id="contact_form">
                                        <form name="form1" id="ff" method="post" action="contact.php">
                                            <label class="row">
                                                <div class="col-1-2">
                                                    <div class="wrap-col">
                                                        <input type="text" name="name" id="name" placeholder="Enter name" required="required" />
                                                    </div>
                                                </div>
                                                <div class="col-1-2">
                                                    <div class="wrap-col">
                                                        <input type="text" name="email" id="email" placeholder="Enter email" required="required" />
                                                    </div>
                                                </div>
                                            </label>
                                            <label class="row">
                                                <div class="col-full">
                                                    <div class="wrap-col">
                                                        <input type="text" name="subject" id="subject" placeholder="Subject" required="required" />
                                                    </div>
                                                </div>
                                            </label>
                                            <label class="row">
                                                <div class="wrap-col">
														<textarea name="message" id="message" class="form-control" rows="4" cols="25" required="required"
                                                                  placeholder="Message"></textarea>
                                                </div>
                                            </label>
                                            <center><input class="button bt1" type="submit" name="submitcontact" value="Submit"></center>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@extends('layouts.home_footer')