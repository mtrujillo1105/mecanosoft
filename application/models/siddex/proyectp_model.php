<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Proyectp_model extends CI_Model {    
    public $table;
    
    public function __construct() {
        parent::__construct();
        $this->siddex = $this->load->database('siddex',TRUE);
        $this->table = "proyectp";
    }   
    
   public function listar($filter,$filter_not="",$number_items='',$offset=''){ 
//        $where = array();
//        if(isset($filter->numero) && $filter->numero!='')   $where = array_merge($where,array("NumeroOrden"=>$filter->numero));
//        if(isset($filter->codigo) && $filter->codigo!='')   $where = array_merge($where,array("CodigoArticulo"=>$filter->codigo));
//        if(isset($filter->maquina) && $filter->maquina!='') $where = array_merge($where,array("CodigoMaquina"=>$filter->maquina));
//        $this->siddex->select('*');
//        $this->siddex->from($this->table,$number_items,$offset);
//        if(is_array($where) && isset($where))   $this->siddex->where($where);
//        $this->siddex->order_by('CodigoArticulo');        
//        $query = $this->siddex->get();
//        $resultado = array();
//        if($query->num_rows>1)      $resultado = $query->result();
//        if($query->num_rows==1)     $resultado = $query->row();
//        return $resultado;
    }   
    
   public function listar_totales($filter,$filter_not="",$number_items='',$offset=''){ 
        $where = array("d.unidad"=>"KG","e.Estado"=>"Aceptado");
        $this->siddex->select("e.numeroorden,sum(c.cantidad*d.Cantidad*e.cantidad) as peso,sum(c.cantidad*d.CantidadDesglose*e.cantidad) as peso_metrado");
        $this->siddex->from("desglsp as c");
        $this->siddex->join('ldesglsp as d','d.Anyo=c.Anyo and d.NumeroProyecto=c.NumeroProyecto and d.Version=c.Version','inner');
        $this->siddex->join('proyectp as e','e.Anyo=d.Anyo and e.Numero=d.NumeroProyecto and e.Version=d.Version and e.Version=e.VersionAceptada','inner');
        if(isset($filter->numero) && $filter->numero!=''){
            $where = array_merge($where,array("e.numeroorden"=>$filter->numero));
        }
        $this->siddex->where($where);        
        $this->siddex->group_by('e.numeroorden');
        $query = $this->siddex->get();
        $resultado = array();
        if($query->num_rows>1)      $resultado = $query->result();
        if($query->num_rows==1)     $resultado = $query->row();
        return $resultado;
    }       
}
?>