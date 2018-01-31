<?php
/**
 * 文章逻辑类
 * @author Yoper www.linglingtang.com
 * @E-mail 944975166@qq.com
 */
namespace Common\Logic;
use \Common\Logic\BaseLogic;
class ArticleLogic extends BaseLogic
{
    //主表
    private $article_obj;
    private $article_cate_obj;

    public function __construct()
    {
        $this->article_obj=M('Article');
        $this->article_cate_obj=M('Article_cate');
    }

    public function add_article($data){
        $result=['status'=>self::RETURN_STATUS_ERR];
        
        $upload_result=upload_img("data/{$data['c_id']}/article/");
        $upload_result['data']['list']['article_img']['url'] && ($data['article_img']=$upload_result['data']['list']['article_img']['url']);
        
        $data['add_time']=time();
        $is_add = $this->article_obj->data($data)->add();
        
        $data['id']=$is_add;
        $result['status'] =$is_add?self::RETURN_STATUS_OK:self::RETURN_STATUS_ERR;
        $result['message']=$is_add?L('adsuccess'):L('adfailed');
        $result['data']   =$data;
        return $result;
    }
    /**
     * 
     * @param array $condition
     * @param string $field
     */
    public function article_info($condition,$field='*'){
        $result=['status'=>self::RETURN_STATUS_ERR];
        
        $data=$this->article_obj->where($condition)->field($field)->cache(10)->find();
        $data['content']=str_replace("/ueditor",'https://'.$_SERVER['HTTP_HOST'].'/ueditor',$data['content']);
        $result['status']=self::RETURN_STATUS_OK;
        $result['message']='成功';
        $result['data']=$data;
        return $result;
    }
    /**
     * 列表
     * @param array $condition
     * @param int $page_size
     */
    public function article_list( $condition,$field='*', $page_size=15){
        $result=['status'=>self::RETURN_STATUS_ERR];
        $count = $this->article_obj->where($condition)->count();// 查询满足要求的总记录数
        $Page  = new \Think\Page($count,$page_size);// 实例化分页类 传入总记录数和每页显示的记录数
        $show  = $Page->show();// 分页显示输出
        $list  = $this->article_obj->where($condition)->field($field)->order('sort,id desc')->limit($Page->firstRow.','.$Page->listRows)->cache(true,5)->select();
        
        $result['status']=self::RETURN_STATUS_OK;
        $result['message']=L('success');
        $result['data']['page']=['count'=>$count,'page_size'=>$page_size,'page'=>$Page->nowPage,'page_str'=>$show];
        $result['data']['list']=$list;
        return $result;
    }
    public function update_article($condition,$data){
        $result=['status'=>self::RETURN_STATUS_ERR];
        if(empty($data)){
            throw new \Exception('数据不能为空',0);
        }
        $upload_result = upload_img("data/{$data['c_id']}/article");
        $upload_result['data']['list']['article_img']['url'] && ($data['article_img']=$upload_result['data']['list']['article_img']['url']);
        
        $data['update_time']=time();
        $is_save = $this->article_obj->where($condition)->save($data);
        
        $result['status']=$is_save?self::RETURN_STATUS_OK:self::RETURN_STATUS_ERR;
        $result['message']=$is_save?L('edsuccess'):L('edfailed');
        return $result;
    }
    public function cate_info($condition,$field='*'){
        $result=['status'=>self::RETURN_STATUS_ERR];
        
        $data=$this->article_cate_obj->where($condition)->field($field)->find();
        $result['status']=self::RETURN_STATUS_OK;
        $result['message']='成功';
        $result['data']=$data;
        return $result;
    }
    public function cate_list( $condition,$field='*', $page_size=15){
        $result=['status'=>self::RETURN_STATUS_ERR];
        $condition['p_id']=0;
        $count = $this->article_cate_obj->where($condition)->count();// 查询满足要求的总记录数
        $Page  = new \Think\Page($count,$page_size);// 实例化分页类 传入总记录数和每页显示的记录数
        $show  = $Page->show();// 分页显示输出
        $list  = $this->article_cate_obj->where($condition)->field($field)->order('sort,id desc')->limit($Page->firstRow.','.$Page->listRows)->cache(true,5)->select();
        foreach($list as $cate_info){
            $p_ids[]=$cate_info['id'];
        }
        if($p_ids){
            $c_condition['p_id']=['in',$p_ids];
            $c_list=$this->article_cate_obj->where($c_condition)->field($field)->order('sort,id desc')->limit('0,100')->cache(true,5)->select();
            
            foreach($list as &$cate_info){
                foreach($c_list as $key=>$c_cate_info){
                    if($cate_info['id']==$c_cate_info['p_id']){
                        $cate_info['children'][]=$c_cate_info;
                        unset($c_list[$key]);
                    }
                }
            }
        }
        
        $result['status']=self::RETURN_STATUS_OK;
        $result['message']=L('success');
        $result['data']['page']=['count'=>$count,'page_size'=>$page_size,'page'=>$Page->nowPage,'page_str'=>$show];
        $result['data']['list']=$list;
        
        return $result;
    }
    public function add_cate($data){
        $result=['status'=>self::RETURN_STATUS_ERR];
        
        $upload_result=upload_img("data/{$data['c_id']}/article/cate");
        $upload_result['data']['list']['thumb']['url'] && ($data['thumb']=$upload_result['data']['list']['thumb']['url']);
        
        $is_add = $this->article_cate_obj->data($data)->add();
        
        $result['status']=$is_add?self::RETURN_STATUS_OK:self::RETURN_STATUS_ERR;
        $result['message']=$is_add?L('adsuccess'):L('adfailed');
        return $result;
    }
    public function update_cate($condition,$data) {
        $result=['status'=>self::RETURN_STATUS_ERR];
        if(empty($data)){
            throw new \Exception('数据不能为空',0);
        }
        $upload_result = upload_img("data/{$condition['c_id']}/article/cate");
        $upload_result['data']['list']['thumb']['url'] && ($data['thumb']=$upload_result['data']['list']['thumb']['url']);
        $is_save = $this->article_cate_obj->where($condition)->save($data);
        
        $result['status']=$is_save?self::RETURN_STATUS_OK:self::RETURN_STATUS_ERR;
        $result['message']=$is_save?L('edsuccess'):L('edfailed');
        return $result;
    }
}