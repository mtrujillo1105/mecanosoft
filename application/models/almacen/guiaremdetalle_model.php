<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Guiaremdetalle_model extends CI_Model{
    var $compania;
    var $table;
    
    public function __construct(){
        parent::__construct();
        $this->compania   = $this->session->userdata('compania');
        $this->table      = "guiaremdetalle";
        $this->table_guia = "guiarem";
        $this->table_prod = "producto";
        $this->table_und  = "unidadmedida";
    }
    
    public function listar($filter,$filter_not="",$order_by='',$number_items='',$offset=''){
        $where = array('c.COMPP_Codigo'=>$this->compania);
        if(isset($filter->guiarem) && $filter->guiarem!='')                 $where = array_merge($where,array("c.GUIAREMP_Codigo"=>$filter->guiarem));
        if(isset($filter->guiaremdetalle) && $filter->guiaremdetalle!='')   $where = array_merge($where,array("c.GUIAREMDETP_Codigo"=>$filter->guiaremdetalle));
        if(isset($filter->producto) && $filter->producto!='')               $where = array_merge($where,array("c.PRODCTOP_Codigo"=>$filter->producto));
        $this->db->select('*');
        $this->db->from($this->table." as c");
        $this->db->join($this->table_guia.' as d','d.GUIAREMP_Codigo=c.GUIAREMP_Codigo','inner');
        $this->db->join($this->table_prod.' as e','e.PROD_Codigo=c.PROD_Codigo','inner');
        $this->db->join($this->table_und.' as f','f.UNDMED_Codigo=e.UNDMED_Codigo','inner');
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
        $this->db->where("GUIAREMDETP_Codigo",$codigo);
        $this->db->update($this->table,$data);
    }
	
    public function eliminar($codigo){
        $this->db->delete($this->table,array('GUIAREMDETP_Codigo' => $codigo));        
    }
}
?>
