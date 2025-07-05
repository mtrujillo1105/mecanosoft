<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ocompra_model extends CI_Model{
    var $entidad;
    var $table;
    
    public function __construct(){
        parent::__construct();
        $this->load->helper('date');
        $this->entidad   = $this->session->userdata('entidad');
        $this->table     = utf8_decode("Reposición");
        $this->table_det = utf8_decode("Reposición_det");
    }

    
     public function seleccionar_TipoOt($default="",$value=''){
        $nombre_x_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_x_defecto);
        foreach($this->listar_TipoOt() as $indice=>$valor)
        {
            $indice1   = $valor->Cod_Argumento;
            $valor1    = $valor->Des_Larga;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
     }  
        
               
     public function listar_TipoOt(){
        $cadena = "  
        select 
        Cod_Argumento,
        Des_Larga 
        from tabla_m_detalle 
        where cod_tabla='TORD' 
        and Des_Corta='OT' 
        and Cod_Argumento>=14 
        and codent='".$this->entidad."'
        ";
        $query = $this->db->query($cadena);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;  
    }  
    
     public function seleccionar_Estado($default="",$value=''){
        $nombre_x_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_x_defecto);
        foreach($this->listar_Estado() as $indice=>$valor)
        {
            $indice1   = $valor->Cod_Argumento;
            $valor1    = $valor->Des_Larga;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
     }  
           
     public function listar_Estado(){
        $cadena = "  
        select 
        Cod_Argumento,
        Des_Larga 
        from tabla_m_detalle 
        where cod_tabla='TEOT' 
        and cod_argumento<>'01'
        and codent='".$this->entidad."'
        ";
        $query = $this->db->query($cadena);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;  
    }  
    
     public function seleccionar_Cliente($default="",$value=''){
        $nombre_x_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_x_defecto);
        foreach($this->listar_Cliente() as $indice=>$valor)
        {
            $indice1   = $valor->CodCli;
            $valor1    = $valor->RazCli;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
     }  
        
     public function listar_Cliente(){
        $cadena = "  
        select
        CodCli,
        RazCli 
        from clientes 
        where tipcli='02' 
        and EstCli='2' 
        and codent='".$this->entidad."'
        order by RazCli
        ";
        $query = $this->db->query($cadena);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;  
    }  

     public function obtener_OldOt($filtrotipoot,$filtroestado,$filtrotipotorre,$filtrocliente){
        $cadena = "  
        select 
        ot.NroOt,
        ot.DirOt,
        ot.CodCli,
        ot.CodOt,
        clientes.razcli,
        ot.Tipo,
        c.Valor_3,
        c.Des_Larga,
        ot.peso,
        OtDetalleVta.Altura,
        ot.Torre,
        view_tipTorre.Des_Larga as descri,
        convert(varchar,ot.FecOt,103) as fecha
        from ot 
        left join OtDetalleVta on OtDetalleVta.CodOt=ot.codot and OtDetalleVta.codent=ot.codent
        inner join view_tipTorre on view_tipTorre.Cod_Argumento=ot.Torre
        inner join clientes on clientes.codent=ot.codent and clientes.codcli=ot.codcli 
        inner join view_tipProducto_Old as c on c.Cod_Argumento = ot.Tipo
        where ot.codent='".$this->entidad."'
        ".$filtrotipoot."
        ".$filtroestado."
        ".$filtrotipotorre."
        ".$filtrocliente."
        and c.Valor_3='02'    
        ";
        $query = $this->db->query($cadena);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;  
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
	
    public function listar($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        if($this->entidad=='01'){
            $filtrofechai = "";
            $filtrofechaf = "";
            $filtrofecha  = "";
            $filtroaprobado = "";
            $filtroserie    = "";
            $filtronumero   = "";
            $filtrorucproveedor = "";
            $filtroproducto = "";
            if(isset($filter->fechai) && $filter->fechai!='')              $filtrofechai = "AND Fecemi>=CTOD('".date_dbf($filter->fechai)."')";  
            if(isset($filter->fechaf) && $filter->fechaf!='')              $filtrofechaf = "AND Fecemi<=CTOD('".date_dbf($filter->fechaf)."')";  
            if(isset($filter->fecha) && $filter->fecha!='')                $filtrofecha  = "AND Fecemi=CTOD('".date_dbf($filter->fecha)."')";  
            if(isset($filter->flgAprobado) && $filter->flgAprobado!='')    $filtroaprobado  = "AND User='".($filter->flgAprobado==1?'X':'')."'";  
            if(isset($filter->serie) && $filter->serie!='')                $filtroserie  = "AND Gserguia='".$filter->serie."'";  
            if(isset($filter->numero) && $filter->numero!='')              $filtronumero  = "AND Gnumguia='".$filter->numero."'";  
            if(isset($filter->rucproveedor) && $filter->rucproveedor!='')  $filtrorucproveedor = "AND Gproveed='".$filter->rucproveedor."'"; 
            if(isset($filter->codproducto) && $filter->codproducto!='')    $filtroproducto = "AND Gcodpro='".$filter->codproducto."'"; 
            $cadena = "
                      select
                      Gserguia as SerieDoc,
                      Gnumguia as NroDoc,
                      Gproveed as Ruccli,
                      Fecemi as FecRep,
                      Gmoneda as Mo,
                      Gentrega,
                      Gformap as ForPago,
                      Tipopago,
                      Tcambio as TC,
                      Afecto,
                      User,
                      Fec_apro,
                      sum(Gcantidad) as cantidad,
                      Got,
                      Gestado,
                      User,
                      Gprecio,
                      Gnumsol
                      from ordenc
                      where codot!=' '
                      ".$filtrofechai."
                      ".$filtrofechaf."
                      ".$filtrofecha."
                      ".$filtroaprobado."
                      ".$filtronumero."
                      ".$filtroserie."
                      ".$filtrorucproveedor."
                      ".$filtroproducto."
                      group by Gserguia,Gnumguia,Gproveed,Fecemi,Gmoneda,Gentrega,Gformap,Tipopago,Tcambio,Afecto,User                                                
                      ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->result();     
        }
        elseif($this->entidad=='02'){
            $where = array("TipDoc"=>"OC",'CodEnt',$this->entidad);
            if(isset($filter->fechai)) $where = array_merge($where,array("FecRep>="=>$filter->fechai));
            if(isset($filter->fechaf)) $where = array_merge($where,array("FecRep<="=>$filter->fechaf));
            if(isset($filter->fecha))  $where = array_merge($where,array("FecRep"=>$filter->fecha));
            if(isset($filter->flgAprobado))  $where = array_merge($where,array("TipSol"=>$filter->flgAprobado));
            if(isset($filter->serie))  $where = array_merge($where,array("SerieDoc"=>$filter->serie));
            if(isset($filter->numero)) $where = array_merge($where,array("NroDoc"=>$filter->numero));
            if(isset($filter->rucproveedor)) $where = array_merge($where,array("Ruccli"=>$filter->rucproveedor));
            if(isset($filter->codproducto)) $where = array_merge($where,array("NroDoc"=>$filter->codproducto));
            $this->db->select('SerieDoc as seriedoc,NroDoc as nrodoc,Ruccli as ruccli,FecRep as fecrep,Mo as mo');
            $this->db->from($this->table,$number_items,$offset);
            $this->db->where($where);		
            $this->db->order_by('NroDoc','desc');
            $query = $this->db->get();
            $resultado = array();
            if($query->num_rows>0){
                $resultado = $query->result();
            }            
        }
        return $resultado;
    }
    
        public function listar_detalle($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        if($this->entidad=='01'){
            $filtrofechai = "";
            $filtrofechaf = "";
            $filtrofecha  = "";
            $filtroaprobado = "";
            $filtroserie    = "";
            $filtronumero   = "";
            $filtrorucproveedor = "";
            $filtroorden    = "";
            $filtroproducto = "";
            if(isset($filter->fechai) && $filter->fechai!='')              $filtrofechai = "AND o.Fecemi>=CTOD('".date_dbf($filter->fechai)."')";  
            if(isset($filter->fechaf) && $filter->fechaf!='')              $filtrofechaf = "AND o.Fecemi<=CTOD('".date_dbf($filter->fechaf)."')";  
            if(isset($filter->fecha) && $filter->fecha!='')                $filtrofecha  = "AND o.Fecemi=CTOD('".date_dbf($filter->fecha)."')";  
            if(isset($filter->flgAprobado) && $filter->flgAprobado!='')    $filtroaprobado = "AND o.User='".($filter->flgAprobado==1?'X':'')."'";  
            if(isset($filter->serie) && $filter->serie!='')                $filtroserie  = "AND o.Gserguia='".$filter->serie."'";  
            if(isset($filter->numero) && $filter->numero!='')              $filtronumero = "AND o.Gnumguia='".$filter->numero."'";  
            if(isset($filter->rucproveedor) && $filter->rucproveedor!='')  $filtrorucproveedor = "AND o.Gproveed='".$filter->rucproveedor."'"; 
            if(isset($filter->codproducto) && $filter->codproducto!='')    $filtroproducto = "AND o.Gcodpro='".$filter->codproducto."'"; 
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
                      select
                      o.Gserguia as SerieDoc,
                      o.Gnumguia as NroDoc,
                      o.Gproveed as Ruccli,
                      o.Fecemi as FecRep,
                      o.Gnumreq,
                      o.Got,
                      o.gentrega,
                      o.gnumsol,
                      o.Gprecio as gprecio,
                      o.codot,
                      o.got,
                      o.mtopercep,
                      o.Gcodpro,
                      o.gestado,
                      o.fec_apro,
                      o.Gcantidad,
                      o.Fecemi as fecha,
                      o.User as flgaprobado,
                      o.Gmoneda as gmoneda,
                      o.Tcambio as tc
                      from ordenc as o
                      where o.codot!=' '
                      ".$filtrofechai."
                      ".$filtrofechaf."
                      ".$filtrofecha."
                      ".$filtroaprobado."
                      ".$filtronumero."
                      ".$filtroserie."
                      ".$filtrorucproveedor."  
                      ".$filtroproducto."
                      ".$filtroorden."
                      ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->result();        
        }
        elseif($this->entidad=='02'){
            $where = array("c.TipDoc"=>"OC",'c.CodEnt'=>$this->entidad);
            if(isset($filter->fechai) && $filter->fechai!="")             $where = array_merge($where,array("c.FecRep>="=>$filter->fechai));
            if(isset($filter->fechaf) && $filter->fechaf!="")             $where = array_merge($where,array("c.FecRep<="=>$filter->fechaf));
            if(isset($filter->fecha) && $filter->fecha!="")               $where = array_merge($where,array("c.FecRep"=>$filter->fecha));
            if(isset($filter->flgAprobado) && $filter->flgAprobado!="")   $where = array_merge($where,array("c.TipSol"=>$filter->flgAprobado));
            if(isset($filter->serie) && $filter->serie!="")               $where = array_merge($where,array("c.SerieDoc"=>$filter->serie));
            if(isset($filter->numero) && $filter->numero!="")             $where = array_merge($where,array("c.NroDoc"=>$filter->numero));
            if(isset($filter->rucproveedor) && $filter->rucproveedor!="") $where = array_merge($where,array("c.Ruccli"=>$filter->rucproveedor));
            if(isset($filter->codproducto) && $filter->codproducto!="")   $where = array_merge($where,array("c.NroDoc"=>$filter->codproducto));
            $this->db->select("c.seriedoc,c.nrodoc,c.ruccli,convert(varchar(10),c.FecRep,120) as fecrep,c.Mo as mo,c.EstRep as gestado,c.ObsRep as gobs,d.CodPro as gcodpro,d.CantSolRep as gcantidad,d.CantSol as gsolicita,d.NroDocRef as gnumreq,d.SerieDocRef as gserreq,d.MO as gmoneda,'' as modo,'' as numord,'' as chk,d.PrecUnit as gprecio,c.TC as tc,convert(varchar(10),c.FecApro,120) as fec_apro");
            $this->db->from($this->table_det." as d",$number_items,$offset);
            $this->db->join($this->table." as c","c.SerieDoc=d.SerieDoc and c.NroDoc=d.NroDoc and c.CodEnt=d.CodEnt and c.TipDoc=d.TipDoc");
            $this->db->where($where);          
            $this->db->order_by('c.NroDoc','desc');
            $query = $this->db->get();
            $resultado = array();
            if($query->num_rows>0){
                $resultado = $query->result();
            }      
        }
        return $resultado;
    }
    
    public function obtener($filter,$filter_not){
        if($this->entidad=='01'){
            $filtroserie    = "";
            $filtronumero   = "";
            if(isset($filter->serie) && $filter->serie!='')                $filtroserie  = "AND Gserguia='".$filter->serie."'";  
            if(isset($filter->numero) && $filter->numero!='')              $filtronumero  = "AND Gnumguia='".$filter->numero."'";  
            $cadena = "
                      select 
                      Gserguia as SerieDoc,
                      Gnumguia as NroDoc,
                      Gproveed as Ruccli,
                      Fecemi as FecRep,
                      Gmoneda as Mo,
                      Gentrega,
                      Gformap as ForPago,
                      Tipopago,
                      gnumsol,
                      gsersol,
                      fecemi,
                      dias,
                      Tcambio as TC,
                      Afecto,
                      User,
                      Fec_apro
                      from ordenc
                      where codot!=' '
                      ".$filtronumero."
                      ".$filtroserie."
                      group by Gserguia,Gnumguia,Gproveed,Fecemi,Gmoneda,Gentrega,Gformap,Tipopago,Tcambio,Afecto,User                                                
                      ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->row();   
        }
        elseif($this->entidad=='02'){
            $where = array("CodEnt"=>$this->entidad,"TipDoc"=>"OC");
            if(isset($filter->serie) && $filter->serie!='')   $where = array_merge($where,array("SerieDoc"=>$filter->serie));
            if(isset($filter->numero) && $filter->numero!='') $where = array_merge($where,array("NroDoc"=>$filter->numero));
            $query = $this->db->where($where)->get(utf8_encode($this->table));
            $resultado = new stdClass();
            if($query->num_rows>1) exit('Existe mas de 1 resultado');
            if($query->num_rows==1){
                $resultado = $query->row();
            }            
        }
        return $resultado;
    }    
    
    
    
     public function obtener_cantidad($filter,$filter_not){
        if($this->entidad=='01'){
            
            $filtroreq   = "";
            $filtrooc   = "";
            $filtroprod   = "";
            
            if(isset($filter->numreq) && $filter->numreq!='')              $filtroserie   = "AND numreq='".$filter->numreq."'";  
            if(isset($filter->numcom) && $filter->numcom!='')              $filtronumero  = "AND Gnumguia='".$filter->numcom."'";  
            if(isset($filter->codigo) && $filter->codigo!='')              $filtronumero  = "AND Gcodpro='".$filter->codigo."'";  
           
            $cadena = "
                      select 
                      Gcantidad
                      from ordenc
                      where codot!=' '
                      ".$filtroreq."
                      ".$filtrooc."
                      ".$filtroprod."    
                                              
                      ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->row();   
        }
        elseif($this->entidad=='02'){
            $where = array("CodEnt"=>$this->entidad,"TipDoc"=>"OC");
            if(isset($filter->serie) && $filter->serie!='')   $where = array_merge($where,array("SerieDoc"=>$filter->serie));
            if(isset($filter->numero) && $filter->numero!='') $where = array_merge($where,array("NroDoc"=>$filter->numero));
            $query = $this->db->where($where)->get(utf8_encode($this->table));
            $resultado = new stdClass();
            if($query->num_rows>1) exit('Existe mas de 1 resultado');
            if($query->num_rows==1){
                $resultado = $query->row();
            }            
        }
        return $resultado;
    }    
    
    
    
    
    
    public function obtener2($numero,$codpro){
        if($this->entidad=='01'){
            $cadena = "SELECT gserguia,gnumguia,gCantidad,codot,Fecemi,Got FROM Ordenc WHERE user='X' AND Gnumreq='".$numero."' and Gcodpro='".$codpro."'"; 
            $query     = $this->dbase->query($cadena);
            $resultado = $query->result();
        }
        elseif($this->entidad=='02'){
            $where = array("SerieDoc"=>"0004","NroDoc"=>$numero,"CodEnt"=>$this->entidad);
            $query = $this->db->order_by('nroOt')->where($where)->get($this->table);
            $resultado = new stdClass();
            if($query->num_rows>1) exit('Existe mas de 1 resultado');
            if($query->num_rows==1){
                $resultado = $query->row();
            }            
        }
        return $resultado;
    }    

    public function insertar(stdClass $filter = null){
        $this->db->insert($this->table,(array)$filter);
    }
	
    public function modificar($id,$filter){
        $where = array("SerieDoc"=>"0004","NroDoc"=>$numero,"CodEnt"=>$this->entidad);
        $this->db->where($where);
        $this->db->update($this->table,(array)$filter);
    }
	
    public function eliminar($id){
        //$this->db->delete($this->table,array('codot' => $id));
    }
	
    public function buscar($filter,$number_items='',$offset=''){
        $this->db->select('*');
        $this->db->from($this->table,$number_items='',$offset='');
        $this->db->where('CodEnt',$this->entidad);
        $this->db->where_not_in('Estado','A');	
        if(isset($filter->CodEnt) && $filter->CodEnt!="")
            $this->db->like('CodEnt',$filter->CodEnt);
        if(isset($filter->Estado) && $filter->Estado!="")
            $this->db->like('Estado',$filter->Estado);
        $query = $this->db->get();
        if($query->num_rows>0){
            foreach($query->result() as $fila){
                    $data[] = $fila;
            }
            return $data;
        }
    }
    
    
    
    public function indicadores(){
     //$this->load->view(compras."consulta_requis.php");
    }
    
    
    
    public function rpt_control_compras($fInicio,$fFin){
        if($this->entidad=='01'){
            $sql = "
                SELECT 
                requis.gserguia as serie_requerimiento,
                requis.gnumguia as numero_requerimiento,
                requis.Fecemi as fecha_requerimiento,
                soli_oc.Gserie as serie_sc,
                soli_oc.gnumero as numero_sc,
                soli_oc.fecemi as fecha_sc,
                ordenc.gserguia as serie_oc,
                ordenc.gnumguia as numero_oc,
                ordenc.fecemi as fecha_oc,
                ordenc.fec_apro as fecha_aproboc,
                ''  as fregistro_oc,
                requis.gcodpro as codpro,
                '' as producto
                FROM ordenc 
                INNER JOIN soli_oc ON (soli_oc.gserie=ordenc.gsersol AND soli_oc.gnumero=ordenc.gnumsol) 
                inner join requis on (requis.gnumguia=soli_oc.gnumreq) 
                WHERE ordenc.Gestado='X' 
                AND ordenc.Fecemi between CTOD('".date_dbf($fInicio)."') and CTOD('".date_dbf($fFin)."')
            "; 
            $query = $this->dbase->query($sql); 
        }
        elseif($this->entidad=='02'){
           $sql = "
                select 
                r_det.tipdocref as requerimiento,
                r_det.seriedocref  as serie_requerimiento,
                r_det.nrodocref as numero_requerimiento,
                (SELECT convert(varchar,b.FecRep,103)
                FROM         Reposición as b
                WHERE     (b.CodEnt = '".$this->entidad."') AND (b.TipDoc = 'RQ') AND (b.NroDoc = r_det.nrodocref ))  fecha_requerimiento,
                r.tipdocref  as sc,
                r.seriedocref  as serie_sc,
                r.nrodocref  as numero_sc,
                (SELECT     convert(varchar,c.FecRep,103)
                FROM         Reposición as c
                WHERE     (c.CodEnt = '".$this->entidad."') AND (c.TipDoc = 'SC') AND (c.NroDoc = r.nrodocref ))  fecha_sc,
                r.tipdoc as oc, 
                r.seriedoc as serie_oc,
                r.nrodoc as numero_oc,
                convert(varchar,r.fecrep,103) as fecha_oc,
                convert(varchar,r.fecApro,103) as fecha_aproboc,
                convert(varchar,r.fec_reg,103) as fregistro_oc,
                r.RucCli,
                p.codpro as codpro,
                p.despro as producto
                from reposición_det as r_det 
                inner join Reposición as r on (r.CodEnt=r_det.CodEnt and r.TipDoc=r_det.TipDoc and r.SerieDoc=r_det.SerieDoc and r.NroDoc=r_det.NroDoc and r.RucCli=r_det.RucCli)
                inner join producto p on (r_det.codpro=p.codpro and r_det.CodEnt=p.CodEnt)
                where r.codent='".$this->entidad."' 
                and r.tipdoc='OC' 
                and r.FecRep >= '".$fInicio."' 
                and r.FecRep<='".$fFin."' 
                and r.estrep='P'
                order by r_det.nrodocref desc
            ";
            $query = $this->db->query(utf8_decode($sql)); 
        }
        $resultado = $query->result();
        return $resultado;   
    }
    
    /*
     * DLANM
     */
    
    public function get_Orders(){
        $date = date("m/d/Y", strtotime('-2 month'));
        $result = array();
        if($this->entidad == '01'){
           /*
            $sql = "select gcodpro as ord_prd_code from ordenc WHERE user='X' AND sanull!=1 GROUP BY GCODPRO";
            $query = $this->dbase->query($sql);
            $result = $query->result();
            
            foreach ($result as $key => $value) {
                
                $codigo      = $value->ord_prd_code;
                $sql_2 = "select gserguia as ord_serie,gnumguia as ord_number,sum(gCantidad) as ord_qty ,Fecemi as ord_date,Got as ord_ot_number FROM ordenc WHERE user='X' AND sanull!=1 AND gcodpro='".$codigo."' GROUP BY Fecemi,got,gserguia,gnumguia";
                $query_2 = $this->dbase->query($sql_2);
                $result_2 = $query_2->result();
                $totalnea     = 0;
                $totaltrans   = 0;	
                $totalcant    = 0;
                $n = 0;
                
                foreach ($result_2 as $key_2 => $value_2) {
                    $serieoc      = $value_2->ord_serie;
                    $numoc        = $value_2->ord_number;
                    $cantidadoc   = $value_2->ord_qty;
                    $fecemi       = $value_2->ord_date;
                    $got          = $value_2->ord_ot_number;
                    
                    
                    $sql_3 ="select Cantidad as krd_qty ,tip_movmto FROM kardex WHERE  numoc='".$numoc."' AND  Codigo='".$codigo."' and ot='".$got."'";
                    $query_3 = $this->dbase->query($sql_3);
                    $zz           = 0;
                    
                    foreach ($result_3 as $key_3 => $value_3) {
                        $zz++;
                        $cantidadnea   = odbc_result($result4,1);
                        $tip_movmto    = odbc_result($result4,2);
                        if($tip_movmto=='S')    $cantidadnea = -1*$cantidadnea;
                        $totalnea      =  $totalnea + $cantidadnea;   
                        
                        
                    }
                    
                }
                
                
                
                
            }*/
            
            $sql = "SELECT 
                recno() as ord_code,
                o.gcodpro as ord_prd_code,
                o.gserguia as ord_serie,
                o.gnumguia as ord_number,
                o.gnumreq as ord_req_number,
                o.gcantidad as ord_qty,
                o.fecemi as ord_Date,
                o.got AS ord_ot_number,
                o.gproveed as ord_sup_ruc,
                p.p_descri as ord_prd_description,
                (' ') as ord_sup_name,
                (0) as ord_qty_s,
                (0) as ord_saldo
                FROM ordenc as o 
                LEFT JOIN producto as p ON o.gcodpro = p.p_codigo
                WHERE o.sanull!=1 AND o.user = 'X' AND fecemi >= CTOD('".$date."')
                ORDER BY o.fecemi ,o.gnumguia , p.p_Descri";
            $query = $this->dbase->query($sql);
            
        }else{
            
        }
        
        $result = $query->result();
        $this->load->model(compras.'proveedor_model');
        
        if($this->entidad == '01'){

            foreach ($result as $key => $value) {
                $value->ord_qty_s = $this->get_Orders_Movements($value->ord_req_number,$value->ord_number,$value->ord_prd_code,$value->ord_ot_number);
                $value->ord_sup_name = $this->proveedor_model->get_Supplier($value->ord_sup_ruc,$this->entidad);
                $value->ord_saldo = $value->ord_qty - $value->ord_qty_s;
                
                if($value->ord_saldo == 0){
                    unset($result[$key]);
                }
            }
            
        }else{
            
        }

        return $result;
    }
    
    /*
     * DLANM
     */
    
    public function get_Orders_Movements($ord_req_number,$ord_number,$ord_prd_code,$ord_ot_number){
        $total = 0 ;
        
        $sql = "SELECT NVL(SUM(cantidad),0) AS krd_qty FROM kardex WHERE tip_movmto = 'I' AND documento NOT in ('AJ','DV','TF') AND numreq = '".$ord_req_number."' AND numoc='".$ord_number."' AND  Codigo='".$ord_prd_code."' and ot='".$ord_ot_number."'  AND numreq != ' '";
        $query = $this->dbase->query($sql);
        $result = $query->result();
        
        $input = 0;
        foreach ($result as $key => $value) {
            $input = $value->krd_qty;
        }
        
        $sql = "SELECT NVL(SUM(cantidad),0) AS krd_qty FROM kardex WHERE tip_movmto = 'S' AND documento in ('DV') AND numreq = '".$ord_req_number."' AND numoc='".$ord_number."' AND  Codigo='".$ord_prd_code."' and ot='".$ord_ot_number."'";
        $query = $this->dbase->query($sql);
        $result = $query->result();
        
        $output = 0;
        foreach ($result as $key => $value) {
            $output = $value->krd_qty;
        }
        
        
        $total = $input-$output;
        return $total;

    }
    
    public function del_Orders($req_code){
        $date = date("m/d/Y", strtotime('-2 month'));
        if($this->entidad == '01'){
            $data = array(
               'sanull' => 1
            );
            
            $val_n = 0;
            $i = 0;
            foreach($req_code as $code => $val){
               if(($i%10)==0){
                   $val_n++;
               }
               $arr_list[$val_n][$code] = $val;
               $i++;
            }

            foreach ($arr_list as $key => $value) {
                $this->dbase->where_in('RECNO()', $value);
                $this->dbase->update('ordenc', $data);
            }
            
            
            
            
            
            
            $data = array(
                   'Stk_trans' => 0
            );
            $this->dbase->update('producto', $data);
         
            
            
            $sql = "select gcodpro as ord_prd_code from ordenc WHERE fecemi >= CTOD('".$date."') AND user='X' AND sanull!=1 group BY gcodpro";
            $query = $this->dbase->query($sql);
            $result = $query->result();
            $n = 0;
            
            
            foreach ($result as $key => $value) {
                
                $codigo      = $value->ord_prd_code;
               
                $sql_2      = "select gserguia as ord_serie,gnumguia as ord_number,sum(gCantidad) as ord_qty,Fecemi,Got as ord_ot_number , gnumreq AS ord_req_number FROM ordenc WHERE sanull!=1 AND user='X' AND Fecemi>=CTOD('".$date."') AND gcodpro='".$codigo."' GROUP BY Fecemi,got,gserguia,gnumguia,gnumreq";
                $query_2 = $this->dbase->query($sql_2);
                $result_2 = $query_2->result();
                
                $totalnea     = 0;
                $transito   = 0;	
                $totalcant    = 0;
                    $n = 0;
                    
                    
                foreach ($result_2 as $key_2 => $value_2) {
                            $n++;
                    $serieoc      = $value_2->ord_serie;
                    $numoc        = $value_2->ord_number;
                    $cantidadoc   = $value_2->ord_qty;
                    $got          = $value_2->ord_ot_number;
                    $ord_req_number= $value_2->ord_req_number;
                    
                    /*Obtengo las NEAS*/
    
                    $sql_3      = "select Cantidad as krd_qty,tip_movmto as krd_type FROM kardex WHERE  numreq = '".$ord_req_number."' AND numoc='".$numoc."' AND  Codigo='".$codigo."' and ot='".$got."'";
                    $query_3 = $this->dbase->query($sql_3);
                    $result_3 = $query_3->result();
                    
                    
                    
                    
                    
                    foreach ($result_3 as $key_3 => $value_3) {
                   
                        $cantidadnea   = $value_3->krd_qty;
                        $tip_movmto    = $value_3->krd_type;
                        if($tip_movmto=='S')    $cantidadnea = -1*$cantidadnea;
                        $totalnea      =  $totalnea + $cantidadnea;        
                    }
           
                    $totalcant  = $totalcant + $cantidadoc;
                }


                $transito = $totalcant - $totalnea;

                
                $array = array(
                    'p_codigo'    => $codigo
                );
                $data = array(
                   'Stk_trans' => $transito
                );

                $this->dbase->where($array);
                $this->dbase->update('producto', $data);
                
                /*$cadena6 = "update producto set Stk_trans=".$transito." where P_codigo='".$codigo."'";
                $result6 = odbc_exec($cid,$cadena6);  */
                
            }
            
             
            /*foreach ($result as $key => $value) {
                $codigo = $value->ord_prd_code;
                $sql_2 = "select gserguia as ord_serie,gnumguia as ord_number,sum(gCantidad) as ord_qty,Fecemi as ord_date,Got as ord_ot_number ,gnumreq as ord_req_number FROM ordenc WHERE sanull!=1 AND user='X' AND Fecemi>=CTOD('".$date."') AND gcodpro='".$codigo."' GROUP BY Fecemi,got,gserguia,gnumguia";
                $query_2 = $this->dbase->query($sql_2);
                $result_2 = $query_2->result();
                
                $solicitado = 0;
                $atendido = 0;
                foreach ($result_2 as $key_2 => $value_2) {
                    
                    $ord_number   = $value_2->ord_number;
                    $ord_qty      = $value_2->ord_qty;
                    $ord_ot_number= $value_2->ord_ot_number;
                    $ord_req_number= $value_2->ord_req_number;
                    $solicitado = $solicitado + $ord_qty;
                    
                    
                    $sql_3  = "select NVL(SUM(cantidad),0) as krd_qty FROM kardex WHERE documento NOT in ('AJ','DV','TF') AND tip_movmto='I' AND numoc='".$ord_number."' AND  Codigo='".$value->ord_prd_code."' and ot='".$ord_ot_number."' AND numreq = '".$ord_req_number."'";
                    $query_3 = $this->dbase->query($sql_3);
                    $result_3 = $query_3->result();
                    
                    $input = 0;
                    foreach ($result_3 as $key_3 => $value_3) {
                        $input = $value_3->krd_qty;
                  
                    }
                    
                    
                    $sql_4  = "select NVL(SUM(cantidad),0) as krd_qty FROM kardex WHERE documento in ('DV') AND tip_movmto='S' AND numoc='".$ord_number."' AND  Codigo='".$value->ord_prd_code."' and ot='".$ord_ot_number."'";
                    $query_4 = $this->dbase->query($sql_4);
                    $result_4 = $query_3->result();
                    
                    $output = 0;
                    foreach ($result_4 as $key_4 => $value_4) {
                        $output = $value_4->krd_qty;
          
                    }
                    
                    $atendido = $atendido + $input - $output;

                }
                
                
                
                
                //if($n == 0) {$transito = 0;}
                $transito  = $solicitado - $atendido;
                
                $array = array(
                    'p_codigo'    => $codigo
                );
                $data = array(
                   'Stk_trans' => $transito
                );

                $this->dbase->where($array);
                $this->dbase->update('producto', $data);
                
            }*/
            
           
            
            
            
            
            
        }else{
            
        }
    }
}
?>