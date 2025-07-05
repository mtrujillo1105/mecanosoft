<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Presupuestosubpartida_model extends CI_Model{
    var $entidad;
    var $table;
    
    public function __construct(){
        parent::__construct();
        $this->entidad   = $this->session->userdata('entidad');
        $this->table     = "presupuesto_subpartida";
    }
    
    public function listar($filter,$filter_not){
        $arrWhere  = array('codent'=>$this->entidad);
        if(isset($filter->codpartida) && $filter->codpartida!='')           $arrWhere = array_merge($arrWhere,array("CodPartida"=>$filter->codpartida));
        if(isset($filter->codsubpartida) && $filter->codsubpartida!='')     $arrWhere = array_merge($arrWhere,array("codSubpartida"=>$filter->codsubpartida));
        if(isset($filter->codpresupuesto) && $filter->codpresupuesto!='')   $arrWhere = array_merge($arrWhere,array("Codpresupuesto "=>$filter->codpresupuesto));
        if(isset($filter->codtipoproducto) && $filter->codtipoproducto!='') $arrWhere = array_merge($arrWhere,array("Tipo"=>$filter->codtipoproducto));
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($arrWhere);
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }
    
    public function obtener($filter,$filter_not){
        $arrWhere  = array('codent'=>$this->entidad);
        if(isset($filter->codpartida) && $filter->codpartida!='')           $arrWhere = array_merge($arrWhere,array("CodPartida"=>$filter->codpartida));
        if(isset($filter->codsubpartida) && $filter->codsubpartida!='')     $arrWhere = array_merge($arrWhere,array("CodSubPartida"=>$filter->codsubpartida));
        if(isset($filter->codtipoproducto) && $filter->codtipoproducto!='') $arrWhere = array_merge($arrWhere,array("Tipo"=>$filter->codtipoproducto));        
        if(isset($filter->codpresupuesto) && $filter->codpresupuesto!='')   $arrWhere = array_merge($arrWhere,array("CodPresupuesto"=>$filter->codpresupuesto));
        $query = $this->db->where($arrWhere)->get($this->table);
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
}
?>