<?php
namespace App\Services\Utils\Tree;
/**
 * 通用的树型类，可以生成任何树型结构
 */
class Tree {

    private $result;
    private $arr;
    public  $tree = array();
    private $root;
    private $fields;

    /**
     * 构造函数
     *
     * @param array $result 树型数据表结果集
     * @param array $fields 树型数据表字段，array(分类id,父id)
     * @param integer $root 顶级分类的父id
     */
    public function __construct($result, $fields = array('id', 'parent_id','name'), $root = 0) {
        $this->fields = $fields;
        $this->root = $root;

        $tmp = array();
        foreach ($result as $node) {
            $tmp[$node[$fields[1]]][$node[$fields[0]]] = $node;
            $this->result[$node[$fields[0]]] = $node;
        }
        krsort($tmp);
        $tree = @$tmp[$root];
        unset($tmp[$root]);//只剩下子类

        $this->tree = $this->handler($tree,$tmp);
    }

    /**
     * 树型数据表结果集处理
     */
    private function handler($tree,$tmp) {
        $id = $this->fields[0];
        if(empty($tree))
            return null;
        foreach ($tree as $key=>$val){
            foreach ($tmp as $k=>$child){
                if($val[$id] == $k){
                    $val['child'] = $child;
                    unset($tmp[$k]);
                }
            }
            $tree[$key] = $val;
        }

        foreach ($tree as $key=>$val){
            if(isset($val['child']) && count($tmp)){
                $tree[$key]['child'] = $this->handler($val['child'],$tmp);
            }
        }

        return $tree;
    }

    /**
     * 根据节点名查找子树
     * @param string $name
     * @return array|0
     */
    public function findTreeByName($name=null){
        $returnTree = 0;
        if($name){
            foreach ($this->result as $node){
                if($node[$this->fields[2]] == $name){
                    $node['child'] = $this->leaf($node[$this->fields[0]]);
                    $returnTree = $node;
                    break;
                }
            }
        }
        return $returnTree;
    }

    /**
     * 树形数据赋值
     * @param array $tree 树形结构
     */
    public function setTree($tree){
        $this->tree = $tree;
    }

    /**
     * 正向递归
     */
    private function recur_p($arr) {
        if(empty($arr)) return false;
        foreach ($arr as $v) {
            $this->arr[] = $v[$this->fields[0]];
            if (!empty($v['child'])) $this->recur_p($v['child']);
        }
    }
    /**
     * 菜单 多维数组
     *
     * @param integer $id 分类id
     * @return array 返回分支，默认返回整个树
     */
    public function leaf($id = 0) {
        if($id){
            $tree = $this->_leaf($this->tree, $id);
            $tree = isset($tree['child']) ? $tree['child'] : NULL;
            return $tree;
        }else {
            return $this->tree;
        }
    }

    /**
     * 遍历子节点
     * @param $tree
     * @param $id
     * @return mixed
     */
    private function _leaf($tree,$id){

        foreach ($tree as $c){
            if($c[$this->fields[0]] == $id){
                break;
            }elseif(isset($c['child'])) {
                $c = $this->_leaf($c['child'], $id);
                if($c && $c[$this->fields[0]] == $id){
                    break;
                }
            }
        }
        return $c;
    }

    /**
     * 获取指定项
     */
    public function getItem($id){
        return array_key_exists($id, $this->result) ? $this->result[$id] : null;
    }

    /**
     * 导航 一维数组
     * 返回所有祖先
     * @param integer $id 分类id
     * @return array 返回单线分类直到顶级分类
     */
    public function navi($id) {
        $parent = $this->result[$id];
        $arr = array();
        $arr[$parent[$this->fields[0]]] = $parent;
        while ($parent = $this->getItem($parent[$this->fields[1]])){
            $arr[$parent[$this->fields[0]]] = $parent;
        }
        return array_reverse($arr);
    }

    /**
     * 散落 一维数组
     * 返回所有子孙
     * @param integer $id 分类id
     * @return array 返回leaf下所有分类id
     */
    public function leafid($id) {
        $this->arr = null;
        $this->arr[] = $id;
        $this->recur_p($this->leaf($id));
        return $this->arr;
    }

    /**
     * 根据级别和格式输出分类名
     * @param int $id
     * @param string $space
     * @param string $str
     * @param string $lhtml 比如 <input type="radio" name="cid" />或<option>
     * @param string $rhtml 比如 </option>
     * @return string
     */
    public function getLevelName($id,$str='|',$space='- - ',$lhtml='',$rhtml=''){
        $item = $this->result[$id];
        if(!isset($item['level'])){
            $item['level'] = count($this->navi($id));
        }
        for ($i=0;$i<$item['level'];$i++){
            $str .= $space;
        }
        return $str.$lhtml.$item[$this->fields[2]].$rhtml;
    }

    /**
     * 获取id levaeName对
     *
     * 可适用于列表以及下拉列表
     *
     * @param null $tree
     * @return array
     */
    public function getValueOptions($tree=NULL)
    {
        $tree = $tree ? $tree : $this->tree;
        $returnList = array();
        if(empty($tree))
            return $returnList;
        foreach($tree as $item) {

            $tmp = array();
            $tmp[$item[$this->fields[0]]] = $this->getLevelName($item[$this->fields[0]]);
            $returnList = Util::array_merge($returnList,$tmp);
            if(isset($item['child'])){
                $childList = $this->getValueOptions($item['child']);
                $returnList = Util::array_merge($returnList,$childList);
            }
        }

        return $returnList;
    }

    /**
     * 获取id levaeName对
     *
     * 可适用于列表以及下拉列表
     *
     * @param null $tree
     * @return array
     */
    public function getValueGroupOptions($tree=NULL)
    {
        $tree = $tree ? $tree : $this->tree;
        $returnList = array();
        if(empty($tree))
            return $returnList;
        foreach($tree as $item) {

            if(isset($item['child'])){
                $returnList[$item[$this->fields[0]]]['label'] = $this->getLevelName($item[$this->fields[0]],$str='|',$space=' - ');

                $childList = $this->getValueGroupOptions($item['child']);
                $returnList[$item[$this->fields[0]]]['options'] = $childList;
            }else{
                $returnList[$item[$this->fields[0]]] = $this->getLevelName($item[$this->fields[0]],$str='|',$space=' - ');
            }
        }

        return $returnList;
    }
}

