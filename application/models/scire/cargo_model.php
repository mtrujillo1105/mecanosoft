<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cargo_model extends CI_Model {    
    public $entidad;
    public $table;
    
    public function __construct() {
        parent::__construct();
        $this->scire = $this->load->database('scire',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->table = "Cargo";
    }
    
   public function select($filter,$default="",$value=""){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array(''=>$nombre_defecto);
        foreach($this->get($filter) as $indice=>$valor)
        {
            $indice1   = $valor->Cargo_Id;
            $valor1    = $valor->Descripcion;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }    
    
   public function get($filter,$number_items='',$offset=''){ 
        $where = array();
        if(isset($filter->estado) && $filter->estado!='')         $where = array_merge($where,array("Estado_Id"=>$filter->estado));
        if(isset($filter->ocupacion) && $filter->ocupacion!='')   $where = array_merge($where,array("Ocupacion_Id"=>$filter->ocupacion));
        if(isset($filter->cargo) && $filter->cargo!='')           $where = array_merge($where,array("Cargo_Id"=>$filter->cargo));
        $this->scire->select('*');
        $this->scire->from($this->table,$number_items,$offset);
        if(is_array($where) && isset($where))   $this->scire->where($where);
        $this->scire->order_by('Descripcion');        
        $query = $this->scire->get();
        $resultado = array();
        if($query->num_rows>1)      $resultado = $query->result();
        if($query->num_rows==1)     $resultado = $query->row();
        return $resultado;
    }     
}
?>
