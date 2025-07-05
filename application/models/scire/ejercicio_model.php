<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ejercicio_model extends CI_Model {
    
    public $entidad;
    public $table;
    
    public function __construct() {
        parent::__construct();
        $this->scire = $this->load->database('scire',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->table = "Ejercicio";
    }
    
    public function select($filter,$default="",$value=""){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array(''=>$nombre_defecto);
        foreach($this->get($filter) as $indice=>$valor)
        {
            $indice1   = $valor->Ejercicio_Id;
            $valor1    = utf8_encode($valor->Descripcion);
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }    
    
    public function get($filter,$filter_not="",$order_by="",$number_items='',$offset=''){ 
        $where = array();
        if(isset($filter->anio) && $filter->anio!='')            $where = array_merge($where,array("Ano"=>$filter->anio));
        if(isset($filter->anioi) && $filter->anioi!='')          $where = array_merge($where,array("Ano>="=>$filter->anioi));
        if(isset($filter->aniof) && $filter->aniof!='')          $where = array_merge($where,array("Ano<="=>$filter->aniof));
        if(isset($filter->estado) && $filter->estado!='')        $where = array_merge($where,array("Estado_Id"=>$filter->estado));
        if(isset($filter->ejercicio) && $filter->ejercicio!='')  $where = array_merge($where,array("Ejercicio_Id"=>$filter->ejercicio));
        $this->scire->select('*');
        $this->scire->from($this->table,$number_items,$offset);
        if(is_array($where) && isset($where))   $this->scire->where($where);
        if(isset($order_by) && count($order_by)>0 && $order_by!=""){
            foreach($order_by as $indice=>$value){
                $this->scire->order_by($indice,$value);
            }
        }        
        $query = $this->scire->get();
        $resultado = array();
        if($query->num_rows>1)   $resultado = $query->result();
        if($query->num_rows==1)  $resultado = $query->row();
        return $resultado;
    }
   
    
    public function insert(){
        
    }
	
    public function edit(){

    }
	
    public function delete(){

    }
	
    public function search(){

    }
}
?>
