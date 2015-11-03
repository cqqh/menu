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
}