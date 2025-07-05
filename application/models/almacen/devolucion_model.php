<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Devolucion_model extends CI_Model{
    var $entidad;
    var $table;
    var $table_det;
    var $table_dbf;
    
    public function __construct(){
        parent::__construct();
        $this->dbase   = $this->load->database('dsn',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->table   = "Almacen_mov";
        $this->table_det = "Almacen_mov_det";
        $this->table_dbf  = "kardex";
    }
    
    public function seleccionar($tipOt,$default=""){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array(''=>$nombre_defecto);
        foreach($this->listar($tipOt) as $indice=>$valor)
        {
            $indice1   = $valor->NroDoc;
            $valor1    = $valor->NroDoc;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }
	
    public function listar($filter,$filter_not,$order_by="",$number_items='',$offset=''){
       if($this->entidad=='01'){
            $filtroinicio = "";
            $filtrofinal  = "";
            $filtroot     = "";
            if(isset($filter->codot) && $filter->codot!=''){
                if(is_array($filter->codot) && count($filter->codot)>0)
                    $filtroot  = "and k.codot in ('".str_replace(",","','",implode(',',$filter->codot))."')";
                else
                    $filtroot  = "and k.codot='".$filter->codot."'";    
            }
            if(isset($filter->fecha) && $filter->fecha!='')     $filtroinicio = "and k.Fecha = CTOD('".date_dbf($filter->fecha)."') ";
            if(isset($filter->fechai) && $filter->fechai!='')   $filtroinicio = "and k.Fecha >= CTOD('".date_dbf($filter->fechai)."') ";
            if(isset($filter->fechaf) && $filter->fechaf!='')   $filtrofinal  = "and k.Fecha <= CTOD('".date_dbf($filter->fechaf)."') ";                 
            $cadena = "
                select 
                k.codot,
                k.Moneda,
                k.Tcambio,
                k.Fecha,
                k.Tip_movmto,
                k.Documento,
                k.Serie,
                k.Numero,
                k.numoc,
                k.numcom
                from kardex as k 
                where k.Tip_movmto in ('S','I') 
                and k.Documento='DV'
                ".$filtroot."
                ".$filtroinicio."
                ".$filtrofinal."
                group by k.Fecha,k.codot,k.Moneda,k.Tcambio,k.Tip_movmto,k.Documento,k.Serie,k.Numero,k.numoc,k.numcom
                ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->result();
        }
        elseif($this->entidad=='02'){
//            $this->db->select('*');
//            $this->db->from($this->table,$number_items,$offset);
//            $this->db->where('CodEnt',$this->entidad);
//            $this->db->where_in('TipOt',$tipOt);
//            $this->db->where_not_in('Estado','A');		
//            $this->db->order_by('NroOt','desc');
//            $query = $this->db->get();
//            $resultado = array();
//            if($query->num_rows>0){
//                $resultado = $query->result();
//            }  
        }
        return $resultado;
    }
    
    public function listar_detalle($filter,$filter_not,$order_by="",$number_items='',$offset=''){
        if($this->entidad=='01'){
            $filtroinicio = "";
            $filtrofinal  = "";
            $filtroorden  = "";
            $filtroot     = "";
            $filtronumero  = "";
            $filtroserie   = "";
            $filtroproducto = "";
            $filtroserreq   = "";
            $filtronumreq   = "";            
            if(isset($filter->fechai) && $filter->fechai!='')   $filtroinicio = "and k.Fecha >= CTOD('".date_dbf($filter->fechai)."') ";
            if(isset($filter->fechaf) && $filter->fechaf!='')   $filtrofinal  = "and k.Fecha <= CTOD('".date_dbf($filter->fechaf)."') ";            
            if(isset($filter->serie) && $filter->serie!='')     $filtroserie  = "and k.Serie = '".$filter->serie."'";
            if(isset($filter->numero) && $filter->numero!='')   $filtronumero = "and k.numero = '".$filter->numero."'";
            if(isset($filter->serreq) && $filter->serreq!='')   $filtroserreq = "and k.Serreq = '".$filter->serreq."'";
            if(isset($filter->numreq) && $filter->numreq!='')   $filtronumreq = "and k.Numreq = '".$filter->numreq."'";            
            if(isset($filter->codproducto) && $filter->codproducto!='') $filtroproducto = "and k.Codigo = '".$filter->codproducto."'";       
            if(isset($order_by) && count($order_by)>0 && $order_by!=""){
                $cad = "";
                foreach($order_by as $indice=>$value){
                    $cad  = $cad .",".$value;
                }
                $cad = substr($cad,1,strlen($cad)-1);
                $filtroorden = "order by ".$cad; 
            }              
            $cadena = "
                    select 
                    k.codot,
                    k.Moneda,
                    k.Preprom,
                    k.cantidad,
                    k.Tcambio,
                    k.codigo,
                    k.Fecha,
                    k.Tip_movmto,
                    k.Documento,
                    k.Serie,
                    k.ruccli,
                    k.numreq,
                    k.codres,
                    k.ot,  
                    k.Numero,
                    k.ubica
                    from kardex as k
                    where k.Documento='DV'
                    ".$filtroot."
                    ".$filtroinicio."
                    ".$filtrofinal."                    
                    ".$filtronumero."
                    ".$filtroserie."
                    ".$filtroproducto."
                    ".$filtroserreq."
                    ".$filtronumreq."
                    ".$filtroorden."                        
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