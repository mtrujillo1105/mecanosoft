<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tipoalmacen_model extends CI_Model{
    var $compania;
    var $table;
    public function __construct(){
        parent::__construct();
        $this->compania = $this->session->userdata('compania');
        $this->table    = "tipoalmacen";
    }
    
    public function seleccionar($default='',$filter='',$filter_not='',$number_items='',$offset=''){
        if($default!="") $arreglo = array($default=>':: Seleccione ::');
        foreach($this->listar($filter,$filter_not,$number_items,$offset) as $indice=>$valor)
        {
            $indice1   = $valor->TIPALMP_Codigo;
            $valor1    = $valor->TIPALM_Descripcion;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }
    
    public function listar($filter='',$filter_not='',$number_items='',$offset=''){
        $where = array('COMPP_Codigo'=>$this->compania);
        if(isset($filter->tipoalmacen) && $filter->tipoalmacen!='')   $where = array_merge($where,array("TIPALMP_Codigo"=>$filter->tipoalmacen));
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
    
    public function insertar($data)
    {
       $data['COMPP_Codigo'] = $this->compania; 
       $this->db->insert($this->table,$data);
       return $this->db->insert_id();  
    }
    
    public function modificar($codigo,$data){
        $this->db->where("TIPALMP_Codigo",$codigo);
        $this->db->update($this->table,$data);
    }
    
    public function eliminar($codigo){
        $this->db->delete($this->table,array('TIPALMP_Codigo' => $codigo));     
    }
    
    public function buscar($filter,$number_items='',$offset='')
    {
        $this->db->where("TIPALM_flagEstado",1);
        if(isset($filter->TIPALM_Descripcion) && $filter->TIPALM_Descripcion!='')
            $this->db->like('TIPALM_Descripcion',$filter->TIPALM_Descripcion,'right');
        $query = $this->db->get('cji_tipoalmacen',$number_items,$offset);
        if($query->num_rows>0){
                return $query->result();
        }
    }
}
?>