<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Planilla_model extends CI_Model {    
    public $entidad;
    public $table;
    
    public function __construct() {
        parent::__construct();
        $this->scire = $this->load->database('scire',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->table = "Planilla";
    }
    
   public function select($filter,$default="",$value=""){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array(''=>$nombre_defecto);
        foreach($this->get($filter) as $indice=>$valor)
        {
            $indice1   = $valor->Planilla_Id;
            $valor1    = $valor->Descripcion;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }
    
    public function get($filter,$number_items='',$offset=''){ 
        $where = array();
        if(isset($filter->estado) && $filter->estado!='')             $where = array_merge($where,array("Estado_Id"=>$filter->estado));
        if(isset($filter->periodicidad) && $filter->periodicidad!='') $where = array_merge($where,array("Periodicidad_Id"=>$filter->periodicidad));
        $this->scire->select('*');
        $this->scire->from($this->table,$number_items,$offset);
        if(is_array($where) && isset($where))   $this->scire->where($where);
        if(isset($filter->planilla) && $filter->planilla!=''){
            if(is_array($filter->planilla) && count($filter->planilla)>0){
                $this->scire->where_in('Planilla_Id',$filter->planilla);
            }
            else{
                $this->scire->where('Planilla_Id',$filter->planilla);
            }            
        } 
        $this->scire->order_by('Descripcion');        
        $query = $this->scire->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }    
}
?>
