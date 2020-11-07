<?php 
class Shopping_cart_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function get_liveData($id = '')
    {
        return $this->db->select('*')->from('live')->where('l_id', $id)->get()->row();
    }

    public function checkLiveIsMy($l_id = '')
    {
        return $this->db->select('*')->from('live')->where('l_id', $l_id)->where('t_id', $_SESSION['Tid'])->get()->num_rows();
    }

    public function checkFilmIsMy($cf_id = '')
    {
        return $this->db->select('*')->from('courseFilm')->where('cf_id', $cf_id)->where('t_id', $cf_id)->get()->num_rows();
    }

    public function get_filmData($id = '')
    {
        return $this->db->select('*')->from('courseFilm')->where('cf_id', $id)->get()->row();
    }

    public function addShopping($dataArray)
    {
        if ($this->db->insert('shoppingcart', $dataArray))
            return true;
        return false;
    }

    public function shoppingRecord()
    {
        $result = $this->db
            ->select('shoppingcart.*, teacher.t_name, teacher.t_country as country, teacher.t_speakLanguage as speakLanguage, teacher.t_photo as photo, live.l_hours as hours, live.l_price as price')
            ->from('shoppingcart')
            ->where('shoppingcart.m_id', $_SESSION['Mid'])
            ->where('sc_payStatus', 0)
            ->join('teacher', 'shoppingcart.t_id = teacher.t_id', 'left')
            ->join('live', 'shoppingcart.l_id = live.l_id', 'left')
            ->get()->result();
        return $result;
    }

    public function deleteShopping($id = '')
    {
        if ($this->db->delete('shoppingcart', array('sc_id' => $id)))
            return true;
        return false;
    }

    public function shoppingBuyClass($dataArray = '', $ph_id = '')
    {
//		for ($i = 0; $i < count($dataArray); ++$i) {
//			if (isset($dataArray[$i]['sc_NumberOfLessons'])) {
//				$l_id = ($this->db->select('l_id')->from('shoppingcart')->where('sc_id', $dataArray[$i]['sc_id'])->get()->row())->l_id;
//				$dataArray[$i]['sc_price'] = ($this->db->select('cd_discountedPrices as price')->from('courtdiscount')->where('l_id', $l_id)
//					->where('cd_number', $dataArray[$i]['sc_NumberOfLessons'])->get()->row())->price;
//			}
//			else{
//				$cf_id = ($this->db->select('cf_id')->from('shoppingcart')->where('sc_id', $dataArray[$i]['sc_id'])->get()->row())->cf_id;
//				$dataArray[$i]['sc_price'] = ($this->db->select('cf_price')->from('courseFilm')->where('cf_id', $cf_id)->get()->row())->cf_price;
//			}
//			if ($this->db->update('shoppingcart', $dataArray[$i], array('sc_id' => $dataArray[$i]['sc_id'])))
//				return true;
//		}
//		return false;
        if ($ph_id != null) {
            unset($dataArray['ph_id']);
            return $this->db->where('ph_id', $ph_id->ph_id)->update('payment_history', $dataArray);
        } else {
            return $this->db->insert('payment_history', $dataArray);
        }
    }

    public function deductionDiamond($data = '')
    {
        $data['ph_price'] = ($data['ph_price'] + $data['ph_drawInto']) * 3; //這邊加上 ph_drawInto 是因為前面有扣除平台抽成價格
        return $this->db->query("UPDATE main SET points = points - {$data['ph_price']} WHERE m_id = '{$data['m_id']}'");
    }

    public function checkDiamond($diamond = 0)
    {
        $diamond *= 3;
        return $this->db->select('*')->from('main')->where('m_id', $_SESSION['Mid'])->where('points >=', $diamond)->get()->num_rows();
    }

    public function getNumberOfLessonsConfig($id = '')
    {
        return $this->db->select('*')->from('courtdiscount')->where('l_id', $id)->order_by('cd_number', "asc")->get()->result();
    }

    public function getFilmData($id = '')
    {
        return $this->db->select('*')->from('courseFilm')->where('cf_id', $id)->where('cf_experienceFilm !=', null)->get()->row();
    }

    public function checkFilm($data)
    {
        return $this->db->select('*')->from('shoppingcart')->where('cf_id !=', null)->where('sc_id', $data['id'])->get()->num_rows();
    }

    public function getLivePrice($sc_id = '')
    {
        return $this->db->select("l_actualMovie AS courseName, l_type AS courseType, (cd_discountedPrices * cc_exchangeRate) AS price")
            ->from('shoppingcart')
            ->where('sc_id', $sc_id)
            ->join('live', 'live.l_id = shoppingcart.l_id', 'left')
            ->join('courtdiscount', 'courtdiscount.l_id = shoppingcart.l_id AND cd_number = sc_NumberOfLessons', 'left')
            ->join('currency_conversion', 'currency_conversion.cc_id = courtdiscount.cd_currency', 'left')
            ->where('cc_toid', 'TWD')
            ->get()->row();
    }

    public function getFilmPrice($sc_id = '')
    {
        return $this->db->select('cf_name AS courseName, cf_type AS courseType, (cf_price * cc_exchangeRate) AS price')
            ->from('shoppingcart')
            ->where('sc_id', $sc_id)
            ->join('courseFilm', 'courseFilm.cf_id = shoppingcart.cf_id', 'left')
            ->join('currency_conversion', 'currency_conversion.cc_id = courseFilm.cf_currency', 'left')
            ->where('cc_toid', 'TWD')
            ->get()->row();
    }

    public function getTNO($TNO = '')
    {
        return ($this->db->select('ph_project')->from('payment_history')->where('ph_id', $TNO)->get()->row())->ph_project;
    }

    public function updatePayStatus($update = '')
    {
        return $this->db->update_batch('shoppingcart', $update, 'sc_id');
    }

    public function updatePaymentHistory($TNO = '')
    {
        return $this->db->where('ph_id', $TNO)->update('payment_history', array('ph_status' => '1'));
    }

    public function getshoppingCartData($sc_id = '', $drawInto = '')
    {
        return $this->db->select("shoppingcart.t_id, shoppingcart.l_id,
		 (CASE WHEN courtdiscount.cd_discountedPrices != 'null' THEN FORMAT(((cd_discountedPrices - (cd_discountedPrices*({$drawInto}/100))) * cc_exchangeRate), 2) ELSE FORMAT((cf_price * cc_exchangeRate), 2) END) AS price")
            ->from('shoppingcart')
            ->where('sc_id', $sc_id)
            ->join('courtdiscount', 'courtdiscount.l_id = shoppingcart.l_id AND courtdiscount.cd_number = shoppingcart.sc_NumberOfLessons', 'left')
            ->join('courseFilm', 'courseFilm.cf_id = shoppingcart.cf_id', 'left')
            ->join('currency_conversion', 'currency_conversion.cc_id = courtdiscount.cd_currency OR currency_conversion.cc_id = courseFilm.cf_currency', 'left')
            ->where('cc_toid', 'USD')
            ->get()->row();
    }

    public function updateTeacherIncome($update = '')
    {
        foreach ($update AS $tmp) {
            if ($tmp['price'] == null) $tmp['price'] = 0;
            if (!$this->db->query("UPDATE teacher SET t_income = t_income + {$tmp['price']} WHERE t_id = '{$tmp['t_id']}'"))
                return false;
        }
        return true;
    }


    public function getCoursePriceTWD($cf_id = '', $sc_id = '')
    {
        if ($cf_id != null) {
            return ($this->db->select('FORMAT((cf_price * cc_exchangeRate), 2) AS price')
                ->from('courseFilm')
                ->where('cf_id', $cf_id)
                ->join('currency_conversion', 'courseFilm.cf_currency = currency_conversion.cc_id', 'left')
                ->where('cc_toid', 'TWD')
                ->get()->row())->price;
        } else {
            return ($this->db->select('FORMAT((cd_discountedPrices * cc_exchangeRate), 2) AS price')
                ->from('shoppingcart')
                ->where('sc_id', $sc_id)
                ->join('courtdiscount', 'courtdiscount.l_id = shoppingcart.l_id AND courtdiscount.cd_number = shoppingcart.sc_NumberOfLessons', 'left')
                ->join('currency_conversion', 'courtdiscount.cd_currency = currency_conversion.cc_id', 'left')
                ->where('cc_toid', 'TWD')
                ->get()->row())->price;
        }
    }

    public function getUSDExchangeRate()
    {
        return ($this->db->select('cc_exchangeRate')
            ->from('currency_conversion')
            ->where('cc_id', 'TWD')
            ->where('cc_toid', 'USD')
            ->get()->row())->cc_exchangeRate;
    }

    public function getTid()
    {
        return $this->db->select('ph_id')->from('payment_history')->where('m_id', $_SESSION['Mid'])
            ->where('ph_status', '0')->where('ph_project !=', 'point')->get()->row();
    }

    public function getDrawInto()
    { //取得目前平台抽成
        return $this->db->select('draw_into')->from('platform_earn')->get()->row();
    }

    public function addEarned_amount($platformDraw)
    { //加總平台獲得總金額
        return $this->db->query("UPDATE platform_earn SET earned_amount = earned_amount + {$platformDraw}");
    }
}
