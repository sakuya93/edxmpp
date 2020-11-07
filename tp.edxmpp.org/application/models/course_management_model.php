<?php

class course_management_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function getCourses_data($t_id = '')
    {
        return $this->db->select("live.*, COUNT(shoppingcart.l_id) as buyNumber")
            ->from('live')
            ->where('live.t_id', $t_id)
            ->join("shoppingcart", "shoppingcart.l_id = live.l_id and sc_payStatus = 1", 'left')
            ->group_by("live.l_id")
            ->get()->result();
    }

    /*[TODO] start-----目前兩個方法較耗效能;待改成第一個註解的方法*/

//    public function getFilm_data($t_id = '')
//    {
//        return $this->db->select('coursefilm.*, COUNT(shoppingcart.cf_id) as buyNumber')
//            ->from('coursefilm')
//            ->where('coursefilm.t_id', $t_id)
//            ->join('shoppingcart', 'shoppingcart.cf_id = coursefilm.cf_id', 'left outer')
//            ->group_by('coursefilm.cf_experienceFilm')
//            ->order_by('cf_actualMovieIndex', 'asc')
//            ->get()->result();
//    }

    public function getFilm_data($t_id = '')
    {
        return $this->db->select('*')
            ->from('coursefilm')
            ->where('t_id', $t_id)
            ->get()->result();
    }

    public function getBuyNumber($cf_id = '')
    {
        return $this->db->select('COUNT(cf_id) as buyNumber')
            ->from('shoppingcart')
            ->where('cf_id', $cf_id)
            ->get()->result();
    }

    /*[TODO] end*/

    //取得是否已經設定課程價格的狀態(直播)
    public function getPriceStatus($l_id = '')
    {
        return $this->db->select('cd_discountedPrices')->from('courtdiscount')->where('l_id', $l_id)->get()->num_rows();
    }

    public function getFundraisingCourseData()
    { //取得募資課程資料
        return $this->db->select("fundraising_course.*, IF(fc_conversionStatus = 1, IF(fc_type = 0, 'live/', 'film/'), 'fundraisingCourse/') AS imagePath")
            ->from('fundraising_course')
            ->where('t_id', $_SESSION['Tid'])
            ->get()->result();
    }

    public function getFundraisingCourseDataOneRecords($fc_id = '')
    {
        return $this->db->select('fundraising_course.*')
            ->from('fundraising_course')
            ->where('fc_id', $fc_id)
            ->get()->row();
    }

    public function getNumberLessonsPreferential($id = '')
    {
        return $this->db->select('cd_number as number, cd_discountedPrices as discountedPrice, cd_currency AS currency')->from('courtdiscount')->where('l_id', $id)->get()->result();
    }

    public function getClassMode($l_id = '')
    {
        return ($this->db->select('l_classMode')->from('live')->where('l_id', $l_id)->get()->row())->l_classMode;
    }

    public function checkCourseNote($lt_id = '')
    {
        return $this->db->select('lt_note')->from('liveTime')->where('lt_id', $lt_id)->get()->row();
    }

    public function setNumberLessonsPreferential($dataArray = '')
    {
        return $this->db->insert_batch('courtdiscount', $dataArray);
    }

    public function setNumberLessonsPreferentialOneStoke($data = '')
    {
        return $this->db->insert('courtdiscount', $data);
    }

    public function deleteNumberLessonsPreferential($id = '')
    {
        return $this->db->delete('courtdiscount', array('l_id' => $id));
    }

    public function checkMatchTime($id = '')
    {
        if ($this->db->select('*')->from('live')->where('t_id', $_SESSION['Tid'])->where('l_id', $id)->get()->num_rows() == 0)
            return true;
        else
            return false;
    }

    public function checkAuthority()
    {
        return $this->db->select('*')->from('teacher')->where('t_id', $_SESSION['Tid'])->where('t_teamsAccount !=', null)->get()->num_rows();
    }

    public function checkLiveDate($dataArray)
    {
        if ($this->db->select('*')->from('liveTime')->where('t_id', $_SESSION['Tid'])->where('l_id', $dataArray['l_id'])->where('lt_time', $dataArray['lt_time'])->get()->num_rows() == 0)
            return true;
        else
            return false;
    }

    public function addLiveTime($dataArray)
    {
        return $this->db->insert('liveTime', $dataArray);
    }

    public function editLiveTime($dataArray, $id)
    {
        $data = $this->db->select('*')->from('liveTime')->where('lt_id', $id)->get()->row();
        $dataArray['lt_lastPeople'] = $dataArray['lt_maxPeople'] - ($data->lt_maxPeople - $data->lt_lastPeople);
        return $this->db->where('lt_id', $id)->update('liveTime', $dataArray);
    }

    public function deleteLiveTime($id = '')
    {
        $this->db->delete('liveTime', array('lt_id' => $id));
        $this->db->delete('liveMatchTime', array('lt_id' => $id));
    }

    public function checkMatchTimeIsMatch($id = '')
    {
        return $this->db->select('*')->from('liveMatchTime')->where('lt_id', $id)->get()->num_rows();
    }


    public function getMatchTime($id = '')
    {
        return $this->db->select('lt_maxPeople AS maxPeople, lt_time AS time, lt_id AS id, lt_lastPeople AS lastPeople, lt_note AS note')->from('liveTime')->where('l_id', $id)->get()->result();
    }

    public function getClassName($id = '')
    {
        return $this->db->select('l_actualMovie')->from('live')->where('l_id', $id)->get()->row()->l_actualMovie;
    }

    public function updateRelease($dataArray = '', $type = '')
    {
        if ($type == 'live')
            return $this->db->update_batch('live', $dataArray, 'l_id');
        else
            return $this->db->update_batch('coursefilm', $dataArray, 'cf_id');
    }

    public function updateLiveURL($l_id = '', $url = '', $lt_id)
    {
        return $this->db->where('l_id', $l_id)->update('live', array('l_url' => $url, 'l_status' => '1', 'lt_id' => $lt_id));
    }

    public function cancelAttendClass($id = '')
    {
        return $this->db->where('l_id', $id)->update('live', array('l_status' => '0'));
    }

    public function getLiveURL($id = '')
    {
        return ($this->db->select('l_url')->from('live')->where('l_id', $id)->get()->row())->l_url;
    }

    public function getStudentEmail($lt_id = '')
    {
        return $this->db->select('m_email AS email')
            ->from('liveMatchTime')
            ->where('liveMatchTime.lt_id', $lt_id)
            ->join('member', 'liveMatchTime.m_id = member.m_id', 'inner')
            ->distinct()->get()->result_array();
    }

    public function getTeacherEmail($m_id)
    {
        return ($this->db->select('m_email')->from('member')->where('m_id', $m_id)->get()->row())->m_email;
    }

    public function getFundraisingCourseStudentEmail($fc_id = '')
    {
        return $this->db->select('m_email AS email')->from('fundraising_course_list')->where('fc_id', $fc_id)->join('member', 'fundraising_course_list.m_id = member.m_id', 'inner')->get()->result();
    }

    public function getFundraisingCourseName($fc_id = '')
    {
        return ($this->db->select('fc_courseName')->from('fundraising_course')->where('fc_id', $fc_id)->get()->row())->fc_courseName;
    }

    public function getDiamond($fc_id = '')
    {
        return ($this->db->select('FORMAT((fc_fundraisingPrice * cc_exchangeRate * 3), 2) AS diamond')
            ->from('fundraising_course')
            ->where('fc_id', $fc_id)
            ->join('currency_conversion', 'currency_conversion.cc_id = fundraising_course.fc_currency', 'inner')
            ->where('cc_toid', 'TWD')
            ->get()->row())->diamond;
    }

    public function getFundraisingList($fc_id = '')
    {
        return $this->db->select('m_id')->from('fundraising_course_list')->where('fc_id', $fc_id)->get()->result();
    }

    public function returnDiamond($list = '', $diamond = '')
    {
        $diamond = (int)$diamond;
        foreach ($list AS $tmp) {
            if (!$this->db->query("UPDATE main SET points = points + {$diamond} WHERE m_id = '{$tmp->m_id}'"))
                return false;
        }
        return true;
    }

    public function stopFundraising($fc_id = '')
    {
        return $this->db->where('fc_id', $fc_id)->update('fundraising_course', array('fc_status' => 4));
    }

    public function checkFundraisingStatus($fc_id = '')
    {
        return ($this->db->select('fc_status')->from('fundraising_course')->where('fc_id', $fc_id)->get()->row())->fc_status;
    }

    public function deleteFundraising($fc_id = '')
    {
        $this->db->trans_begin();
        $this->db->delete('fundraising_course', array('fc_id' => $fc_id));
        $this->db->delete('fundraising_course_list', array('fc_id' => $fc_id));
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function checkFundraisingCourse($fc_id = '')
    {
        if ($this->db->select('*')->from('fundraising_course')->where('fc_id', $fc_id)->where('t_id', $_SESSION['Tid'])->get()->num_rows() == 0)
            return true;
        else
            return false;
    }

    public function getFundraisingCourseImage($fc_id = '')
    {
        return ($this->db->select('fc_image')->from('fundraising_course')->where('fc_id', $fc_id)->get()->row())->fc_image;
    }

    public function checkIsMyFundraisingCourse($fc_id = '')
    {
        if ($this->db->select('*')->from('fundraising_course')->where('fc_id', $fc_id)->where('T_id', $_SESSION['Tid'])->get()->num_rows() != 1)
            return true;
        else
            return false;
    }

    public function checkFundraisingSuccess($fc_id = '')
    {
        if ($this->db->select('fc_status')->from('fundraising_course')->where('fc_id', $fc_id)->where('fc_status', '1')->get()->num_rows() != 1)
            return true;
        else
            return false;
    }

    public function checkFundraisingCourseToOrdinaryClass($fc_id = '')
    {
        return $this->db->select('*')->from('fundraising_course')->where('fc_id', $fc_id)->where('fc_conversionStatus', '1')->get()->num_rows();
    }

    public function fundraisingCourseToOrdinaryClass($insert = '', $fc_type = '')
    {
        if ($fc_type == 0)
            return $this->db->insert('live', $insert);
        elseif ($fc_type == 1)
            return $this->db->insert('coursefilm', $insert);
    }

    public function fundraisingCourseStatus($fc_id = '')
    {
        return $this->db->where('fc_id', $fc_id)->update('fundraising_course', array('fc_conversionStatus' => '1', 'fc_status' => '2'));
    }

    public function checkFundraisingCourseToOrdinaryClassNotice($fc_id = '')
    {
        return $this->db->select('*')->from('fundraising_course')->where('fc_id', $fc_id)->where('fc_conversionStatus', '1')->get()->num_rows();
    }

    public function checkFundraisingCourseToOrdinaryClassNoticeStatus($fc_id = '')
    {
        return $this->db->select('*')->from('fundraising_course')->where('fc_id', $fc_id)->where('fc_status', '5')->get()->num_rows();
    }

    public function getFundraisingCourseList($fc_id = '')
    {
        return $this->db->select('m_id')->from('fundraising_course_list')->where('fc_id', $fc_id)->get()->result();
    }

    public function checkFundraisingCourseList($fc_id = '')
    {
        return $this->db->select('*')->from('fundraising_course_list')->where('fc_id', $fc_id)->get()->num_rows();
    }

    public function successFundraisingList($fc_id = '', $list = '')
    {
        $fundraisingData = $this->db->select('*')->from('fundraising_course')->where('fc_id', $fc_id)->get()->row();
        $insert = array();
        if ($fundraisingData->fc_type == 0)
            $cf_l_id = 'l_id';
        else
            $cf_l_id = 'cf_id';
        foreach ($list as $Mid) {
            $insert[] = array(
                'sc_id' => uniqid(),
                't_id' => $fundraisingData->t_id,
                $cf_l_id => $fundraisingData->fc_id,
                'm_id' => $Mid->m_id,
                'sc_className' => $fundraisingData->fc_courseName,
                'sc_payStatus' => '1',
                'sc_date' => date("Y-m-d H:i:s")
            );
        }
        if (!$this->db->insert_batch('shoppingcart', $insert))
            return false;
        if (!$this->db->where('fc_id', $fc_id)->update('fundraising_course', array('fc_status' => '5')))
            return false;
        return true;
    }

    public function checkStopFundraising($fc_id = '')
    {
        if ($this->db->select('*')->from('fundraising_course')->where('fc_status', '1')->get()->num_rows() == 1)
            return true;
        else
            return false;
    }

    public function addNoticeRecord($insert = '')
    {
        return $this->db->insert('notice_record', $insert);
    }

    public function deleteNoticeRecord($idArray = '')
    {
        $this->db->trans_begin();
        foreach ($idArray as $temp)
            $this->db->delete('notice_record', array('nr_id' => $temp));
        if ($this->db->trans_status() === true) {
            $this->db->trans_commit();
            return true;
        } else {
            $this->db->trans_rollback();
            return false;
        }
    }

    public function getNoticeRecord($type = '')
    {
        return $this->db->select('nr_id AS id, nr_noticeObject AS notice_object, nr_messageTitle AS message_title, nr_emailOrNotice AS email_or_notice, nr_date AS date')
            ->from('notice_record')
            ->where('nr_noticeObject', $type)
            ->get()->result();
    }

    public function getNoticeDetail($nr_id = '')
    {
        return $this->db->select('nr_id AS id, nr_noticeObject AS notice_object, nr_messageTitle AS message_title, nr_emailOrNotice AS email_or_notice, nr_date AS date, nr_sendMessage AS message')
            ->from('notice_record')
            ->where('nr_id', $nr_id)
            ->get()->row();
    }

    public function checkRepeatNotice($data = '')
    {
        $date = date("Y/m/d H:i");
        return $this->db->select('*')
            ->from('notice_record')
            ->where('nr_sendIdentity', $data['nr_sendIdentity'])
            ->where('nr_date >', $date)
            ->where('nr_date <', date("Y-m-d h:i:s", strtotime("{$date} +30 second")))
            ->get()->num_rows();
    }

    public function getNoticeEmail($type = '', $id = '')
    {
        if ($type == '0' | $type == '1') {
            return $this->db->select('m_email')->from('member')->where('m_email !=', null)->join('main', 'main.m_id = member.m_id', 'inner')->get()->result();
        } elseif ($type == '2') {
            return $this->db->select('m_email')->from('member')->where('m_email !=', null)->join('main', 'main.m_id = member.m_id', 'inner')->where('t_id !=', null)->get()->result();
        } elseif ($type == '3') {
            return $this->db->select('m_email')->from('member')->where('m_id', $id)->get()->result();
        } elseif ($type == '4') {
            return $this->db->select('m_email')->from('main')->where('t_id', $id)->join('member', 'main.m_id = member.m_id', 'inner')->get()->result();
        } elseif ($type == '5') {
            return $this->db->select('m_email')->from('shoppingcart')->where('cf_id', $id)->join('member', 'shoppingcart.m_id = member.m_id', 'inner')->get()->result();
        } elseif ($type == '6') {
            return $this->db->select('m_email')->from('shoppingcart')->where('l_id', $id)->join('member', 'shoppingcart.m_id = member.m_id', 'inner')->get()->result();
        } elseif ($type == '7') {
            return $this->db->select('m_email')->from('fundraising_course_list')->where('fc_id', $id)->join('member', 'fundraising_course_list.m_id = member.m_id', 'inner')->get()->result();
        }
    }

    public function resetNoticeHaveRead()
    {
        $this->db->truncate('notice_record');
        $this->db->query("ALTER TABLE notice_record AUTO_INCREMENT = 1");
        $this->db->update('main', array('haveRead' => null));
    }

    public function checkMemberIsNull($id = '')
    {
        return $this->db->select('*')->from('member')->where('m_id', $id)->get()->num_rows();
    }

    public function checkTeacherIsNull($id = '')
    {
        return $this->db->select('*')->from('teacher')->where('t_id', $id)->get()->num_rows();
    }

    public function checkLiveIsNull($id = '')
    {
        return $this->db->select('*')->from('live')->where('l_id', $id)->get()->num_rows();
    }

    public function checkFilmIsNull($id = '')
    {
        return $this->db->select('*')->from('coursefilm')->where('cf_id', $id)->get()->num_rows();
    }

    public function checkFundraisingIsNull($id = '')
    {
        return $this->db->select('*')->from('fundraising_course')->where('fc_id', $id)->get()->num_rows();
    }

    public function addSpecifyMatch($insert = '', $insert2 = '')
    {
        $this->db->insert('liveTime', $insert);
        $this->db->insert('liveMatchTime', $insert2);
    }

    public function checkTeahcerLiveOwnership($l_id = '')
    {
        return $this->db->select('*')->from('live')->where('l_id', $l_id)->where('t_id', $_SESSION['Tid'])->get()->num_rows();
    }

    public function getDesignated_1_shoppingData($m_id = '', $l_id = '')
    {
        return $this->db->select('sc_id')->from('shoppingcart')->where('l_id', $l_id)->where('m_id', $m_id)->where('sc_payStatus', '1')->where('sc_NumberOfLessons >', '0')->get()->row();
    }

    public function deductNumberOfClasses($sc_id = '')
    {
        $this->db->query("UPDATE shoppingcart SET sc_NumberOfLessons = sc_NumberOfLessons - 1 WHERE sc_id = '{$sc_id}'");
    }
}
