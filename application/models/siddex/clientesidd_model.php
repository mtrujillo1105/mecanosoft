<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Clientesidd_model extends CI_Model {    
    public $table;
    public function __construct() {
        parent::__construct();
        $this->siddex = $this->load->database('siddex',TRUE);
        $this->table = "cliente";
    }   
    
    public function seleccionar($filter,$default="",$value='')
    {
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_defecto);
        foreach($this->listar($filter) as $indice=>$valor)
        {
            $indice1   = $valor->Codigo;
            $valor1    = $valor->RazonSocial;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }    
    
   public function listar($filter,$filter_not="",$number_items='',$offset=''){ 
        $where = array();
        $this->siddex->select('*');
        $this->siddex->from($this->table." as c",$number_items,$offset);
        if(is_array($where) && isset($where))   $this->siddex->where($where);
        if(isset($filter->codigo) && $filter->codigo!=''){
            if(is_array($filter->codigo) && count($filter->codigo)>0){
                $this->siddex->where_in("c.codigo",$filter->codigo);
            }
            else{
                $this->siddex->where("c.codigo",$filter->codigo);
            }
        } 
        if(isset($filter->ruc) && $filter->ruc!=''){
            if(is_array($filter->ruc) && count($filter->ruc)>0){
                $this->siddex->where_in("c.cif",$filter->ruc);
            }
            else{
                $this->siddex->where("c.cif",$filter->ruc);
            }
        }         
        $this->siddex->order_by('c.RazonSocial');        
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