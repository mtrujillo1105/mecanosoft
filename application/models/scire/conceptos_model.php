<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Conceptos_model extends CI_Model {
    
    public $table;
    
    public function __construct() {
        parent::__construct();
        $this->scire = $this->load->database('scire',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->table = "Conceptos";
    }
    
    public function select($filter,$default="",$value=""){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_defecto);
        foreach($this->get($filter) as $indice=>$valor)
        {
            $indice1   = $valor->Concepto_Id;
            $valor1    = $valor->Detalle;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }    
    
    public function get($filter,$number_items='',$offset=''){ 
        $where = array();
        //if(isset($filter->concepto) && $filter->concepto!='')            $where = array_merge($where,array("Concepto_id"=>$filter->concepto));          
        $this->scire->select('*');
        $this->scire->from($this->table);
        //if(is_array($where) && isset($where))  $this->scire->where($where);  
        if(isset($filter->concepto) && $filter->concepto!=''){
            if(is_array($filter->concepto) && count($filter->concepto)>0){
                $this->scire->where_in('Concepto_id',$filter->concepto);
            }
            else{
                $this->scire->where('Concepto_id',$filter->concepto);
            }            
        }  
        if(isset($filter->order_by) && count($filter->order_by)>0 && $filter->order_by!=""){
            foreach($filter->order_by as $indice=>$value){
                $this->scire->order_by($indice,$value);
            }
        }          
        $query = $this->scire->get();
        $resultado = array();
        if($query->num_rows>1)   $resultado = $query->result();
        if($query->num_rows==1)  $resultado = $query->row();
        return $resultado;        
    }
    
}
?>
