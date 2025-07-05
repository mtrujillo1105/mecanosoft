<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Atributo_model extends CI_Controller{
    var $compania;
    var $table;
    
    public function __construct(){
        parent::__construct();
        $this->compania = $this->session->userdata('compania');
        $this->table    = "atributo";
    }
    
    public function listar_atributos(){
		
                $query = $this->db->order_by('ATRIB_Descripcion')->where('ATRIB_FlagEstado','1')->get($this->table);
                
		if($query->num_rows>0){
			foreach($query->result() as $fila){
				$data[] = $fila;
			}
			return $data;
		}
                 else {
                        return array();
                }
	}
	function obtener($atributo){
		$query = $this->db->where("ATRIB_Codigo",$atributo)->get($this->table);
		if($query->num_rows>0){
			foreach($query->result() as $fila){
				$data[] = $fila;
			}
			return $data;		
		}	
	}
	function insertar_atributo($tipo,$descripcion){
		$data = array(
					  "ATRIB_TipoAtributo" => $tipo,
					  "ATRIB_Descripcion"  => strtoupper($descripcion)
					 );
		$this->db->insert("cji_atributo",$data);
	}
	function modificar_atributo($atributo,$descripcion){
		$data = array("ATRIB_Descripcion" => strtoupper($descripcion));
		$this->db->where('ATRIB_Codigo',$atributo);
		$this->db->update("cji_atributo",$data);
	}
	function eliminar_atributo($atributo){
		$data  = array("ATRIB_FlagEstado" => '0');
		$where = array("ATRIB_Codigo"     => $atributo); 
		$this->db->where($where);
		$this->db->update('cji_atributo',$data);	
	}
        public function buscar_atributo($filter,$number_items='',$offset='')
	{
            if(isset($filter->nombre_atributo) && $filter->nombre_atributo!='')
                $this->db->like('ATRIB_Descripcion',$filter->nombre_atributo,'both');
            $query = $this->db->get('cji_atributo',$number_items,$offset);
            if($query->num_rows>0){
                foreach($query->result() as $fila){
                        $data[] = $fila;
                }
                return $data;
            }
	}
}
?>