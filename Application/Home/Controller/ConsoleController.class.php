<?php
/**
 * 
 * Create: cqqh
 * Date: 2015/10/29
 * Time: 9:53
 */

namespace Home\Controller;
use Think\Controller;

class ConsoleController extends Controller
{
    function __construct(){
        parent::__construct();
        if(empty($_SESSION['islogin']) && ACTION_NAME != 'login'){
            redirect(__ROOT__.'/console/login');
        }
    }

    public function index(){
        foreach(M('dish')->select() as $data){
            $dish[$data['id']] = $data;
        }

        $list = M('order_dish')->field('dr_order_dish.*,dr_order.table_num')->where('dr_order_dish.status = 0')->join('dr_order ON dr_order.id = dr_order_dish.order_id')->select();

        $this->list = $list;
        $this->dish = $dish;
        $this->display();
    }

    public function setStatus(){
        $id = I('post.id');
        $status = I('post.status');
        $res = M('order_dish')->where(['id' => $id])->save(['status' => $status]);
        if($res){
            echo json_encode(['status' => 0,'info' => 'success']);
        }
    }

    public function dish(){
        foreach(M('DishType')->select() as $data){
            $type[$data['id']] = $data['name'];
        }
        $this->list = M('dish')->select();
        $this->type = $type;
        $this->display();
    }

    public function dishEdit(){
        $id = I('get.id');
        $this->info = M('dish')->find($id);
        foreach(M('DishType')->select() as $data){
            $type[$data['id']] = $data['name'];
        }


        $this->type = $type;
        $this->display();
    }

    public function dishDel(){
        $id = I('get.id');
        M('dish')->delete($id);
        redirect(__ROOT__.'/console/dish');
    }

    public function dishSave(){
        $data['name']  = I('post.name');
        $data['type']  = I('post.type');
        $data['price'] = I('post.price');

        if($_POST['id']){
            $id = I('post.id');
        }else{
            $Dish = M("Dish");
            $id = (string)$Dish->add($data);
        }

        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     314572800;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './upload/'; // 设置附件上传根目录
        $upload->savePath  =     '1/'; // 设置附件上传（子）目录
        $upload->subName   =     ''; //
        $upload->saveName  =     $id; // 设置附件上传（子）目录
        $upload->replace   = true;
        $info   =   $upload->upload();
        if($info){
            $data['img'] = __ROOT__.'/upload/1/'.$info['img']['savename'];
        }else{
            //var_dump($upload->getError());
        }

        M("Dish")->where('id='.$id)->save($data);

        redirect(__ROOT__.'/console/dish');
    }

    public function login(){
        if(IS_POST){
            //提交登录
            if(I('post.password') == '123456'){
                $_SESSION['islogin'] = 1;
                redirect(__ROOT__.'/console/index');
            }
        }
        $this->display();
    }
}