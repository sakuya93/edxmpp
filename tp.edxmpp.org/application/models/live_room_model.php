<?php

class live_room_model extends CI_Model
{
    public function __destruct()
    {
        $this->db->flush_cache();
        $this->db->close();
    }
}