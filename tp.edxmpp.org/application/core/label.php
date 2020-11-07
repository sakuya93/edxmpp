<?php

class label extends Infrastructure
{
    public function getCourseLabel()
    {
        $data = $this->input->post();
        if ($data['status'] == 0)
            $label = ($this->db->select('cf_label')->from('coursefilm')->where('cf_id', $data['id'])->get()->row())->cf_label;
        elseif($data['status'] == 1)
            $label = ($this->db->select('l_label')->from('live')->where('l_id', $data['id'])->get()->row())->l_label;
        else $label = ($this->db->select('fc_label')->from('fundraising_course')->where('fc_id', $data['id']->get()->row()->fc_lable));
        $labelArray = explode("、", $label);
        echo json_encode($labelArray);
    }

    public function getCourseLabelOption(){
        $data = $this->input->post();
        echo json_encode($this->db->select('*')->from('course_label')->get()->result());
    }

    public function checkLabelModifyPermissions($id = '', $type = '')
    {
        if ($type == 0){
            if($this->db->select('*')->from('coursefilm')->where('cf_id', $id)->where('cf_actualMovie', null)->where('t_id', $_SESSION['Tid'])->get()->num_rows() == 1)
                return false;
        }elseif($type == 1){
            if ($this->db->select('*')->from('live')->where('l_id', $id)->where('t_id', $_SESSION['Tid'])->get()->num_rows() == 1)
                return false;
        }else{
            if($this->db->select('*')->from('fundraising_course')->where('fc_id', $id)->where('t_id', $_SESSION['Tid'])->get()->num_rows() == 1)
                return false;
        }
        return true;
    }

    public function checkLabelIsNull($label = ''){
        return $this->db->select('*')->from('course_label')->where('label', $label)->get()->num_rows();
    }

