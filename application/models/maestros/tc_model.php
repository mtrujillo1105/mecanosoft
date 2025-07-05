<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tc_model extends CI_Model{
    var $table;
    var $entidad;
    public function __construct(){
        parent::__construct();
        $this->entidad = $this->session->userdata('entidad');
        $this->table   = "tabla_m_detalle";
    }
	
    public function listar($number_items='',$offset=''){
        
        $this->db->select('cod_tabla,cod_argumento,valor_2');
        $this->db->from($this->table,$number_items,$offset);
        $this->db->where('CodEnt',$this->entidad);
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }
	
    public function obtener($fecha){
	$arrfFin = explode("/",$fecha);
	$mkfFin  = mktime(0,0,0,$arrfFin[1]+1-1,$arrfFin[0]+1-1,$arrfFin[2]+1-1); 
	$dia_hoy = date("d",$mkfFin);
	$mes_hoy = date("my",$mkfFin); 
        $where = array("cod_argumento"=>$dia_hoy,"cod_tabla"=>$mes_hoy,"CodEnt"=>$this->entidad);
        $query = $this->db->where($where)->get($this->table);
        $resultado = new stdClass();
        if($query->num_rows>1) exit('Existe mas de 1 resultado');
        if($query->num_rows==1){
            $resultado = $query->row();
        }
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