<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Listamat_model extends CI_Model {    
    public $table;
    
    public function __construct() {
        parent::__construct();
        $this->siddex = $this->load->database('siddex',TRUE);
        $this->table = "listamat";
    }   
    
   public function listar($filter,$filter_not="",$number_items='',$offset=''){ 
        $where = array();
        //if(isset($filter->numero) && $filter->numero!='')   $where = array_merge($where,array("lm.NumeroOrden"=>$filter->numero));
        if(isset($filter->codigo) && $filter->codigo!='')   $where = array_merge($where,array("lm.CodigoArticulo"=>$filter->codigo));
        if(isset($filter->maquina) && $filter->maquina!='') $where = array_merge($where,array("lm.CodigoMaquina"=>$filter->maquina));
        $this->siddex->select("lm.*,f.descripcion as Familia,m.cantidad as CantidadConjuntoG,lc.cantidad as CantidadConjunto,m.cantidad*lc.cantidad*lm.cantidad*(case isnull(lm.pesopieza,0) when 0 then (case f.unidadcoeficiente when 'M' then (a.coeficientemedicion*lm.largopieza/1000) ELSE (a.coeficientemedicion*lm.largopieza/1000*lm.anchopieza/1000) end) else lm.pesopieza end) as Peso");
        $this->siddex->from($this->table." as lm",$number_items,$offset);
        $this->siddex->join('listacto as lc','lc.numeroorden=lm.numeroorden and lc.codigoconjunto=lm.codigoconjunto and lc.codigomaquina=lm.codigomaquina','inner');
        $this->siddex->join("maquinap as m","m.numeroorden=lc.numeroorden and m.codigomaquina=lc.codigomaquina","inner");        
        $this->siddex->join("articulo as a","a.codigo=lm.codigomp","inner");
        $this->siddex->join("familia as f","f.codigo=a.codigofamilia","inner");
        if(is_array($where) && isset($where))   $this->siddex->where($where);
        if(isset($filter->numero) && $filter->numero!=''){
          if(is_array($filter->numero) && count($filter->numero)>0){
            $this->siddex->where_in("lm.NumeroOrden",$filter->numero); 
          }
          else{
            $this->siddex->where(array("lm.NumeroOrden"=>$filter->numero));
          }  
        }        
        if(isset($filter->order_by) && count($filter->order_by)>0 && $filter->order_by!=""){
            foreach($filter->order_by as $indice=>$value){
                $this->siddex->order_by($indice,$value);
            }
        }             
        $query = $this->siddex->get();
        $resultado = array();
        if($query->num_rows>1)      $resultado = $query->result();
        if($query->num_rows==1)     $resultado = $query->row();
        return $resultado;
    }   
    
   public function listar_totales($filter,$filter_not="",$number_items='',$offset=''){ 
        $where = array("m.FechaPreparado!="=>'',"m.FechaAprobado!="=>'');
        $this->siddex->select("lm.numeroorden,sum(m.cantidad*lc.cantidad*lm.cantidad*(case isnull(lm.pesopieza,0) when 0 then (case f.unidadcoeficiente when 'M' then (a.coeficientemedicion*lm.largopieza/1000) ELSE (a.coeficientemedicion*lm.largopieza/1000*lm.anchopieza/1000) end) else lm.pesopieza end)) as peso");
        $this->siddex->from($this->table." as lm");
        $this->siddex->join('listacto as lc','lc.numeroorden=lm.numeroorden and lc.codigoconjunto=lm.codigoconjunto and lc.codigomaquina=lm.codigomaquina','inner');
        $this->siddex->join("maquinap as m","m.numeroorden=lc.numeroorden and m.codigomaquina=lc.codigomaquina","inner");
        $this->siddex->join("articulo as a","a.codigo=lm.codigomp","inner");
        $this->siddex->join("familia as f","f.codigo=a.codigofamilia","inner");
        $this->siddex->where($where); 
        if(isset($filter->numero) && $filter->numero!=''){
          if(is_array($filter->numero) && count($filter->numero)>0){
            $this->siddex->where_in("lm.numeroorden",$filter->numero); 
          }
          else{
            $this->siddex->where(array("lm.numeroorden"=>$filter->numero));
          }  
        }     
        
        $this->siddex->group_by('lm.numeroorden');       
        $query = $this->siddex->get();
        $resultado = array();
        if($query->num_rows>0)      $resultado = $query->result();
        return $resultado;
    }       
}
?>