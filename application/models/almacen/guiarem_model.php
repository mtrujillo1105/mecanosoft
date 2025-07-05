<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Guiarem_model extends CI_Model{
    var $compania;
    var $table;
    
    public function __construct(){
        parent::__construct();
        $this->compania = $this->session->userdata('compania');
        $this->table      = "guiarem";
        $this->table_prod = "producto";
        $this->table_alm  = "almacen";
        $this->table_cli  = "cliente";
        $this->table_emp  = "empresa";
        $this->table_usu  = "usuario";
        $this->table_per  = "persona";
    }

    public function seleccionar($default='',$filter='',$filter_not='',$number_items='',$offset=''){
        if($default!="") $arreglo = array($default=>':: Seleccione ::');
        foreach($this->listar($filter,$filter_not,$number_items,$offset) as $indice=>$valor){
            $indice1   = $valor->PROD_Codigo;
            $valor1    = $valor->PROD_Nombre;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }
    
    public function listar($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        $where = array('c.COMPP_Codigo'=>$this->compania);
        if(isset($filter->guiarem) && $filter->guiarem!='')  $where = array_merge($where,array("c.GUIAREMP_Codigo"=>$filter->guiarem));
        if(isset($filter->fecha) && $filter->fecha!='')      $where = array_merge($where,array("c.GUIAREMC_Fecha"=>$filter->fecha));
        if(isset($filter->fechai) && $filter->fechai!='')    $where = array_merge($where,array("c.GUIAREMC_Fecha>="=>$filter->fecha));
        if(isset($filter->fechaf) && $filter->fechaf!='')    $where = array_merge($where,array("c.GUIAREMC_Fecha<="=>$filter->fecha));
        $this->db->select('*');
        $this->db->from($this->table." as c");
        $this->db->join($this->table_alm.' as d','d.ALMAP_Codigo=c.ALMAP_Codigo','inner');
        $this->db->join($this->table_cli.' as e','e.CLIP_Codigo=c.CLIP_Codigo','inner');
        $this->db->join($this->table_emp.' as f','f.EMPRP_Codigo=e.EMPRP_Codigo','inner');
        $this->db->join($this->table_usu.' as g','g.USUA_Codigo=c.USUA_Codigo','inner');
        $this->db->join($this->table_per.' as h','h.PERSP_Codigo=g.PERSP_Codigo','inner');
        $this->db->where($where);		
        if(isset($filter->order_by) && count($filter->order_by)>0){
            foreach($filter->order_by as $indice=>$value){
                $this->db->order_by($indice,$value);
            }
        }           
        $this->db->limit($number_items, $offset); 
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }
    
    public function obtener($filter,$filter_not='',$number_items='',$offset=''){
        $listado = $this->listar($filter,$filter_not='',$number_items='',$offset='');
        if(count($listado)>1)
            $resultado = "Existe mas de un resultado";
        else
            $resultado = (object)$listado[0];
        return $resultado;
    }
  
    public function listar_detalle($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        if($this->entidad=='01'){
            $filtrocodpro  = "";
            $filtrofechai  = "";
            $filtrofechaf  = "";   
            $filtroot  = "";
            if ($filter->fechai=='__/__/____')
                $filter->fechai='';
            if ($filter->fechaf=='__/__/____')
                $filter->fechaf='';
            if(isset($filter->codproducto) && $filter->codproducto!='') $filtrocodpro  = "and gcodpro='".$filter->codproducto."'";  
            if(isset($filter->codot) && $filter->codot!='' && ($filter->ot)!=0)             $filtroot = "and a.codot='".$filter->codot."'";  
            if(isset($filter->fechai) && $filter->fechai!='')           $filtrofechai  = "and a.fecemi>=CTOD('".($filter->fechai)."')"; 
            if(isset($filter->fechaf) && $filter->fechaf!='')           $filtrofechaf  = "and a.fecemi<=CTOD('".($filter->fechaf)."')"; 
            $cadena = "select *
                    from guiasrem as a
                    INNER JOIN producto as b ON a.gcodpro=b.p_codigo
                    where a.gnumguia!=' '
                    ".$filtroot."
                    ".$filtrocodpro." 
                    ".$filtrofechai."
                    ".$filtrofechaf."
                    order by a.fecemi
                    ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->result();
        }
        elseif($this->entidad=='02'){
            
        }
        return $resultado;
    }
    
      
}
?>