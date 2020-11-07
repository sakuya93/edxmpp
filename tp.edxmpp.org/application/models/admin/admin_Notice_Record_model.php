<?php

class admin_Notice_Record_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

	public function addNoticeRecord($insert = ''){
    	return $this->db->insert('notice_record', $insert);
	}

	public function deleteNoticeRecord($idArray = ''){
        $this->db->trans_begin();
        foreach($idArray as $temp)
    	    $this->db->delete('notice_record', array('nr_id' => $temp));
        if ($this->db->trans_status() === true) {
            $this->db->trans_commit();
            return true;
        }else{
            $this->db->trans_rollback();
            return false;
        }
	}

	public function getNoticeRecord($type = ''){
    	return $this->db->select('nr_id AS id, nr_noticeObject AS notice_object, nr_messageTitle AS message_title, nr_emailOrNotice AS email_or_notice, nr_date AS date')
			->from('notice_record')
			->where('nr_noticeObject', $type)
			->get()->result();
	}

	public function getNoticeDetail($nr_id = ''){
    	return $this->db->select('nr_id AS id, nr_noticeObject AS notice_object, nr_messageTitle AS message_title, nr_emailOrNotice AS email_or_notice, nr_date AS date, nr_sendMessage AS message')
			->from('notice_record')
			->where('nr_id', $nr_id)
			->get()->row();
	}

	public function checkRepeatNotice($data = ''){
        $date = date("Y/m/d H:i");
        return $this->db->select('*')
            ->from('notice_record')
            ->where('nr_sendIdentity', $data['nr_sendIdentity'])
            ->where('nr_date >', $date)
            ->where('nr_date <', date("Y-m-d h:i:s",strtotime("{$date} +30 second")))
            ->get()->num_rows();
    }

    public function getNoticeEmail($type = '', $id = ''){
        if($type == '0' | $type == '1'){
            return $this->db->select('m_email')->from('member')->where('m_email !=', null)->join('main', 'main.m_id = member.m_id', 'inner')->get()->result();
        }
        elseif($type == '2'){
            return $this->db->select('m_email')->from('member')->where('m_email !=', null)->join('main', 'main.m_id = member.m_id', 'inner')->where('t_id !=', null)->get()->result();
        }elseif($type == '3'){
            return $this->db->select('m_email')->from('member')->where('m_id', $id)->get()->result();
        }elseif($type == '4'){
            return $this->db->select('m_email')->from('main')->where('t_id', $id)->join('member', 'main.m_id = member.m_id', 'inner')->get()->result();
        }elseif($type == '5'){
            return $this->db->select('m_email')->from('shoppingCart')->where('cf_id', $id)->join('member', 'shoppingCart.m_id = member.m_id', 'inner')->get()->result();
        }elseif($type == '6'){
            return $this->db->select('m_email')->from('shoppingCart')->where('l_id', $id)->join('member', 'shoppingCart.m_id = member.m_id', 'inner')->get()->result();
        }elseif($type == '7'){
            return $this->db->select('m_email')->from('fundraising_course_list')->where('fc_id', $id)->join('member', 'fundraising_course_list.m_id = member.m_id', 'inner')->get()->result();
        }
    }

    public function resetNoticeHaveRead(){
        $this->db->truncate('notice_record');
        $this->db->query("ALTER TABLE notice_record AUTO_INCREMENT = 1");
        $this->db->update('main', array('haveRead' => null));
    }

    public function checkMemberIsNull($id = ''){
        return $this->db->select('*')->from('member')->where('m_id', $id)->get()->num_rows();
    }

    public function checkTeacherIsNull($id = ''){
        return $this->db->select('*')->from('teacher')->where('t_id', $id)->get()->num_rows();
    }

    public function checkLiveIsNull($id = ''){
        return $this->db->select('*')->from('live')->where('l_id', $id)->get()->num_rows();
    }

    public function checkFilmIsNull($id = ''){
        return $this->db->select('*')->from('courseFilm')->where('cf_id', $id)->get()->num_rows();
    }

    public function checkFundraisingIsNull($id = ''){
        return  $this->db->select('*')->from('fundraising_course')->where('fc_id', $id)->get()->num_rows();
    }
}
