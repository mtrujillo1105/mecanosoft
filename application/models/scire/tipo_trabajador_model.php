<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tipo_trabajador_model extends CI_Model {    
    public $entidad;
    public $table;
    
    public function __construct() {
        parent::__construct();
        $this->scire = $this->load->database('scire',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->table = "Tipo_Trabajador";
    }
    
    public function select($filter,$default="",$valor="") {
        $nombre_defecto = $default == "" ? ":: Seleccione ::" : $default;
        $arreglo = array($valor => $nombre_defecto);
        $result = $this->get($filter);
        
        foreach($result as $key => $value) {
            $indice1   = $value->Tipo_Trabajador_Id;
            $valor1    = ($value->Descripcion=='--'?'4TA CATEG.':$value->Descripcion);
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }
    
    public function get($filter,$number_items='',$offset=''){ 
        $where = array();        
        $this->scire->select('*');
        $this->scire->from($this->table,$number_items,$offset);
        if(is_array($where) && isset($where))          $this->scire->where($where);
        if(isset($filter->tipo) && $filter->tipo!='')  $this->scire->where_in('Tipo_Trabajador_id', $filter->tipo);
        $this->scire->order_by('Descripcion');        
        $query = $this->scire->get();
//        $resultado = array();
//        if($query->num_rows>0){
//            $resultado = $query->result();
//        }
//        return $resultado;
        $resultado = array();
        if($query->num_rows>1)   $resultado = $query->result();
        if($query->num_rows==1)  $resultado = $query->row();
        return $resultado;        
    }
}
?>
