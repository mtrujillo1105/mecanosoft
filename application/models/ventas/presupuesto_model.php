<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Presupuesto_model extends CI_Model{
    var $entidad;
    var $table;
    
    public function __construct(){
        parent::__construct();
        $this->entidad   = $this->session->userdata('entidad');
        $this->table     = "presupuesto";
        $this->table_det = "presupuesto_det";
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
	
    public function listar($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        $arrWhere  = array('pre.codent'=>$this->entidad);
        if(isset($filter->fechai) && $filter->fechai!='')           $arrWhere = array_merge($arrWhere,array("pre.FecDoc>="=>$filter->fechai));
        if(isset($filter->fechaf) && $filter->fechaf!='')           $arrWhere = array_merge($arrWhere,array("pre.FecDoc<="=>$filter->fechaf));
        if(isset($filter->fecha) && $filter->fecha!='')             $arrWhere = array_merge($arrWhere,array("pre.FecDoc"=>$filter->fecha));
        if(isset($filter->codcliente) && $filter->codcliente!='000000')   $arrWhere = array_merge($arrWhere,array("pre.CodCli"=>$filter->codcliente));
        if(isset($filter->estado) && $filter->estado!='')           $arrWhere = array_merge($arrWhere,array("pre.Estado"=>$filter->estado));
        if(isset($filter->codres) && $filter->codres!='')           $arrWhere = array_merge($arrWhere,array("pre.CodRes "=>$filter->codres));
        if(isset($filter->codproyecto) && $filter->codproyecto!='000') $arrWhere = array_merge($arrWhere,array("pre.CodProyecto "=>$filter->codproyecto));
        $this->db->select('convert(varchar,pre.FecDoc,103) as Fecha,*');
        $this->db->from("presupuesto as pre");
        $this->db->join('Clientes as cli','cli.codent=pre.codent and cli.CodCli=pre.CodCli','inner');
        $this->db->where($arrWhere);
        if($order_by!="" && count($order_by)>0){
            foreach($order_by as $indice=>$value){
                $this->db->order_by($indice,$value);
            }
        }  
        $this->db->limit($offset,$number_items);
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }
	
    public function listar_detalle($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        $arrWhere  = array('presdet.codent'=>$this->entidad);
        if(isset($filter->fechai) && $filter->fechai!='')           $arrWhere = array_merge($arrWhere,array("presdet.FecDoc>="=>$filter->fechai));
        if(isset($filter->fechaf) && $filter->fechaf!='')           $arrWhere = array_merge($arrWhere,array("presdet.FecDoc<="=>$filter->fechaf));
        if(isset($filter->fecha) && $filter->fecha!='')             $arrWhere = array_merge($arrWhere,array("presdet.FecDoc"=>$filter->fecha));
        if(isset($filter->codpresupuesto) && $filter->codpresupuesto!='')   $arrWhere = array_merge($arrWhere,array("presdet.CodPresupuesto"=>$filter->codpresupuesto));
        if(isset($filter->estado) && $filter->estado!='')           $arrWhere = array_merge($arrWhere,array("presdet.Estado"=>$filter->estado));
        if(isset($filter->tipo) && $filter->tipo!='')               $arrWhere = array_merge($arrWhere,array("presdet.Tipo "=>$filter->tipo));
        $this->db->select('*,convert(varchar,presdet.FecDoc,103) as Fecha');
        $this->db->from("presupuesto_det as presdet",$number_items,$offset);
        $this->db->where($arrWhere);
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }    
    
    public function obtener($filter,$filter_not){
        $arrWhere  = array('codent'=>$this->entidad);
        if(isset($filter->estado) && $filter->estado!='')                 $arrWhere = array_merge($arrWhere,array("pre.Estado"=>$filter->estado));
        if(isset($filter->codpresupuesto) && $filter->codpresupuesto!='') $arrWhere = array_merge($arrWhere,array("pre.CodPresupuesto"=>$filter->codpresupuesto));
        if(isset($filter->maximo) && $filter->maximo!=''){
            $this->db->select_max("".$filter->maximo."");
        }
        else{
            $this->db->select('*,convert(varchar,pre.FecDoc,103) as Fecha');
        }
        $query = $this->db->where($arrWhere)->get("presupuesto as pre");
        $resultado = new stdClass();
        if($query->num_rows>1) exit('Existe mas de 1 resultado');
        if($query->num_rows==1){
            $resultado = $query->row();
        }
        return $resultado;
    }
	
    public function obtener_detalle($filter,$filter_not){
        $arrWhere  = array('codent'=>$this->entidad);
        if(isset($filter->estado) && $filter->estado!='')                         $arrWhere = array_merge($arrWhere,array("Estado"=>$filter->estado));
        if(isset($filter->codpresupuesto) && trim($filter->codpresupuesto)!='')   $arrWhere = array_merge($arrWhere,array("CodPresupuesto"=>$filter->codpresupuesto));
        if(isset($filter->codtipoproducto) && trim($filter->codtipoproducto)!='') $arrWhere = array_merge($arrWhere,array("Tipo"=>$filter->codtipoproducto));
        $this->db->select('*,convert(varchar,FecDoc,103) as Fecha');
        $query = $this->db->where($arrWhere)->get($this->table_det);
        $resultado = new stdClass();
        if($query->num_rows>1) exit('Existe mas de 1 resultado');
        if($query->num_rows==1){
            $resultado = $query->row();
        }
        return $resultado;
    }    
    
    public function insertar(stdClass $filter = null){
        $this->db->insert($this->table,(array)$filter);
    }
    
    public function insertar_detalle(stdClass $filter = null){
        $this->db->insert("presupuesto_det",(array)$filter);
    }    
	
    public function modificar($id,$filter){
        $arrWhere  = array('CodEnt'=>$this->entidad,'CodPresupuesto'=>$id);
        $this->db->where($arrWhere);
        $this->db->update($this->table,(array)$filter);
    }

    public function modificar_detalle($where,$filter){
        $arrWhere  = array('CodEnt'=>$this->entidad);
        if(isset($where->codpresupuesto) && $where->codpresupuesto!='')   $arrWhere = array_merge($arrWhere,array("CodPresupuesto"=>$where->codpresupuesto));
        if(isset($where->tipo) && $where->tipo!='')                       $arrWhere = array_merge($arrWhere,array("Tipo"=>$where->tipo));
        $this->db->where($arrWhere);
        $this->db->update($this->table_det,(array)$filter);
    }    
    
    public function eliminar($id){
        $this->db->delete($this->table,array('CodPresupuesto' => $id));
    }

    public function eliminar_detalle($id){
        $this->db->delete($this->table_det,array('CodPresupuesto' => $id));
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
    
    
    
    
    
    
    
    
   
    
     public function listar_documentos1($tipdcto,$fecha1,$fecha2,$filtrocodcli){
                $cadena  = "
                        select
                        doc.seriedcto,
                        doc.nrodcto,
                        cli.razcli,
                        det.detalle,
                        ot.nroot,
                        convert(varchar,doc.fecdcto,103) as FecDcto,
                        convert(varchar,doc.fecvcto,103) as FecVcto,
                        fp.Des_larga,
                        det.canpro, 
                        det.preuni,
                        det.total,
                        (det.total*doc.impuesto/100) as igv,
                        doc.tcambio,
                        doc.mndcto,
                        doc.estdcto,
                        ot.peso
                        from documentos as doc
                        inner join view_fPago as fp on (fp.codent=doc.codent and fp.Cod_Argumento=doc.fpago)
                        left join detalle as det on (det.TipDcto=doc.tipdcto and det.SerieDcto=doc.seriedcto and det.NroDcto=doc.nrodcto)
                        left join ot as ot on (ot.codot=det.codot and ot.codent=det.codent)
                        left join clientes as cli on (cli.codcli=ot.codcli and cli.codent=ot.codent)
                        where det.codent='".$this->entidad."' 
                        and det.codot!=''
                        and doc.tipdcto='".$tipdcto."'
                        and doc.fecdcto between '".$fecha1."' and '".$fecha2."'
                        ".$filtrocodcli."
                        order by doc.seriedcto,doc.nrodcto    
                ";    

        $query = $this->db->query($cadena);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;  
    }  
    
    
        
     public function listar_documentos2($tipdcto,$fecha1,$fecha2){

                $cadena  = "
                        select
                        doc.seriedcto, 
                        doc.nrodcto,
                        cli.razcli,
                        doc.Observa as detalle,
                        '' as nroot,
                        convert(varchar,doc.fecdcto,103) as FecDcto,
                        convert(varchar,doc.fecvcto,103) as FecVcto,
                        fp.Des_larga,
                        '' as canpro,
                        '' as preuni,
                        doc.subtotal as total,
                        doc.igv,
                        doc.tcambio,
                        doc.mndcto,
                        doc.estdcto,
                        '' as peso
                        from documentos as doc
                        inner join clientes as cli on (cli.codent=doc.codent and cli.codcli=doc.codcli)
                        inner join view_fPago as fp on (fp.codent=doc.codent and fp.Cod_Argumento=doc.fpago)
                        where doc.codent='".$this->entidad."'
                        and doc.tipdcto='".$tipdcto."'
                        and doc.fecdcto between '".$fecha1."' and '".$fecha2."' 
                        order by doc.seriedcto,doc.nrodcto    
                ";  
            
        $query = $this->db->query($cadena);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;  
    }  
    
    
    
    
        
     public function listar_NroOts($serie,$numdoc,$tipdcto){

       $cadena="
                select 
                ot.nroot
                from detalle as det 
                inner join ot as ot on (ot.codent=det.codent and ot.codot=det.codot)
                where det.codent='".$this->entidad."' 
                and det.seriedcto='".$serie."' 
                and det.nrodcto='".$numdoc."' 
                and det.tipdcto='".$tipdcto."'
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