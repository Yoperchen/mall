<?php
/**
 * 文章管理
 * @author Yoper www.weiyuekj.com
 * @E-mail 944975166@qq.com
 */
namespace Custom\Controller;

class ArticleController extends UserController {

    function __construct()
    {
        parent::__construct();
        $this->article_logic =  new \Common\Logic\ArticleLogic();
    }
    public function index(){
        
    }
    public function article_list(){
//         $type  =I('request.type',\Common\Logic\CommentLogic::COMMENT_TYPE_GOODS);
        $author =I('request.author');
        $title=I('request.title');
        $status=I('request.status');
        $sort=I('request.sort');
        $start_time=I('request.start_time');
        $end_time=I('request.end_time');
        
        $c_id   =session('c_id');
        
        $page_size=I('request.page_size',10);
        
        $condition=[];
        $c_id && $condition['c_id']=$c_id;
//         $type   && $condition['type']=$type;
        $author && $condition['author']=$author;
        $title && $condition['title']=['like',"%$title%"];
        $status!=''&&$condition['status']=$status;
        $sort!=''  &&$condition['sort']=$sort;
        $start_time&& $condition['add_time']=['EGT',strtotime($start_time)];
        $end_time  && $condition['add_time']=['ELT',strtotime($end_time)];
        
        try{
            $result=$this->article_logic->article_list($condition,'*',$page_size);
        }catch(\Exception $e){
            $result['status']=$e->getCode();
            $result['message']=$e->getMessage();
            $this->error($result['message']);
        }
        
        IS_AJAX && $this->ajaxReturn($result);
        
        $this->assign('article_list_result',$result);
        $this->display();
    }
    public function article_edit()
    {
        $id =I('request.id',0,'int');
        $author =I('request.author');
        $from_url=I('request.from_url');
        $title=I('request.title');
        $article_img=I('request.article_img');
        $cate_id=I('request.cate_id');
        $description=I('request.description');
        $content=I('request.content');
        $type  =I('request.type');
        $status=I('request.status');
        $sort=I('request.sort');
        $c_id=session('c_id');
        
        $cate_list_result = $this->article_logic->cate_list(['c_id'=>$c_id]);
        if(IS_POST && ($title||$status!='')){
            $data=[];
            $c_id && $data['c_id']=$c_id;
            $author && $data['author']=$author;
            $title  && $data['title']=$title;
            $article_img&& $data['article_img']=$article_img;
            $cate_id&&$data['cate_id']=$cate_id;
//             $type && $data['type']=$type;
            $from_url && $data['from_url']=$from_url;
            $description && $data['description']=$description;
            $content && $data['content']=$content;
            $sort && $data['sort']=$sort;
            $status!='' && $data['status']=$status;
    
            $id && $result = $this->article_logic->update_article(['id'=>$id,'c_id'=>$c_id],$data);
           !$id && $result = $this->article_logic->add_article($data);
           
            IS_AJAX && $this->ajaxReturn($result);
            $this->success($result['message']);
        }
        $id && $article_info_result=$this->article_logic->article_info(['id'=>$id,'c_id'=>$c_id]);
        IS_AJAX && $this->ajaxReturn($article_info_result);
        $this->assign('article_info_result',$article_info_result);
        $this->assign('cate_list_result',$cate_list_result);
        $this->display();
    }
    public function delete() {
        $ids=I('request.id');
        $c_id = session('c_id');
        $condition=[];
        $condition['c_id']=$c_id;
        $condition['id']=['in',$ids];
        $is_delete = M('Article')->where($condition)->delete();
        $result=[];
        $result['status']=$is_delete?1:0;
        $this->ajaxReturn($result);
    }
    public function is_status(){
        $id = I('request.id',0,'int');
        $where['c_id'] = $this->c_id;
        $where['id']=$id;
        $Article=M("Article");
        $info = $Article->find($id);
        $status = $info['status']?0:1;
        $Article->status = $status;
        $is_save=$Article->where($where)->save();
        $this->ajaxReturn(['status'=>$status]);
    }
    public function cate_list() {
        $name=I('request.name');
        $page_size=I('request.page_size');
        
        $condition=[];
        $condition['c_id']=session('c_id');
        $condition['name']=['like',"%$name%"];
        
        $cate_list_result = $this->article_logic->cate_list($condition,'*',$page_size);
        
        $this->assign('cate_list_result',$cate_list_result);
        IS_AJAX && $this->ajaxReturn($cate_list_result);
        $this->display();
    }
    public function cate_edit() {
        $id=I('request.id',0,'int');
        $p_id=I('request.p_id');
        $thumb=I('request.thumb');
        $name=I('request.name');
        $status=I('request.status');
        $sort=I('request.sort');
        $c_id=session('c_id');
        
        $cate_list_result = $this->article_logic->cate_list(['c_id'=>$c_id]);
        $id && $cate_info_result = $this->article_logic->cate_info(['id'=>$id,'c_id'=>$c_id]);
        
        if(IS_POST && ($name||$status!='')){
            $data['c_id']=$c_id;
            $p_id && $data['p_id']=$p_id;
            $thumb&& $data['thumb']=$thumb;
            $name && $data['name']=$name;
            $status!='' && $data['status']=$status;
            $sort && $data['sort']=$sort;
            $id && ($result=$this->article_logic->update_cate(['id'=>$id,'c_id'=>$c_id], $data));
           !$id && ($result=$this->article_logic->add_cate($data));
           
            IS_AJAX && $this->ajaxReturn($result);
            $this->success($result['message']);
        }
        
        IS_AJAX && $this->ajaxReturn($cate_list_result);
        
        $this->assign('cate_list_result'   ,$cate_list_result);
        $this->assign('cate_info_result',$cate_info_result);
        $this->display();
    }
    public function delete_cate() {
        $ids=I('request.id');
        $c_id = session('c_id');
        $condition=[];
        $condition['c_id']=$c_id;
        $condition['id']=['in',$ids];
        $is_delete = M('Article_cate')->where($condition)->delete();
        $result=[];
        $result['status']=$is_delete?1:0;
        $this->ajaxReturn($result);
    }
    public function is_status_cate() {
        ;
    }
}