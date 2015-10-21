<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $res = M('Dish')->select();

        foreach($res as $data){
            $list[$data['type']][] = $data;
        }

        $this->type = M('DishType')->select();
        $this->list = $list;
        $this->title = 'index';
        $this->display();
    }
    
    public function test(){
        $res = M('dish')->select();
        var_dump($res);
    }

    public function submit(){
        var_dump($_POST);
    }
}