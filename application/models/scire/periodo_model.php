<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Periodo_model extends CI_Model {
    
    public $table;
    
    public function __construct() {
        parent::__construct();
        $this->scire = $this->load->database('scire',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->table = "periodo";
    }
    
    public function select($filter,$default="",$value=""){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_defecto);
        foreach($this->listar($filter) as $indice=>$valor)
        {
            $indice1   = $valor->Periodo_Id;
            $valor1    = $valor->Descripcion;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }    
    
    public function listar($filter,$number_items='',$offset=''){ 
        $where = array();
        if(isset($filter->anio) && $filter->anio!='')          $where = array_merge($where,array("m.Ejercicio_id"=>$filter->anio));    
        if(isset($filter->mes) && $filter->mes!='')            $where = array_merge($where,array("p.Mes_id"=>$filter->mes));      
        if(isset($filter->semana) && $filter->semana!='')      $where = array_merge($where,array("p.Semana_id"=>$filter->semana));        
        if(isset($filter->periodo) && $filter->periodo!='')    $where = array_merge($where,array("p.Periodo_Id"=>$filter->periodo));   
        $this->scire->select('p.*,convert(char,p.Fecha_Ini,103) as fInicio,convert(char,p.Fecha_Fin,103) as fFin');
        $this->scire->from($this->table.' as p');
        $this->scire->join('mes as m','m.Mes_id=p.Mes_id','inner');
        if(is_array($where) && isset($where))  $this->scire->where($where);
        if(isset($filter->planilla)){
            if(is_array($filter->planilla) && count($filter->planilla)>0){
                $this->scire->where_in('p.Planilla_id',$filter->planilla);
            }
            else{
                $this->scire->where('p.Planilla_id',$filter->planilla);
            }            
        }          
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
