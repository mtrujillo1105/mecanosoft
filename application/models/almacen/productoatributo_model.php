<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Productoatributo_model extends CI_Model{
    var $compania;
    var $table;
    
    public function __construct(){
        parent::__construct();
        $this->compania  = $this->session->userdata('compania');
        $this->table     = "productoatributo";
        $this->table_cab = "producto";
    }
	
    public function seleccionar($default='',$filter='',$filter_not='',$number_items='',$offset=''){
        if($default!="") $arreglo = array($default=>':: Seleccione ::');
        foreach($this->listar($filter,$filter_not,$number_items,$offset) as $indice=>$valor)
        {
            $indice1   = $valor->PRODATRIB_Codigo;
            $valor1    = $valor->PRODATRIB_Descripcion;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }
    
    public function listar($filter,$filter_not="",$number_items='',$offset=''){
        $where = array('c.COMPP_Codigo'=>$this->compania);
        if(isset($filter->producto) && $filter->producto!='') $where = array_merge($where,array("c.PROD_Codigo"=>$filter->producto));
        if(isset($filter->productoatributo) && $filter->productoatributo!='') $where = array_merge($where,array("c.PRODATRIB_Codigo"=>$filter->productoatributo));
        $this->db->select('*');
        $this->db->from($this->table.' as c');
        $this->db->join($this->table_cab.' as d','d.PROD_Codigo=c.PROD_Codigo','inner');
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
        $this->db->where("PRODATRIB_Codigo",$codigo);
        $this->db->update($this->table,$data);
    }
	
    public function eliminar($codigo){
        $this->db->delete($this->table,array('PRODATRIB_Codigo' => $codigo));        
    }
}
?>