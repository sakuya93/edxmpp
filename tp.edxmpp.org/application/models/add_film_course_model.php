<?php

class add_film_course_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function check_coursesBasicInformation($data)
    {
        if ($this->db->select('*')->from('coursefilm')->where('cf_name', $data)->get()->num_rows() == 0)
            return true;
        else
            return false;
    }


    public function add_coursesBasicInformation($dataArray)
    {
        return $this->db->insert('coursefilm', $dataArray);
    }

    public function get_coursesBasicInformation($id)
    {
        return $this->db->select('cf_name as name, cf_experienceFilm as experienceFilm, cf_experienceFilmName as experienceFilmName,
        cf_type as type, cf_thumbnail as thumbnail, cf_introduction as introduction, cf_hours as hours, cf_briefIntroduction AS briefIntroduction,
        cf_price as price, cf_currency AS currency')
            ->from('coursefilm')->where('cf_id', $id)->get()->row();
    }

    public function get_actualMovie($id)
    {
        return $this->db->select('cf_actualMovie as actualMovie, cf_actualMovieName as actualMovieName, cf_unitName AS unitName')->from('coursefilm')->where('cf_id', $id)->get()->result();
    }

    public function add_actualMovie($dataArray)
    {
        $this->db->where("cf_id", $dataArray[0]['cf_id'])->where('cf_actualMovie !=', null)->delete('coursefilm');
        return $this->db->insert_batch('coursefilm', $dataArray);
    }

    public function edit_coursesBasicInformation($dataArray, $id)
    {
        return $this->db->where('cf_id', $id)->update('coursefilm', $dataArray);
    }

    public function getOrdImage($id = '')
    {
        return $this->db->select('cf_thumbnail as name')->from('coursefilm')->where('cf_id', $id)->get()->row();
    }

    public function checkEditFilm($id = '')
    {
        return $this->db->select('*')->from('coursefilm')->where('cf_id', $id)->where('t_id', $_SESSION['Tid'])->get()->num_rows();
    }

    public function getOption()
    {
        return $this->db->select('option')->from('classOption')->get()->result();
    }

    public function delete_film_course($cf_id)
    {
        return $this->db->delete('coursefilm', array('cf_id' => $cf_id));
    }

    public function getImageName($cf_id = ''){
        return ($this->db->select('cf_thumbnail')->from('coursefilm')->where('cf_id', $cf_id)->get()->row())->cf_thumbnail;
    }

    public function checkDeleteFilmCourse($cf_id = ''){
        return $this->db->select('*')->from('shoppingcart')->where('cf_id', $cf_id)->get()->num_rows();
    }
}