<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notice_Record extends adminInfrastructure
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/admin_Notice_Record_model", "Model", TRUE);
    }

    public function index()
    {
        if ($this->getlogin()) {
            $HTML['sideBarContent'] = $this->getSideBar("");
            $this->load->view('admin/notice_record_view', $HTML);
            $this->load->view('window/admin/notice_record_window');
            $this->load->view('window/admin/notice_record_detail_window');
            $this->load->view('window/admin/hint_window');
        } else
            redirect(base_url('TPManager'));
    }

    public function addNoticeRecord()
    {
        $data = $this->Form_security_processing($this->input->post());
        $config = array(
            0 => array('key' => 'not null', 'msg' => '通知對象不可為空'),
            1 => array('key' => '', 'msg' => ''),
            2 => array('key' => 'not null', 'msg' => '寄信或通知選項不可為空'),
            3 => array('key' => 'not null', 'msg' => '通知訊息標題不可為空'),
            4 => array('key' => 'not null', 'msg' => '通知訊息不可為空')
        );
        $Form_normalization = $this->Form_normalization($data, $config);
        if (!$Form_normalization->type) {
            echo json_encode(array('status' => false, 'msg' => $Form_normalization->msg));
            return;
        }
        if($data['notice_object'] == 0 | $data['notice_object'] == 1 | $data['notice_object'] == 2) {
            $insert = array(
                'nr_sendIdentity' => 'A',
                'nr_noticeObject' => $data['notice_object'],
                'nr_messageTitle' => $data['message_title'],
                'nr_sendMessage' => $data['send_message'],
                'nr_emailOrNotice' => $data['email_or_notice'],
                'nr_date' => date('Y/m/d H:i:s'),
            );
        }else{
         if($data['notice_object'] == 3)
             if($this->Model->checkMemberIsNull($data['specificObject']) != 1){
                echo json_encode(array('status' => false, 'msg' => '查無此會員資料，請確認後再操作'));
                return;
             }
         elseif($data['notice_object'] == 4)
             if($this->Model->checkTeacherIsNull($data['specificObject']) != 1){
                 echo json_encode(array('status' => false, 'msg' => '查無此老師資料，請確認後再操作'));
                 return;
             }
         elseif($data['notice_object'] == 5)
             if($this->Model->checkLiveIsNull($data['specificObject']) != 1) {
                 echo json_encode(array('status' => false, 'msg' => '查無此直播課程，請確認後再操作'));
                 return;
             }
         elseif($data['notice_object'] == 6)
             if($this->Model->checkFilmIsNull($data['specificObject']) != 1){
                 echo json_encode(array('status' => false, 'msg' => '查無此影片課程，請確認後再操作'));
                 return;
             }
         elseif($data['notice_object'] == 7)
             if($this->Model->checkFundraisingIsNull($data['specificObject']) != 1){
                 echo json_encode(array('status' => false, 'msg' => '查無此募資課程，請確認後再操作'));
                 return;
             }
         else{
             echo json_encode(array('status' => false, 'msg' => '發生不明錯誤，請刷新頁面後重新嘗試'));
             return;
         }
            $insert = array(
                'nr_sendIdentity' => 'A',
                'nr_noticeObject' => $data['notice_object'],
                'nr_specificObject' => $data['specificObject'],
                'nr_messageTitle' => $data['message_title'],
                'nr_sendMessage' => $data['send_message'],
                'nr_emailOrNotice' => $data['email_or_notice'],
                'nr_date' => date('Y/m/d H:i:s'),
            );
        }

        if ($this->Model->checkRepeatNotice($insert) > 10) {
            echo json_encode(array('status' => false, 'msg' => '請勿重複新曾相同內容的通知，或放慢新增通知的速度'));
            return;
        }

        if($data['email_or_notice'] == 2){
            if ($this->Model->addNoticeRecord($insert))
                echo json_encode(array('status' => true, 'msg' => '通知新增成功'));
            else
                echo json_encode(array('status' => false, 'msg' => '通知新增失敗'));
        }else{
            $this->db->trans_begin();
            if ($this->Model->addNoticeRecord($insert)) {
                    $this->load->library('email');
                    $this->email->set_mailtype("html");

                    $this->email->from('tpManager0732@gmail.com', 'XXX 教學平台管理員');
                    $emailArray = $this->Model->getNoticeEmail($insert['nr_noticeObject'], $data['specificObject']);
                    if(count($emailArray) == 0){
                        echo json_encode(array('status' => false, 'msg' => '此通知對象找不到對應的信箱，請改用通知方式'));
                        return;
                    }
                    $emailNewArray = array();
                    for ($i = 0; $i < count($emailArray); ++$i)
                        array_push($emailNewArray, $emailArray[$i]->m_email);

                    $this->email->to($emailNewArray);
                    $this->email->subject($insert['nr_messageTitle']);
                    $this->email->message($insert['nr_sendMessage']);


                if ($this->db->trans_status() === TRUE & $this->email->send())
                    $this->db->trans_commit();
                echo json_encode(array('status' => true, 'msg' => '通知新增成功'));
            } else {
                $this->db->trans_rollback();
                echo json_encode(array('status' => false, 'msg' => '通知新增失敗'));
            }
        }
    }

    public function deleteNoticeRecord()
    {
        $data = $this->input->post();
        if ($this->Model->deleteNoticeRecord($data))
            echo json_encode(array('status' => true, 'msg' => '刪除通知成功'));
        else
            echo json_encode(array('status' => false, 'msg' => '刪除通知失敗'));
    }

    public function getNoticeRecord($type = 0)
    {
        echo json_encode($this->Model->getNoticeRecord($type));
    }

    public function getNoticeDetail()
    {
        $data = $this->input->post();
        echo json_encode($this->Model->getNoticeDetail($data['id']));
    }

    public function resetNoticeHaveRead(){
        $this->db->trans_begin();
        $this->Model->resetNoticeHaveRead();
        if ($this->db->trans_status() === TRUE)
        {
            $this->db->trans_commit();
            echo json_encode(array('status' => true, 'msg' => '重置通知成功'));
        }
        else
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'msg' => '重置通知失敗'));
        }
    }

    public function getTeacherEmail($t_id = ''){
        return ($this->db->select('m_email AS email')->from('main')->where('main.t_id', $t_id)->join('member', 'main.m_id = member.m_id', 'inner')->get()->row())->email;
    }
}
