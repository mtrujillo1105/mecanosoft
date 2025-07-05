<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Guiasa_model extends CI_Model{
    var $entidad;
    var $table;
    var $table_det;
    var $table_dbf;
    
    public function __construct(){
        parent::__construct();
        $this->entidad   = $this->session->userdata('entidad');
        $this->table     = "Almacen_mov"; 
        $this->table_det = "Almacen_mov_det";
        $this->table_dbf = "kardex";
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
                where k.Tip_movmto='S' 
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
    
    /*Lista el detalle de los vales de salida*/
    public function listar_detalle($filter,$filter_not,$order_by='',$number_items='',$offset=''){
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
            $filtroot       = "";
            if(isset($filter->codot) && $filter->codot!=''){
                if(is_array($filter->codot) && count($filter->codot)>0)
                    $filtroot  = "and k.codot in ('".str_replace(",","','",implode(',',$filter->codot))."')";
                else
                    $filtroot  = "and k.codot='".$filter->codot."'";    
            }            
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
                    k.ubica,
                    k.codmov,
                    k.user,
                    k.estmov,
                    k.numcom,
                    k.Fec_reg
                    from kardex as k
                    where k.Tip_movmto='S'
                    ".$filtroot."
                    ".$filtroinicio."
                    ".$filtrofinal."                    
                    ".$filtronumero."
                    ".$filtroserie."
                    ".$filtroproducto."
                    ".$filtroserreq."
                    ".$filtronumreq."
                    ".$filtroot."
                    ".$filtroorden."                        
                ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->result();            
        } 
        elseif($this->entidad=='02'){
            $where = array("c.TipDoc"=>"VS",'c.CodEnt'=>$this->entidad);
            if(isset($filter->fechai) && $filter->fechai!="")           $where = array_merge($where,array("c.FecMov>="=>$filter->fechai));
            if(isset($filter->fechaf) && $filter->fechaf!="")           $where = array_merge($where,array("c.FecMov<="=>$filter->fechaf));
            if(isset($filter->fecha) && $filter->fecha!="")             $where = array_merge($where,array("c.FecMov"=>$filter->fecha));
            if(isset($filter->serie) && $filter->serie!="")             $where = array_merge($where,array("c.SerieDoc"=>$filter->serie));
            if(isset($filter->numero) && $filter->numero!="")           $where = array_merge($where,array("c.NroDoc"=>$filter->numero));
            if(isset($filter->codproducto) && $filter->codproducto!="") $where = array_merge($where,array("d.CodPro"=>$filter->codproducto));
            if(isset($filter->codot) && $filter->codot!="")             $where = array_merge($where,array("d.CodOt"=>$filter->codot));
            if(isset($filter->got) && $filter->got!="")                 $where = array_merge($where,array("ot.NroOt"=>$filter->got));
            if(isset($filter->numoc) && $filter->numoc!="")             $where = array_merge($where,array("c.NroOc"=>$filter->numoc));            
            $this->db->select("d.codot,c.mo,d.preprom,d.cantidad,d.tc_p as tcambio,d.CodPro as codigo,c.FecMov as fecha,c.TipMov as tip_movmto,c.TipDocRef as documento,c.SerieDoc as serie,c.NroDoc as numero,c.NroOc as numoc,c.NroDoc as sercom,c.NroDoc as numcom,c.ruccli,'' as numreq,c.codres,'' as ot,'' as ubica,'' as codmov,'' as estmov,'' as fec_reg");
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
    
//    public function listar_detallex($filter,$filter_not,$order_by='',$number_items='',$offset=''){
//        if($this->entidad=='01'){
//            $filtroinicio = "";
//            $filtrofinal  = "";
//            $filtroorden  = "";
//            $filtroot     = "";
//            $filtronumero  = "";
//            $filtroserie   = "";
//            $filtroproducto = "";
//            $filtroserreq   = "";
//            $filtronumreq   = "";   
//            $filtroot       = "";
//            if(isset($filter->codot) && $filter->codot!=''){
//                if(is_array($filter->codot) && count($filter->codot)>0)
//                    $filtroot  = "and k.codot in ('".str_replace(",","','",implode(',',$filter->codot))."')";
//                else
//                    $filtroot  = "and k.codot='".$filter->codot."'";    
//            }            
//            if(isset($filter->fechai) && $filter->fechai!='')   $filtroinicio = "and k.Fecha >= CTOD('".date_dbf($filter->fechai)."') ";
//            if(isset($filter->fechaf) && $filter->fechaf!='')   $filtrofinal  = "and k.Fecha <= CTOD('".date_dbf($filter->fechaf)."') ";            
//            if(isset($filter->serie) && $filter->serie!='')     $filtroserie  = "and k.Serie = '".$filter->serie."'";
//            if(isset($filter->numero) && $filter->numero!='')   $filtronumero = "and k.numero = '".$filter->numero."'";
//            if(isset($filter->serreq) && $filter->serreq!='')   $filtroserreq = "and k.Serreq = '".$filter->serreq."'";
//            if(isset($filter->numreq) && $filter->numreq!='')   $filtronumreq = "and k.Numreq = '".$filter->numreq."'";            
//            if(isset($filter->codproducto) && $filter->codproducto!='') $filtroproducto = "and k.Codigo = '".$filter->codproducto."'";       
//            if(isset($order_by) && count($order_by)>0 && $order_by!=""){
//                $cad = "";
//                foreach($order_by as $indice=>$value){
//                    $cad  = $cad .",".$value;
//                }
//                $cad = substr($cad,1,strlen($cad)-1);
//                $filtroorden = "order by ".$cad; 
//            }              
//            $cadena = "
//                    select 
//                    k.codot,
//                    k.Moneda,
//                    k.Preprom,
//                    k.cantidad,
//                    k.Tcambio,
//                    k.codigo,
//                    k.Fecha,
//                    k.Tip_movmto,
//                    k.Documento,
//                    k.Serie,
//                    k.ruccli,
//                    k.numreq,
//                    k.codres,
//                    k.ot,  
//                    k.Numero,
//                    k.ubica,
//                    k.codmov,
//                    k.user,
//                    k.estmov,
//                    k.numcom
//                    from kardex as k
//                    where k.Tip_movmto!='K'
//                    ".$filtroot."
//                    ".$filtroinicio."
//                    ".$filtrofinal."                    
//                    ".$filtronumero."
//                    ".$filtroserie."
//                    ".$filtroproducto."
//                    ".$filtroserreq."
//                    ".$filtronumreq."
//                    ".$filtroot."
//                    ".$filtroorden."                        
//                ";
//            $query = $this->dbase->query($cadena);
//            $resultado = $query->result();            
//        } 
//        elseif($this->entidad=='02'){
//            
//        }
//        return $resultado;
//    }    

    /*Lista todos los totales de todas las salidas menos todas las devoluciones por OT*/
    public function listar_salidas($filter,$filter_not){
        if($this->entidad=='01'){
            $filtromaterial = "";
            $filtrofecha    = "";
            $filtrofechai   = "";
            $filtrofechaf   = "";
            $filtroot       = "";
            $filtrotipoot   = "";
            $filtrolinea    = "";
            $filtrolinea_not = "";            
            /*Filter*/
            if(isset($filter->tipoot) && $filter->tipoot!='')             $filtrotipoot = "and k.tipot='".$filter->tipoot."'";
            if(isset($filter->tipomaterial)){
                if(is_array($filter->tipomaterial)){
                    if(count($filter->tipomaterial)>0)  $filtromaterial = "in ('".implode("','",$filter->tipomaterial)."')";
                 } else{//Es un valor numerico
                    $filtromaterial = "AND p.Material='".$filter->tipomaterial."'";
                 }                 
            }
            /*Filter not*/
            if(isset($filter_not->tipomaterial)){
                if(is_array($filter_not->tipomaterial)){
                    if(count($filter_not->tipomaterial)>0)  $filtromaterial = "AND p.Material not in ('".implode("','",$filter_not->tipomaterial)."')";
                 } else{//Es un valor numerico
                    $filtromaterial = "AND p.Material in not ('".$filter_not->tipomaterial."')";
                 }  
            }
            if(isset($filter_not->linea) && $filter_not->linea!=''){
                if(is_array($filter_not->linea) && count($filter_not->linea)>0){
                    $interv = 20;
                    for($i=0;$i<ceil(count($filter_not->linea)/$interv);$i++){
                        $arrlinea = array_slice($filter_not->linea,$i*$interv,$interv);
                        $filtrolinea_not .= ($i==0?"and (":" or")." SUBSTR(p.p_codigo,1,4) not in ('".str_replace(",","','",implode(',',$arrlinea))."')";
                    }
                    $filtrolinea_not .= ")";
                }
                else{
                    $filtrolinea_not  = "and SUBSTR(p.p_codigo,1,4)!='".$filter_not->linea."'";    
                }
            }               
            if(isset($filter->fecha) && $filter->fecha!='')      $filtrofecha = "and Fecha=CTOD('".date_dbf($filter->fecha)."')";
            if(isset($filter->fechai) && $filter->fechai!='')    $filtrofechai = "and Fecha>=CTOD('".date_dbf($filter->fechai)."')";
            if(isset($filter->fechaf) && $filter->fechaf!='')    $filtrofechaf = "and Fecha<=CTOD('".date_dbf($filter->fechaf)."')";
            if(isset($filter->codot) && $filter->codot!='')      $filtroot     = "having k.codot='".$filter->codot."'";
            if(isset($filter->group_by) && count($filter->group_by)>0 && $filter->group_by!="") $campos   = implode(",",$filter->group_by);
            $cadena = "
                SELECT
                ".$campos.",
                SUM(iif(k.Tip_movmto='I',-1*k.Cantidad,k.Cantidad)) as cantidad,
                SUM(iif(k.Tip_movmto='I',-1*k.Preprom,k.Preprom)*(k.Cantidad/k.Tcambio)) as dolares,
                SUM(iif(k.Tip_movmto='I',-1*k.Preprom,k.Preprom)*k.Cantidad) as soles,
                sum(p.p_peso*k.cantidad) as p_atendido,
                sum(0) as p_solicitado                
                from kardex as k
                left outer join producto as p on k.codigo=p.p_codigo
                WHERE ((k.Tip_movmto='S' AND k.Documento='G') OR  ( k.Tip_movmto='I' AND k.Documento='DV'))
                ".$filtromaterial."
                ".$filtrotipoot."
                ".$filtrofecha."
                ".$filtrofechai."
                ".$filtrofechaf."
                ".$filtrolinea."
                ".$filtrolinea_not."
                and k.codot!=''
                GROUP BY ".$campos."
                ".$filtroot."
            ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->result();
            // echo "salida ::: ".$this->dbase->last_query()."<br>";
            return $resultado;
        }
        elseif($this->entidad=='02'){
            $where = array("TipDoc"=>"OC",'CodEnt',$this->entidad);
            $this->db->select('*');
            $this->db->from($this->table,$number_items,$offset);
            $this->db->where($where);
            $this->db->where_not_in('EstRep','A');
            $this->db->order_by('NroDoc','desc');
            $query = $this->db->get();
            $resultado = array();
            if($query->num_rows>0){
                $resultado = $query->result();
            }
            return $resultado;
        }
    }
    
   /*Listar el detalle de los vales de salida - devoluciones por OT*/
    public function listar_salidas_detalle($filter,$filter_not,$order_by="",$number_items='',$offset=''){
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
                k.peso_total,
                k.Numero,
                k.Serreq,
                k.Numreq
                from kardex as k 
                where ((k.Tip_movmto='S' AND k.Documento='G') OR  ( k.Tip_movmto='I' AND k.Documento='DV')) 
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
    
//    public function listar_totales2($filter,$filter_not){
//        if($this->entidad=='01'){
//            $filtromaterial = "";
//            /*Filter*/
//            if(isset($filter->tipoot) && $filter->tipoot!='')             $tipOt = $filter->tipoot;
//            if(isset($filter->tipomaterial)){
//                if(is_array($filter->tipomaterial)){
//                    if(count($filter->tipomaterial)>0)  $filtromaterial = "in ('".implode("','",$filter->tipomaterial)."')";
//                 } else{//Es un valor numerico
//                    $filtromaterial = "AND p.Material='".$filter->tipomaterial."'";
//                 }                 
//            }
//            /*Filter not*/
//            if(isset($filter_not->tipomaterial)){
//                if(is_array($filter_not->tipomaterial)){
//                    if(count($filter_not->tipomaterial)>0)  $filtromaterial = "AND p.Material not in ('".implode("','",$filter_not->tipomaterial)."')";
//                 } else{//Es un valor numerico
//                    $filtromaterial = "AND p.Material in not ('".$filter_not->tipomaterial."')";
//                 }  
//            }
//            if(isset($filter->fechai) && $filter->fechai!='')             $fechai = date_dbf($filter->fechai);
//            if(isset($filter->fechaf) && $filter->fechaf!='')             $fechaf = date_dbf($filter->fechaf);
//            $cadena = "
//                SELECT
//                k.codot,
//                SUM(iif(k.Tip_movmto='I',-1*k.Preprom,k.Preprom)*(k.Cantidad/k.Tcambio)),
//                SUM(iif(k.Tip_movmto='I',-1*k.Preprom,k.Preprom)*k.Cantidad)
//                from kardex as k
//                left outer join producto as p on k.codigo=p.p_codigo
//                WHERE k.tipot='".$tipOt."'
//                ".$filtromaterial."
//                AND  ((k.Tip_movmto='S' AND k.Documento='G') OR  ( k.Tip_movmto='I' AND k.Documento='DV'))
//                GROUP BY k.codot
//            ";
//            $query = $this->dbase->query($cadena);
//            $resultado = $query->result();
//            return $resultado;
//        }
//        elseif($this->entidad=='02'){
//            $where = array("TipDoc"=>"OC",'CodEnt',$this->entidad);
//            $this->db->select('*');
//            $this->db->from($this->table,$number_items,$offset);
//            $this->db->where($where);
//            $this->db->where_not_in('EstRep','A');
//            $this->db->order_by('NroDoc','desc');
//            $query = $this->db->get();
//            $resultado = array();
//            if($query->num_rows>0){
//                $resultado = $query->result();
//            }
//            return $resultado;
//        }
//
//    }
    
    public function obtener($filter,$filter_not){
        if($this->entidad=='01'){
           $filtroinicio = "";
            $filtrofinal  = "";
            $filtroot     = "";
            $filtrofecha  = "";
            $filtronumero  = "";
            if(isset($filter->codot) && $filter->codot!=''){
                if(is_array($filter->codot) && count($filter->codot)>0){
                    $filtroot  = "and k.codot in ('".str_replace(",","','",implode(',',$filter->codot))."')";
                }
                else{
                    $filtroot  = "and k.codot='".$filter->codot."'";    
                }
            }
            if(isset($filter->fechai) && $filter->fechai!='')           $filtroinicio   = "and k.Fecha >= CTOD('".date_dbf($filter->fechai)."') ";
            if(isset($filter->fechaf) && $filter->fechaf!='')           $filtrofinal    = "and k.Fecha <= CTOD('".date_dbf($filter->fechaf)."') ";
            if(isset($filter->fecha) && $filter->fecha!='')             $filtrofecha    = "and k.Fecha = CTOD('".date_dbf($filter->fecha)."') ";
            if(isset($filter->codproducto) && $filter->codproducto!='') $filtroproducto = "and k.Codigo = '".$filter->codproducto."'";
             if(isset($filter->numero) && $filter->numero!='')          $filtronumero = "and k.numero = '".$filter->numero."'";
            if(isset($order_by) && count($order_by)>0){
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
                k.ruccli,
                k.numreq,
                k.codres,
                k.ot,  
                k.Numero
                from kardex as k 
                where k.Tip_movmto='S'
                ".$filtroot."
                ".$filtroinicio."
                ".$filtrofinal."
                ".$filtrofecha."
                ".$filtronumero."
                   
                ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->row();
        }
        elseif($this->entidad=='02'){
            
        }
        return $resultado;
    }
}
?>
