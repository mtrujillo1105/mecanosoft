<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Parte_model extends CI_Model {    
    public $table;
    public function __construct() {
        parent::__construct();
        $this->siddex = $this->load->database('siddex',TRUE);
        $this->table = "parte";
    }   
    
   public function listar($filter,$filter_not="",$number_items='',$offset=''){ 
        $where = array();
        if(isset($filter->fechai) && $filter->fechai!='')     $where = array_merge($where,array("p.fecha>="=>$filter->fechai));
        if(isset($filter->fechaf) && $filter->fechaf!='')     $where = array_merge($where,array("p.fecha<="=>$filter->fechaf));
        if(isset($filter->fecha) && $filter->fecha!='')       $where = array_merge($where,array("p.fecha"=>$filter->fecha));
        if(isset($filter->numeroi) && $filter->numeroi!='')   $where = array_merge($where,array("p.numeroorden>="=>$filter->numeroi));
        if(isset($filter->numerof) && $filter->numerof!='')   $where = array_merge($where,array("p.numeroorden<="=>$filter->numerof));
        if(isset($filter->operario) && $filter->operario!='') $where = array_merge($where,array("o.Codigo="=>$filter->operario));
        if(isset($filter->dni) && $filter->dni!='')           $where = array_merge($where,array("o.NumeroTarjeta="=>$filter->dni));
        if(isset($filter->tipohora) && $filter->tipohora!='') $where = array_merge($where,array("p.TipoHora="=>$filter->tipohora));
        if(isset($filter->proceso) && $filter->proceso!='')         $where = array_merge($where,array("p.codigoproceso"=>$filter->proceso));
        if(isset($filter_not->proceso) && $filter_not->proceso!='') $where = array_merge($where,array("p.codigoproceso!="=>$filter_not->proceso));             
        $this->siddex->select("CONVERT(VARCHAR,p.Fecha,103) as FechaParte,pr.Descripcion as Proceso,*");
        $this->siddex->from($this->table." as p");
        $this->siddex->join('operario as o','o.Codigo = p.CodigoOperario','left');
        $this->siddex->join('proceso as pr','pr.Codigo = p.CodigoProceso','left');
        if(isset($filter->numero)){
            if(is_array($filter->numero) && count($filter->numero)>0){
                $this->siddex->where_in("p.numeroorden",$filter->numero);
            }
            else{
                $this->siddex->where("p.numeroorden",$filter->numero);
            }
        }    
        $this->siddex->where($where);        
        $query = $this->siddex->get();
        $resultado = array();
        if($query->num_rows>1)      $resultado = $query->result();
        if($query->num_rows==1)     $resultado = $query->row();
        return $resultado;
    }   
    
//   public function listar_totales($filter,$filter_not="",$number_items='',$offset=''){ 
//        $where = array("p.numeroorden>"=>140000,"len(p.numeroorden)"=>6);
//        if(isset($filter->fechai) && $filter->fechai!='')           $where = array_merge($where,array("p.fecha>="=>$filter->fechai));
//        if(isset($filter->fechaf) && $filter->fechaf!='')           $where = array_merge($where,array("p.fecha<="=>$filter->fechaf));
//        if(isset($filter->fecha) && $filter->fecha!='')             $where = array_merge($where,array("p.fecha"=>$filter->fecha));
//        $this->siddex->select("p.numeroorden,substring(codigoproceso,1,1)+'00' as proceso,sum(p.tiempoejecucion) as horas,sum(p.tiempoejecucion*p.tarifa) as monto");
//        $this->siddex->from($this->table." as p");
//        if(isset($filter->numero) && $filter->numero!=''){
//            $where = array_merge($where,array("p.numeroorden"=>$filter->numero));
//        }
//        $this->siddex->where($where);        
//        $this->siddex->group_by('substring(codigoproceso,1,1),p.numeroorden');
//        $query = $this->siddex->get();
//        $resultado = array();
//        if($query->num_rows>1)      $resultado = $query->result();
//        if($query->num_rows==1)     $resultado = $query->row();
//        return $resultado;
//    }       
    
   public function listar_totales2($filter,$filter_not="",$number_items='',$offset=''){ 
        $where = array();
        if(isset($filter->group_by) && $filter->group_by!='') $campos = implode(",",$filter->group_by);
        if(isset($filter->fechai) && $filter->fechai!='')     $where = array_merge($where,array("p.fecha>="=>$filter->fechai));
        if(isset($filter->fechaf) && $filter->fechaf!='')     $where = array_merge($where,array("p.fecha<="=>$filter->fechaf));
        if(isset($filter->fecha) && $filter->fecha!='')       $where = array_merge($where,array("p.fecha"=>$filter->fecha));
        if(isset($filter->numeroi) && $filter->numeroi!='')   $where = array_merge($where,array("p.numeroorden>="=>$filter->numeroi));
        if(isset($filter->numerof) && $filter->numerof!='')   $where = array_merge($where,array("p.numeroorden<="=>$filter->numerof));
        if(isset($filter->operario) && $filter->operario!='') $where = array_merge($where,array("o.Codigo="=>$filter->operario));
        if(isset($filter->dni) && $filter->dni!='')           $where = array_merge($where,array("o.NumeroTarjeta="=>$filter->dni));
        if(isset($filter->tipohora) && $filter->tipohora!='') $where = array_merge($where,array("p.TipoHora="=>$filter->tipohora));
        if(isset($filter->proceso) && $filter->proceso!='')         $where = array_merge($where,array("p.codigoproceso"=>$filter->proceso));
        if(isset($filter_not->proceso) && $filter_not->proceso!='') $where = array_merge($where,array("p.codigoproceso!="=>$filter_not->proceso));        
        $sql  = "".$campos.",";
        $sql .= "sum(p.tiempoejecucion) as Horas,sum(p.tiempoejecucion*p.tarifa) as Monto";        
        $this->siddex->select($sql);
        $this->siddex->from($this->table." as p");
        $this->siddex->join('operario as o','o.Codigo = p.CodigoOperario','left');
        if(isset($filter->numero)){
            if(is_array($filter->numero) && count($filter->numero)>0){
                $this->siddex->where_in("p.numeroorden",$filter->numero);
            }
            else{
                $this->siddex->where("p.numeroorden",$filter->numero);
            }
        }    
        $this->siddex->where($where);        
        $this->siddex->group_by($filter->group_by); 
        $query = $this->siddex->get();
        $resultado = array();
        if($query->num_rows>1)      $resultado = $query->result();
        if($query->num_rows==1)     $resultado = $query->row();
        return $resultado;
    }    
}
?>