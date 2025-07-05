<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tipoorden_model extends CI_Model{
    var $compania;
    var $table;
    public function __construct(){
        parent::__construct();
        $this->compania = $this->session->userdata('compania');
        $this->table    = "tipoorden";
    }
    
    public function seleccionar($default='',$filter='',$filter_not='',$number_items='',$offset=''){
        if($default!="") $arreglo = array($default=>':: Seleccione ::');
        foreach($this->listar($filter,$filter_not,$number_items,$offset) as $indice=>$valor)
        {
            $indice1   = $valor->TIPORDP_Codigo;
            $valor1    = $valor->TIPORDC_Descripcion;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }
    
    public function listar($filter='',$filter_not='',$number_items='',$offset=''){
        $where = array('COMPP_Codigo'=>$this->compania);
        if(isset($filter->tipoorden) && $filter->tipoorden!='')   $where = array_merge($where,array("TIPORDP_Codigo"=>$filter->tipoorden));
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($where);		
        if(isset($filter->order_by) && count($filter->order_by)>0){
            foreach($filter->order_by as $indice=>$value){
                $this->db->order_by($indice,$value);
            }
        }           
        $this->db->limit($number_items, $offset); 
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;        
    }
    
    public function obtener($filter,$filter_not='',$number_items='',$offset=''){
        $listado = $this->listar($filter,$filter_not='',$number_items='',$offset='');
        if(count($listado)>1)
            $resultado = "Existe mas de un resultado";
        else
            $resultado = (object)$listado[0];
        return $resultado;
    }
    
    public function insertar($data){
       $data['COMPP_Codigo'] = $this->compania; 
       $this->db->insert($this->table,$data);
       return $this->db->insert_id();        
    }
    
    public function modificar($codigo,$data){
        $this->db->where("TIPORDP_Codigo",$codigo);
        $this->db->update($this->table,$data);
    }
    
    public function eliminar($codigo){
        $this->db->delete($this->table,array('TIPORDP_Codigo' => $codigo));     
    }
}
?>