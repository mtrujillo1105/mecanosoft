<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Requiser_model extends CI_Model{
    var $entidad;
    var $table;
    
    public function __construct(){
        parent::__construct();
        $this->dbase   = $this->load->database('dsn',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->table     = utf8_decode("Reposición");
        $this->table_det = utf8_decode('Reposición_Det');        
        $this->table_dbf = "requi_ser";
    }

    public function listarg($filter,$filter_not,$order_by="",$number_items='',$offset=''){
        $filtroservicio = "";
        $filtrocodot    = "";
        $filtroorden    = "";
        $filtrofechai   = "";
        $filtrofechaf   = "";
        $filtrofecha    = "";
        $filtrofrealizadoi = "";
        $filtrofrealizadof = "";
        $filtrofrealizado  = "";
        $filtroruc      = "";
        $filtropersonal = "";
        if($this->entidad=='01'){
            if(isset($filter->fechai) && $filter->fechai!='')  $filtrofechai = "and r.Fecemi>=CTOD('".date_dbf($filter->fechai)."')";
            if(isset($filter->fechaf) && $filter->fechaf!='')  $filtrofechaf = "and r.Fecemi<=CTOD('".date_dbf($filter->fechaf)."')";
            if(isset($filter->fecha) && $filter->fecha!='')    $filtrofecha  = "and r.Fecemi=CTOD('".date_dbf($filter->fecha)."')";            
            if(isset($filter->frealizadoi) && $filter->frealizadoi!='')  $filtrofrealizadoi = "and r.Fdespacho>=CTOD('".date_dbf($filter->frealizadoi)."')";
            if(isset($filter->frealizadof) && $filter->frealizadof!='')  $filtrofrealizadof = "and r.Fdespacho<=CTOD('".date_dbf($filter->frealizadof)."')";
            if(isset($filter->frealizado) && $filter->frealizado!='')    $filtrofrealizado  = "and r.Fdespacho=CTOD('".date_dbf($filter->frealizado)."')";            
            if(isset($filter->ruc) && $filter->ruc!='')        $filtroruc       = "and r.Gruc='".trim($filter->ruc)."'";            
            if(isset($filter->codres) && $filter->codres!='')  $filtropersonal = "and r.Gpersonal='".trim($filter->codres)."'";  
            //if(isset($filter->codot) && $filter->codot!=''){ $filtrocodot  = "and r.codot='".$filter->codot."'"; } //Esto no debe activarse, lo debe abajo deberia funcionar, favor probar.
            if(isset($filter->codot)){
                if(is_array($filter->codot) && count($filter->codot)>0){
                    $interv = 20;
                    for($i=0;$i<ceil(count($filter->codot)/$interv);$i++){
                        $arrcodot = array_slice($filter->codot,$i*$interv,$interv);
                        $filtrocodot .= ($i==0?"and (":"or")." r.codot in ('".str_replace(",","','",implode(',',$arrcodot))."')";
                    }
                    $filtrocodot .= ")";
                }  
                else{
                    $filtrocodot  = "and r.codot='".$filter->codot."'";    
                }                  
            }
            /*Filter*/
            if(isset($filter->codservicio)){
                if(is_array($filter->codservicio)){
                    if(count($filter->codservicio)>0)  $filtroservicio = "AND GcodSer in ('".implode("','",$filter->codservicio)."')";
                 } else{//Es un valor numerico
                    $filtroservicio = "AND GcodSer='".$filter->codservicio."'";
                 }
            }
            /*Filter not*/
            if(isset($filter_not->codservicio)){
                if(is_array($filter_not->codservicio)){
                    if(count($filter_not->codservicio)>0)  $filtroservicio = "AND GcodSer not in ('".implode("','",$filter_not->codservicio)."')";
                 } else{//Es un valor numerico
                    $filtroservicio = "AND GcodSer in not ('".$filter_not->codservicio."')";
                 }
            }
            /*Filtro orden by*/
            if(isset($order_by) && count($order_by)>0 && is_array($order_by)){
                $cad = "";
                foreach($order_by as $indice=>$value){
                    $cad  = $cad .",".$value;
                }
                $cad = substr($cad,1,strlen($cad)-1);
                $filtroorden = "order by ".$cad; 
            }               
            $cadena = "
                        SELECT 
                        r.codot,
                        r.tipser,
                        r.costo,
                        r.Gcodser,
                        r.Gfentrega,
                        r.Gpeso,
                        r.Gruc,
                        r.Costo,
                        r.Moneda,
                        r.Tipod,
                        r.Seried,
                        r.Nrod,
                        r.Cambio,
                        r.Subtotal,
                        r.Igv,
                        r.Gserguia,
                        r.Gnumguia,
                        r.Gestado,
                        r.Gobserva,
                        r.Fecemi,
                        r.Fdespacho,
                        r.Gpersonal
                        from requi_ser as r
                        WHERE codot NOT IN ('.',' ') 
                        ".$filtroservicio."
                        ".$filtrofechai."
                        ".$filtrofechaf."
                        ".$filtrofecha."
                        ".$filtrofrealizadoi."
                        ".$filtrofrealizadof."
                        ".$filtrofrealizado."
                        ".$filtrocodot."
                        ".$filtroruc."
                        ".$filtropersonal."
                        ".$filtroorden."
                        ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->result();            
        }
        elseif($this->entidad=='02'){
            $where = array("c.TipDoc"=>"RS",'c.CodEnt'=>$this->entidad);
            if(isset($filter->fechai)) $where = array_merge($where,array("c.FecRep>="=>$filter->fechai));
            if(isset($filter->fechaf)) $where = array_merge($where,array("c.FecRep<="=>$filter->fechaf));
            if(isset($filter->fecha))  $where = array_merge($where,array("c.FecRep"=>$filter->fecha));
            if(isset($filter->flgAprobado))  $where = array_merge($where,array("c.TipSol"=>$filter->flgAprobado));
            if(isset($filter->serie))  $where = array_merge($where,array("c.SerieDoc"=>$filter->serie));
            if(isset($filter->numero)) $where = array_merge($where,array("c.NroDoc"=>$filter->numero));
            if(isset($filter->ruc)) $where = array_merge($where,array("c.Rucprov"=>$filter->ruc));
            if(isset($filter->codproducto)) $where = array_merge($where,array("c.NroDoc"=>$filter->codproducto));
            if(isset($filter->codservicio)) $where = array_merge($where,array("c.codserv"=>$filter->codservicio));
            if(isset($filter->codres)) $where = array_merge($where,array("c.codres"=>$filter->codres));
            if(isset($filter->codot) && $filter->codot!='') $where =  array_merge($where,array("c.codot"=>$filter->codot)); 
            $this->db->select("DISTINCT c.seriedoc as gserguia,c.nrodoc as gnumguia,convert(varchar(10),c.FecRep,120) as fecemi, 
                                convert(varchar(10), c.FecEnt, 120) as gfentrega, c.MO as moneda,c.CodServ as gcodser,
                                '' as gcantidad,'' as gcantidads,'' as gentrega,c.CodRes as gpersonal,
                                td.des_larga as gdepa, c.CodDpto as gcoddpto,c.CodOt as codot, ''  as gprecio,
                                ot.TipOt as tipot,ot.NroOt as got,c.CodRes as codres,'' as useraprob,c.peso as gpeso,
                                c.ObsRep as gobs,convert(varchar,c.FecApro,120) as fec_apro, c.tc as cambio, c.estrep as gestado,
                                c.obsrep as gobserva, convert(varchar, c.FecDes, 120) as fdespacho,  c.rucprov as gruc,
                                c.Saldo as costo,c.MtoSub as subtotal,c.MtoIgv as igv,
                                '' as codresot,c.TipDoc as tipo, '' as tipser,
                               ,'' as tipser,     
                                '' as tipod , '' as serieod, '' as nrod, '' as seried,  '' as despro ");
            $this->db->from($this->table." as c",$number_items,$offset);
            $this->db->join("ot","ot.codot=c.codot");
            $this->db->join("tabla_m_detalle as td","td.cod_tabla='TLAB' and td.cod_argumento=c.CodDpto and td.codent=c.codent");
            $this->db->where($where);	
            if(isset($filter->tipo) && $filter->tipo!=""){
                if($filter->tipo=='R')  $this->db->where("c.Codmov","03");
                if($filter->tipo=='O')  $this->db->where("c.TipDoc","RU");
                if($filter->tipo=='H')  $this->db->where("c.Codmov","04");
            } 
            else{
                $this->db->where_in("c.TipDoc",array('RS','RU'));                
            }
            $this->db->order_by('c.NroDoc','c.desc');
            $query = $this->db->get();
            $resultado = array();
             if($query->num_rows>0){
                $resultado = $query->result();
            }   
        }
        return $resultado;
    }

    public function listar_detalle($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        $filtroserie    = "";
        $filtronumero   = "";
        $filtroproducto = "";
        if($this->entidad=='01'){
            if(isset($filter->numero) && $filter->numero!='')            $filtroserie    = "and Gnumguia='".$filter->numero."'";
            if(isset($filter->serie) && $filter->serie!='')              $filtronumero   = "and Gserguia='".$filter->serie."'";
            if(isset($filter->codproducto) && $filter->codproducto!='')  $filtroproducto = "and Codpro='".$filter->codproducto."'";
            $cadena  = "
                    SELECT 
                    Codpro,
                    Und,
                    Descri,
                    Cantidad,
                    Codot2 
                    FROM requi_ser_det 
                    WHERE Gnumguia!='' 
                    ".$filtroserie."
                    ".$filtronumero."
                    ".$filtroproducto."
                    ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->result();
        }
        elseif($this->entidad=='02'){
            
        }
        return $resultado;
    }      
    
    public function listar_servicios_x_ot($codot){
        $cadena   = "SELECT * FROM requi_ser WHERE Gestado=1 and codot='".$codot."' order by Gcodser";
        $query     = $this->dbase->query($cadena);
        $resultado = $query->result();
        return $resultado;  
    }  

    public function listar_totales($filter,$filter_not,$order,$number_items='',$offset=''){
        $filtroservicio = "";
        $filtrofechai   = "";
        $filtrofechaf   = "";
        $filtrofecha    = "";
        $filtroot       = "";
        $filtrofrealizadoi = "";
        $filtrofrealizadof = "";
        $filtrofrealizado  = "";
        if($this->entidad=='01'){
            /*Filter*/
            if(isset($filter->codservicio)){
                if(is_array($filter->codservicio)){
                    if(count($filter->codservicio)>0)  $filtroservicio = "AND GcodSer in ('".implode("','",$filter->codservicio)."')";
                 } else{//Es un valor numerico
                    $filtroservicio = "AND GcodSer='".$filter->codservicio."'";
                 }
            }
            /*Filter not*/
            if(isset($filter_not->codservicio)){
                if(is_array($filter_not->codservicio)){
                    if(count($filter_not->codservicio)>0)  $filtroservicio = "AND GcodSer not in ('".implode("','",$filter_not->codservicio)."')";
                 } else{//Es un valor numerico
                    $filtroservicio = "AND GcodSer in not ('".$filter_not->codservicio."')";
                 }
            }
            if(isset($filter->fechai) && $filter->fechai!='')    $filtrofechai = "and Fecemi>=CTOD('".date_dbf($filter->fechai)."')";
            if(isset($filter->fechaf) && $filter->fechaf!='')    $filtrofechaf = "and Fecemi<=CTOD('".date_dbf($filter->fechaf)."')";
            if(isset($filter->fecha) && $filter->fecha!='')      $filtrofecha  = "and Fecemi=CTOD('".date_dbf($filter->fecha)."')";
            if(isset($filter->frealizadoi) && $filter->frealizadoi!='')  $filtrofrealizadoi = "and Fdespacho>=CTOD('".date_dbf($filter->frealizadoi)."')";
            if(isset($filter->frealizadof) && $filter->frealizadof!='')  $filtrofrealizadof = "and Fdespacho<=CTOD('".date_dbf($filter->frealizadof)."')";
            if(isset($filter->frealizado) && $filter->frealizado!='')    $filtrofrealizado  = "and Fdespacho=CTOD('".date_dbf($filter->frealizado)."')";                        
            if(isset($filter->codot) && $filter->codot!='')      $filtroot     = "having codot='".$filter->codot."'";
            $cadena = "
                    SELECT
                    codot,
                    tipser,
                    SUM(iif(Moneda='S',costo,costo*Cambio)) as soles,
                    sum(iif(Moneda='S',Subtotal,Subtotal*Cambio)) as subtotalsoles,
                    SUM(iif(Moneda='D',costo,costo/Cambio)) as dolares,
                    sum(iif(Moneda='D',Subtotal,Subtotal/Cambio)) as subtotaldolares
                    FROM requi_ser
                    WHERE codot NOT IN ('.',' ')
                    AND tipser!=' '
                    ".$filtroservicio."
                    ".$filtrofechai."
                    ".$filtrofechaf."
                    ".$filtrofecha."
                    ".$filtrofrealizadoi."
                    ".$filtrofrealizadof."
                    ".$filtrofrealizado."
                    GROUP BY codot,tipser
                    ".$filtroot."
            ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->result();
            return $resultado;
        }
        elseif($this->entidad=='02'){
            
        }
        
        
        
    }
    
  public function obtener($filter,$filter_not){
        if($this->entidad=='01'){
            $filtroserie    = "";
            $filtronumero   = "";
            
            
            if(isset($filter->serie) && $filter->serie!='')                $filtroserie  = "AND Gserguia='".$filter->serie."'";  
            if(isset($filter->numero) && $filter->numero!='')              $filtronumero  = "AND Gnumguia='".$filter->numero."'";  
            $cadena = "select * from requi_ser where codot!=' '
                      ".$filtronumero."
                      ".$filtroserie."                                          
                      ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->row(); 
            
           // print_r($resultado);die;
             return $resultado;
        }   
     }  
    
      public function obtener_factura($filter,$filter_not){
        if($this->entidad=='01'){
            $filtroserie    = "";
            $filtronumero   = "";
            
            // print_r($filter);die;
            if(isset($filter->serie) && $filter->serie!='')                $filtroserie  = "AND SerOc='".$filter->serie."'";  
            if(isset($filter->numero) && $filter->numero!='')              $filtronumero  = "AND NroOc='".$filter->numero."'";  
            $cadena = "select TipDoc,SerieDoc, NroDoc from view_facturac where CodEnt='01'
                      ".$filtronumero."
                      ".$filtroserie."                                          
                      ";
            $query = $this->db->query($cadena);
            //if($query->num_rows>1) exit("Existe mas de 1 resultados en la tabla ".$this->table."");    
            
           // if($query->num_rows==1){
            $resultado = $query->row(); 
           
            return $resultado;
            
            
            }
            
             //if($query->num_rows==0)
                 //exit("0 resultados en la tabla ".$this->table."");
     //   }   
        
        else
            print_r('ESTA SECCION ESTA EN CONTRUCCION - GALVANIZADO');die;
     } 
     
     
     
     
}
?>
