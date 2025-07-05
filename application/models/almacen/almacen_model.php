<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Almacen_model extends CI_Model{
    var $compania;
    var $table;
    public function __construct(){
        parent::__construct();
        $this->compania = $this->session->userdata('compania');
        $this->table     = "almacen";
        $this->tabletipo = "tipoalmacen";
    }
    
    public function seleccionar($default='',$filter='',$filter_not='',$number_items='',$offset=''){
        if($default!="") $arreglo = array($default=>':: Seleccione ::');
        foreach($this->listar($filter,$filter_not,$number_items,$offset) as $indice=>$valor)
        {
            $indice1   = $valor->ALMAP_Codigo;
            $valor1    = $valor->ALMAC_Descripcion;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }
    
    public function listar($filter='',$filter_not='',$number_items='',$offset='')
    {
        $where = array('c.COMPP_Codigo'=>$this->compania);
        if(isset($filter->almacen) && $filter->almacen!='')   $where = array_merge($where,array("c.ALMAP_Codigo"=>$filter->almacen));
        $this->db->select('*');
        $this->db->from($this->table.' as c');
        $this->db->join($this->tabletipo.' as d','d.TIPALMP_Codigo=c.TIPALMP_Codigo','inner');
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
        $this->db->where("ALMAP_Codigo",$codigo);
        $this->db->update($this->table,$data);
    }
    
    public function eliminar($codigo){
        $this->db->delete($this->table,array('ALMAP_Codigo' => $codigo));     
    }
}
?>