<?php
namespace Admin\Controller;
use       Think\Controller;
use Think\Log;

//header('ManegeController.class.php');
class SearchController extends  Controller{

    function _empty($id){
//        $is_num = is_numeric($id);
//        if($is_num){
//        }else{
//            echo '非数字';
//        }
        $int = 'd';
        if (!filter_var($int,FILTER_VALIDATE_INT)) {
            echo("no valid");
            http_request();
        }else{
            echo("a valid");

        }
    }
    function index(){
        $this->display();
    }
    function search(){
        $s_id = null;
        $info = array();
        if(!empty($_POST)){
            $s_id = $_POST['s_id'];
            $term = $_POST['term'];

            $info = $this->get_score_by_id($s_id,$term);
            $info['term'] = $term;

            if ($info['class_rank'] == NULL && $info['major_rank'] == NULL) {
                if($s_id == "" || !is_numeric($s_id)){
                    $str = '学号不能为空并且为数字';
                    $this->assign('warn',$str);
                    $this->assign('iswarn',1);
                    $this->display();
                }
                $this->display();
                return;
             }
            else{
                $this->assign('ishave',1);
                $this->assign('list',$info);
//                echo print_r($info);
                Log::init();
                Log::record($info,Log::INFO);
                Log::save();
                $this->assign('s_id',$s_id);
                $this->display();
                return;
             }
        }
        
    }

    function personScoreDetail(){
        $info = null;
        $id = null;
        $term = null;

        if(!empty($_POST)){
            $id = $_POST['s_id'];
            $term = $_POST['term'];
        }else{
            $id = $_GET['s_id'];
            $term = $_GET['term'];
        }

        if(!empty($id)){
            $model = M('scoredetail');
            if (student_is_exist($id)) {
                $info = $model->where('s_id='.'"' . $id . '" and sc_term like '.'"'.$term.'%"')->select();
            }
        }

        $title = '个人积分详情';

        $this->viewTheDetail($info,$title,1,$term);
    }
    function classScoreDetail(){

        $info = null;
        $term = null;

        if(!empty($_POST)){
            $class_name = $_POST['c_name'];
            $term       = $_POST['term'];

            if (!empty($class_name)) {
                $info = $this->get_class_detail_data($class_name,$term);
            }
        }
        $title = '班级积分详情';

        $this->viewTheDetail($info,$title,0,$term);
    }
    function  getTerm(){
        $model = M('scoredetail');
        if(!empty($_POST)){
            $info = $model->field('sc_term')->group('sc_term')->select();
            echo json_encode($info);
        }
    }
    function getNews(){

    }
    /**
     * @param $info 班级积分详情信息
     * @param $title 标题
     * @param $isperson 是否是个人  0 否；1 是
     * @param $term 学期
     */
    private function viewTheDetail($info,$title,$isperson,$term){

        $this->assign('term',$term);
        $this->assign('title',$title);
        $this->assign('isPerson',$isperson);
        $this->assign('list',$info);

        $this->display('viewTheDetail');
    }

    /**
     * @param $id 个人学号
     * @param $term 学期
     * @return bool 查询是否成功
     */

   private function get_score_by_id($id,$term){

    	$model = M('student');

    	if (!empty($id)) {
    		if (student_is_exist($id)) {
                $info = $model->where('s_id='.'"'.$id.'"')->select();
                if (count($info)) {
                    $class_name = $info[0]['c_name'];
                    $data_class = $this->get_class_ranking($id,$class_name,$term);
                    $major_name = $this->get_major($class_name);

                    $data_major = $this->get_major_ranking($id,$major_name,$term);

                    $data['name'] = $info[0]['s_name'];
                    $data['id'] = $data_class['sid'];
                    $data['class_rank'] = $data_class['rank'];
                    $data['major_rank'] = $data_major['rank'];
                    $data['all_score'] = $data_major['all_score'];
                    $data['class_name'] = $class_name;
                    return $data;   
                }else{
                    return false;
                }
    		}
    	}
    }


   private function  get_major($class_name){
        if(!empty($class_name)){
            $major_name = substr($class_name,0,strlen($class_name)-1);

            return $major_name;
        }

    }
   private function  get_major_detail_data($major,$term){
        $model = M('scoredetail');
        $info = $model->field('s_id,c_name,sum(sc_number)')->where('c_name like'.'"'.$major.'%" and sc_term like'.'"'.$term.'%"')->order('sum(sc_number) desc')->group('s_id')->select();

        return $info;
    }

   private function  get_class_detail_data($class_name,$term){
        $model = M('scoredetail');
        $info = $model->field('s_id,s_name,sum(sc_number)')->where('c_name like'.'"'.$class_name.'%" and sc_term like'.'"'.$term.'%"')->order('sum(sc_number) desc')->group('s_id')->select();

        foreach($info as $k => $v){
            $info[$k]['all_score'] = sprintf("%.2f",floatval( $v['sum(sc_number)']));
        }
        return $info;
    }
    /*
     * 获取专业排名
     **/
    /**
     * @param $id
     * @param $major
     * @param $term
     * @return array
     */
   private function get_major_ranking($id,$major,$term){
        $detal_rank = array();

        $info = $this->get_major_detail_data($major,$term);
//       echo "总数：".count($info);
//        echo '<pre>';
//        print_r($info);
//        echo '</pre>';
        if(!empty($info)){
            foreach($info as $key => $value){
                if($value['s_id'] == $id){
                    $detal_rank['rank'] = $key + 1;
                    $detal_rank['sid']  = $id;
                    $detal_rank['all_score'] = sprintf("%.2f",floatval( $value['sum(sc_number)']));
                    return $detal_rank;
                }
            }
        }
    }
    /*
     * 获取班级排名
     * */
   private function get_class_ranking($id,$class_name ,$term){
        $detal_rank = array();
        $info = $this->get_class_detail_data($class_name ,$term);
        if(!empty($info)){
            foreach($info as $key => $value){
                if($value['s_id'] == $id){
                    $detal_rank['rank'] = $key + 1;
                    $detal_rank['sid']  = $id;
                    $detal_rank['all_score'] = sprintf("%.2f",floatval( $value['sum(sc_number)']));

                    return $detal_rank;
                }
            }
        }
    }

}
