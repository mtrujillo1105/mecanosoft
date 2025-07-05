<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Guiain_model extends CI_Model{
    var $compania;
    var $table;  
    var $table_det;
    public function __construct(){
        parent::__construct();
        $this->compania = $this->session->userdata('compania');
        $this->table   = "guiain";
        $this->table_det = "guiain_detalle";    
    }
    
    public function seleccionar($default='',$filter='',$filter_not='',$number_items='',$offset=''){
        if($default!="") $arreglo = array($default=>':: Seleccione ::');
        foreach($this->listar($filter,$filter_not,$number_items,$offset) as $indice=>$valor)
        {
            $indice1   = $valor->GUIAINP_Codigo;
            $valor1    = $valor->GUIAINC_Numero;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }

    public function listar($filter,$filter_not='',$number_items='',$offset=''){
            $filtroinicio = "";
            $filtrofinal  = "";
            $filtroot     = "";
            $filtronumoc  = "";
            if(isset($filter->codot) && $filter->codot!=''){
                if(is_array($filter->codot) && count($filter->codot)>0)
                    $filtroot  = "and k.codot in ('".str_replace(",","','",implode(',',$filter->codot))."')";
                else
                    $filtroot  = "and k.codot='".$filter->codot."'";    
            }
            if(isset($filter->fecha) && $filter->fecha!='')     $filtroinicio = "and k.Fecha = CTOD('".date_dbf($filter->fecha)."') ";
            if(isset($filter->fechai) && $filter->fechai!='')   $filtroinicio = "and k.Fecha >= CTOD('".date_dbf($filter->fechai)."') ";
            if(isset($filter->fechaf) && $filter->fechaf!='')   $filtrofinal  = "and k.Fecha <= CTOD('".date_dbf($filter->fechaf)."') "; 
            if(isset($filter->numoc) && $filter->numoc!='')     $filtronumoc  = "and k.Numoc = '".trim($filter->numoc)."'";
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
                where k.Tip_movmto='I' 
                AND k.Documento not in ('AJ','D','DV','TF','TR')
                ".$filtroot."
                ".$filtroinicio."
                ".$filtrofinal."
                ".$filtronumoc."
                group by k.Fecha,k.codot,k.Moneda,k.Tcambio,k.Tip_movmto,k.Documento,k.Serie,k.Numero,k.numoc,k.numcom
                ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->result();    
        return $resultado;
    }
    
    public function listar_detalle($filter,$filter_not,$order_by="",$number_items='',$offset=''){
        if($this->entidad=='01'){
            $filtroinicio = "";
            $filtrofinal  = "";
            $filtroorden  = "";
            $filtroot     = "";
            $filtroserie  = "";
            $filtronumero = "";
            $filtroproducto = "";
            $filtrocodot  = "";
            $filtrogot    = "";
            $filtronumoc  = "";
            if(isset($filter->codot) && $filter->codot!=''){
                if(is_array($filter->codot) && count($filter->codot)>0){
                    $filtroot  = "and k.codot in ('".str_replace(",","','",implode(',',$filter->codot))."')";
                }
                else{
                    $filtroot  = "and k.codot='".$filter->codot."'";    
                }
            }
            if(isset($filter->fecha) && $filter->fecha!='')     $filtroinicio = "and k.Fecha = CTOD('".date_dbf($filter->fecha)."') ";
            if(isset($filter->fechai) && $filter->fechai!='')   $filtroinicio = "and k.Fecha >= CTOD('".date_dbf($filter->fechai)."') ";
            if(isset($filter->fechaf) && $filter->fechaf!='')   $filtrofinal  = "and k.Fecha <= CTOD('".date_dbf($filter->fechaf)."') ";
            if(isset($filter->serie) && $filter->serie!='')     $filtroserie  = "and k.Serie = '".$filter->serie."'";
            if(isset($filter->numero) && $filter->numero!='')   $filtronumero = "and k.Numero = '".$filter->numero."'";
            if(isset($filter->codproducto) && $filter->codproducto!='')   $filtroproducto = "and k.Codigo = '".$filter->codproducto."'";
            if(isset($filter->codot) && $filter->codot!='')     $filtrocodot  = "and k.Codot = '".trim($filter->codot)."'";
            if(isset($filter->got) && $filter->got!='')         $filtrogot    = "and k.ot = '".trim($filter->got)."'";
            if(isset($filter->numoc) && $filter->numoc!='')     $filtronumoc  = "and k.Numoc = '".trim($filter->numoc)."'";
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
                k.Cantidad,
                k.Tcambio,
                k.Codigo,
                k.Fecha,
                k.Tip_movmto,
                k.Documento,
                k.Serie,
                k.Numero,
                k.numoc,
                k.sercom,
                k.numcom,
                k.ruccli,
                k.numreq, 
                k.codres,
                k.ot,  
                k.ubica,
                k.codmov,
                k.user,
                k.estmov,
                k.Fec_reg as fechareg
                from kardex as k 
                where k.Tip_movmto='I' 
                AND k.Documento not in ('AJ','D','DV','TF','TR')
                ".$filtroot."
                ".$filtroinicio."
                ".$filtrofinal."
                ".$filtroserie."
                ".$filtronumero." 
                ".$filtroproducto."
                ".$filtrocodot."
                ".$filtrogot."
                ".$filtronumoc."
                ".$filtroorden."
                ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->result();
            
        }
        elseif($this->entidad=='02'){
            $where = array("c.TipDoc"=>"NE",'c.CodEnt'=>$this->entidad);
            if(isset($filter->fechai) && $filter->fechai!="")           $where = array_merge($where,array("c.FecMov>="=>$filter->fechai));
            if(isset($filter->fechaf) && $filter->fechaf!="")           $where = array_merge($where,array("c.FecMov<="=>$filter->fechaf));
            if(isset($filter->fecha) && $filter->fecha!="")             $where = array_merge($where,array("c.FecMov"=>$filter->fecha));
            if(isset($filter->serie) && $filter->serie!="")             $where = array_merge($where,array("c.SerieDoc"=>$filter->serie));
            if(isset($filter->numero) && $filter->numero!="")           $where = array_merge($where,array("c.NroDoc"=>$filter->numero));
            if(isset($filter->codproducto) && $filter->codproducto!="") $where = array_merge($where,array("d.CodPro"=>$filter->codproducto));
            if(isset($filter->codot) && $filter->codot!="")             $where = array_merge($where,array("d.CodOt"=>$filter->codot));
            if(isset($filter->got) && $filter->got!="")                 $where = array_merge($where,array("ot.NroOt"=>$filter->got));
            if(isset($filter->numoc) && $filter->numoc!="")             $where = array_merge($where,array("c.NroOc"=>$filter->numoc));            
            $this->db->select("d.codot,c.mo,d.preprom,d.cantidad,d.tc_p as tcambio,d.CodPro as codigo,convert(varchar(10),c.FecMov,120) as fecha,convert(varchar(10),c.fec_reg,120) as fechareg,c.TipMov as tip_movmto,c.TipDocRef as documento,c.SerieDoc as sercom,c.NroDoc as numcom,c.NroDoc as numero,c.NroOc as numoc,c.ruccli,'' as numreq,c.codres,'' as ot,'' as ubica,'' as codmov,'' as estmov");
            $this->db->from($this->table_det." as d",$number_items,$offset);
            $this->db->join($this->table." as c","c.SerieDoc=d.SerieDoc and c.NroDoc=d.NroDoc and c.TipDoc=d.TipDoc and c.Codent=d.Codent");
            $this->db->join("ot","ot.codot=d.codot");
            $this->db->where($where);
            $this->db->where_not_in('c.EstMov','A');		
            $this->db->order_by('c.NroDoc','desc');
            $query = $this->db->get();
            $resultado = array();
            if($query->num_rows>0){
                $resultado = $query->result();
            }   
        }
        return $resultado;
    }
    
    /*Lista el detalle de las NEAs*/
    public function listar_detalle2($serie_oc,$numero_oc,$codpro){
        $where = array("Almacen_mov_det.tipdoc"=>"NE",'Almacen_mov_det.CodEnt'=>$this->entidad,"Almacen_mov.SerOc"=>$serie_oc,"Almacen_mov.NroOc"=>$numero_oc,"Almacen_mov_det.CodPro"=>$codpro);
        $this->db->select('Almacen_mov.SerieDoc,Almacen_mov.NroDoc,convert(varchar,Almacen_mov.Fec_Doc,103) as Fec_Doc,convert(varchar,Almacen_mov.FecMov,103) as FecMov,convert(varchar,Almacen_mov.Fec_Reg,103) as Fec_Reg');
        $this->db->from("Almacen_mov_det");
        $this->db->join($this->table,"Almacen_mov.NroDoc=Almacen_mov_det.NroDoc and Almacen_mov.codent=Almacen_mov_det.codent");
        $this->db->where($where);
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }    
        
    public function listar_ingresos($filter,$filter_not="",$number_items='',$offset=''){
        if($this->entidad=='01'){
           $filtroinicio    = "";
            $filtrofinal    = "";
            $filtronumoc    = "";
            $filtroproducto = "";
            $filtroorden    = "";
            $filtrotipoot   = "";
            $filtroot       = "";
            if(isset($filter->tipoot) && $filter->tipoot!='')   $filtrotipoot = "and k.tipot='".$filter->tipoot."'";
            if(isset($filter->fecha) && $filter->fecha!='')     $filtroinicio = "and k.Fecha = CTOD('".date_dbf($filter->fecha)."') ";
            if(isset($filter->fechai) && $filter->fechai!='')   $filtroinicio = "and k.Fecha >= CTOD('".date_dbf($filter->fechai)."') ";
            if(isset($filter->fechaf) && $filter->fechaf!='')   $filtrofinal  = "and k.Fecha <= CTOD('".date_dbf($filter->fechaf)."') ";  
            if(isset($filter->numoc) && $filter->numoc!='')     $filtronumoc  = "and k.Numoc = '".trim($filter->numoc)."'";
            if(isset($filter->codproducto) && $filter->codproducto!='')   $filtroproducto = "and k.Codigo = '".$filter->codproducto."'";
            if(isset($filter->group_by) && count($filter->group_by)>0 && $filter->group_by!="") $campos   = implode(",",$filter->group_by);
            if(isset($filter->codot) && $filter->codot!='')     $filtroot     = "having k.codot='".$filter->codot."'";
            if(isset($filter->order_by) && count($filter->order_by)>0 && $filter->order_by!=""){
                $cad = "";
                foreach($filter->order_by as $indice=>$value){
                    $cad  = $cad .",".$value;
                }
                $cad = substr($cad,1,strlen($cad)-1);
                $filtroorden = "order by ".$cad; 
            }              
            $cadena = "
                select 
                ".$campos.",
                SUM(iif(k.Tip_movmto='S',-1*k.Cantidad,k.Cantidad)) as cantidad,
                SUM(iif(k.Tip_movmto='S',-1*k.Preprom,k.Preprom)*(k.Cantidad/k.Tcambio)) as dolares,
                SUM(iif(k.Tip_movmto='S',-1*k.Preprom,k.Preprom)*k.Cantidad) as soles
                from kardex as k 
                where k.Tip_movmto='I' 
                AND k.Documento not in ('AJ','D','DV','TF','TR')
                ".$filtroinicio."
                ".$filtrofinal."
                ".$filtronumoc."
                ".$filtroproducto."
                ".$filtrotipoot."
                group by ".$campos."
                ".$filtroorden."
                ".$filtroot."
                ";
            $query = $this->dbase->query($cadena);
            //echo "ingreso ::: ". $this->dbase->last_query()."<br>";
            $resultado = $query->result();
            
        }
        elseif($this->entidad=='02'){
            
        }
        return $resultado;
    }
    
    /*Listar el detalle de las NEAS - devoluciones al proveedor*/
    public function listar_ingresos_detalle($filter,$filter_not,$number_items='',$offset=''){
        if($this->entidad=='01'){
            $filtroinicio = "";
            $filtrofinal  = "";
            $filtroorden  = "";
            $filtroot     = "";
            $filtroserie  = "";
            $filtronumero = "";
            $filtrolinea_not = "";
            if(isset($filter->codot) && $filter->codot!=''){
                if(is_array($filter->codot) && count($filter->codot)>0){
                    $interv = 20;
                    for($i=0;$i<ceil(count($filter->codot)/$interv);$i++){
                        $arrcodot = array_slice($filter->codot,$i*$interv,$interv);
                        $filtroot .= ($i==0?"and (":"or")." k.codot in ('".str_replace(",","','",implode(',',$arrcodot))."')";
                    }
                    $filtroot .= ")";
                }
                else{
                    $filtroot  = "and k.codot='".$filter->codot."'";    
                }
            }
            if(isset($filter->fechai) && $filter->fechai!='')   $filtroinicio = "and k.Fecha >= CTOD('".date_dbf($filter->fechai)."') ";
            if(isset($filter->fechaf) && $filter->fechaf!='')   $filtrofinal  = "and k.Fecha <= CTOD('".date_dbf($filter->fechaf)."') ";
            if(isset($filter->serie) && $filter->serie!='')     $filtroserie  = "and k.Serie = '".$filter->serie."'";
            if(isset($filter->numero) && $filter->numero!='')   $filtronumero = "and k.Numero = '".$filter->numero."'";
            if(isset($filter_not->linea) && $filter_not->linea!=''){
                if(is_array($filter_not->linea) && count($filter_not->linea)>0){
                    $interv = 20;
                    for($i=0;$i<ceil(count($filter_not->linea)/$interv);$i++){
                        $arrlinea = array_slice($filter_not->linea,$i*$interv,$interv);
                        $filtrolinea_not .= ($i==0?"and ":" and")." SUBSTR(k.Codigo,1,4) not in ('".str_replace(",","','",implode(',',$arrlinea))."')";
                    }
                }
                else{
                    $filtrolinea_not  = "and SUBSTR(k.Codigo,1,4)!='".$filter_not->linea."'";    
                }
            }              
            if(isset($filter->order_by) && count($filter->order_by)>0 && $filter->order_by!=""){
                $cad = "";
                foreach($filter->order_by as $indice=>$value){
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
                k.Cantidad,
                k.Tcambio,
                k.Codigo,
                k.Fecha,
                k.Tip_movmto,
                k.Documento,
                k.Serie,
                k.Numero,
                k.peso_total,
                k.Numero,
                k.Serreq,
                k.Numreq
                from kardex as k 
                where k.Tip_movmto='I' 
                AND k.Documento not in ('AJ','D','DV','TF','TR')
                ".$filtroot."
                ".$filtroinicio."
                ".$filtrofinal."
                ".$filtroserie."
                ".$filtronumero." 
                ".$filtrolinea_not."
                ".$filtroorden."
                ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->result();
        }
        elseif($this->entidad=='02'){
            $this->db->select('*');
            $this->db->from($this->table,$number_items,$offset);
            $this->db->where('CodEnt',$this->entidad);
            $this->db->where_in('TipOt',$tipOt);
            $this->db->where_not_in('Estado','A');		
            $this->db->order_by('NroOt','desc');
            $query = $this->db->get();
            $resultado = array();
            if($query->num_rows>0){
                $resultado = $query->result();
            }  
        }
        return $resultado;
    }    
}
?>
