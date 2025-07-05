<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proyecto_model extends CI_Model {
    
    public $table;
    
    public function __construct() {
        parent::__construct();
        $this->scire = $this->load->database('scire',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->table = "proyecto";
    }
    
    public function select($filter,$default="",$value=""){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_defecto);
        foreach($this->get($filter) as $indice=>$valor)
        {
            $indice1   = $valor->Proyecto_id;
            $valor1    = $valor->Descripcion;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }    
    
    public function get($filter,$number_items='',$offset=''){ 
        $where = array();
        if(isset($filter->proyecto) && $filter->proyecto!='')          $where = array_merge($where,array("p.Proyecto_id"=>$filter->proyecto));     
        $this->scire->select('p.*');
        $this->scire->from($this->table.' as p');
        if(is_array($where) && isset($where))  $this->scire->where($where);    
        $query = $this->scire->get();
        $resultado = array();
        if($query->num_rows>0)   $resultado = $query->result();
        if($query->num_rows==1)  $resultado = $query->row();
        return $resultado;        
    }
    
    public function search($per_code){
        $this->scire->select('*');
        $this->scire->from($this->table);
        $this->scire->where("periodo_id",$per_code);
        $query = $this->scire->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }
}
?>
