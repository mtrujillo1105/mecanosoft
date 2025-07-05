<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dfijos_model extends CI_Model {
    public $entidad;
    public $table;
    
    public function __construct() {
        parent::__construct();
        $this->scire = $this->load->database('scire',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->table = "D_fijos";
    }
    
    public function listar($filter,$filter_not="",$number_items='',$offset='') {
        $where = array();     
        if(isset($filter->personal) && $filter->personal!='')  $where = array_merge($where,array("v.Personal_Id"=>$filter->personal)); 
        if(isset($filter->planilla) && $filter->planilla!='')  $where = array_merge($where,array("v.Planilla_id"=>$filter->planilla));        
        if(isset($filter->periodo) && $filter->periodo!='')    $where = array_merge($where,array("v.Periodo_Id"=>$filter->periodo));         
        $this->scire->select('v.*');
        $this->scire->from($this->table." as v");
        $this->scire->join('Conceptos as c','c.Concepto_Id=v.Concepto_Id','inner');
        $this->scire->where($where);
        if(isset($filter->concepto)){
            if(is_array($filter->concepto) && count($filter->concepto)>0){
                $this->scire->where_in("v.Concepto_Id",$filter->concepto);
            }
            else{
                $this->scire->where("v.Concepto_Id",$filter->concepto);
            }
        }  
        $query = $this->scire->get();
        $resultado = array();
        if($query->num_rows>0)   $resultado = $query->result();
        return $resultado;  
    }
    
    public function listar_totales($filter,$filter_not="",$number_items='',$offset='') {
        $where = array();     
        if(isset($filter->personal) && $filter->personal!='')  $where = array_merge($where,array("v.Personal_Id"=>$filter->personal)); 
        if(isset($filter->planilla) && $filter->planilla!='')  $where = array_merge($where,array("v.Planilla_id"=>$filter->planilla));        
        if(isset($filter->periodo) && $filter->periodo!='')    $where = array_merge($where,array("v.Periodo_Id"=>$filter->periodo));         
        $this->scire->select('v.Personal_Id,v.Planilla_Id,v.Periodo_Id,sum(v.Valor) as Valor');
        $this->scire->from($this->table." as v");
        $this->scire->join('Conceptos as c','c.Concepto_Id=v.Concepto_Id','inner');
        $this->scire->where($where);
        if(isset($filter->concepto)){
            if(is_array($filter->concepto) && count($filter->concepto)>0){
                $this->scire->where_in("v.Concepto_Id",$filter->concepto);
            }
            else{
                $this->scire->where("v.Concepto_Id",$filter->concepto);
            }
        }  
        $this->scire->group_by("v.Periodo_Id,v.Planilla_Id,v.Personal_Id"); 
        $query = $this->scire->get();
        $resultado = array();
        if($query->num_rows>0)   $resultado = $query->result();
        if($query->num_rows==1)  $resultado = $query->row();
        return $resultado;  
    }    
}
?>