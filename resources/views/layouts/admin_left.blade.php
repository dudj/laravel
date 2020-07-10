@section('left')
    <div class="left-nav">
        <div id="side-nav">
            <ul id="nav">
                <?php
                    $menu = unserialize(session('menu'));
                    function menuChildren($menu,$level){
                        static $menuStr = "";
                        if($level == 1){
                            $menuStr.= '';
                        }else{
                            $menuStr .=  '<ul class="sub-menu">';
                        }
                        foreach($menu as $key=>$val){
                            $i = 1;
                            $url = urlConnect('admin/right_menu',['id'=>$val['id']]);
                            if($level == 1){
                                $menuStr.= '<li><a href="javascript:;"><i class="iconfont left-nav-li" lay-tips="'.$val['name'].'">'.$val['icon'].'</i><cite>'.$val['name'].'</cite><i class="iconfont nav_right">&#xe697;</i></a>';
                            }else{
                                if($level == 2 && $i == 1 && isset($val['list']) && !empty($val['list'])){
                                    $menuStr.= '<li><a href="javascript:;"><i class="iconfont left-nav-li" lay-tips="'.$val['name'].'">'.$val['icon'].'</i><cite>'.$val['name'].'</cite><i class="iconfont nav_right">&#xe697;</i></a>';
                                }else{
                                    $url = url('admin/'.$val['controller'].'/'.$val['method']);
                                    $menuStr .=  '<li><a onclick="xadmin.add_tab(\''.$val['name'].'\',\''.$url.'\')"><i class="iconfont">'.$val['icon'].'</i><cite>'.$val['name'].'</cite></a>';
                                }
                                $i++;
                            }
                            if(isset($val['list']) && !empty($val['list'])){
                                if($level == 2){
                                    menuChildren($val['list'],3);
                                }else{
                                    menuChildren($val['list'],2);
                                }
                            }
                            if($level == 1){
                                $menuStr .= '</li>';
                            }
                        }
                        if($level == 1){
                            $menuStr.= '';
                        }else{
                            $menuStr .=  '</li></ul>';
                        }
                        return $menuStr;
                    }
                    echo menuChildren($menu,1);
                ?>
                <li>
                    <a href="javascript:;">
                        <i class="iconfont left-nav-li" lay-tips="第三方组件">&#xe6b4;</i>
                        <cite>layui第三方组件</cite>
                        <i class="iconfont nav_right">&#xe697;</i></a>
                    <ul class="sub-menu">
                        <li>
                            <a onclick="xadmin.add_tab('滑块验证','https://fly.layui.com/extend/sliderVerify/')" target="">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>滑块验证</cite></a>
                        </li>
                        <li>
                            <a onclick="xadmin.add_tab('富文本编辑器','https://fly.layui.com/extend/layedit/')">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>富文本编辑器</cite></a>
                        </li>
                        <li>
                            <a onclick="xadmin.add_tab('eleTree 树组件','https://fly.layui.com/extend/eleTree/')">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>eleTree 树组件</cite></a>
                        </li>
                        <li>
                            <a onclick="xadmin.add_tab('图片截取','https://fly.layui.com/extend/croppers/')">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>图片截取</cite></a>
                        </li>
                        <li>
                            <a onclick="xadmin.add_tab('formSelects 4.x 多选框','https://fly.layui.com/extend/formSelects/')">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>formSelects 4.x 多选框</cite></a>
                        </li>
                        <li>
                            <a onclick="xadmin.add_tab('Magnifier 放大镜','https://fly.layui.com/extend/Magnifier/')">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>Magnifier 放大镜</cite></a>
                        </li>
                        <li>
                            <a onclick="xadmin.add_tab('notice 通知控件','https://fly.layui.com/extend/notice/')">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite>notice 通知控件</cite></a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
@endsection