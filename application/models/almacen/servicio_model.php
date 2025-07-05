<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servicio_model extends CI_Model{
    var $entidad;
    var $table;
    
    public function __construct(){
        parent::__construct();
        $this->dbase   = $this->load->database('dsn',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->table   = "Producto";
    }
    
    public function seleccionar($filter,$default="",$value=""){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_defecto);
        foreach($this->listar($filter) as $indice=>$valor)
        {
            $indice1   = $valor->CodPro;
            $valor1    = $valor->DesPro;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }
    
    public function listar($filter,$number_items='',$offset=''){
        $where = array('CodEnt'=>$this->entidad,"Tipo"=>"3");
        $this->db->select('*');
        $this->db->from($this->table,$number_items,$offset);
        $this->db->where($where);	
        if(isset($filter->order_by) && count($filter->order_by)>0){
            foreach($filter->order_by as $indice=>$value){
                $this->db->order_by($indice,$value);
            }
        }
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }  
    
    public function obtener($filter,$filter_not){
        $where = array("codent"=>$this->entidad); 
        if(isset($filter->codservicio) && $filter->codservicio!='')  $where = array_merge($where,array("codpro"=>$filter->codservicio));
        $query = $this->db->where($where)->get($this->table);
        $resultado = new stdClass();
        if($query->num_rows>1) exit('Existe mas de 1 resultado');
        if($query->num_rows==1){
            $resultado = $query->row();
        }
        return $resultado;
    }    
    
    
    public function obtener1($filter,$filter_not){
        $where = array("codent"=>$this->entidad); 
        if(isset($filter->codservicio) && $filter->codservicio!='')  $where = array_merge($where,array("codpro"=>$filter->codservicio));
        $query = $this->db->select('codpro, DesPro')->where($where)->get($this->table);
        $resultado = new stdClass();
        if($query->num_rows>1) exit('Existe mas de 1 resultado');
        if($query->num_rows==1){
            $resultado = $query->row();
        }
        return $resultado;
    }    
}
?>