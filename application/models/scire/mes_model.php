<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mes_model extends CI_Model {
    
    public $entidad;
    public $table;
    
    public function __construct() {
        parent::__construct();
        $this->scire = $this->load->database('scire',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->table = "Mes";
    }
    
    public function select($filter,$default="",$value=""){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array(''=>$nombre_defecto);
      
        foreach($this->get($filter) as $indice=>$valor)
        {
            $indice1   = $valor->Mes_Id;
            $valor1    = $valor->Descripcion;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }
    
    public function get($filter,$number_items='',$offset=''){ 
        $where = array();
        if(isset($filter->anio) && $filter->anio!='')         $where = array_merge($where,array("Ejercicio_id"=>$filter->anio));
        if(isset($filter->mes) && $filter->mes!='')           $where = array_merge($where,array("Mes_Id"=>$filter->mes));
        if(isset($filter->mesi) && $filter->mesi!='')         $where = array_merge($where,array("CAST(Mes_Id AS int)>="=>(int)$filter->mesi));
        if(isset($filter->mesf) && $filter->mesf!='')         $where = array_merge($where,array("CAST(Mes_Id AS int)<="=>(int)$filter->mesf));
        if(isset($filter->semana) && $filter->semana!='')     $where = array_merge($where,array("nSemanas"=>$filter->semana));
        $this->scire->select('*');
        $this->scire->from($this->table,$number_items,$offset);
        if(is_array($where) && isset($where))   $this->scire->where($where);
        $this->scire->order_by('nMes');        
        $query = $this->scire->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }
   
    
    public function insert(){
        
    }
	
    public function edit(){

    }
	
    public function delete(){

    }
	
    
}
?>