<?php
/**
 * Created by PhpStorm.
 * User: del
 * Date: 2018/1/2
 * Time: 19:58
 */
namespace app\index\controller;
use think\Controller;
use Think\Model;
use app\index\model\User;
class Regist extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    public function do_register()
    {
        $username=$_POST['username'];
        $password=$_POST['password'];
        $repassword=$_POST['repassword'];
        if(empty($username))
        {
            $this->error('用户名不能为空');
        }
        if(empty($password))
        {
            $this->error('密码不能为空');
        }
        if($password != $repassword)
        {
            $this->error('确认密码错误');
        }
        //检测用户是否已注册
        $model=new User();
        $user=$model->where(array('username'=>$username))->find();
        if (!empty($user))
        {
            $this->error('用户名已存在');
        }
        $data=array(
            'username'=>$username,
            'password'=>md5($password),
            'createAt'=>time()
        );
        if(!($model->create($data)))
        {
            $this->error('注册失败',$model->gerDbError());
        }
        $this->success('注册成功，请登录','/message/public/index/loan');
    }
}