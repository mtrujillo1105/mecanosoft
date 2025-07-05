<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Procesos_model extends CI_Model {
    
    public $table;
    
    public function __construct() {
        parent::__construct();
        $this->scire = $this->load->database('scire',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->table = "Procesos";
    }
 
    public function select($filter,$default="",$value=""){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_defecto);
        foreach($this->get($filter) as $indice=>$valor)
        {
            $indice1   = $valor->Proceso_Id;
            $valor1    = $valor->Proceso;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }    
    
    public function get($filter,$number_items='',$offset=''){ 
        $where = array();
        if(isset($filter->estado) && $filter->estado!='')      $where = array_merge($where,array("Estado_id"=>$filter->estado));      
        $this->scire->select('*');
        $this->scire->from($this->table);
        if(is_array($where) && isset($where))  $this->scire->where($where);
        if(isset($filter->proceso) && $filter->proceso!=''){
            if(is_array($filter->proceso) && count($filter->proceso)>0){
                $this->scire->where_in('Proceso_Id',$filter->proceso);
            }
            else{
                $this->scire->where('Proceso_Id',$filter->proceso);
            }            
        }          
        $query = $this->scire->get();
        $resultado = array();
        if($query->num_rows>0)  $resultado = $query->result();
        return $resultado;        
    }
    
}
?>
