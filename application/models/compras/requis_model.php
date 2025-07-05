<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Requis_model extends CI_Model{
    var $entidad;
    var $table;
    var $table_det;
    
    public function __construct(){
        parent::__construct();
        $this->entidad = $this->session->userdata('entidad');
        $this->table     = utf8_decode("Reposición");
        $this->table_det = utf8_decode('Reposición_Det');
        $this->tablet = "tipo";
    }
	
    public function seleccionar_requis_tipo($filter,$filter_not,$order_by='',$default="",$value='')
    {
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_defecto);
        foreach($this->listarg($filter,$filter_not,$order_by) as $indice=>$valor)
        {
            $indice1   = $valor->codtip;
            $valor1    = $valor->codtip." - ".$valor->tipo;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
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

    /*Lista las cabeceras de todas las requisiciones*/
    public function listar($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        if($this->entidad=='01'){
            $filtrofechai = "";
            $filtrofechaf = "";
            $filtrofecha  = "";
            $filtroaprobado = "";
            $filtroserie    = "";
            $filtronumero   = "";
            $filtrotipo     = "";            
            $filtrocodot    = ""; 
            if(isset($filter->fechai) && $filter->fechai!='')              $filtrofechai = "AND Fecemi>=CTOD('".date_dbf($filter->fechai)."')";  
            if(isset($filter->fechaf) && $filter->fechaf!='')              $filtrofechaf = "AND Fecemi<=CTOD('".date_dbf($filter->fechaf)."')";  
            if(isset($filter->fecha) && $filter->fecha!='')                $filtrofecha  = "AND Fecemi=CTOD('".date_dbf($filter->fecha)."')";  
            if(isset($filter->flgAprobado) && $filter->flgAprobado!='')    $filtroaprobado  = "AND User='".($filter->flgAprobado==1?'X':'')."'";  
            if(isset($filter->serie) && $filter->serie!='')                $filtroserie  = "AND Gserguia='".$filter->serie."'";  
            if(isset($filter->numero) && $filter->numero!='')              $filtronumero  = "AND Gnumguia='".$filter->numero."'";  
            if(isset($filter->tipo) && $filter->tipo!='')                  $filtrotipo    = "AND Tipo='".$filter->tipo."'";  
            if(isset($filter->codot) && $filter->codot!='')                $filtrocodot    = "AND Codot='".$filter->codot."'";  
            $cadena = "
                      select 
                      Gserguia as SerieDoc,
                      Gnumguia as NroDoc,
                      Fecemi as FecRep,
                      Gmoneda as Mo,
                      Gentrega,
                      Gsolicita,
                      Gdepa,
                      Gcoddpto,
                      Codot,
                      Tipot,
                      Codresot,
                      User
                      from requis
                      where codot!=' '
                      ".$filtrofechai."
                      ".$filtrofechaf."
                      ".$filtrofecha."
                      ".$filtroaprobado."
                      ".$filtronumero."
                      ".$filtroserie."
                      ".$filtrotipo."
                      ".$filtrocodot."
                      group by Gserguia,Gnumguia,Fecemi,Gmoneda,Gentrega,Gsolicita,Gdepa,Gcoddpto,Codot,Tipot,Codresot,User                                                
                      ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->result();
        }
        elseif($this->entidad=='02'){
            $where = array("TipDoc"=>"RQ",'CodEnt',$this->entidad);
            if(isset($filter->fechai)) $where = array_merge($where,array("FecRep>="=>$filter->fechai));
            if(isset($filter->fechaf)) $where = array_merge($where,array("FecRep<="=>$filter->fechaf));
            if(isset($filter->fecha))  $where = array_merge($where,array("FecRep"=>$filter->fecha));
            if(isset($filter->flgAprobado))  $where = array_merge($where,array("TipSol"=>$filter->flgAprobado));
            if(isset($filter->serie))  $where = array_merge($where,array("SerieDoc"=>$filter->serie));
            if(isset($filter->numero)) $where = array_merge($where,array("NroDoc"=>$filter->numero));
            if(isset($filter->rucproveedor)) $where = array_merge($where,array("Ruccli"=>$filter->rucproveedor));
            if(isset($filter->codproducto)) $where = array_merge($where,array("NroDoc"=>$filter->codproducto));
            $this->db->select('*');
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
    
    /*Lista el detalle de todas las requisiciones*/
    public function listar_detalle($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        if(isset($filter->fechai)){
            if ($filter->fechai=='__/__/____')    $filter->fechai='';    
        }
        if(isset($filter->fechaf)){
            if ($filter->fechaf=='__/__/____')    $filter->fechaf='';
        }
        if($this->entidad=='01'){
            $filtrofechai    = "";
            $filtrofechaf    = "";
            $filtrofecha     = "";
            $filtrofechaproi = "";
            $filtrofechaprof = "";
            $filtrofechapro  = "";            
            $filtroaprobado  = "";
            $filtroserie     = "";
            $filtronumero    = "";
            $filtrotipo      = "";            
            $filtrocodot     = ""; 
            $filtrocodpro    = "";
            $filtrocodot_not = "";
            $filtrocodres    = "";
            $filtrocodresot  = "";
            $filtrofechas    = "";
            $filtrolinea_not = "";
            if(isset($filter->fechai) && $filter->fechai!='')              $filtrofechai = "AND Fecemi >= CTOD('".date_dbf(trim($filter->fechai))."')";  
            if(isset($filter->fechaf) && $filter->fechaf!='')              $filtrofechaf = "AND Fecemi <= CTOD('".date_dbf(trim($filter->fechaf))."')";  
            if(isset($filter->fecha) && $filter->fecha!='')                $filtrofecha  = "AND Fecemi = CTOD('".date_dbf($filter->fecha)."')";  
            if(isset($filter->fechaproi) && $filter->fechaproi!='')        $filtrofechaproi = "AND CTOD(DTOC(fec_apro)) >= CTOD('".date_dbf($filter->fechaproi)."')";  
            if(isset($filter->fechaprof) && $filter->fechaprof!='')        $filtrofechaprof = "AND CTOD(DTOC(fec_apro)) <= CTOD('".date_dbf($filter->fechaprof)."')";  
            if(isset($filter->fechapro) && $filter->fechapro!='')          $filtrofechapro  = "AND CTOD(DTOC(fec_apro))=CTOD('".date_dbf($filter->fechapro)."')";             
            if(isset($filter->flgAprobado) && $filter->flgAprobado!='')    $filtroaprobado  = "AND User='".($filter->flgAprobado==1?'X':'')."'";  
            if(isset($filter->serie) && $filter->serie!='')                $filtroserie     = "AND Gserguia='".$filter->serie."'";  
            if(isset($filter->numero) && $filter->numero!='')              $filtronumero    = "AND Gnumguia='".$filter->numero."'";  
            if(isset($filter->tipo) && $filter->tipo!='')                  $filtrotipo      = "AND Tipo='".$filter->tipo."'";  
            if(isset($filter->codot) && $filter->codot!=''){
                if(is_array($filter->codot) && count($filter->codot)>0){
                    $interv = 20;
                    for($i=0;$i<ceil(count($filter->codot)/$interv);$i++){
                        $arrcodot = array_slice($filter->codot,$i*$interv,$interv);
                        $filtrocodot .= ($i==0?"and (":" or")." Codot in ('".str_replace(",","','",implode(',',$arrcodot))."')";
                    }
                    $filtrocodot .= ")";
                }
                else{
                    $filtrocodot  = " and Codot='".$filter->codot."'";    
                }
            }             
            if(isset($filter->codpro) && $filter->codpro!='')              $filtrocodpro    = "AND Gcodpro='".$filter->codpro."'";  
            if(isset($filter->codres) && $filter->codres!='' && $filter->codres!='000000')        $filtrocodres = "AND Codres='".$filter->codres."'";  
            if(isset($filter->codresot) && $filter->codresot!='' && $filter->codresot!='000000')  $filtrocodresot = "AND Codresot='".$filter->codresot."'";  
            if(isset($filter_not->linea) && $filter_not->linea!=''){
                if(is_array($filter_not->linea) && count($filter_not->linea)>0){
                    $interv = 20;
                    for($i=0;$i<ceil(count($filter_not->linea)/$interv);$i++){
                        $arrlinea = array_slice($filter_not->linea,$i*$interv,$interv);
                        $filtrolinea_not .= ($i==0?"and ":" and")." SUBSTR(Gcodpro,1,4) not in ('".str_replace(",","','",implode(',',$arrlinea))."')";
                    }
                }
                else{
                    $filtrolinea_not  = "and SUBSTR(Gcodpro,1,4)!='".$filter_not->linea."'";    
                }
            } 
            if(isset($order_by) && $order_by!='')                          $order_by        = "order by ".$order_by;  
            $cadena = "
                      select 
                      Gserguia as SerieDoc,
                      Gnumguia as NroDoc,
                      Fecemi as Fecemi,
                      Gmoneda as Mo,
                      Gcodpro,
                      Gcantidad,
                      Gcantidads,
                      Gentrega,
                      Gsolicita,
                      Gdepa,
                      Gcoddpto,
                      Codot,
                      gprecio,
                      Tipot,
                      Got,
                      Codresot,
                      User,
                      Useraprob,
                      Peso,
                      Gobs,
                      Fec_apro,
                      Codresot,
                      Tipo
                      from requis
                      where codot!=' '
                      ".$filtrofechai." ".$filtrofechaf."
                      ".$filtrofecha."
                      ".$filtrofechaproi." ".$filtrofechaprof."
                      ".$filtrofechapro."                          
                      ".$filtroaprobado."
                      ".$filtronumero."
                      ".$filtroserie."
                      ".$filtrocodot."
                      ".$filtrocodpro." 
                      ".$filtrotipo."  
                      ".$filtrocodot_not."
                      ".$filtrocodres."
                      ".$filtrocodresot."
                      ".$filtrolinea_not."
                      ".$order_by."
                      ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->result(); 
        }
        elseif($this->entidad=='02'){
            $where = array('c.CodEnt'=>$this->entidad);
            if(isset($filter->fechai) && $filter->fechai!="")        $where = array_merge($where,array("c.FecRep>="=>$filter->fechai));
            if(isset($filter->fechaf) && $filter->fechaf!="")        $where = array_merge($where,array("c.FecRep<="=>$filter->fechaf));
            if(isset($filter->fecha) && $filter->fecha!="")          $where = array_merge($where,array("c.FecRep"=>$filter->fecha));
            if(isset($filter->fechaproi) && $filter->fechaproi!="")  $where = array_merge($where,array("c.FecApro>="=>$filter->fechaproi));
            if(isset($filter->fechaprof) && $filter->fechaprof!="")  $where = array_merge($where,array("c.FecApro<="=>$filter->fechaprof));
            if(isset($filter->fechapro) && $filter->fechapro!="")    $where = array_merge($where,array("c.FecApro"=>$filter->fechapro));     
            if(isset($filter->serie) && $filter->serie!="")          $where = array_merge($where,array("c.SerieDoc"=>$filter->serie));
            if(isset($filter->numero) && $filter->numero!="")        $where = array_merge($where,array("c.NroDoc"=>$filter->numero));           
            if(isset($filter->codot) && $filter->codot!="")          $where = array_merge($where,array("d.CodOt"=>$filter->codot));
            if(isset($filter->codpro) && $filter->codpro!="")        $where = array_merge($where,array("d.CodPro"=>$filter->codpro));
            if(isset($filter->codres) && $filter->codres!="000000")     $where = array_merge($where,array("c.CodRes"=>$filter->codres));
            if(isset($filter->codresot) && $filter->codresot!="000000") $where = array_merge($where,array("d.CodPro"=>$filter->codresot));
            $this->db->select("c.seriedoc,c.nrodoc,convert(varchar(10),c.FecRep,120) as fecemi,d.MO as mo,d.CodPro as gcodpro,d.CantSolRep as gcantidad,CantAten as gcantidads,'' as gentrega,c.CodRes as gsolicita,td.des_larga as gdepa,c.CodDpto as gcoddpto,c.CodOt as codot,d.PrecUnit as gprecio,ot.TipOt as tipot,ot.NroOt as got,c.CodRes as codres,'' as useraprob,d.peso as peso,c.ObsRep as gobs,convert(varchar,c.FecApro,120) as fec_apro,'' as codresot,c.TipDoc as tipo");
            $this->db->from($this->table_det." as d",$number_items,$offset);
            $this->db->join($this->table." as c","c.SerieDoc=d.SerieDoc and c.NroDoc=d.NroDoc and c.CodEnt=d.CodEnt and c.TipDoc=d.TipDoc");
            $this->db->join("ot","ot.codot=c.codot");
            $this->db->join("tabla_m_detalle as td","td.cod_tabla='TLAB' and td.cod_argumento=c.CodDpto and td.codent=d.codent");
            $this->db->where($where);	
            if(isset($filter->tipo) && $filter->tipo!=""){
                if($filter->tipo=='R')  $this->db->where("c.Codmov","03");
                if($filter->tipo=='O')  $this->db->where("c.TipDoc","RU");
                if($filter->tipo=='H')  $this->db->where("c.Codmov","04");
            } 
            else{
                $this->db->where_in("c.TipDoc",array('RQ','RU'));                
            }
            $this->db->order_by('c.NroDoc','desc');
            $query = $this->db->get();
            $resultado = array();
            if($query->num_rows>0){
                $resultado = $query->result();
            }  
        }
        return $resultado;
    }

    /*Lista totales agrupando según consulta*/
    public function listar_totales($filter,$filter_not='',$order_by='',$number_items='',$offset=''){
        if($this->entidad=='01'){
            $filtrofechai = "";
            $filtrofechaf = "";
            $filtrofecha  = "";
            $filtrotipo   = "";
            $filtroaprobado = "";
            $filtrolinea    = "";
            $filtrolinea_not = "";
            $campos="";
            if(isset($filter->fechai) && $filter->fechai!='')              $filtrofechai   = "AND r.fecemi>=CTOD('".date_dbf($filter->fechai)."')";  
            if(isset($filter->fechaf) && $filter->fechaf!='')              $filtrofechaf   = "AND r.fecemi<=CTOD('".date_dbf($filter->fechaf)."')";  
            if(isset($filter->fecha) && $filter->fecha!='')                $filtrofecha    = "AND r.fecemi=CTOD('".date_dbf($filter->fecha)."')";  
            if(isset($filter->flgAprobado) && $filter->flgAprobado!='')    $filtroaprobado = "AND r.user='".($filter->flgAprobado==1?'X':'')."'";              
            if(isset($filter->tipo) && $filter->tipo!='')                  $filtrotipo     = "AND r.tipot='".$filter->tipo."'";  
            if(isset($filter->group_by) && count($filter->group_by)>0 && $filter->group_by!="") $campos   = implode(",",$filter->group_by);
            /*Filter*/
            if(isset($filter->linea) && $filter->linea!=''){
                if(is_array($filter->linea) && count($filter->linea)>0){
                    $interv = 20;
                    for($i=0;$i<ceil(count($filter->linea)/$interv);$i++){
                        $arrlinea = array_slice($filter->linea,$i*$interv,$interv);
                        $filtrolinea .= ($i==0?"and (":"or")." SUBSTR(p.p_codigo,1,4) in ('".str_replace(",","','",implode(',',$arrlinea))."')";
                    }
                    $filtrolinea .= ")";
                }
                else{
                    $filtrolinea  = "and SUBSTR(p.p_codigo,1,4)='".$filter->linea."'";    
                }
            }  
            /*Filter_not*/
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
            $cadena = "
                      select 
                      ".$campos.",
                      sum(r.gcantidad) as cantidad,
                      sum(r.gprecio) as soles,
                      sum(r.gprecio) as dolares,
                      sum(p.p_peso*r.gcantidad) as p_solicitado
                      from requis as r
                      inner join producto as p on p.p_codigo=r.GCODPRO 
                      where !r.useraprob==''
                      ".$filtrofechai."
                      ".$filtrofechaf."
                      ".$filtrofecha."
                      ".$filtroaprobado."
                      ".$filtrotipo."
                      ".$filtrolinea."
                      ".$filtrolinea_not."
                      group by ".$campos."
                      ";     
            $query = $this->dbase->query($cadena);
            $resultado = $query->result(); 
        }   
        elseif($this->entidad=='02'){
            
        }
        return $resultado;
    }
    
    public function listar_personal_x_ot($codot){
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
        return $resultado;
    }    
    
     public function listarg($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        $where = array("estado"=>1);
        if(isset($filter->estado) && $filter->estado!='')  $where = array_merge($where,array("estado"=>$filter->estado));
     //   if(isset($filter->estado) && $filter->estado!='')                  $where = array_merge($where,array("Estper"=>$filter->estado));
      //  if(isset($filter->situacion) && $filter->situacion!='')            $where = array_merge($where,array("Codsituac"=>$filter->situacion));
        $this->db->select('*');
        $this->db->from($this->tablet,$number_items,$offset);
        $this->db->where($where);
       if(isset($order_by) && count($order_by)>0){
            foreach($order_by as $indice=>$value){
                $this->db->order_by($indice,$value);
            }
        }  
        $query = $this->db->get();
        $resultado = new stdClass();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
     }

//    public function listar_requis_x_ot($codot){
//        if($this->entidad=='01'){
//            $cadena = "
//                    SELECT 
//                    r.gserguia,
//                    r.gnumguia,
//                    r.gcodpro,
//                    r.gcantidad,
//                    r.guser,
//                    r.gcantidads,
//                    r.gdepa,
//                    r.tipot,
//                    r.codot,
//                    r.got,
//                    r.codresot,
//                    r.useraprob,
//                    r.fecemi,
//                    p.P_descri,
//                    p.P_unidad,
//                    r.Peso,
//                    iif(SUBSTR(p.P_codigo,1,2)='01','MATERIALES',iif(SUBSTR(p.P_codigo,1,2)='02','PERNOS',iif((SUBSTR(p.P_codigo,1,2)='03' or SUBSTR(p.P_codigo,1,2)='07' or SUBSTR(p.P_codigo,1,2)='12'),'OTROS CONSUMIBLES',iif(SUBSTR(p.P_codigo,1,2)='05','PINTURA',iif(SUBSTR(p.P_codigo,1,2)='06','SOLDADURA','VARIOS'))))) as linea,
//                    p.P_cantidad as stk_actual,
//                    r.Fec_apro,
//                    r.gobs
//                    FROM requis as r
//                    inner join producto as p on p.p_codigo=r.GCODPRO 
//                    WHERE r.tipo='R' 
//                    AND r.User='X' 
//                    and r.codot='".$codot."' 
//                    ORDER BY linea,r.fecemi
//                    "; 
//            $query     = $this->dbase->query($cadena);
//            $resultado = $query->result();
//        }
//        elseif($this->entidad=='02'){
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
//        }
//        return $resultado;
//    }       
    
//    public function obtener($filter,$filter_not){
//        if($this->entidad=="01"){
//            $filtroserie    = "";
//            $filtronumero   = "";
//            $filtrotipo     = "";            
//            if(isset($filter->serie) && $filter->serie!='')                $filtroserie     = "AND Gserguia='".$filter->serie."'";  
//            if(isset($filter->numero) && $filter->numero!='')              $filtronumero    = "AND Gnumguia='".$filter->numero."'";  
//            if(isset($filter->tipo) && $filter->tipo!='')                  $filtrotipo      = "AND Tipo='".$filter->tipo."'";   
//            $cadena = "
//                      select 
//                      Gserguia as SerieDoc,
//                      Gnumguia as NroDoc,
//                      Fecemi as Fecemi,
//                      Gmoneda as Mo,
//                      Gcodpro,
//                      Gcantidad,
//                      Gcantidads,
//                      Gentrega,
//                      Gsolicita,
//                      Gdepa,
//                      Gcoddpto,
//                      Codot,
//                      Tipot,
//                      Got,
//                      Codresot,
//                      User,
//                      Useraprob
//                      from requis
//                      where codot!=' '
//                      ".$filtronumero."
//                      ".$filtroserie."
//                      ".$filtrotipo."
//                      ";
//            $query = $this->dbase->query($cadena);
//            $resultado = $query->result(); 
//        }
//        elseif($this->entidad=="02"){
//            
//        }
//        return $resultado;
//    }
	
//    public function obtener2($fecha){
//        $where = array("CONVERT(VARCHAR,fec_reg,112)"=>$fecha,"CodEnt"=>$this->entidad,"Estado!="=>"A");
//        $this->db->select("substring(CONVERT(VARCHAR,fec_reg,108),1,2) as hora,codot,nroot,desot");
//        $this->db->from($this->table);
//        $this->db->where($where);
//        $this->db->order_by('substring(CONVERT(VARCHAR,fec_reg,108),1,2)');
//        $query = $this->db->get();
//        $resultado = new stdClass();
//        if($query->num_rows>0){
//            $resultado = $query->result();
//        }
//        return $resultado;
//    }
    
    public function insertar(stdClass $filter = null){
        $this->db->insert($this->table,(array)$filter);
    }
	
    public function modificar($id,$filter){
        $this->db->where("CodOt",$id);
        $this->db->update($this->table,(array)$filter);
    }
	
    public function eliminar($id){
        $this->db->delete($this->table,array('codot' => $id));
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
   
    public function peso_atendido(){
        $cadena = "
                select 
                k.codot as codigo, 
                k.ot as got, 
                sum(p.p_peso*k.cantidad) as p_atendido,
                sum(0) as p_solicitado
                from kardex as k 
                inner join producto as p on p.p_codigo=k.codigo 
                where k.tip_movmto='S' 
                and k.documento='G' 
                and k.tipot in ('12')
                and SUBSTR(p.p_codigo,1,4) not in ('0202','1301','0301','1303','0703','0304','1503','1703','1504','0602','1201','0704','1404','1405','2012','0701','1402','1603','1430','1416','1302','0403','1304','1202')
                and SUBSTR(p.p_codigo,1,4) not in ('0225','1608','1202','1305','1601','1605','1305','0403','1401','1606','0505','1700','1702','1101','2010','3020','5030','5040','6010','6050','6090','7070')				
                and SUBSTR(p.p_codigo,1,4) not in ('2100','2110','2120','0210','0212','0217','0218','0201','0180','0707','2014','0302','1103','0605','0601','0211','0220','1102','1005','0503','0504','0609','0204')
                group by k.ot,k.codot            
            ";
        $query     = $this->dbase->query($cadena);
        $resultado = $query->result();
        return $resultado;  
    }
    
    public function peso_solicitado(){
        $cadena = "
                select 
                r.codot as codigo, 
                r.got as got, 
                sum(0) as p_atendido,
                sum(p.p_peso*r.gcantidad) as p_solicitado
                from requis as r 
                inner join producto as p on p.p_codigo=r.GCODPRO 
                where !r.useraprob=='' 
                and r.user='X' 
                and r.tipot in ('12') 
                and SUBSTR(p.p_codigo,1,4) not in ('0202','1301','0301','1303','0703','0304','1503','1703','1504','0602','1201','0704','1404','1405','2012','0701','1402','1603','1430','1416','1302','0403','1304','1202')
                and SUBSTR(p.p_codigo,1,4) not in ('0225','1608','1202','1305','1601','1605','1305','0403','1401','1606','0505','1700','1702','1101','2010','3020','5030','5040','6010','6050','6090','7070')				
                and SUBSTR(p.p_codigo,1,4) not in ('2100','2110','2120','0210','0212','0217','0218','0201','0180','0707','2014','0302','1103','0605','0601','0211','0220','1102','1005','0503','0504','0609','0204')
                group by r.got,r.codot
            ";
        $query     = $this->dbase->query($cadena);
        $resultado = $query->result();
        return $resultado; 
    }  
    
    public function peso_atendido_det($codigo){
        $cadena = "
                select 
                k.codot as codigo, 
                k.ot as got, 
                p.p_peso as p_peso,
                k.codigo as codpro,
                k.cantidad as k_atendida,
                0 as k_solicitada,
                p.p_descri
                from kardex as k 
                inner join producto as p on p.p_codigo=k.codigo 
                where k.tip_movmto='S' 
                and k.documento='G' 
                and k.tipot in ('12') 
                and SUBSTR(p.p_codigo,1,4) not in ('0202','1301','0301','1303','0703','0304','1503','1703','1504','0602','1201','0704','1404','1405','2012','0701','1402','1603','1430','1416','1302','0403','1304','1202')
                and SUBSTR(p.p_codigo,1,4) not in ('0225','1608','1202','1305','1601','1605','1305','0403','1401','1606','0505','1700','1702','1101','2010','3020','5030','5040','6010','6050','6090','7070')				
                and SUBSTR(p.p_codigo,1,4) not in ('2100','2110','2120','0210','0212','0217','0218','0201','0180','0707','2014','0302','1103','0605','0601','0211','0220','1102','1005','0503','0504','0609','0204')
                and k.codot in ('".$codigo."')         
            ";
        $query     = $this->dbase->query($cadena);
        $resultado = $query->result();
        return $resultado;  
    }    
    
   public function peso_solicitado_det($codigo){
        $cadena = "
                select 
                r.codot as codigo, 
                r.got as got, 
                p.p_peso as p_peso, 
                r.gcodpro as codpro,
                0 as k_atendida,
                r.gcantidad as k_solicitada,
                p.p_descri
                from requis as r 
                inner join producto as p on p.p_codigo=r.GCODPRO 
                where !r.useraprob=='' 
                and r.user='X' 
                and r.tipot in ('12') 
                and SUBSTR(p.p_codigo,1,4) not in ('0202','1301','0301','1303','0703','0304','1503','1703','1504','0602','1201','0704','1404','1405','2012','0701','1402','1603','1430','1416','1302','0403','1304','1202')
                and SUBSTR(p.p_codigo,1,4) not in ('0225','1608','1202','1305','1601','1605','1305','0403','1401','1606','0505','1700','1702','1101','2010','3020','5030','5040','6010','6050','6090','7070')														
                and SUBSTR(p.p_codigo,1,4) not in ('2100','2110','2120','0210','0212','0217','0218','0201','0180','0707','2014','0302','1103','0605','0601','0211','0220','1102','1005','0503','0504','0609','0204')
                and r.codot in ('".$codigo."')
            ";
        $query     = $this->dbase->query($cadena);
        $resultado = $query->result();
        return $resultado; 
    }  
  
    public function rpt_control_pesos($tipOt,$fInicio,$fFin,$codcli,$proyecto,$estado){
        $cadena = "
		select 
		month(Ot.FecOt) as mes,
		Ot.nroOt as numero,
		cli.razcli as cliente,
		Ot.dirOt as site,
		Ot.fecOt as fecha,
		Ot.finOt as ffin,
		Ot.Peso as peso,
		Ot.codot as codot,
		dat.p_galva as p_galva,
		ot.peso_teorico as p_teorico,
		ot.peso_teorico_sp as p_teorico_sp,
                ot.peso_fabricacion as peso_fabricacion,
                ot.proyecto as proyecto,
                ot.Tipo as tipot,
                ot.Estado as estado
		from ot as ot
		inner join clientes as cli on (cli.codcli=ot.codcli and cli.codent=ot.codent)
		left join (
			select  
			nroOT as num,
			sum(pesoTotal) as p_galva
			from datos_det 
			where codent='02' 
			and tipdoc<>''
			group by nroOT			
		) as dat on dat.num=ot.nroOt
		where Ot.FecOt BETWEEN '".$fInicio."' and '".$fFin."'
		and ot.tipot in (".$tipOt .")
		and ot.codent='".$this->entidad."'
		".($codcli!="000000"?"and ot.codcli='".$codcli."'":"")."
                ".($proyecto!="000"?"and ot.proyecto='".$proyecto."'":"")."
                ".($estado!="00"?"and ot.estado='".$estado."'":"and ot.estado!='A'")."
		order by ot.nroot
                ";
        $query = $this->db->query($cadena);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;         
    }
    
    public function rpt_gestion_ot($tipOt,$fInicio,$fFin){
        $cadena = "
                select 
                cli.codcli as codcli,
                cli.RazCli as razcli,
                Ot.DirOt,
                Ot.NroOt,
                ot.impOt as monto,
                convert(varchar,Ot.FecOt,103) as fInicio,
                convert(varchar,Ot.FinOt,103) as fFin,
                Ot.estOt as moneda,
                Ot.MtoPre as presupuesto,
                convert(varchar,(Ot.FinOt-Ot.FecOt),103) as diferencia,
                Ot.preOt
                from ot as ot
                inner join clientes as cli on (cli.codcli=ot.codcli and cli.codent=ot.codent)
                where Ot.FecOt BETWEEN '".$fInicio."' and '".$fFin."'
                and ot.tipot in (".$tipOt .")
                and ot.estado!='A'
                and ot.codent='".$this->entidad."'
                order by Ot.FecOt desc
                ";
        $query = $this->db->query($cadena);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;          
    }
    
    public function indicador_requis_atendidas_dbf($fecha_inicial,$fecha_final){
        $arrFecha1 = explode("/",$fecha_inicial);
        $arrFecha2 = explode("/",$fecha_final);
        $sql   = "
                SELECT 
                SUM(gcantidad), 
                gnumguia, 
                gserguia,Fecemi  
                FROM requis 
                WHERE tipo='R' 
                AND user='X' 
                AND codot <> '0001999' 
                AND fecemi between CTOD('".$arrFecha1[1]."/".$arrFecha1[0]."/".$arrFecha1[2]."') 
                AND CTOD('".$arrFecha2[1]."/".$arrFecha2[0]."/".$arrFecha2[2]."') 
                GROUP BY gnumguia, gserguia,Fecemi 
             ";
        $query     = $this->dbase->query($sql);
        $resultado = $query->result();
        return $resultado;          
    } 
    
    public function indicador_requis_atendidas(){

    }     
    
    public function obtener_vale_xfecha($fecha_inicial,$fecha_final,$serie,$numero){
        $arrFecha1 = explode("/",$fecha_inicial);
        $arrFecha2 = explode("/",$fecha_final);        
        $cadena = "
                SELECT 
                cantidad,
                Fecha 
                FROM kardex 
                WHERE serreq='".$serie."' 
                AND numreq='".$numero."' 
                AND fecha between ctod('".$arrFecha1[1]."/".$arrFecha1[0]."/".$arrFecha1[2]."') 
                AND ctod('".$arrFecha2[1]."/".$arrFecha2[0]."/".$arrFecha2[2]."')
             ";      
        $query     = $this->dbase->query($cadena);
        $resultado = $query->result();
        return $resultado;  
    }
    
    
    /*
     * DLANM
     */
    public function get_Requis(){
        
        $date = date("m/d/Y", strtotime('-2 month'));
         //$date = "12/01/2012";
        $result = array();
        
        if($this->entidad == '01'){
            $sql = "SELECT RECNO() as req_code,(0)as req_saldo,gserguia as req_serie,gnumguia as req_number,fecemi as req_date,gcodpro as req_prd_code,gcantidad as req_qty,(0) as req_qty_s,ALLTRIM(p_descri) as req_prd_description,gdepa as req_department , got AS req_ot_number , (0)as qty_oc FROM requis as r LEFT JOIN producto ON gcodpro = p_codigo WHERE p_Descri is not null AND r.user = 'X' AND r.tipo IN ('R','O') AND fecemi >= ctod('".$date."') and r.codot!='0001999' AND r.sanull!=1 ORDER BY fecemi";

            $query = $this->dbase->query($sql);
 
        }else{
            $this->db->select('*,qty_oc');
            $this->db->from('view_requis_pending')
            ->order_by('req_date','asc')
            ->order_by('req_number','desc');
            $query = $this->db->get();
        }

        $result = $query->result();
        
        if($this->entidad == '01'){
            foreach ($result as $key => $value) {
                $value->req_qty_s = $this->get_Requeriments_Movements($value->req_serie,$value->req_number,$value->req_prd_code); 
                $value->qty_oc = $this->get_Orders_Movements($value->req_serie,$value->req_number,$value->req_prd_code); 
                $value->req_saldo = $value->req_qty - $value->req_qty_s ;
                
                if($value->req_saldo == 0){
                    unset($result[$key]);
                }
                
            }
            $result = array_values($result);
        }else{
            
        }
        
        
        return $result;
        
    }
    /*
     * DLANM
     */
    
    public function get_Orders_Movements($req_serie,$numreq,$codigo){
		
	$sql = "SELECT NVL(SUM(gcantidad),0) as qty_oc FROM ordenc WHERE gnumreq = '".$numreq."' AND gcodpro = '".$codigo."'";
        $query = $this->dbase->query($sql);
        $result = $query->result();
        
        $total = 0;
        foreach ($result as $key => $value) {
            $total = $value->qty_oc;
        }
		return $total;
	}
    
    /*
     * DLANM
     */
    
    public function get_Requeriments_Movements($req_serie,$numreq,$codigo){
        
        $total = 0 ;
        
        $sql = "SELECT NVL(SUM(cantidad),0) AS krd_qty FROM kardex WHERE tip_movmto = 'S' AND documento NOT in ('AJ','DV','TF') AND serreq = '".$req_serie."' AND numreq = '".$numreq."' AND codigo = '".$codigo."'";
        $query = $this->dbase->query($sql);
        $result = $query->result();
        
        $output = 0;
        foreach ($result as $key => $value) {
            $output = $value->krd_qty;
        }
        
        $sql = "SELECT NVL(SUM(cantidad),0) AS krd_qty FROM kardex WHERE tip_movmto = 'I' AND documento in ('DV') AND serreq = '".$req_serie."' AND numreq = '".$numreq."' AND codigo = '".$codigo."'";
        $query = $this->dbase->query($sql);
        $result = $query->result();
        
        $input = 0;
        foreach ($result as $key => $value) {
            $input = $value->krd_qty;
        }
        
        
        $total = $output-$input;
        return $total;
    }
    
    
    
    /*
     * DLANM
     */
    public function del_Requis($req_code){
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
                $this->dbase->update('requis', $data);
            }
            
            
            $data = array(
                   'Stk_comp' => 0
            );
            $this->dbase->update('producto', $data);
         
            
            
            $sql = "SELECT gcodpro as req_prd_code,SUM(gcantidad) as  req_qty FROM requis WHERE fecemi>= CTOD('".$date."') AND tipo IN ('R','O') AND User='X' and codot!='0001999' and sanull!=1 group BY gcodpro";
            $query = $this->dbase->query($sql);
            $result = $query->result();
  

        

            
            foreach ($result as $key => $value) {
                $codigo     = $value->req_prd_code;
                $solicitada = $value->req_qty;
                $atendido   =0;
                $sql_2 = "SELECT gserguia as req_serie ,gnumguia as req_number FROM requis WHERE fecemi>= CTOD('".$date."') AND tipo IN ('R','O') AND User='X' and gcodpro='".$codigo."' and codot!='0001999' and sanull!=1 ORDER BY gnumguia";
                $query_2 = $this->dbase->query($sql_2);
                $result_2 = $query_2->result();
                
           
                foreach ($result_2 as $key_2 => $value_2) {
             
                    $serie     = $value_2->req_serie;
                    $numguia   = $value_2->req_number;
                    
                    $sql_3   = "SELECT NVL(sum(cantidad),0) as krd_qty FROM kardex WHERE documento NOT in ('AJ','DV','TF') AND tip_movmto='S' AND serreq='".$serie."' AND numreq='".$numguia."' and Codigo='".$codigo."'";
                    $query_3 = $this->dbase->query($sql_3);
                    $result_3 = $query_3->result();
                    
                    foreach ($result_3 as $key_3 => $value_3) {
                        $valcantidad  = $value_3->krd_qty;
                        $atendido     = $atendido+$valcantidad;
                    }
                    
                    $sql_4 = "SELECT NVL(SUM(cantidad),0) AS krd_qty FROM kardex WHERE tip_movmto = 'I' AND documento in ('DV') AND serreq = '".$serie."' AND numreq = '".$numguia."' AND codigo = '".$codigo."'";
                    $query_4 = $this->dbase->query($sql_4);
                    $result_4 = $query_4->result();

                    $input = 0;
                    foreach ($result_4 as $key_4 => $value_4) {
                        $input = $value_4->krd_qty;
                        $atendido     = $atendido-$input;
                    }
        
                }
           
                $comprometido  = $solicitada - $atendido;
                
                
       
                $array = array(
                    'p_codigo'    => $codigo
                );
                $data = array(
                   'Stk_comp' => $comprometido
                );

                $this->dbase->where($array);
                $this->dbase->update('producto', $data);
            }


            //system("C:/xampp/htdocs/upd_stk_comprometido.bat");
        }else{
            $data = array(
               'chk' => 1
            );
      
            foreach($req_code as $value){
                $req = explode('-',$value);

                $array = array(
                    'tipdoc'    => 'RQ', 
                    'estrepdet' => 'S',
                    'codent'    => $this->entidad,
                    'nrodoc'    => $req[0],
                    'codpro'    => $req[1]
                );

                $this->db->where($array);
                $this->db->update($this->table_det, $data);
                
            }
        }
        
    }
    
    /*
     * DLANM
     */
    public function upd_Comprometido(){
        $date = date("m/d/Y", strtotime('-2 month'));
        if($this->entidad == '01'){
            
      
        $data = array(
                   'Stk_comp' => 0
        );
        $this->dbase->update('producto', $data);
         
        
        $sql = "SELECT gcodpro as req_prd_code,SUM(gcantidad) as  req_qty FROM requis WHERE fecemi>= CTOD('".$date."') AND tipo='R' AND User='X' and codot!='0001999' and sanull!=1 group BY gcodpro";
        $query = $this->dbase->query($sql);
        $result = $query->result();
  

            foreach ($result as $key => $value) {
                $codigo     = $value->req_prd_code;
                $solicitada = $value->req_qty;
                $atendido   =0;
                $sql_2 = "SELECT gserguia as req_serie ,gnumguia as req_number FROM requis WHERE fecemi>= CTOD('".$date."') AND tipo='R' AND User='X' and gcodpro='".$codigo."' and codot!='0001999' and sanull!=1 ORDER BY gnumguia";
                $query_2 = $this->dbase->query($sql_2);
                $result_2 = $query_2->result();
                
           
                foreach ($result_2 as $key_2 => $value_2) {
             
                    $serie     = $value_2->req_serie;
                    $numguia   = $value_2->req_number;
                    
                    $sql_3   = "SELECT NVL(sum(cantidad),0) as krd_qty FROM kardex WHERE documento NOT in ('AJ','DV','TF') AND tip_movmto='S' AND serreq='".$serie."' AND numreq='".$numguia."' and Codigo='".$codigo."'";
                    $query_3 = $this->dbase->query($sql_3);
                    $result_3 = $query_3->result();
                    
                    foreach ($result_3 as $key_3 => $value_3) {
                        $valcantidad  = $value_3->krd_qty;
                        $atendido     = $atendido+$valcantidad;
                    }
                    
                    $sql_4 = "SELECT NVL(SUM(cantidad),0) AS krd_qty FROM kardex WHERE tip_movmto = 'I' AND documento in ('DV') AND serreq = '".$serie."' AND numreq = '".$numguia."' AND codigo = '".$codigo."'";
                    $query_4 = $this->dbase->query($sql_4);
                    $result_4 = $query_4->result();

                    $input = 0;
                    foreach ($result_4 as $key_4 => $value_4) {
                        $input = $value_4->krd_qty;
                        $atendido     = $atendido-$input;
                    }
        
                }
           
                $comprometido  = $solicitada - $atendido;
                
                
       
                $array = array(
                    'p_codigo'    => $codigo
                );
                $data = array(
                   'Stk_comp' => $comprometido
                );

                $this->dbase->where($array);
                $this->dbase->update('producto', $data);
            }
        }else{
            
        }
    }
}
?>