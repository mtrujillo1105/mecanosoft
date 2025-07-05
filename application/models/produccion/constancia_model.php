<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class constancia_model extends CI_Model{
    var $entidad;
    var $table;
    public function __construct()
    {
        parent::__construct();
        $this->entidad   = "02";
        $this->table     = utf8_decode("reposición");
        $this->table_det = utf8_decode("reposición_det");
    }
	
    public function insertar(stdClass $filter = null)
    {

    }
	
    public function modificar($id,$filter)
    {
       
    }
	
    public function eliminar($id)
    {
       
    }
	
    public function listar($filter,$filter_not,$order_by="",$number_items='',$offset=''){
        $arrWhere  = array('rep.codent'=>$this->entidad,"rep.tipdoc"=>'CR');  
        if(isset($filter->codot) && $filter->codot!='')                $arrWhere = array_merge($arrWhere,array("CodOt"=>$filter->codot));
        if(isset($filter->fechai) && $filter->fechai!='')              $arrWhere = array_merge($arrWhere,array("FecRep>="=>$filter->fechai));
        if(isset($filter->fechaf) && $filter->fechaf!='')              $arrWhere = array_merge($arrWhere,array("FecRep<="=>$filter->fechaf));
        if(isset($filter->fecha) && $filter->fecha!='')                $arrWhere = array_merge($arrWhere,array("FecRep"=>$filter->fecha));        
        $this->db->select('mat.Des_larga as material,d.Des_larga as motivo,*,convert(char,FecRep,103) as fecha');                                                                                                                                                     
        $this->db->from($this->table." as rep",$number_items,$offset);
        $this->db->join('tabla_m_detalle as d','rep.FPagoRep = d.cod_argumento and rep.codent = d.codent and cod_tabla = "TMOS"','left');
        $this->db->join('view_tipMaterial as mat','rep.TipoMat = mat.Cod_Argumento and rep.codent = mat.CodEnt','left');
        
        
        $this->db->where($arrWhere); 
        if(isset($order_by) && count($order_by)>0 && $order_by!=""){
            foreach($order_by as $indice=>$value){
                $this->db->order_by($indice,$value);
            }
        }          
        $query = $this->db->get();
        
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;          
    }
    
    /*Lista el detalle de las constancias de recepción para una OT*/
    public function listar_detalle($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        $arrWhere  = array('repdet.codent'=>$this->entidad,"rep.tipdoc"=>'CR');    
        if(isset($filter->nroot) && $filter->nroot!='')            $arrWhere = array_merge($arrWhere,array("substring(ltrim(rtrim(repdet.DesRepDet)),4,9)"=>$filter->nroot));       
        if(isset($filter->estado) && $filter->estado!='')          $arrWhere = array_merge($arrWhere,array("EstRep"=>$filter->estado));
        if(isset($filter_not->estado) && $filter_not->estado!='')  $arrWhere = array_merge($arrWhere,array("EstRep!="=>$filter_not->estado));
        if(isset($filter->fechai) && $filter->fechai!='')          $arrWhere = array_merge($arrWhere,array("rep.FecRep>="=>$filter->fechai));
        if(isset($filter->fechaf) && $filter->fechaf!='')          $arrWhere = array_merge($arrWhere,array("rep.FecRep<"=>$filter->fechaf));
        if(isset($filter->fecha) && $filter->fecha!='')            $arrWhere = array_merge($arrWhere,array("rep.FecRep"=>$filter->fecha));         
        $this->db->select('*,convert(char,rep.FecRep,103) as fecha,repdet.peso as pesodet');                                                                                                                                                     
        $this->db->from($this->table_det." as repdet",$number_items,$offset);
        $this->db->join($this->table." as rep",'rep.SerieDoc=repdet.SerieDoc and rep.NroDoc=repdet.NroDoc');
        $this->db->where($arrWhere); 
        if(isset($filter->codot) && $filter->codot!=''){
            if(is_array($filter->codot) && count($filter->codot)>0){
                $this->db->where_in('nrodref',$filter->codot);
            }
            else{
                $this->db->where('nrodref',$filter->codot);
            }
        }         
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;  
    }
    
    /*Listas la suma de las constancias de recepción(CR) por OT*/
    public function listar_totales($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        $arrWhere  = array('repdet.codent'=>$this->entidad,"rep.tipdoc"=>'CR');  
        //if(isset($filter->nroot) && $filter->nroot!='')            $arrWhere = array_merge($arrWhere,array("substring(ltrim(rtrim(repdet.DesRepDet)),4,9)"=>$filter->nroot));
        if(isset($filter->codot) && $filter->codot!='')            $arrWhere = array_merge($arrWhere,array("repdet.nrodref"=>$filter->codot));
        if(isset($filter->estado) && $filter->estado!='')          $arrWhere = array_merge($arrWhere,array("rep.EstRep"=>$filter->estado));
        if(isset($filter_not->estado) && $filter_not->estado!='')  $arrWhere = array_merge($arrWhere,array("rep.EstRep!="=>$filter_not->estado));        
        if(isset($filter->fechai) && $filter->fechai!='')          $arrWhere = array_merge($arrWhere,array("rep.FecRep>="=>$filter->fechai));
        if(isset($filter->fechaf) && $filter->fechaf!='')          $arrWhere = array_merge($arrWhere,array("rep.FecRep<="=>$filter->fechaf));
        if(isset($filter->fecha) && $filter->fecha!='')            $arrWhere = array_merge($arrWhere,array("rep.FecRep"=>$filter->fecha));         
        $this->db->select('sum(repdet.peso) as p_galv,sum(case when (ot.estot=3) then (repdet.Peso*otdet.Precio_ot) else (repdet.Peso*otdet.Precio_ot/dbo.getTipoCambioV(ot.FecOt,"02")) end) as imp_dolares,sum(case when (ot.estot=2) then (repdet.Peso*otdet.Precio_ot) else (repdet.Peso*otdet.Precio_ot*dbo.getTipoCambioV(ot.FecOt,"02")) end) as imp_soles');                                                                                                                                                     
        $this->db->from($this->table_det." as repdet",$number_items,$offset);
        $this->db->join($this->table." as rep",'rep.SerieDoc=repdet.SerieDoc and rep.NroDoc=repdet.NroDoc');
        $this->db->join("otdetalle as otdet","otdet.codent=rep.codent and otdet.codot=rep.codot and otdet.oc=rep.SerieDoc+'-'+rep.NroDoc","left");
        $this->db->join("ot as ot","ot.codot=otdet.codot and ot.codent=otdet.codent","left");
        $this->db->where($arrWhere); 
        $this->db->group_by("repdet.nrodref");
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->row();
        }
        return $resultado;  
    } 
    
    public function listarServiciosOS($fecha1,$fecha2,$filtroTipoOt,$filtrocodcli,$filter=''){
       $filtroTipoOt = "";
       $filtrocodcli = "";
       if(isset($filter->tipot) && $filter->tipot!='000')      $filtroTipoOt = " and ot.tipot='".$filter->tipot."' ";
       if(isset($filter->codcli) && $filter->codcli!='000')    $filtrocodcli = " and ot.codcli='".$filter->codcli."'";  
       if(isset($filter->fechai) && $filter->fechai!='')       $fecha1 = $filter->fechai;
       if(isset($filter->fechaf) && $filter->fechaf!='')       $fecha2 = $filter->fechaf;
       $cadena = "
            select 
            ot.CodOt,
            ot.nroot,
            convert(varchar,ot.fecot,103) as FecOt,
            convert(varchar,rep.FecRep,103) as FecRep,
            ot.codcli,
            cli.razcli,
            rep.TipoMat ,
	    repdet.DesRepDet as material,
            mat.Des_Larga as clasificacion,
            rep.MtoIgv as nroPiezas,
            rep.SerieDoc,
            rep.NroDoc,
            rep.SerieDocRef,
            rep.NroDocRef,
            rep.EstRep,
            ot.estot,
	    rep.obsRep,
	    repdet.CantSolRep,
	    repdet.Peso,
            convert(varchar,rep.FecDes,103) as FecDes,
            convert(varchar,ot.FteOt,103) as FecEst
            from Ot as ot
            inner join clientes as cli on (cli.codent=ot.codent and cli.codcli=ot.codcli)
            left join view_constancia as rep on (rep.codot=ot.codot and rep.CodEnt=ot.codent)
            left join view_constancia_det as repdet on (repdet.SerieDoc=rep.SerieDoc and repdet.NroDoc=rep.NroDoc)	
            left join view_tipMaterial as mat on (mat.Cod_Argumento=rep.TipoMat and mat.CodEnt=rep.codent)
            where Ot.codent='".$this->entidad."'
            ".$filtroTipoOt."
            ".$filtrocodcli."
            and rep.fecrep between '".$fecha1."' and '".$fecha2."' 			
            order by ot.nroot	
            ";
        $cadena = "
            select 
            rep.CodOt,
            ot.nroot,
            convert(varchar,ot.fecot,103) as FecOt,
            convert(varchar,rep.FecRep,103) as FecRep,
            ot.codcli,
            cli.razcli,
            rep.TipoMat,
            repdet.DesRepDet as material,
            mat.Des_Larga as clasificacion,
            rep.MtoIgv as nroPiezas,
            rep.SerieDoc,
            rep.NroDoc,
            rep.SerieDocRef,
            rep.NroDocRef,
            rep.EstRep,
            ot.estot,
            rep.obsRep,
            repdet.CantSolRep as CantSolRep,
            repdet.Peso as Peso,
            convert(varchar,rep.FecDes,103) as FecDes,
            convert(varchar,ot.FteOt,103) as FecEst 
            from view_constancia as rep
            left join view_constancia_det as repdet on (repdet.SerieDoc=rep.SerieDoc and repdet.NroDoc=rep.NroDoc)
            left join view_tipMaterial as mat on (mat.Cod_Argumento=rep.TipoMat and mat.CodEnt=rep.codent)
            left join ot as ot on (ot.nroot=rep.AccionRep and ot.codent=rep.codent)
            left join clientes as cli on (cli.codent=rep.codent and cli.codcli=ot.codcli)
            where rep.codent='".$this->entidad."'
            and rep.EstRep!='A'
            and rep.fecrep between '".$fecha1."' and '".$fecha2."' 	
            ".$filtroTipoOt."
            ".$filtrocodcli."
            order by rep.AccionRep desc
        ";
        $query = $this->db->query($cadena);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;  
    }
    
    
    
    
    public function listar_guias($codot)
    {
       $cadena = "
        select serieDcto,
        nroDcto,
        convert(varchar,fecdcto,103) as fdcto,
        convert(varchar,fec_reg,103) as freg
        from view_guiarem
        where codent='".$this->entidad."' 
        and codot='".$codot."'
            ";
        
        $query = $this->db->query($cadena);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;  
    }
    
    public function listar_precioOt($codot,$nroConsRecep)
    {
       $cadena = "
                   select Precio_ot, Tmat  
                   from otdetalle 
                   where oc='".$nroConsRecep."' and codot='".$codot."' 
                ";
        
        $query = $this->db->query($cadena);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;  
    }
    

    
    
    
     public function listar_fpagoOt0($codot)
    {
       $cadena = "
                    select fpaOt 
                    from otdetalle 
                    where codent='".$this->entidad."' 
                    AND codOt='".$codot."'
                ";
        
        $query = $this->db->query($cadena);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;  
    }  
    
    
    
    
     public function listar_fpagoOt1($fpago0)
    {
       $cadena = "
                    select Des_Larga 
                    from Tabla_M_detalle 
                    WHERE cod_tabla='TFOR' 
                    and  codent='".$this->entidad."' 
                    and Cod_Argumento='".$fpago0."'

                ";
        
        $query = $this->db->query($cadena);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;  
    } 
    
    
   
}
?>