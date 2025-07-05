<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Kardex_model extends CI_Model{
    var $entidad;
    var $table;
    
    public function __construct(){
        parent::__construct();
        $this->entidad = $this->session->userdata('entidad');
        $this->table   = "Almacen_mov";
        $this->table_det = "Almacen_mov_det";
        $this->table_dbf = "kardex";
    }
    
    public function listar($filter,$filter_not,$order_by='',$number_items='',$offset='')
    {
        if($this->entidad=='01'){
            $filtrofecha   = "";
            $filtrofechai  = "";
            $filtrofechaf  = "";
            $filtrotipomov = ""; 
            if(isset($filter->tipomov) && $filter->tipomov!='')  $filtrotipomov = "AND Tip_movmto='".$filter->tipomov."'";  
            if(isset($filter->fecha) && $filter->fecha!='')      $filtrofecha = "AND FecMov='".$filter->tipomov."'";  
            $cadena = "
                    select 
                    * 
                    from kardex 
                    where codalm='001'
                    ".$filtrotipomov."
                    ".$filtrofecha."
                    order by Fecha desc
                    ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->result();
        }
        elseif($this->entidad=='02'){
            $arrWhere  = array('CodEnt'=>$this->entidad);
            $this->db->select("*");
            $this->db->from($this->table,$number_items,$offset);
            $this->db->where($arrWhere);   
            if(isset($filter->fecha) && $filter->fecha!='')     $this->db->where('FecMov',$filter->fecha);
            if(isset($filter->fechai) && $filter->fechai!='')   $this->db->where('FecMov>=',$filter->fechai);
            if(isset($filter->fechaf) && $filter->fechaf!='')   $this->db->where('FecMov<=',$filter->fechaf);
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
            $filtrocodpro  = "";
            $filtrotipomov = "";
            $filtrofecha   = "";
            $filtrofechai  = "";
            $filtrofechaf  = "";            
            if(isset($filter->codproducto) && $filter->codproducto!='') $filtrocodpro  = "and Codigo='".$filter->codproducto."'";  
            if(isset($filter->tipomov) && $filter->tipomov!='')         $filtrotipomov = "and Tip_movmto='".$filter->tipomov."'";  
            if(isset($filter->fecha) && $filter->fecha!='')             $filtrofecha   = "and Fecha=CTOD('".date_dbf($filter->fecha)."')"; 
            if(isset($filter->fechai) && $filter->fechai!='')           $filtrofechai  = "and Fecha>=CTOD('".date_dbf($filter->fechai)."')"; 
            if(isset($filter->fechaf) && $filter->fechaf!='')           $filtrofechaf  = "and Fecha<=CTOD('".date_dbf($filter->fechaf)."')"; 
            $cadena = "
                    select 
                    * 
                    from kardex 
                    where codigo!=' '
                    ".$filtrocodpro." 
                    ".$filtrotipomov."
                    ".$filtrofecha."
                    ".$filtrofechai."
                    ".$filtrofechaf."
                    order by Fecha
                    ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->result();
        }
        elseif($this->entidad=='02'){
            $arrWhere  = array('CodEnt'=>$this->entidad,);
            $this->db->select(" *,
                                convert(varchar(10), Fec_Reg, 120) as fecha,
                                SUBSTRING(CodPro,1,2) as codalm,
                                codpro as codigo, 
                                tipmov as tip_movmto, 
                                Mo as moneda,
                                PrecUnit as precio,
                                Cantidad as cantidad,
                                Preprom as preprom,
                                tipdoc as documento,
                                seriedoc as serie,
                                nrodoc as numero,
                                '' as serreq,
                                nroreq as numreq,
                                Codot as codot,
                                seriedoc as sercom,
                                nrodoc as numcom,
                                '' as ot,
                                
                                ");
            $this->db->from($this->table_det,$number_items,$offset);
            $this->db->where($arrWhere);   
            if(isset($filter->codproducto) && $filter->codproducto!='')  $this->db->where('CodPro',$filter->codproducto);
            if(isset($filter->tipomov) && $filter->tipomov!='')          $this->db->where('TipMov',$filter->tipomov);
            if(isset($filter->fecha) && $filter->fecha!='')              $this->db->where('FecMov',$filter->fecha);
            if(isset($filter->fechai) && $filter->fechai!='')            $this->db->where('FecMov>=',$filter->fechai);
            if(isset($filter->fechaf) && $filter->fechaf!='')            $this->db->where('FecMov<=',$filter->fechaf);
            $query = $this->db->get();
            $resultado = array();
            if($query->num_rows>0){
                $resultado = $query->result();
            }            
        }
        return $resultado;
    }
    

  
    
    

    
        public function listar_detalle_kardex($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        if($this->entidad=='01'){
            $filtrocodpro  = "";
       //     $filtrotipomov = "";
            $filtroot   = "";
            $filtrofechai  = "";
            $filtrofechaf  = "";    
            $filtrotipomaterial="";
            $filtrotipomovimiento="";
     
            
            if(isset($filter->codproducto) && $filter->codproducto!='') $filtrocodpro  = "and codigo='".$filter->codproducto."'";  
            if(isset($filter->codtipomaterial) && $filter->codtipomaterial!='')  $filtrotipomaterial = "and Material='".$filter->codtipomaterial."'";
            if(isset($filter->codtipomovimiento) && $filter->codtipomovimiento!='')  $filtrotipomovimiento = "and tip_movmto='".$filter->codtipomovimiento."'";
           if(isset($filter->codot) && $filter->codot!='')  $filtroot = "and a.Codot='".$filter->codot."'";
           
        //        if(isset($filter->fecha) && $filter->fecha!='')             $filtrofecha   = "and Fecha=CTOD('".date_dbf($filter->fecha)."')"; 
            if(isset($filter->fechai) && $filter->fechai!='')           $filtrofechai  = "and Fecha>=CTOD('".$filter->fechai."')"; 
            if(isset($filter->fechaf) && $filter->fechaf!='')           $filtrofechaf  = "and Fecha<=CTOD('".$filter->fechaf."')"; 
            $cadena = "select 
                    a.*,b.p_descri,b.material , c.gcantidad
                    from kardex as a
                    INNER JOIN producto as b ON a.codigo=b.p_codigo
                    LEFT OUTER JOIN ordenc as c ON a.numoc=c.gnumguia and a.numreq=c.gnumreq and a.codigo=c.gcodpro
                    where a.codigo!=' '
                    
                    ".$filtrocodpro."
                    ".$filtrofechai."
                    ".$filtrofechaf."
                    ".$filtrotipomaterial."
                    ".$filtrotipomovimiento."
                    ".$filtroot."
                    order by b.p_descri, fecha
                    ";
            
               /* ".$filtrocodpro." 
                    ".$filtrotipomov."
                    ".$filtrofecha."*/      
            
            
            $query = $this->dbase->query($cadena);
            $resultado = $query->result();
            
       //     print_r($resultado);die;
        }
        elseif($this->entidad=='02'){
            $arrWhere  = array('CodEnt'=>$this->entidad);
            $this->db->select("*");
            $this->db->from($this->table,$number_items,$offset);
            $this->db->where($arrWhere);   
            if(isset($filter->codproducto) && $filter->codproducto!='')  $this->db->where('CodPro',$filter->codproducto);
            if(isset($filter->tipomov) && $filter->tipomov!='')          $this->db->where('TipMov',$filter->tipomov);
            if(isset($filter->fecha) && $filter->fecha!='')              $this->db->where('FecMov',$filter->fecha);
            if(isset($filter->fechai) && $filter->fechai!='')            $this->db->where('FecMov>=',$filter->fechai);
            if(isset($filter->fechaf) && $filter->fechaf!='')            $this->db->where('FecMov<=',$filter->fechaf);
            $query = $this->db->get();
            $resultado = array();
            if($query->num_rows>0){
                $resultado = $query->result();
            }            
        }
        return $resultado;
    }
    
    
    public function ultimo($filter,$filter_not,$order_by='',$number_items='',$offset='')
    {
        if($this->entidad=='01'){
            $filtrofecha   = "";
            $filtrofechai  = "";
            $filtrofechaf  = "";
            $filtrotipomov = ""; 
            if(isset($filter->tipomov) && $filter->tipomov!='')  $filtrotipomov = "AND Tip_movmto='".$filter->tipomov."'";  
            if(isset($filter->fecha) && $filter->fecha!='')      $filtrofecha = "AND FecMov='".$filter->tipomov."'";  
            $cadena = "
                    select 
                    * 
                    from kardex 
                    where codalm='001'
                    ".$filtrotipomov."
                    ".$filtrofecha."
                    order by Fecha desc
                    ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->result();
        }
        elseif($this->entidad=='02'){
            $arrWhere  = array('CodEnt'=>$this->entidad);
            $this->db->select("*");
            $this->db->from($this->table,$number_items,$offset);
            $this->db->where($arrWhere);   
            if(isset($filter->fecha) && $filter->fecha!='')     $this->db->where('FecMov',$filter->fecha);
            if(isset($filter->fechai) && $filter->fechai!='')   $this->db->where('FecMov>=',$filter->fechai);
            if(isset($filter->fechaf) && $filter->fechaf!='')   $this->db->where('FecMov<=',$filter->fechaf);
            $query = $this->db->get();
            $resultado = array();
            if($query->num_rows>0){
                $resultado = $query->result();
            }            
        }
        return $resultado;
    }    
    
    public function ultimo_detalle($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        if($this->entidad=='01'){
            $filtrocodpro  = "";
            $filtrotipomov = "";
            $filtrofecha   = "";
            $filtrofechai  = "";
            $filtrofechaf  = "";            
            if(isset($filter->codproducto) && $filter->codproducto!='') $filtrocodpro  = "and Codigo='".$filter->codproducto."'";  
            if(isset($filter->tipomov) && $filter->tipomov!='')         $filtrotipomov = "and Tip_movmto='".$filter->tipomov."'";  
            if(isset($filter->fecha) && $filter->fecha!='')              $this->db->where('Fecha',$filter->fecha);
            if(isset($filter->fechai) && $filter->fechai!='')            $this->db->where('Fecha>=',$filter->fechai);
            if(isset($filter->fechaf) && $filter->fechaf!='')            $this->db->where('Fecha<=',$filter->fechaf);            
            $cadena = "
                    select 
                    * 
                    from kardex 
                    where codalm='001'
                    ".$filtrocodpro." 
                    ".$filtrotipomov."
                    ".$filtrofecha."
                    ".$filtrofechai."
                    ".$filtrofechaf."
                    ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->last_row();  
        }
        elseif($this->entidad=='02'){
            $arrWhere  = array('CodEnt'=>$this->entidad);
            $this->db->select("*");
            $this->db->from($this->table_det,$number_items,$offset);
            $this->db->where($arrWhere);   
            if(isset($filter->codproducto) && $filter->codproducto!='')  $this->db->where('CodPro',$filter->codproducto);
            if(isset($filter->tipomov) && $filter->tipomov!='')          $this->db->where('TipMov',$filter->tipomov);
            if(isset($filter->fecha) && $filter->fecha!='')              $this->db->where('FecMov',$filter->fecha);
            if(isset($filter->fechai) && $filter->fechai!='')            $this->db->where('FecMov>=',$filter->fechai);
            if(isset($filter->fechaf) && $filter->fechaf!='')            $this->db->where('FecMov<=',$filter->fechaf);
            $query = $this->db->get();
            $resultado = array();
            if($query->num_rows>0){
                $resultado = $query->last_row();  
            }            
        }
        return $resultado;
    }    
    
    public function obtener($filter,$filter_not)
    {
        $arrWhere  = array('p.codent'=>$this->entidad);
        $this->db->select("*");
        $this->db->from("Pagos as p");
        $this->db->where($arrWhere);   
        if(isset($filter->numero) && $filter->numero!='')   $this->db->where('p.NroVoucher',$filter->numero);
        if(isset($filter->fecha) && $filter->fecha!='')     $this->db->where('p.FecPago',$filter->fecha);
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>1) exit('Existe mas de 1 resultado');    
        if($query->num_rows>0){
            $resultado = $query->row();
        }
        return $resultado;
    }
}
?>