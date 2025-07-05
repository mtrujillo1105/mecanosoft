<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Facturac_model extends CI_Model{
    var $entidad;
    var $table;
    var $table_det;
    
    public function __construct(){
        parent::__construct();
        $this->entidad   = $this->session->userdata('entidad');
        $this->table     = "view_facturac";
        $this->table_det = "view_facturac_det";
    }
    
  public function seleccionar($tipOt,$default=""){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array(''=>$nombre_defecto);
        foreach($this->listar($tipOt) as $indice=>$valor)
        {
            $indice1   = $valor->CodOt;
            $valor1    = $valor->NroOt;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }    
    
    public function obtener($filter,$filter_not){
        $arrWhere  = array('c.CodEnt'=>$this->entidad);
        $this->db->select("top 1 *");
        $this->db->from($this->table."  as c");
        $this->db->where($arrWhere);   
        if(isset($filter->serie) && $filter->serie!='')               $this->db->where("replicate('0',(10 - len(c.SerieDoc)))+c.SerieDoc=",str_pad(trim($filter->serie),10,'0',STR_PAD_LEFT));
        if(isset($filter->numero) && $filter->numero!='')             $this->db->where("replicate('0',(10 - len(c.NroDoc)))+c.NroDoc=",str_pad(trim($filter->numero),10,'0',STR_PAD_LEFT));        
        if(isset($filter->rucproveedor) && $filter->rucproveedor!='') $this->db->where("RucCli=",$filter->rucproveedor);  
        
        if(isset($filter->codot) && $filter->codot!='')               $this->db->where("CodOt=",$filter->codot);    
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>1) exit("Existe mas de 1 resultados en la tablax ".$this->table."");    
        if($query->num_rows>0){
            $resultado = $query->row();
        }
        return $resultado;
    }  
    
    
     public function obtenerx($filter,$filter_not){
        $arrWhere  = array('c.CodEnt'=>$this->entidad);
        $this->db->select("TOP 1 *");
        $this->db->from($this->table."  as c");
        $this->db->where($arrWhere);   
        if(isset($filter->serie) && $filter->serie!='')               $this->db->where("replicate('0',(10 - len(c.SerieDoc)))+c.SerieDoc=",str_pad(trim($filter->serie),10,'0',STR_PAD_LEFT));
        if(isset($filter->numero) && $filter->numero!='')             $this->db->where("replicate('0',(10 - len(c.NroDoc)))+c.NroDoc=",str_pad(trim($filter->numero),10,'0',STR_PAD_LEFT));        
        //if(isset($filter->rucproveedor) && $filter->rucproveedor!='') $this->db->where("RucCli=",$filter->rucproveedor);  
        
        if(isset($filter->codot) && $filter->codot!='')               $this->db->where("CodOt=",$filter->codot);    
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>1) exit("Existe mas de 1 resultados en la tablax ".$this->table."");    
        if($query->num_rows>0){
            $resultado = $query->row();
        }
        return $resultado;
    }  
    
    
       public function obtener_factura($filter,$filter_not){
        $var1='000000';
        $var2='00000000000';
        $rs1='007';
        $arrWhere  = array('c.CodEnt'=>$this->entidad);
        $this->db->select("top 1 TipDoc, NroDoc, SerieDoc,RucCli");
        $this->db->from($this->table."  as c");
        $this->db->where($arrWhere);   
        if(isset($filter->numguia) && $filter->numguia!='') $this->db->where("c.NroOC=",$filter->numguia);
        $this->db->where("replicate('0',(10 - len(c.SerOC)))+c.SerOC=",str_pad(trim($rs1),10,'0',STR_PAD_LEFT)); 
        if(isset($filter->ruccli) && $filter->ruccli!='') $this->db->where("c.RucCli=",$filter->ruccli);
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>1) exit("Existe mas de 1 resultados en la tablaX ".$this->table."");    
        else if($query->num_rows==1){
            $resultado = $query->row();
            return $resultado;
        }
        else if($query->num_rows==0){
            $arrWhere  = array('c.CodEnt'=>$this->entidad);
            $this->db->select("TipDoc, NroDoc, SerieDoc,RucCli");
            $this->db->from($this->table."  as c");
            $this->db->where($arrWhere);   
            $this->db->where("c.NroOC=",$var1);  
            $this->db->where("c.RucCli=",$var2);  
            $this->db->where("c.SerieDoc=",'0000'); 
            $query = $this->db->get();
            $resultado = array();
            $resultado = $query->row();
            return $resultado;
        }
    }
    
    public function obtener_precio($filter,$filter_not){
        $arrWhere  = array('c.CodEnt'=>$this->entidad);
        $this->db->select("*");
        $this->db->from($this->table_det."  as c");
        $this->db->where($arrWhere);   
          if(isset($filter->numero) && $filter->numero!='')             $this->db->where("replicate('0',(10 - len(c.NroDoc)))+c.NroDoc=",str_pad(trim($filter->numero),10,'0',STR_PAD_LEFT));        
          if(isset($filter->codot) && $filter->codot!='')               $this->db->where("CodOt=",$filter->codot);    
        $query = $this->db->get();
        $resultado = array();
        
        if($query->num_rows>1){exit('Existe mas de 1 resultado123');
        }
        
        if($query->num_rows==1){
            $resultado = $query->row();
           return $resultado;
            }
            
       // if($query->num_rows==0){
       // $cadena="select ImpPdet from pagos_det WHERE NroVoucher='$filter->voucher' and codot='$filter->codot' ";
       // $query = $this->db->$cadena;
       //  $resultado = $query->row();
       //     return $resultado;
       //     }
   
        //print_r($query->num_rows);die;
    }  
    
    
    public function obtener_dbf($filter,$filter_not){
        $arrWhere  = array('c.CodEnt'=>$this->entidad);
        $this->db->select("*");
        $this->db->from($this->table."  as c");
        $this->db->where($arrWhere);   
        if(isset($filter->serie) && $filter->serie!='')               $this->db->where("c.SerieDoc=",$filter->serie);
        if(isset($filter->numero) && $filter->numero!='')             $this->db->where("c.NroDoc=",$filter->numero);  
          if(isset($filter->proveedor) && $filter->proveedor!='')             $this->db->where("c.RucCli=",$filter->proveedor);        
    //    if(isset($filter->rucproveedor) && $filter->rucproveedor!='') $this->db->where("RucCli=",$filter->rucproveedor);        
  //      if(isset($filter->codot) && $filter->codot!='')               $this->db->where("CodOt=",$filter->codot);    
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>1) exit('Existe mas de 1 resultado456');    
        if($query->num_rows>0){
            $resultado = $query->row();
        }
        return $resultado;
    }  
    
    public function obtener_rs($filter,$filter_not){
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
         /*   if(isset($filter->fechai) && $filter->fechai!='')           $filtroinicio   = "and k.Fecha >= CTOD('".date_dbf($filter->fechai)."') ";
            if(isset($filter->fechaf) && $filter->fechaf!='')           $filtrofinal    = "and k.Fecha <= CTOD('".date_dbf($filter->fechaf)."') ";
            if(isset($filter->fecha) && $filter->fecha!='')             $filtrofecha    = "and k.Fecha = CTOD('".date_dbf($filter->fecha)."') ";
            if(isset($filter->codproducto) && $filter->codproducto!='') $filtroproducto = "and k.Codigo = '".$filter->codproducto."'";*/
             if(isset($filter->numero) && $filter->numero!='') $filtronumero = "and rs.gnumguia = '".$filter->numero."'";
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
                rs.gserguia,
                rs.gnumguia,
                rs.fecemi,
                rs.gpersonal,
                rs.got,
                rs.gcodser,
                rs.gpersonal,
                rs.got,
                rs.nlote,
                rs.gcodser,
                rs.gdestino,
                rs.gcontacto,
                rs.gfentrega,
                rs.gpeso,
                
                rs.ghora,
                rs.gruc,
                rs.tipod,
                rs.seried,
                rs.nrod,
                rs.fdespacho,
                rs.fpago,
                rs.gobserva,
                rs.igv, rs.subtotal,
                rs.gdetrac, rs.gp_detrac,
                rs.moneda, rs.cambio

                from requi_ser as rs 
                            

                where rs.gserguia='007'
                ".$filtroot."
                ".$filtroinicio."
                ".$filtrofinal."
                ".$filtrofecha."
                ".$filtronumero."
                   
                ";
            
            /* inner join producto as p on p.P_codigo=k.codigo*/
            $query = $this->dbase->query($cadena);
            $resultado = $query->row();
       
            
        
        return $resultado;
    }
    
    
    
    public function obtener_rq($filter,$filter_not){
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
         /*   if(isset($filter->fechai) && $filter->fechai!='')           $filtroinicio   = "and k.Fecha >= CTOD('".date_dbf($filter->fechai)."') ";
            if(isset($filter->fechaf) && $filter->fechaf!='')           $filtrofinal    = "and k.Fecha <= CTOD('".date_dbf($filter->fechaf)."') ";
            if(isset($filter->fecha) && $filter->fecha!='')             $filtrofecha    = "and k.Fecha = CTOD('".date_dbf($filter->fecha)."') ";
            if(isset($filter->codproducto) && $filter->codproducto!='') $filtroproducto = "and k.Codigo = '".$filter->codproducto."'";*/
             if(isset($filter->numero) && $filter->numero!='') $filtronumero = "and rs.gnumguia = '".$filter->numero."'";
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
                rs.gserguia,
                rs.gnumguia,
                rs.fecemi,
                rs.gsolicita,
                rs.gdepa,
      
                rs.codot,
                rs.gentrega,
                rs.got,
                rs.gobs
                from requis as rs
                where rs.gserguia='003'
                ".$filtroot."
                ".$filtroinicio."
                ".$filtrofinal."
                ".$filtrofecha."
                ".$filtronumero."
                   
                order by rs.gnumguia";
            
            /* inner join producto as p on p.P_codigo=k.codigo*/
            $query = $this->dbase->query($cadena);
            $resultado = $query->row();
       
            
        
        return $resultado;
    }
    
     public function listar_rq($filter,$filter_not){
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
         /*   if(isset($filter->fechai) && $filter->fechai!='')           $filtroinicio   = "and k.Fecha >= CTOD('".date_dbf($filter->fechai)."') ";
            if(isset($filter->fechaf) && $filter->fechaf!='')           $filtrofinal    = "and k.Fecha <= CTOD('".date_dbf($filter->fechaf)."') ";
            if(isset($filter->fecha) && $filter->fecha!='')             $filtrofecha    = "and k.Fecha = CTOD('".date_dbf($filter->fecha)."') ";
            if(isset($filter->codproducto) && $filter->codproducto!='') $filtroproducto = "and k.Codigo = '".$filter->codproducto."'";*/
             if(isset($filter->numero) && $filter->numero!='') $filtronumero = "and rs.gnumguia = '".$filter->numero."'";
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
                rs.gserguia,
                rs.gcodpro,
                rs.gnumguia,
                rs.fecemi,
                rs.gsolicita,
                rs.gdepa,
                rs.gcantidad,
                rs.gprecio,
                rs.codot,
                rs.gentrega,
                rs.got,
                rs.gobs
                from requis as rs
                where rs.gserguia='003'
                ".$filtroot."
                ".$filtroinicio."
                ".$filtrofinal."
                ".$filtrofecha."
                ".$filtronumero."
                   
                order by rs.gnumguia";
            
            /* inner join producto as p on p.P_codigo=k.codigo*/
            $query = $this->dbase->query($cadena);
            $resultado = $query->result();
       
            
        
        return $resultado;
    }
    
    
    
    
    
    
    
    
    
    public function listar($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        $arrWhere  = array('c.CodEnt'=>$this->entidad);
        $this->db->select("*");
        $this->db->from($this->table." as c");
        $this->db->where($arrWhere);   
        if(isset($filter->serie) && $filter->serie!='')     $this->db->where("replicate('0',(10 - len(c.SerieDoc)))+c.SerieDoc=",$filter->serie);
        if(isset($filter->numero) && $filter->numero!='')   $this->db->where("replicate('0',(10 - len(c.NroDoc)))+c.NroDoc=",$filter->numero);
        if(isset($filter->fecha) && $filter->fecha!='')     $this->db->where('c.FecRep',$filter->fecha);
        if(isset($filter->fechai) && $filter->fechai!='')   $this->db->where('c.FecRep>=',$filter->fechai);
        if(isset($filter->fechaf) && $filter->fechaf!='')   $this->db->where('c.FecRep<=',$filter->fechaf);
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }
    
    public function listar_detalle($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        $where = array("d.CodEnt"=>$this->entidad);
        $this->db->select("*");
        $this->db->from($this->table_det." as d");
        $this->db->join($this->table." as c",'d.CodEnt=c.CodEnt and c.SerieDoc=d.SerieDoc and c.NroDoc=d.NroDoc and c.TipDoc=d.TipDoc','inner');
        $this->db->where($where);
        if(isset($filter->tipo) && $filter->tipo!='')           $this->db->where('d.TipoDoc',$filter->tipo);
        if(isset($filter->serie) && $filter->serie!='')         $this->db->where("replicate ('0',(10 - len(d.SerieDoc)))+d.SerieDoc=",str_pad($filter->serie,10,'0',STR_PAD_LEFT));
        if(isset($filter->numero) && $filter->numero!='')       $this->db->where("replicate ('0',(10 - len(d.NroDoc)))+d.NroDoc=",str_pad($filter->numero,10,'0',STR_PAD_LEFT));
        if(isset($filter->codot) && $filter->codot!='')         $this->db->where('d.CodOt',$filter->codot);
        if(isset($filter->fecha) && $filter->fecha!='')         $this->db->where('c.FecRep',$filter->fecha);
        if(isset($filter->fechai) && $filter->fechai!='')       $this->db->where('c.FecRep>=',$filter->fechai);
        if(isset($filter->fechaf) && $filter->fechaf!='')       $this->db->where('c.FecRep<=',$filter->fechaf);
        if(isset($order_by) && is_array($order_by) && count($order_by)>0) $this->db->order_by($order_by);
        $query = $this->db->get();
     
     
        $resultado = new stdClass();
        if($query->num_rows>0){
            $resultado = $query->result();
             
        }
         
        
        return $resultado;
    }       
    
    
    public function listar_detalle1($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        $where = array("d.CodEnt"=>$this->entidad);
        $this->db->select("*,d.CodOt as ots");
        $this->db->from($this->table_det." as d");
        $this->db->join($this->table." as c",'d.CodEnt=c.CodEnt and c.SerieDoc=d.SerieDoc and c.NroDoc=d.NroDoc and c.TipDoc=d.TipDoc','inner');
        $this->db->where($where);
      //  if(isset($filter->tipo) && $filter->tipo!='')           $this->db->where('d.TipoDoc',$filter->tipo);
        if(isset($filter->serie) && $filter->serie!='')         $this->db->where("replicate ('0',(10 - len(d.SerieDoc)))+d.SerieDoc=",str_pad($filter->serie,10,'0',STR_PAD_LEFT));
        if(isset($filter->numero) && $filter->numero!='')       $this->db->where("replicate ('0',(10 - len(d.NroDoc)))+d.NroDoc=",str_pad($filter->numero,10,'0',STR_PAD_LEFT));
       if(isset($filter->ruc) && $filter->ruc!='')       $this->db->where("d.ruccli=".$filter->ruc);
        /*if(isset($filter->codot) && $filter->codot!='')         $this->db->where('d.CodOt',$filter->codot);
        */if(isset($filter->nrooc) && $filter->nrooc!='')         $this->db->where('c.NroOC',$filter->nrooc);
   //     if(isset($filter->fechai) && $filter->fechai!='')       $this->db->where('c.FecRep>=',$filter->fechai);
   //     if(isset($filter->fechaf) && $filter->fechaf!='')       $this->db->where('c.FecRep<=',$filter->fechaf);
  //      if(isset($order_by) && is_array($order_by) && count($order_by)>0) $this->db->order_by($order_by);
        $query = $this->db->get();
        $resultado = new stdClass();
        if($query->num_rows>0){
            $resultado = $query->result();
      //    print_r($resultado);die;
        }
        
        
        return $resultado;
    }       
    
    public function listar_totales($filter,$filter_not,$order_by='',$number_items='',$offset=''){
       $where = array("d.CodEnt"=>$this->entidad);
        $this->db->select("d.CodOt,d.SerieDoc,d.NroDoc,c.Mo,c.TC,sum(case c.Mo when '2' then (d.PrecUnit*d.CantAten) else (d.PrecUnit*d.CantAten*c.TC) end) as montoS,sum(case c.Mo when '3' then (d.PrecUnit*d.CantAten) else (d.PrecUnit*d.CantAten/c.TC) end) as montoD");
        $this->db->from($this->table_det." as d");
        $this->db->join($this->table." as c",'d.CodEnt=c.CodEnt and c.SerieDoc=d.SerieDoc and c.NroDoc=d.NroDoc and c.TipDoc=d.TipDoc','inner');
        $this->db->where($where);
        if(isset($filter->tipo) && $filter->tipo!='')           $this->db->where('d.TipoDoc',$filter->tipo);
        if(isset($filter->serie) && $filter->serie!='')         $this->db->where("replicate ('0',(10 - len(d.SerieDoc)))+d.SerieDoc=",str_pad(trim($filter->serie),10,'0',STR_PAD_LEFT));
        if(isset($filter->numero) && $filter->numero!='')       $this->db->where("replicate ('0',(10 - len(d.NroDoc)))+d.NroDoc=",str_pad(trim($filter->numero),10,'0',STR_PAD_LEFT));
        if(isset($filter->seroc) && $filter->seroc!='')         $this->db->where('c.SerOC',$filter->seroc);
        if(isset($filter->nrooc) && $filter->nrooc!='')         $this->db->where('c.NroOC',$filter->nrooc);
        if(isset($filter->codot) && $filter->codot!='')         $this->db->where('d.CodOt',$filter->codot);
        if(isset($filter->fecha) && $filter->fecha!='')         $this->db->where('c.FecRep',$filter->fecha);
        if(isset($filter->fechai) && $filter->fechai!='')       $this->db->where('c.FecRep>=',$filter->fechai);
        if(isset($filter->fechaf) && $filter->fechaf!='')       $this->db->where('c.FecRep<=',$filter->fechaf);
        $this->db->group_by('d.codot,d.SerieDoc,d.NroDoc,c.Mo,c.TC');
        if(isset($order_by) && is_array($order_by) && count($order_by)>0) $this->db->having($order_by);
        $query = $this->db->get();
        $resultado = new stdClass();
        if($query->num_rows>0){
            $resultado = $query->row();
        }
        return $resultado; 
    }
}
?>
