//<?php
//if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//
//class Dvariable_model extends CI_Model {
//    
//    public $entidad;
//    public $table;
//    
//    public function __construct() {
//        parent::__construct();
//        $this->scire = $this->load->database('scire',TRUE);
//        $this->entidad = $this->session->userdata('entidad');
//        $this->table = "D_variables";
//    }
//    
//    public function getHorasExtrasByPersona($persona,$periodo) {
//        $where = array(
//            "Planilla_Id" => '02',
//            "Periodo_Id" => $periodo,
//            "Personal_Id" => $persona,
//            "Concepto_Id" => '001171'
//        );
//        $this->scire->select('*');
//        $this->scire->from($this->table);
//        $this->scire->where($where);
//        $query = $this->scire->get();
//        if ($query->num_rows > 0) {
//            return $query->result();
//        }
//        else return 0;
//    }
//    
//}
//
//?>
