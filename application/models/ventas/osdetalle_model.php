<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Osdetalle_model extends CI_Model{
    var $entidad;
    var $table;
    
    public function __construct()
    {
        parent::__construct();
        $this->entidad = "02";
        $this->table   = "OtDetalle";
    }  
    
    public function listar($filter,$filter_not,$order_by,$number_items='',$offset='')
    {
        $where = array('CodEnt'=>$this->entidad,'CodOt'=>$codot);
        $this->db->select('*');
        $this->db->from($this->table,$number_items,$offset);
        $this->db->where($where);
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();//result
        }
        return $resultado;
    } 
	
    public function obtener($filter,$filter_not)
    {
        $arrWhere  = array('os.CodEnt'=>$this->entidad);
        $this->db->select("*");
        $this->db->from($this->table." as os");
        $this->db->where($arrWhere);   
        if(isset($filter->codot) && $filter->codot!='')  $this->db->where('os.CodOt',$filter->codot);
        if(isset($filter->oc) && $filter->oc!='')        $this->db->where('os.oc',$filter->oc);
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>1) exit("Existe mas de 1 resultado en la tabla ".$this->table."");
        if($query->num_rows>0){
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
    
}
?>
