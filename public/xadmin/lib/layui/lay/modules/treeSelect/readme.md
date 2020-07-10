1. 引入layui.js
<script src="../assets/layui/layui.js"></script>
2. 在页面上添加input
<input type="text" id="tree" lay-filter="tree" class="layui-input"<
3. 加载formSelect模块
layui.config({
    base: "../module/"
}).extend({
    treeSelect: "treeSelect/treeSelect"
});
4. 初始化
layui.use(["treeSelect"], function () {
    var treeSelect = layui.treeSelect;
    treeSelect.render({
        // 选择器
        elem: '#tree',
        // 数据
        data: 'data/data3.json',
        // 异步加载方式：get/post，默认get
        type: 'get',
        // 占位符
        placeholder: '修改默认提示信息',
        // 是否开启搜索功能：true/false，默认false
        search: true,
        // 一些可定制的样式
        style: {
            folder: {
                enable: true
            },
            line: {
                enable: true
            }
        },
        // 点击回调
        click: function(d){
            console.log(d);
        },
        // 加载完成后的回调函数
        success: function (d) {
            console.log(d);
//                选中节点，根据id筛选
            treeSelect.checkNode('tree', 3);
            console.log($('#tree').val());
//                获取zTree对象，可以调用zTree方法
           var treeObj = treeSelect.zTree('tree');
           console.log(treeObj);
//                刷新树结构
           treeSelect.refresh('tree');
        }
    });
});



注意： children没有数据的时候，请把这个字段移除，否则在下拉框中会一直显示箭头