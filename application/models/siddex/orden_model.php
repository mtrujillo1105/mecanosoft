<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Orden_model extends CI_Model {    
    public $table;
    public function __construct() {
        parent::__construct();
        $this->siddex = $this->load->database('siddex',TRUE);
        $this->table = "orden";
    }   
    
   public function listar($filter,$filter_not="",$number_items='',$offset=''){ 
        $where = array();
        if(isset($filter->numero) && $filter->numero!='')       $where = array_merge($where,array("o.numero"=>$filter->numero));
        if(isset($filter->numeroi) && $filter->numeroi!='')     $where = array_merge($where,array("o.numero>="=>$filter->numeroi));
        if(isset($filter->numerof) && $filter->numerof!='')     $where = array_merge($where,array("o.numero<="=>$filter->numerof));
        if(isset($filter->clase) && $filter->clase!='')         $where = array_merge($where,array("p.ClaseOferta"=>$filter->clase));
        if(isset($filter->codcli) && $filter->codcli!='')       $where = array_merge($where,array("o.CodigoCliente"=>$filter->codcli));
        if(isset($filter->situacion) && $filter->situacion!='') $where = array_merge($where,array("o.Situacion"=>$filter->situacion));
        $this->siddex->select('o.*,p.CodigoCliente,p.ClaseOferta,p.Nombre,convert(char,o.Fecha,103) as fecot,convert(char,o.FechaEntrega,103) as fteot');
        $this->siddex->from($this->table." as o",$number_items,$offset);
        $this->siddex->join('proyectp as p','p.numeroorden=o.numero','left');
        if(is_array($where) && isset($where))   $this->siddex->where($where);
        $this->siddex->order_by('o.numero desc');        
        $query = $this->siddex->get();
        $resultado = array();
        if($query->num_rows>1)      $resultado = $query->result();
        if($query->num_rows==1)     $resultado = $query->row();
        return $resultado;
    }   
    
   public function listar_totales($filter,$filter_not="",$number_items='',$offset=''){ 
//        $where = array("e.numeroorden>"=>140000);
//        $this->siddex->select("e.numeroorden,sum(c.cantidad*d.Cantidad*e.cantidad) as peso,sum(c.cantidad*d.CantidadDesglose*e.cantidad) as peso_metrado");
//        $this->siddex->from("desglsp as c");
//        $this->siddex->join('ldesglsp as d','d.Anyo=c.Anyo and d.NumeroProyecto=c.NumeroProyecto and d.Version=c.Version','inner');
//        $this->siddex->join('proyectp as e','e.Anyo=d.Anyo and e.Numero=d.NumeroProyecto and e.Version=d.Version','inner');
//        if(isset($filter->numero) && $filter->numero!=''){
//            $where = array_merge($where,array("e.numeroorden"=>$filter->numero));
//        }
//        $this->siddex->where($where);        
//        $this->siddex->group_by('e.numeroorden');
//        $query = $this->siddex->get();
//        $resultado = array();
//        if($query->num_rows>1)      $resultado = $query->result();
//        if($query->num_rows==1)     $resultado = $query->row();
//        return $resultado;
    }       
}
?>