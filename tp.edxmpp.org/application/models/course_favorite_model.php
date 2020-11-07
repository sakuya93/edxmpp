<?php

class Course_favorite_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }

    public function getAllCourseFavorite(){
        return $this->db->select('IF(l_id != null , IF(courseFilm.cf_id != null , t3.t_name, t2.t_name) , t1.t_name) AS  teacherName,
         IF(l_id != null , IF(courseFilm.cf_id != null , fc_courseName, cf_name) , l_actualMovie) AS courseName,
         IF(l_id != null , IF(courseFilm.cf_id != null , fc_type, cf_type) , l_type) AS type,
          IF(l_id != null , IF(courseFilm.cf_id != null , fc_image, cf_thumbnail) , l_thumbnail) AS image,
           IF(l_id != null , IF(courseFilm.cf_id != null , fc_briefIntroduction, cf_briefIntroduction) , l_briefIntroduction) AS briefIntroduction,
           IF(l_id != null , IF(courseFilm.cf_id != null , \'resource/image/teacher/fundraisingCourse/\', \'resource/image/teacher/film/\') , \'resource/image/teacher/live/\') AS path,
            course_favorite.cf_id AS id')
            ->from('course_favorite')
            ->where('m_id', $_SESSION['Mid'])

            ->join('live', 'course_favorite.cf_id = live.l_id', 'left')
            ->join('teacher AS t1', 'live.t_id = t1.t_id', 'left')

            ->join('courseFilm', 'course_favorite.cf_id = courseFilm.cf_id', 'left')
            ->join('teacher AS t2', 'courseFilm.t_id = t2.t_id', 'left')

            ->join('fundraising_course', 'course_favorite.cf_id = fundraising_course.fc_id', 'left')
            ->join('teacher AS t3', 'fundraising_course.t_id = t3.t_id', 'left')

            ->get()->result();
    }

    public function getLiveCourseFavorite(){
        return $this->db->select('t_name AS teacherName, l_actualMovie AS courseName, l_type AS type, l_thumbnail AS image, l_briefIntroduction AS briefIntroduction, cf_id AS id,
        "resource/image/teacher/live/" AS path')
            ->from('course_favorite')
            ->where('m_id', $_SESSION['Mid'])
            ->join('live', 'course_favorite.cf_id = live.l_id', 'inner')
            ->join('teacher', 'live.t_id = teacher.t_id', 'inner')
            ->get()->result();
    }

    public function getFilmCourseFavorite(){
        return $this->db->select('t_name AS teacherName, cf_name AS courseName, cf_type AS type, cf_thumbnail AS image, cf_briefIntroduction AS briefIntroduction, course_favorite.cf_id AS id,
        "resource/image/teacher/film/" AS path')
            ->from('course_favorite')
            ->where('m_id', $_SESSION['Mid'])
            ->join('courseFilm', 'course_favorite.cf_id = courseFilm.cf_id', 'inner')
            ->join('teacher', 'courseFilm.t_id = teacher.t_id', 'left')
            ->get()->result();
    }

    public function getFundraisingCourseFavorite(){
        return $this->db->select('t_name AS teacherName, fc_courseName AS courseName, fc_type AS type, fc_image AS image, fc_briefIntroduction AS briefIntroduction, cf_id AS id,
        "resource/image/teacher/fundraisingCourse/" AS path')
            ->from('course_favorite')
            ->where('m_id', $_SESSION['Mid'])
            ->join('fundraising_course', 'course_favorite.cf_id = fundraising_course.fc_id', 'inner')
            ->join('teacher', 'fundraising_course.t_id = teacher.t_id', 'inner')
            ->get()->result();
    }
}