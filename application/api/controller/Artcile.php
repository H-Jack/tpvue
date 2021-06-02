<?php


namespace app\api\controller;


use app\api\model\ArtcileModel;
use think\db\Where;
use think\Request;

class Artcile extends Base
{
    public function index(Request $request)
    {
        //获取搜索key
        $key = $request->param('key');
        //获取页数
        $page = $request->param('page');
        //获取条数
        $llimit = $request->param('limit');
        $db = new ArtcileModel();

        if(isset($key) && !empty($key)){
            $db = $db->where('content','like','%' .$key. '%');
        }
        //通过前台发送的页面及条数进行查询且返回
        $info = $db->page($page)->limit($llimit)->select();
        $total = $db->count('id');
        return json(['code'=>1,'msg'=>$info,'total'=>$total]);
    }

    public function delete(Request $request)
    {
        $id = $request->param('id');
        $db = new ArtcileModel();
        $info = $db->where('id',$id)->delete();
        if($info){
            return json(['code'=>1,'msg'=>'删除成功']);
        }
        return json(['code'=>0,'msg'=>'删除失败']);
    }

    public function save(Request $request)
    {
        $data = $request->param();
        $db = new ArtcileModel();

        if(isset($data['id']) && !empty($data['id'])){
            $res = $db->save($data,['id'=>$data['id']]);
        }else{
            $res = $db->save($data);
        }
        if($res){
            return json(['code'=>1,'msg'=>'添加成功']);
        }else{
            return json(['code'=>0,'msg'=>'添加失败']);
        }
    }
}