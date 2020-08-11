1._开头的视图文件 都是增加和修改的文件
2.table单元格编辑
//监听
table.on('edit(brandList)', function(obj){
    var data = {};
    data[obj.field] = obj.value;
    data.id = obj.data.id;
    changeTableVal('{{url('admin/goods/changeBrand')}}',data);
});
3.单选按钮切换
3.1 table表格中列加入 templet: '#is_recommend'
3.2 script的js内容
<script type="text/html" id="is_recommend">
    <input type="checkbox" name="is_recommend" data-id = "@{{ d.id }}" value="@{{ d.is_recommend }}" lay-skin="switch" lay-text="是|否" lay-filter="isRecommendListen" @{{ d.is_recommend == 1?'checked':'' }}/>
</script>
3.3 操作 也是通过监听
form.on('switch(isRecommendListen)', function(obj){
    var value = this.value == 1 ? 0 : 1;
    changeTableVal('{{url('admin/goods/changeBrand')}}', {'id':this.getAttribute('data-id'),'is_recommend':value});
});
4.删除
4.1 多删
//获取选中状态 checkStatus参数是table的id值     
var data = table.checkStatus('brandList');
//获取选中数量
var selectCount = data.data.length;
if(selectCount == 0){
    layer.msg('至少选中一项数据',function(){});
    return false;
}
var ids = "";
for(var i=0; i<selectCount; i++){
    ids += data.data[i].id + ",";
}
publicHandle('{{url('admin/goods/deleteBrand')}}',ids,'get','{{url('admin/goods/brandList')}}');
4.2 单删
publicHandle('{{url('admin/goods/deleteBrand')}}',obj.data.id,'get','{{url('admin/goods/brandList')}}');