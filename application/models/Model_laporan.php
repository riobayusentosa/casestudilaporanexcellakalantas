<?php
class Model_laporan extends CI_Model
{	
     
    public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('default',TRUE);
    }
    
    public function get_pendidikan()
    {
        $this->db->SELECT('id as id_pendidikan,
                            keterangan as jenjang_pendidikan
                                ');
        $this->db->from('_pendidikan');
        $query = $this->db->get();
        return $query->result_array();
    }       

    public function get_laka_bulan()
    {
        $this->db->SELECT('distinct(MONTHNAME(date_format(tgl,"%Y-%m-%d"))) as nama_bulan,
                           date_format(tgl,"%Y-%m") as group_bulan,
                           count(id_laka) as total_laka
                                ');
        $this->db->from('_laka');
        $this->db->group_by('date_format(tgl,"%Y-%m")');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_laka_pendidikan()
    {
        $this->db->SELECT('a.id_laka,
                            a.id_pendidikan,
                           count(a.id_korban) as jumlah_laka,
                           date_format(b.tgl,"%Y-%m") as group_bulan
                                ');
        $this->db->from('_korban a');
        $this->db->join('_laka b', 'a.id_laka=b.id_laka');
        $this->db->group_by('a.id_pendidikan');
        $this->db->group_by('a.id_laka');
        $query = $this->db->get();
        return $query->result_array();
    }
}