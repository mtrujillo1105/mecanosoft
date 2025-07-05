<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Entidad_model extends CI_Model{
    var $table;
    
    public function __construct(){
        parent::__construct();
        $this->table   = "compania";
    }
	
    public function seleccionar($default=""){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array(''=>$nombre_defecto);
        foreach($this->listar() as $indice=>$valor)
        {
            $indice1   = $valor->COMPP_Codigo;
            $valor1    = $valor->EMPRC_RazonSocial;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }
	
    public function listar($number_items='',$offset=''){
        $this->db->select('*');
        $this->db->from($this->table." as c",$number_items,$offset);
        $this->db->join('empresa as e','e.EMPRP_Codigo=c.EMPRP_Codigo','inner');     
        $query = $this->db->get();
        $resultado = array();

        //if($query->count_all>0){
            $resultado = $query->result();
        //}

        return $resultado;
    }
	
    public function obtener($id){
        $where = array("c.COMPP_Codigo"=>$id);
        $this->db->select('*');
        $this->db->from($this->table." as c");
        $this->db->join('empresa as e','e.EMPRP_Codigo=c.EMPRP_Codigo','inner');   
        $this->db->where($where);
        $query = $this->db->get();
        $resultado = new stdClass();
        //if($query->num_rows>1) exit('Existe mas de 1 resultado');
        //if($query->num_rows==1){
            $resultado = $query->row();
        //}
        return $resultado;
    }
	
    public function insertar(stdClass $filter = null){
        $this->db->insert($this->table,(array)$filter);
    }
	
    public function modificar($id,$filter){
        $this->db->where("CodEnt",$id);
        $this->db->update($this->table,(array)$filter);
    }
	
    public function eliminar($id){
        $this->db->delete($this->table,array('CodEnt' => $id));
    }
}
?>