<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        foreach(M('Dish')->select() as $data){
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

    public function preview(){
        //var_dump(array_keys($_POST));

        $map['id'] = ['in', array_keys($_POST)];
        $quantity = 0;
        $total = 0;
        foreach(M('dish')->where($map)->select() as $data){
            if(!$_POST[$data['id']]) continue;
            $data['quantity'] = $_POST[$data['id']];
            $data['total'] = $data['quantity'] * $data['price'];
            $list[$data['id']] = $data;
            $quantity +=$data['quantity'];
            $total +=$data['total'];
        }

        $this->list = $list;
        $this->quantity = $quantity;
        $this->total = $total;
        $this->display();
    }

    public function submit(){
        $map['id'] = ['in', array_keys($_POST)];
        $quantity = 0;
        $total = 0;

        $order['table_num'] = $_POST['table_num'];
        $order['customer_num'] = $_POST['customer_num'];
        $order['desc'] = $_POST['desc'];
        $order_id = M('order')->add($order);

        foreach(M('dish')->where($map)->select() as $data){
            if(!$_POST[$data['id']]) continue;
            $data['quantity'] = $_POST[$data['id']];
            $data['total'] = $data['quantity'] * $data['price'];
            $list[$data['id']] = $data;
            $quantity +=$data['quantity'];
            $total +=$data['total'];
            M('order_dish')->add(['order_id' => $order_id, 'dish_id' => $data['id'], 'num' => $data['quantity']]);
        }

        echo json_encode($list);
    }
}