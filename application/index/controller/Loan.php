<?php
/**
 * Created by PhpStorm.
 * User: del
 * Date: 2018/1/2
 * Time: 19:59
 */
namespace app\index\controller;
use think\Controller;
use app\index\model\User;
class Loan extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    public function login()
    {
       $username=$_POST['username'];
       $password=$_POST['password'];
       $model=new User();
       $user=$model->where(array('username'=>$username))->find();
       if(empty($user) ||$user['password']!=md5($password))
       {
           $this->error('账号或密码错误');
       }
       //写入session
        session('user.userId',$user['userId']);
        session('user.username',$user['username']);
        $_SESSION['username']=$user['username'];
        //跳转首页
        $this->success('登录成功','index/index');
    }
    /**
     * 退出登录
     */
    public function logout()
    {
        if (!session('user.userId'))
        {
            $this->error('请登录');
        }
        session_destroy();
        $this->success('退出登录成功','index/index');
    }
}