    public function addFilmLabel()
    {
        $Data = $this->input->post();
        $data = array();
        $label = ($this->db->select('cf_label')->from('coursefilm')->where('cf_id', $Data[0]['id'])->get()->row())->cf_label;
        $config = array(
            0 => array('key' => 'not null', 'msg' => '資料不完整'),
            1 => array('key' => 'not null', 'msg' => '標籤不可為空'),
        );

        foreach ($Data as $x) {
            $data[] = $this->Form_security_processing($x);
        }
        if($this->checkLabelModifyPermissions($data[0]['id'], 0)){
            echo json_encode(array('status' => false, 'msg' => '這課程不是您的，您無此課程的存處權限，請刷新頁面重新嘗試'));
            return;
        }
        foreach ($data as $temp) {
            $Form_normalization = $this->Form_normalization($temp, $config);
            if (!$Form_normalization->type) {
                echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
                return;
            }
//            if($this->checkLabelIsNull($temp['label']) != 1){
//                echo json_encode(array('status' => false, 'msg' => '新增標籤中有無規定的標籤，請勿嘗試修改'));
//                return;
//            }

            if (!preg_match("/{$temp['label']}/i", $label))
                $label .= "{$temp['label']}、";
        }


        if ($this->db->where('cf_id', $data[0]['id'])->update('coursefilm', array('cf_label' => $label)))
            echo json_encode(array('status' => true, 'msg' => '新增課程標籤成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '新增課程標籤失敗'));
    }

    public function addLiveLabel()
    {
        $Data = $this->input->post();

        $data = array();
        $label = ($this->db->select('l_label')->from('live')->where('l_id', $Data[0]['id'])->get()->row())->l_label;
        $config = array(
            0 => array('key' => 'not null', 'msg' => '資料不完整'),
            1 => array('key' => 'not null', 'msg' => '標籤不可為空'),
        );
        foreach ($Data as $x) {
            $data[] = $this->Form_security_processing($x);
        }
        if($this->checkLabelModifyPermissions($data[0]['id'], 1)){
            echo json_encdoe(array('status' => false, 'msg' => '這課程不是您的，您無此課程的存處權限，請刷新頁面重新嘗試'));
            return;
        }
        foreach ($data as $temp) {
            $Form_normalization = $this->Form_normalization($temp, $config);
            if (!$Form_normalization->type) {
                echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
                return;
            }
//            if($this->checkLabelIsNull($temp['label']) != 1){
//                echo json_encode(array('status' => false, 'msg' => '新增標籤中有無規定的標籤，請勿嘗試修改'));
//                return;
//            }
            if (!preg_match("/{$temp['label']}/i", $label))
                $label .= "{$temp['label']}、";
        }

        if ($this->db->where('l_id', $data[0]['id'])->update('live', array('l_label' => $label)))
            echo json_encode(array('status' => true, 'msg' => '新增課程標籤成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '新增課程標籤失敗'));
    }

    public function addFundraisingLabel()
    {
        $Data = $this->input->post();
        $data = array();

        $label = ($this->db->select('fc_label')->from('fundraising_course')->where('fc_id', $Data[0]['id'])->get()->row())->fc_label;
        $config = array(
            0 => array('key' => 'not null', 'msg' => '資料不完整'),
            1 => array('key' => 'not null', 'msg' => '標籤不可為空'),
        );
        foreach ($Data as $x) {
            $data[] = $this->Form_security_processing($x);
        }
        if($this->checkLabelModifyPermissions($data[0]['id'], 3)){
            echo json_encdoe(array('status' => false, 'msg' => '這課程不是您的，您無此課程的存處權限，請刷新頁面重新嘗試'));
            return;
        }
        foreach ($data as $temp) {
            $Form_normalization = $this->Form_normalization($temp, $config);
            if (!$Form_normalization->type) {
                echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
                return;
            }
//            if($this->checkLabelIsNull($temp['label']) != 1){
//                echo json_encode(array('status' => false, 'msg' => '新增標籤中有無規定的標籤，請勿嘗試修改'));
//                return;
//            }
            if (!preg_match("/{$temp['label']}/i", $label))
                $label .= "{$temp['label']}、";
        }

        if ($this->db->where('fc_id', $data[0]['id'])->update('fundraising_course', array('fc_label' => $label)))
            echo json_encode(array('status' => true, 'msg' => '新增課程標籤成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '新增課程標籤失敗'));
    }

    public function deleteCourseLabel()
    {
        $data = $this->input->post();

        $config = array(
            0 => array('key' => 'not null', 'msg' => '資料不完整'),
            1 => array('key' => 'not null', 'msg' => '標籤不可為空'),
            2 => array('key' => 'not null', 'msg' => '資料不完整'),
        );
        if($this->checkLabelModifyPermissions($data['id'], $data['status'])){
            echo json_encode(array('status' => false, 'msg' => '這課程不是您的，您無此課程的存取權，請刷新頁面重新嘗試'));
            return;
        }
        if ($data['status'] == 0)
            $label = ($this->db->select('cf_label')->from('coursefilm')->where('cf_id', $data['id'])->get()->row())->cf_label;
        elseif ($data['status'] == 1)
            $label = ($this->db->select('l_label')->from('live')->where('l_id', $data['id'])->get()->row())->l_label;
        else
            $label = ($this->db->select('fc_label')->from('fundraising_course')->where('fc_id', $data['id'])->get()->row())->fc_label;
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }
        $label = str_replace($data['label'] . "、", "", $label);

        if ($data['status'] == 0) {
            if ($this->db->where('cf_id', $data['id'])->update('coursefilm', array('cf_label' => $label)))
                echo json_encode(array('status' => true, 'msg' => '刪除課程標籤成功'));
            else
                echo json_encode(array('status' => false, 'msg' => '刪除課程標籤失敗'));
        } elseif ($data['status'] == 1) {
            if ($this->db->where('l_id', $data['id'])->update('live', array('l_label' => $label)))
                echo json_encode(array('status' => true, 'msg' => '刪除課程標籤成功'));
            else
                echo json_encode(array('status' => false, 'msg' => '刪除課程標籤失敗'));
        } else {
            if ($this->db->where('fc_id', $data['id'])->update('fundraising_course', array('fc_label' => $label)))
                echo json_encode(array('status' => true, 'msg' => '刪除課程標籤成功'));
            else
                echo json_encode(array('status' => false, 'msg' => '刪除課程標籤失敗'));
        }

    }

    public function studentAddFilmLabel()
    {
        $Data = $this->input->post();
        $data = array();
        $label = ($this->db->select('cf_studentLabel')->from('coursefilm')->where('cf_id', $Data[0]['id'])->get()->row())->cf_studentLabel;
        $config = array(
            0 => array('key' => 'not null', 'msg' => '資料不完整'),
            1 => array('key' => 'not null', 'msg' => '標籤不可為空'),
        );

        foreach ($Data as $x) {
            $data[] = $this->Form_security_processing($x);
        }

        foreach ($data as $temp) {
            $Form_normalization = $this->Form_normalization($temp, $config);
            if (!$Form_normalization->type) {
                echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
                return;
            }

            if (strpos(mb_convert_encoding($label, 'utf-8'), mb_convert_encoding($temp['label'], 'utf-8')) === false)
                $label .= "{$temp['label']}、";
        }


        if ($this->db->where('cf_id', $data[0]['id'])->update('coursefilm', array('cf_studentLabel' => $label)))
            echo json_encode(array('status' => true, 'msg' => '新增課程標籤成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '新增課程標籤失敗'));
    }

    public function studentAddLiveLabel()
    {
        $Data = $this->input->post();
        $data = array();
        $label = ($this->db->select('l_studentLabel')->from('live')->where('l_id', $Data[0]['id'])->get()->row())->l_studentLabel;
        $config = array(
            0 => array('key' => 'not null', 'msg' => '資料不完整'),
            1 => array('key' => 'not null', 'msg' => '標籤不可為空'),
        );
        foreach ($Data as $x) {
            $data[] = $this->Form_security_processing($x);
        }
        foreach ($data as $temp) {
            $Form_normalization = $this->Form_normalization($temp, $config);
            if (!$Form_normalization->type) {
                echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
                return;
            }
            if (strpos(mb_convert_encoding($label, 'utf-8'), mb_convert_encoding($temp['label'], 'utf-8')) === false)
                $label .= "{$temp['label']}、";
        }

        if ($this->db->where('l_id', $data[0]['id'])->update('live', array('l_studentLabel' => $label)))
            echo json_encode(array('status' => true, 'msg' => '新增課程標籤成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '新增課程標籤失敗'));
    }

    public function deleteStudentCourseLabel()
    {
        $Data = $this->input->post();
        $data = array();
        foreach ($Data as $x) {
            $data[] = $this->Form_security_processing($x);
        }
        $config = array(
            0 => array('key' => 'not null', 'msg' => '資料不完整'),
            1 => array('key' => 'not null', 'msg' => '標籤不可為空'),
        );
        if ($data['status'] == 0)
            $label = ($this->db->select('cf_studentLabel')->from('coursefilm')->where('cf_id', $data['id'])->get()->row())->cf_studentLabel;
        else
            $label = ($this->db->select('l_studentLabel')->from('live')->where('l_id', $data['id'])->get()->row())->l_studentLabel;

        foreach ($data as $temp) {
            $Form_normalization = $this->Form_normalization($temp, $config);
            if (!$Form_normalization->type) {
                echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
                return;
            }
            mb_ereg_replace($temp['label'] . "、", "", $label);
        }
        if ($data['status'] == 0) {
            if ($this->db->where('cf_id', $data[0]['id'])->update('coursefilm', array('cf_studentLabel' => $label)))
                echo json_encode(array('status' => true, 'msg' => '刪除課程標籤成功'));
            else
                echo json_encode(array('status' => false, 'msg' => '刪除課程標籤失敗'));
        } else {
            if ($this->db->where('l_id', $data[0]['id'])->update('live', array('l_studentLabel' => $label)))
                echo json_encode(array('status' => true, 'msg' => '刪除課程標籤成功'));
            else
                echo json_encode(array('status' => false, 'msg' => '刪除課程標籤失敗'));
        }
    }
}