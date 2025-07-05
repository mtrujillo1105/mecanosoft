<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tareo_model extends CI_Model{
    var $entidad;
    var $table;
    public function __construct(){
        parent::__construct();
        $this->entidad = $this->session->userdata('entidad');
        $this->table   = "tareo";
    }
    
    public function listar($codres,$dni,$fecha){
        $where = array("tareo.codent"=>$this->entidad,"tareo.codres"=>$codres,"tareo.dni"=>$dni,"tareo.fecha"=>$fecha,"tareo.item"=>1);
        $this->db->select('tareo.areaproduccion,tareo.cantidad,tareo.horas,tareo.descripcion,ot.nroOt,ot.dirOt,ot.codot');
        $this->db->from($this->table);
        $this->db->join('ot','ot.codent=tareo.codent and ot.codot=tareo.codot');
        $this->db->where($where);
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }    
    
    public function listar2($fecha,$fechafin,$codres,$codarea,$codot,$cargo){
        $where = array("tareo.codent"=>$this->entidad,"tareo.item"=>1);
        if(trim($fecha)!="")        $where = array_merge($where,array("tareo.fecha>="=>$fecha));
        if(trim($fechafin)!="")     $where = array_merge($where,array("tareo.fecha<="=>$fechafin));
//        if(trim($fecha)=="" || trim($fechafin)=="") 
//            $where = array("tareo.codent"=>$this->entidad,"tareo.item"=>1);
//        else
//            $where = array("tareo.codent"=>$this->entidad,"tareo.fecha>="=>$fecha,"tareo.fecha<="=>$fechafin,"tareo.item"=>1);
        if($codres!="000000")  $where = $where + array("responsable.codres" => $codres);
        if($codarea!="000000") $where = $where + array("tabla_m_detalle.Cod_Argumento" => $codarea);  
        if($cargo!="000000") $where = $where + array("tcargo.Cod_Argumento" => $cargo);  
        if(trim($codot)!="")   $where = $where + array("tareo.codot" => trim($codot));
        $this->db->select('convert(char,tareo.Fecha,103) as fecha2,responsable.nomper,tcargo.Des_larga as cargos,tabla_m_detalle.Des_Larga as areapro,tareo.descripcion,tareo.cantidad,ot.NroOt,tareo.horas,ot.codot,ot.tipot,tareo.fecha,tareo.codres,tareo.dni,tareo.monto');
        $this->db->from($this->table);
        $this->db->join('responsable','tareo.codent=responsable.codent and tareo.codres=responsable.codres','LEFT');
        $this->db->join('ot','tareo.codent=ot.codent and tareo.codot=ot.codot','LEFT');
        $this->db->join('tabla_m_detalle','tabla_m_detalle.cod_tabla="APRO" and tabla_m_detalle.codent = tareo.codent and tabla_m_detalle.Cod_Argumento = tareo.areaproduccion','LEFT');
        $this->db->join('tabla_m_detalle as tcargo','tcargo.cod_tabla="TCAR" and tcargo.codent=tareo.codent and tcargo.Cod_Argumento=responsable.cargo','LEFT');
        $this->db->where($where);
        if(trim($fecha)=="")  $this->db->order_by('tareo.fecha');
        $this->db->order_by('ot.nroOt');
        $this->db->order_by('responsable.nomper');
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }  
    
    /*tAREO rOGER*/
    public function listar_tareo($filter,$order_by,$number_items='',$offset=''){
        if(isset($filter->fecha) && trim($filter->fecha)!="") $arrWhere = array("tareo.codent"=>$this->entidad,"tareo.fecha"=>$fecha,"tareo.item"=>1);
        else $arrWhere = array("tareo.codent"=>$this->entidad,"tareo.item"=>1);
        
        if(isset($filter->fechai) && $filter->fechai!='')                   $arrWhere = array_merge($arrWhere,array("tareo.fecha>="=>$filter->fechai));
        if(isset($filter->fechaf) && $filter->fechaf!='')                   $arrWhere = array_merge($arrWhere,array("tareo.fecha<="=>$filter->fechaf));
        if(isset($filter->flagtareado) && $filter->flagtareado!='000000')   $arrWhere = array_merge($arrWhere,array("tareo.flgPlanilla"=>$filter->flagtareado));
        $arrWhere = array_merge($arrWhere,array("remuneraciones.codent"=>'01'));
        $this->db->select('dbo.getTipoCambioV(tareo.Fecha,"'.$this->entidad.'") as tcambio,convert(char,tareo.Fecha,103) as fecha2,responsable.nomper,tabla_m_detalle.Des_Larga as areapro,tareo.descripcion,ot.NroOt,tareo.horas,ot.codot,ot.tipot,tareo.fecha,tareo.horas*(remuneraciones.Jornal/8) as simple,(tareo.horas*(remuneraciones.Jornal/8)+remuneraciones.asigfam +remuneraciones.a_essalud+a_sctr+a_sctr1+a_sctr2+senati)  as real,tareo.flgPlanilla,responsable.codres');
        $this->db->from($this->table);
        $this->db->join('responsable','responsable.codent=tareo.codent and responsable.codres=tareo.codres');
        $this->db->join('ot','ot.codent=tareo.codent and ot.codot=tareo.codot');
        $this->db->join('tabla_m_detalle',"tabla_m_detalle.cod_tabla='APRO' and tabla_m_detalle.codent=tareo.codent and tabla_m_detalle.Cod_Argumento=tareo.areaproduccion");
        $this->db->join('remuneraciones',"remuneraciones.codres=tareo.codres and remuneraciones.fec_labor=tareo.fecha");      
        $this->db->where($arrWhere);
        if(isset($filter->codot) && $filter->codot!=''){
          if(is_array($filter->codot) && count($filter->codot)>0){
            $this->db->where_in("tareo.codot",$filter->codot); 
          }
          else{
            $this->db->where(array("tareo.codot"=>$filter->codot));
          }  
        }
        if(isset($order_by) && count($order_by)>0){
          foreach($order_by as $indice=>$value){
          $this->db->order_by($indice,$value); }}   
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
        $resultado = $query->result();
        }    
        return $resultado;
    }
    
    /*Lista el detalle de las horas hombre valorizadas por OT por área de producción*/
    public function listarg($filter,$order_by,$number_items='',$offset=''){
        if(isset($filter->fecha) && trim($filter->fecha)!="") 
            $arrWhere = array("tareo.codent"=>$this->entidad,"tareo.fecha"=>$fecha,"tareo.item"=>1);
        else
            $arrWhere = array("tareo.codent"=>$this->entidad,"tareo.item"=>1);
        if(isset($filter->fechai) && $filter->fechai!='')         $arrWhere = array_merge($arrWhere,array("tareo.fecha>="=>$filter->fechai));
        if(isset($filter->fechaf) && $filter->fechaf!='')         $arrWhere = array_merge($arrWhere,array("tareo.fecha<="=>$filter->fechaf));
        if(isset($filter->codres) && $filter->codres!='000000')   $arrWhere = array_merge($arrWhere,array("responsable.codres"=>$filter->codres));
        if(isset($filter->codarea) && $filter->codarea!='000000') $arrWhere = array_merge($arrWhere,array("tabla_m_detalle.Cod_Argumento"=>$filter->codarea));
        if(isset($filter->flagtareado) && $filter->flagtareado!='000000') $arrWhere = array_merge($arrWhere,array("tareo.flgPlanilla"=>$filter->flagtareado));
        $this->db->select('dbo.getTipoCambioV(tareo.Fecha,"'.$this->entidad.'") as tcambio,convert(char,tareo.Fecha,103) as fecha2,responsable.nomper,tabla_m_detalle.Des_Larga as areapro,tareo.descripcion,tareo.cantidad as horas,ot.NroOt,tareo.horas,ot.codot,ot.tipot,tareo.fecha,tareo.horas*(responsable.Jornal/8) as simple,tareo.monto as real,tareo.flgPlanilla,responsable.codres');
        $this->db->from($this->table);
        $this->db->join('responsable','responsable.codent=tareo.codent and responsable.codres=tareo.codres');
        $this->db->join('ot','ot.codent=tareo.codent and ot.codot=tareo.codot');
        $this->db->join('tabla_m_detalle',"tabla_m_detalle.cod_tabla='APRO' and tabla_m_detalle.codent=tareo.codent and tabla_m_detalle.Cod_Argumento=tareo.areaproduccion");
        $this->db->where($arrWhere);
        if(isset($filter->codot) && $filter->codot!=''){
            if(is_array($filter->codot) && count($filter->codot)>0){
                $this->db->where_in("tareo.codot",$filter->codot);
            }
            else{
                $this->db->where(array("tareo.codot"=>$filter->codot));
            }
        }
//        $this->db->order_by('ot.nroOt');
//        $this->db->order_by('responsable.nomper');
        if(isset($order_by) && count($order_by)>0){
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
    
    /*Lista los totales por OT de las horas hombre valorizadas*/
    public function listar_totales($filter,$filter_not){
        if(isset($filter->group_by) && count($filter->group_by)>0 && $filter->group_by!="")     $campos = implode($filter->group_by);
        $arrWhere  = array("a.codent"=>$this->entidad);
        $this->db->select('
            '.$campos.',sum(a.horas*(b.Jornal/8)) as simple,
            sum(a.monto) as real,
            sum(a.horas*(b.Jornal/8)/dbo.getTipoCambioV(a.Fecha,"'.$this->entidad.'")) as simpleD,            
            sum(a.monto/dbo.getTipoCambioV(a.Fecha,"'.$this->entidad.'")) as realD,
            sum(a.horas) as horas
            ');
        $this->db->from("$this->table as a");
        $this->db->join('responsable as b','b.codres=a.codres and b.codent=a.codent','left');
        $this->db->join('ot as c','c.codot=a.codot and c.codent=a.codent','left');
        $this->db->where($arrWhere);
        if(isset($filter->dni) && $filter->dni!='')       $this->db->where('a.dni',$filter->dni);
        if(isset($filter->codot) && $filter->codot!='')   $this->db->where('a.codot',$filter->codot);
        if(isset($filter->fecha) && $filter->fecha!='')   $this->db->where('a.Fecha',$filter->fecha);
        if(isset($filter->fechai) && $filter->fechai!='') $this->db->where('a.Fecha>=',$filter->fechai);
        if(isset($filter->fechaf) && $filter->fechaf!='') $this->db->where('a.Fecha<=',$filter->fechaf);
        if(isset($filter->group_by) && count($filter->group_by)>0 && $filter->group_by!="") $this->db->group_by($filter->group_by);
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>1)   $resultado = $query->result();
        if($query->num_rows==1)  $resultado = $query->row();
        return $resultado;        
    }

    public function obtener_ot($codres,$dni,$fecha)
    {
        $where = array("tareo.codent"=>$this->entidad,"tareo.codres"=>$codres,"tareo.dni"=>$dni,"tareo.fecha"=>$fecha,"tareo.item"=>1);
        $this->db->select('nroOt');
        $this->db->from($this->table);
        $this->db->join('ot','ot.codent=tareo.codent and ot.codot=tareo.codot');
        $this->db->where($where);
        $this->db->group_by('nroOt');
        $query = $this->db->get();
        $resultado = new stdClass();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    } 
    
    public function insertar(stdClass $filter = null){
        $this->db->insert($this->table,(array)$filter);
    }
	
    public function modificar($codres,$dni,$fecha,$codot,$area,$filter)
    {
        $where = array("fecha"=>$fecha,"dni"=>$dni,"codres"=>$codres,"item"=>'1',"CodEnt"=>$this->entidad,"codot"=>$codot,"areaproduccion"=>$area);
        $this->db->where($where);
        $this->db->update($this->table,(array)$filter);
    }
	
    public function eliminar($where)
    {   
        $this->db->delete($this->table,$where);
    }    
    
     public function obtener_HorasJornal($codot){
        $cadena = "  
        select 
        t.codOt,  
        t.areaproduccion,
        sum (t.horas) as horas, 
        sum((r.Jornal/8)*t.horas)as pago,
        sum(monto) as pago_real
        from tareo t 
        inner join responsable r on r.codres = t.CodRes and t.CodEnt = r.CodEnt
        inner join ot o on t.CodOt = o.CodOt and t.CodEnt = o.CodEnt
        where t.codot='".$codot."'
        and t.codent='01'
        group by t.codOt, t.areaproduccion 
        ";
        $query = $this->db->query($cadena);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;  
    }   
    /*
     * Monto por CC - Mano de obra indirecta
     */
    public function getMonto($fecha_ini,$fecha_fin){
        $sql = "
            select ot.codot,ot.dirot,ot.nroot,sum(monto) as monto from tareo as tar
            left join ot on
            tar.codot = ot.codot and ot.codent = '01'
            where ot.nroot like 'CC-%' and tar.fecha between '".$fecha_ini."' and '".$fecha_fin."'
            group by ot.dirot,ot.nroot,ot.codot
            ";
        $query = $this->db->query($sql);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;  
    }
}
?>