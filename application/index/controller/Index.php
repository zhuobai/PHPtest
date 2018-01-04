<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\message;
use Think\Page;
use Think\Model;

class Index extends Controller
{
    /**
     * 检测登录
     */
    public function checkLogin()
    {
        if(!session('user.userId'))
        {
            $this->error('请登录','/message/public/index/loan');
        }
     //   return "尽量了";
    }

    /**
     * 留言列表
     */
   public function index()
   {
     $this->checkLogin();
       // 查询状态为1的用户数据 并且每页显示10条数据
//       $list = User::where('status',1)->paginate(10);
//// 获取分页显示
//       $page = $list->render();
//// 模板变量赋值
//       $this->assign('list', $list);
//       $this->assign('page', $page);
//// 渲染模板输出
//       return $this->fetch();
      $model=new Message();
      $list=$model::paginate(3);
      //获取分页显示
       $page=$list->render();
       //模板变量赋值
       $this->assign('list',$list);
       $this->assign('page',$page);
       return $this->fetch();
    }
    /**
     * 发表留言
     */
    public function post()
    {
        $this->checkLogin();
        return $this->fetch();
    }

    /**
     * 留言处理
     */
    public function do_post()
    {
        $this->checkLogin();//检测是否登录
        $content=$_POST['content'];
        if(empty($content))
        {
            $this->error('留言内容不能为空');
        }
        if(mb_strlen($content,'utf-8')>100)
        {
            $this->error('留言内容不能多于100字');
        }
        $model=new Message();
        $userId=session('user.userId');
        $data=array(
            'content'=>$content,
            'createdAt'=>time(),
            'userId'=>$userId
        );
        if(!$model->save($data))
        {
            $this->error('留言失败');
        }
        $this->success('留言成功','/message/public/index/index');
    }
    /**
     * 删除留言
     */
    public function delete($id)
    {
        if(empty($id))
        {
            $this->error('缺少参数');
        }
        $this->checkLogin();
        $model=new Message();
        if(!$model->where(array('messageId'=>$id,'userId'=>session('user.userId')))->delete())
        {
            $this->error('删除失败');
        }
        $this->success('删除成功','index');
    }
}
