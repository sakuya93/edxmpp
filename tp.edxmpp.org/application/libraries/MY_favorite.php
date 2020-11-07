<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MY_favorite extends CI_Model {
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }
    public function addFavorite($data = ''){
		$data = $this->input->post();
		$bo = true;
		if($this->db->select('*')->from('coursefilm')->where('cf_id', $data['id'])->get()->num_rows() > 0){
			$bo = false;
		}else{
			if($this->db->select('*')->from('live')->where('l_id', $data['id'])->get()->num_rows() > 0){
				$bo = false;
			}else{
				if($this->db->select('*')->from('fundraisingCourse')->where('fc_id', $data['id'])->get()->num_rows() > 0){
					$bo = false;
				}
			}
		}

		if($bo){
			echo json_encode(array('status' => false, 'msg' => '無此課程資料，請刷新頁面重新嘗試'));
			return;
		}
        $insert = array(
            'cf_id' => $data['id'],
            'm_id' => $_SESSION['Mid'],
            'cf_date' => date("Y/m/d H:i:s")
        );
        if($this->db->select('*')->from('course_favorite')->where('cf_id', $insert['cf_id'])->where('m_id', $insert['m_id'])->get()->num_rows() > 0){
            if($this->db->delete('course_favorite', array('cf_id' => $insert['cf_id'], 'm_id' => $insert['m_id'])))
                return json_encode(array('status' => true, 'msg' => '刪除課程收藏成功'));
            else
                return json_encode(array('status' => false, 'msg' => '刪除課程收藏失敗'));
        }else{
            if($this->db->insert('course_favorite', $insert))
                return json_encode(array('status' => true, 'msg' => '課程收藏成功'));
            else
                return json_encode(array('status' => false, 'msg' => '課程收藏失敗'));
        }

    }


    public function checkFavorite($cf_id = ''){
        if($this->select('*')->from('course_favorite')->where('cf_id', $cf_id)->where('m_id', $_SESSION['Mid'])->get()->num_rows() == 0)
            return false;
        else
            return true;
    }

}
