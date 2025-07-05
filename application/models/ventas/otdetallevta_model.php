<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Otdetallevta_model extends CI_Model{
    var $entidad;
    var $table;
    
    public function __construct()
    {
        parent::__construct();
        $this->entidad = $this->session->userdata('entidad');
        $this->table   = "OTDetalleVta";
    }  
	
    public function listar($codot,$number_items='',$offset='')
    {
        $where = array('CodEnt'=>$this->entidad,'CodOt'=>$codot);
        $this->db->select('*');
        $this->db->from($this->table,$number_items,$offset);
        $this->db->where($where);
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    } 
	
    public function obtener($codot,$item)
    {
        $where = array("CodOt"=>$codot,"CodEnt"=>$this->entidad,"item"=>$item);
        $query = $this->db->where($where)->get($this->table);
        $resultado = new stdClass();
        if($query->num_rows>1) exit('Existe mas de 1 resultado');
        if($query->num_rows==1){
            $resultado = $query->row();
        }
        return $resultado;
    }
	
    public function insertar(stdClass $filter = null)
    {
        $this->db->insert($this->table,(array)$filter);
    }
	
    public function modificar($codot,$item)
    {
        $where = array("CodOt"=>$codot,"CodEnt"=>$this->entidad,"item"=>$item);
        $this->db->where($where);
        $this->db->update($this->table,(array)$filter);
    }
	
    public function eliminar($codot,$item)
    {
        $where = array("CodOt"=>$codot,"CodEnt"=>$this->entidad,"item"=>$item);
        $this->db->delete($this->table,array($where));
    }
	
    public function buscar($filter,$number_items='',$offset='')
    {
        $this->db->select('*');
        $this->db->from($this->table,$number_items='',$offset='');
        $this->db->where('CodEnt',$this->entidad);
        $this->db->where_not_in('Estado','A');	
        if(isset($filter->CodEnt) && $filter->CodEnt!="")
            $this->db->like('CodEnt',$filter->CodEnt);
        if(isset($filter->Estado) && $filter->Estado!="")
            $this->db->like('Estado',$filter->Estado);
        $query = $this->db->get();
        if($query->num_rows>0){
            foreach($query->result() as $fila){
                    $data[] = $fila;
            }
            return $data;
        }
    }
}
?>