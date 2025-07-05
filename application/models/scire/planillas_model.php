<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Planillas_model extends CI_Model {
    
    public $entidad;
    public $table  = 'Calculo';
    
    public function __construct() {
        parent::__construct();
        $this->scire = $this->load->database('scire',TRUE);
        $this->entidad = $this->session->userdata('entidad');
    }

    public function getDetallePlanilla($filter,$filternot = "") {
        $where = array("P.Categoria2_id" => $this->entidad);
        if(isset($filter->planilla) && $filter->planilla != '')         $where = array_merge($where, array("p.Planilla_Id" => $filter->planilla));
        if(isset($filter->periodo) && $filter->periodo != '')           $where = array_merge($where, array("k.Periodo_Id" => $filter->periodo));
        if(isset($filter->persona) && $filter->persona != '')           $where = array_merge($where, array("p.Personal_Id" => $filter->persona));
        if(isset($filter->concepto) && $filter->concepto != '')         $where = array_merge($where, array("k.Concepto_Id" => $filter->concepto));
        if(isset($filter->proceso) && $filter->proceso != '')           $where = array_merge($where, array("k.Proceso_Id" => $filter->proceso));
        if(isset($filter->ccosto) && $filter->ccosto != '')             $where = array_merge($where, array("p.Ccosto_Id" => $filter->ccosto));
        if(isset($filter->ccosto_conta) && $filter->ccosto_conta != '') $where = array_merge($where, array("cc.Codigo_Auxiliar2" => $filter->ccosto_conta));
        if(isset($filter->proceso) && isset($filter->planilla)){
            switch($filter->planilla.$filter->proceso){
                case '0101'://Empleado - mensual
                    $basico           = "000052"; $dsemanal         = "000935"; $no_tributario      = "001168"; $hextra        = "001169"; $onp_fondo        = "000079"; $afp_fondo          = "000085";
                    $afp_com          = "000083"; $afp_pri          = "000084"; $prestamos          = "000514"; $comedor       = "001166"; $sobretiempo      = "000059"; $essalud            = "000075";
                    $senati           = "000592"; $sctr_salud       = "000593"; $sctr_pension       = "000837"; $asignacion    = "000056"; $bonificacion     = "001126"; $tardanza           = "000945";
                    $adelanto         = "000218"; $retencion        = "000131"; $monto_tardanza     = "000963"; $reintegro     = "000581"; $hdoble           = "001173"; $hora_extra         = "";
                    $reintegro_afecto = "";       $dscto_adic       = "001111"; $hora_extra_doble   = "";       $dscto_varios  = "";       $ing_varios       = "";       $reintegro_inafecto = "001176";
                    $movilidad        = "000093"; $viaticos         = "001240"; $afp_com_mixta      = "001222"; $vale_alimento = "";       $permiso_sin_goce = "";       $dscto_eps          = "000897";
                    $gratificacion    = "";       $bon_extra_ley    = "";
                    break;
                case '0102'://Empleado - quincenal
                    $basico           = "000217"; $dsemanal         = "";       $no_tributario = "001159"; $hextra           = "";       $onp_fondo        = "";       $afp_fondo     = "";
                    $afp_com          = "";       $afp_pri          = "";       $prestamos     = "000513"; $comedor          = "";       $sobretiempo      = "";       $essalud       = "";
                    $senati           = "";       $sctr_salud       = "";       $sctr_pension  = "";       $asignacion       = "";       $bonificacion     = "001236"; $tardanza      = "";
                    $adelanto         = "";       $retencion        = "";       $monto_tardanza = "";      $reintegro        = "001215"; $hdoble           = "";       $hora_extra        = "";
                    $reintegro_afecto = "";       $dscto_adic       = "001230"; $hora_extra_doble = "";    $dscto_varios     = "";       $ing_varios       = "";       $reintegro_inafecto = "";
                    $movilidad        = "000093"; $viaticos         = "001240"; $afp_com_mixta      = "";  $vale_alimento    = "";       $permiso_sin_goce = "001127"; $dscto_eps          = "";
                    $gratificacion    = "";       $bon_extra_ley    = "";
                    break;                
                case '0201'://Obrero - semanal
                    $basico           = "000052"; $dsemanal    = "000935"; $no_tributario      = "001168"; $hextra        = "001169"; $onp_fondo    = "000079";       $afp_fondo  = "000085";
                    $afp_com          = "000083"; $afp_pri     = "000084"; $prestamos          = "000514"; $comedor       = "001166"; $sobretiempo  = "000059";       $essalud    = "000075";
                    $senati           = "000592"; $sctr_salud  = "000593"; $sctr_pension       = "000837"; $asignacion    = "000056"; $bonificacion = "001126";       $tardanza   = "000945";
                    $adelanto         = "000217"; $retencion   = "000131"; $monto_tardanza     = "000963"; $reintegro     = "001176"; $hdoble       = "001173";       $hora_extra = "001171";
                    $reintegro_afecto = "000581"; $dscto_adic  = "001111"; $hora_extra_doble   = "001174"; $dscto_varios  = "";       $ing_varios   = "";             $reintegro_inafecto = "";
                    $movilidad        = "";       $viaticos    = "";       $afp_com_mixta      = "";       $vale_alimento = "";       $permiso_sin_goce = "001127";   $dscto_eps          = "";
                    $gratificacion    = "";       $bon_extra_ley    = "";
                    break;                
                case '0401'://Honorarios - mensual
                    $basico           = "001192"; $dsemanal     = "";       $no_tributario      = "";       $hextra       = "";        $onp_fondo        = ""; $afp_fondo          = "";
                    $afp_com          = "";       $afp_pri      = "";       $prestamos          = "001195"; $comedor      = "";        $sobretiempo      = ""; $essalud            = "";
                    $senati           = "";       $sctr_salud   = "";       $sctr_pension       = "";       $asignacion    = "";       $bonificacion     = ""; $tardanza           = "";
                    $adelanto         = "001193"; $retencion    = "001196"; $monto_tardanza     = "";       $reintegro     = "";       $hdoble           = ""; $hora_extra         = "";       
                    $reintegro_afecto = "";       $dscto_adic   = "";       $hora_extra_doble   = "";       $dscto_varios  = "001229"; $ing_varios       = ""; $reintegro_inafecto = "";
                    $movilidad        = "000093"; $viaticos     = "001240"; $afp_com_mixta      = "";       $vale_alimento = "";       $permiso_sin_goce = ""; $dscto_eps          = "";
                    $gratificacion    = "";       $bon_extra_ley    = "";
                    break;                
                case '0402'://Honorarios - quincenal
                    $basico           = "001198"; $dsemanal     = "";       $no_tributario      = "";       $hextra       = "";       $onp_fondo        = "";  $afp_fondo          = "";
                    $afp_com          = "";       $afp_pri      = "";       $prestamos          = "000513"; $comedor      = "";       $sobretiempo      = "";  $essalud            = "";
                    $senati           = "";       $sctr_salud   = "";       $sctr_pension       = "";       $asignacion   = "";       $bonificacion     = "";  $tardanza           = "";
                    $adelanto         = "";       $retencion    = "001190"; $monto_tardanza     = "";       $reintegro    = "";       $hdoble           = "";  $hora_extra         = "";       
                    $reintegro_afecto = "";       $dscto_adic   = "";       $hora_extra_doble   = "";       $dscto_varios = "001238"; $ing_varios       = "";  $reintegro_inafecto = "001233";
                    $movilidad        = "000093"; $viaticos     = "001161"; $afp_com_mixta      = "";       $vale_alimento= "";       $permiso_sin_goce = "";  $dscto_eps          = "";          
                    $gratificacion    = "";       $bon_extra_ley    = "";
                    break;
                case '0501'://Obrero ::: mensual
                    $basico           = "000052"; $dsemanal         = "000935"; $no_tributario      = "001168"; $hextra        = "001169"; $onp_fondo        = "000079"; $afp_fondo          = "000085";
                    $afp_com          = "000083"; $afp_pri          = "000084"; $prestamos          = "000514"; $comedor       = "001166"; $sobretiempo      = "000059"; $essalud            = "000075";
                    $senati           = "000592"; $sctr_salud       = "000593"; $sctr_pension       = "000837"; $asignacion    = "000056"; $bonificacion     = "001126"; $tardanza           = "000945";
                    $adelanto         = "000218"; $retencion        = "000131"; $monto_tardanza     = "000963"; $reintegro     = "000581"; $hdoble           = "001173"; $hora_extra         = "";
                    $reintegro_afecto = "";       $dscto_adic       = "001111"; $hora_extra_doble   = "";       $dscto_varios  = "";       $ing_varios       = "";       $reintegro_inafecto = "001176";
                    $movilidad        = "000093"; $viaticos         = "001240"; $afp_com_mixta      = "001222"; $vale_alimento = "";       $permiso_sin_goce = "";       $dscto_eps          = "";
                    $gratificacion    = "000872"; $bon_extra_ley    = "000538";
                    break;                                    
                case '0502'://Obrero ::: quincena
                    $basico           = "000217"; $dsemanal         = "";       $no_tributario = "001159"; $hextra           = "001244"; $onp_fondo        = "";       $afp_fondo     = "";
                    $afp_com          = "";       $afp_pri          = "";       $prestamos     = "000513"; $comedor          = "";       $sobretiempo      = "";       $essalud       = "";
                    $senati           = "";       $sctr_salud       = "";       $sctr_pension  = "";       $asignacion       = "";       $bonificacion     = "001236"; $tardanza      = "";
                    $adelanto         = "";       $retencion        = "";       $monto_tardanza = "";      $reintegro        = "001215"; $hdoble           = "001245"; $hora_extra        = "";
                    $reintegro_afecto = "";       $dscto_adic       = "001230"; $hora_extra_doble = "";    $dscto_varios     = "";       $ing_varios       = "";       $reintegro_inafecto = "001233";
                    $movilidad       = "000093";  $viaticos         = "001240"; $afp_com_mixta      = "";  $vale_alimento    = "";       $permiso_sin_goce = "001127"; $dscto_eps          = "";
                    $gratificacion    = "";       $bon_extra_ley    = "";
                    break;                                    
            }
        }        
        $sql  = "distinct(k.Personal_Id),LTRIM(RTRIM(P1.Apellido_Paterno)) AS Apellido_Paterno,LTRIM(RTRIM(P1.Apellido_Materno)) AS Apellido_Materno,LTRIM(RTRIM(P1.Nombres)) AS Nombres,P1.Categoria2_Id,p.Tipo_Trabajador_Id,P.Nro_cta,p.Categoria2_id,P1.Ccosto_Id,k.Planilla_Id,P1.Nro_Doc,cc.Codigo_Auxiliar,k.Personal_Id,p.Afp_Id,";
        $sql .= "(select ISNULL(a.valor,0) from SCIRERH.dbo.calculos as a where a.personal_id = k.Personal_Id and a.concepto_id='".$basico."' and a.periodo_id = $filter->periodo) as basico_diario,";
        $sql .= "(select ISNULL(b.valor,0) from SCIRERH.dbo.calculos as b where b.personal_id = k.Personal_Id and b.concepto_id='".$dsemanal."' and b.periodo_id = $filter->periodo) as dsemanal,";
        $sql .= "(select ISNULL(c.valor,0) from SCIRERH.dbo.calculos as c where c.personal_id = k.Personal_Id and c.concepto_id='".$no_tributario."' and c.periodo_id = $filter->periodo) as no_tributario,";
        $sql .= "(select ISNULL(d.valor,0) from SCIRERH.dbo.calculos as d where d.personal_id = k.Personal_Id and d.concepto_id='".$hextra."' and d.periodo_id = $filter->periodo) as montoh_extras,";
        $sql .= "(select ISNULL(s.valor,0) from SCIRERH.dbo.calculos as s where s.personal_id = k.Personal_Id and s.concepto_id='".$hdoble."' and s.periodo_id = $filter->periodo) as hdoble,";        
        $sql .= "(select ISNULL(h.valor,0) from SCIRERH.dbo.calculos as h where h.personal_id = k.Personal_Id and h.concepto_id='".$onp_fondo."' and h.periodo_id = $filter->periodo) as onp_fondo,";
        $sql .= "(select ISNULL(g.valor,0) from SCIRERH.dbo.calculos as g where g.personal_id = k.Personal_Id and g.concepto_id='".$afp_fondo."' and g.periodo_id = $filter->periodo) as afp_fondo,";
        $sql .= "(select ISNULL(e.valor,0) from SCIRERH.dbo.calculos as e where e.personal_id = k.Personal_Id and e.concepto_id='".$afp_com."' and e.periodo_id = $filter->periodo) as afp_com_var,";
        $sql .= "(select ISNULL(f.valor,0) from SCIRERH.dbo.calculos as f where f.personal_id = k.Personal_Id and f.concepto_id='".$afp_pri."' and f.periodo_id = $filter->periodo) as afp_pri_seg,";
        $sql .= "(select ISNULL(j.valor,0) from SCIRERH.dbo.calculos as j where j.personal_id = k.Personal_Id and j.concepto_id='".$prestamos."' and j.periodo_id = $filter->periodo) as prestamos,";
        $sql .= "(select ISNULL(i.valor,0) from SCIRERH.dbo.calculos as i where i.personal_id = k.Personal_Id and i.concepto_id='".$comedor."' and i.periodo_id = $filter->periodo) as dscto_comedor,";
        $sql .= "(select ISNULL(l.valor,0) from SCIRERH.dbo.calculos as l where l.personal_id = k.Personal_Id and l.concepto_id='".$monto_tardanza."' and l.periodo_id = $filter->periodo) as monto_tardanza,";
        $sql .= "(select ISNULL(m.valor,0) from SCIRERH.dbo.calculos as m where m.personal_id = k.Personal_Id and m.concepto_id='".$sobretiempo."' and m.periodo_id = $filter->periodo) as sobretiempo,";
        $sql .= "(select ISNULL(w.valor,0) from SCIRERH.dbo.calculos as w where w.personal_id = k.Personal_Id and w.concepto_id='".$essalud."' and w.periodo_id = $filter->periodo) as essalud,";
        $sql .= "(select ISNULL(x.valor,0) from SCIRERH.dbo.calculos as x where x.personal_id = k.Personal_Id and x.concepto_id='".$senati."' and x.periodo_id = $filter->periodo) as senati,";
        $sql .= "(select ISNULL(y.valor,0) from SCIRERH.dbo.calculos as y where y.personal_id = k.Personal_Id and y.concepto_id='".$sctr_salud."' and y.periodo_id = $filter->periodo) as sctr_salud,";
        $sql .= "(select ISNULL(z.valor,0) from SCIRERH.dbo.calculos as z where z.personal_id = k.Personal_Id and z.concepto_id='".$sctr_pension."' and z.periodo_id = $filter->periodo) as sctr_pension,";
        $sql .= "(select ISNULL(n.valor,0) from SCIRERH.dbo.calculos as n where n.personal_id = k.Personal_Id and n.concepto_id='".$asignacion."' and n.periodo_id = $filter->periodo) as asignacion,";
        $sql .= "(select ISNULL(o.valor,0) from SCIRERH.dbo.calculos as o where o.personal_id = k.Personal_Id and o.concepto_id='".$bonificacion."' and o.periodo_id = $filter->periodo) as bonificacion,";
        $sql .= "(select ISNULL(p.valor,0) from SCIRERH.dbo.calculos as p where p.personal_id = k.Personal_Id and p.concepto_id='".$tardanza."' and p.periodo_id = $filter->periodo) as tardanza,";
        $sql .= "(select ISNULL(q.valor,0) from SCIRERH.dbo.calculos as q where q.personal_id = k.Personal_Id and q.concepto_id='".$adelanto."' and q.periodo_id = $filter->periodo) as adelanto,";
        $sql .= "(select ISNULL(r.valor,0) from SCIRERH.dbo.calculos as r where r.personal_id = k.Personal_Id and r.concepto_id='".$retencion."' and r.periodo_id = $filter->periodo) as retencion,";
        $sql .= "(select ISNULL(n.valor,0) from SCIRERH.dbo.calculos as n where n.personal_id = k.Personal_Id and n.concepto_id='".$reintegro."' and n.periodo_id = $filter->periodo) as reintegro,";
        $sql .= "(select ISNULL(t.valor,0) from SCIRERH.dbo.D_variables as t where t.personal_id = k.Personal_Id and t.concepto_id='".$hora_extra."' and t.periodo_id = $filter->periodo) as hora_extra,";
        $sql .= "(select ISNULL(u.valor,0) from SCIRERH.dbo.D_variables as u where u.personal_id = k.Personal_Id and u.concepto_id='".$hora_extra_doble."' and u.periodo_id = $filter->periodo) as hora_extra_doble,";
        $sql .= "(select ISNULL(g.valor,0) from SCIRERH.dbo.calculos as g where g.personal_id = k.Personal_Id and g.concepto_id='".$dscto_adic."' and g.periodo_id = $filter->periodo) as dscto_adicional,";
        $sql .= "(select ISNULL(v.valor,0) from SCIRERH.dbo.calculos as v where v.personal_id = k.Personal_Id and v.concepto_id='".$reintegro_afecto."' and v.periodo_id = $filter->periodo) as reintegro_afecto,";
        $sql .= "(select ISNULL(t.valor,0) from SCIRERH.dbo.calculos as t where t.personal_id = k.Personal_Id and t.concepto_id='".$reintegro_inafecto."' and t.periodo_id = $filter->periodo) as reintegro_inafecto,";        
        $sql .= "(select ISNULL(v.valor,0) from SCIRERH.dbo.calculos as v where v.personal_id = k.Personal_Id and v.concepto_id='".$permiso_sin_goce."' and v.periodo_id = $filter->periodo) as permiso_sin_goce,";
        $sql .= "(select ISNULL(aa1.valor,0) from SCIRERH.dbo.calculos as aa1 where aa1.personal_id = k.Personal_Id and aa1.concepto_id='".$dscto_varios."' and aa1.periodo_id = $filter->periodo) as dscto_4TA ,";
        $sql .= "(select ISNULL(aa2.valor,0) from SCIRERH.dbo.calculos as aa2 where aa2.personal_id = k.Personal_Id and aa2.concepto_id='".$ing_varios."' and aa2.periodo_id = $filter->periodo) as ing_4TA,";
        $sql .= "(select ISNULL(aa3.valor,0) from SCIRERH.dbo.calculos as aa3 where aa3.personal_id = k.Personal_Id and aa3.concepto_id='".$movilidad."' and aa3.periodo_id = $filter->periodo) as movilidad,";
        $sql .= "(select ISNULL(aa4.valor,0) from SCIRERH.dbo.calculos as aa4 where aa4.personal_id = k.Personal_Id and aa4.concepto_id='".$viaticos."' and aa4.periodo_id = $filter->periodo) as viaticos,";
        $sql .= "(select ISNULL(aa5.valor,0) from SCIRERH.dbo.calculos as aa5 where aa5.personal_id = k.Personal_Id and aa5.concepto_id='".$afp_com_mixta."' and aa5.periodo_id = $filter->periodo) as afp_com_mixta,";
        $sql .= "(select ISNULL(aa6.valor,0) from SCIRERH.dbo.calculos as aa6 where aa6.personal_id = k.Personal_Id and aa6.concepto_id='".$vale_alimento."' and aa6.periodo_id = $filter->periodo) as vale_alimento,";           
        $sql .= "(select ISNULL(aa7.valor,0) from SCIRERH.dbo.calculos as aa7 where aa7.personal_id = k.Personal_Id and aa7.concepto_id='".$dscto_eps."' and aa7.periodo_id = $filter->periodo) as dscto_eps,";           
        $sql .= "(select ISNULL(aa8.valor,0) from SCIRERH.dbo.calculos as aa8 where aa8.personal_id = k.Personal_Id and aa8.concepto_id='".$gratificacion."' and aa8.periodo_id = $filter->periodo) as gratificacion,";           
        $sql .= "(select ISNULL(aa9.valor,0) from SCIRERH.dbo.calculos as aa9 where aa9.personal_id = k.Personal_Id and aa9.concepto_id='".$bon_extra_ley."' and aa9.periodo_id = $filter->periodo) as bono_extra_ley";                           
        $this->scire->select($sql);
        $this->scire->from('calculos as k');
        $this->scire->join("personal_activo as P","P.Personal_Id=k.Personal_Id and P.Periodo_Id='".$filter->periodo."'");
        $this->scire->join("personal as P1","P1.Personal_Id=k.Personal_Id");
        $this->scire->join("ccosto as cc","cc.Ccosto_Id=P.Ccosto_Id and cc.Categoria2_Id=P.Categoria2_id","left");
        $this->scire->order_by("P1.Apellido_Paterno","asc");
        $this->scire->order_by("P1.Apellido_Materno","asc");
        $this->scire->order_by("P1.Nombres","asc");
        if(isset($filter->tcuenta) && $filter->tcuenta != ''){
            if($filter->tcuenta==1){
                $this->scire->where("P.Nro_cta!=","");
            }
            elseif($filter->tcuenta==2){
                $this->scire->where("P.Nro_cta","");
            }
        }
        $this->scire->where($where);
        $query = $this->scire->get();
        return $query->result();
    }
    
    public function getDetallePlanillaEmpleado($filter,$filternot = "") {
        $filtrocuenta = "";
        $filtroccosto = "";
        $filtroproceso = "";
        $filtroccosto_conta = "";
        if(isset($filter->proceso) && $filter->proceso != ''){
            $filtroproceso = " and k.Proceso_Id='".$filter->proceso."'";            
            switch($filter->proceso){
                case '01': //MENSUAL
                    /*Planilla formal*/
                    $basico           = "000052"; $dsemanal         = "000935"; $no_tributario      = "001168";
                    $hextra           = "001169"; $onp_fondo        = "000079"; $afp_fondo          = "000085";
                    $afp_com          = "000083"; $afp_pri          = "000084"; $prestamos          = "000514";
                    $comedor          = "001166"; $sobretiempo      = "000059"; $essalud            = "000075";
                    $senati           = "000592"; $sctr_salud       = "000593"; $sctr_pension       = "000837";
                    $asignacion       = "000056"; $bonificacion     = "001126"; $tardanza           = "000945";
                    $adelanto         = "000218"; $retencion        = "000131"; $monto_tardanza     = "000963";
                    $reintegro        = "000581"; $hdoble           = "001173"; $hora_extra         = "";
                    $reintegro_afecto = "";       $dscto_adic       = "001111"; $hora_extra_doble   = "";
                    $dscto_varios     = "";       $ing_varios       = "";       $reintegro_inafecto = "001176";
                    $movilidad        = "000093"; $viaticos         = "001240"; $afp_com_mixta      = "";
                    $vale_alimento    = "";
                    /*Planilla por honorarios*/
                    $basico_rph       = "001192"; $dsemanal_rph     = "";       $no_tributario_rph      = "";
                    $hextra_rph       = "";       $onp_fondo_rph    = "";       $afp_fondo_rph          = "";
                    $afp_com_rph      = "";       $afp_pri_rph      = "";       $prestamos_rph          = "001195";
                    $comedor_rph      = "";       $sobretiempo_rph  = "";       $essalud_rph            = "";
                    $senati_rph       = "";       $sctr_salud_rph   = "";       $sctr_pension_rph       = "";
                    $asignacion_rph   = "";       $bonificacion_rph = "";       $tardanza_rph           = "";
                    $adelanto_rph     = "001193"; $retencion_rph    = "001196"; $monto_tardanza_rph     = "";
                    $reintegro_rph    = "";       $hdoble_rph       = "";       $hora_extra_rph         = "";       
                    $reintegro_afecto_rph = "";   $dscto_adic_rph   = "";       $hora_extra_doble_rph   = "";
                    $dscto_varios_rph = "001229"; $ing_varios_rph   = "";       $reintegro_inafecto_rph = "";
                    $movilidad_rph    = "000093"; $viaticos_rph     = "001240"; $afp_com_mixta_rph      = "";
                    $vale_alimento_rph= "";
                    break;
                case '02': //QUINCENA
                    /*Planilla formal*/
                    $basico           = "000217"; $dsemanal         = "";       $no_tributario = "001159";
                    $hextra           = "";       $onp_fondo        = "";       $afp_fondo     = "";
                    $afp_com          = "";       $afp_pri          = "";       $prestamos     = "000513";
                    $comedor          = "";       $sobretiempo      = "";       $essalud       = "";
                    $senati           = "";       $sctr_salud       = "";       $sctr_pension  = "";
                    $asignacion       = "";       $bonificacion     = "001236"; $tardanza      = "";
                    $adelanto         = "";       $retencion        = "";       $monto_tardanza = "";
                    $reintegro        = "001215"; $hdoble           = "";       $hora_extra        = "";
                    $reintegro_afecto = "";       $dscto_adic       = "001230"; $hora_extra_doble = "";        
                    $dscto_varios     = "";       $ing_varios       = "";       $reintegro_inafecto = "";
                    $movilidad       = "000093";  $viaticos         = "001240"; $afp_com_mixta      = "";
                    $vale_alimento    = "";
                    /*Recibos por honorarios*/
                    $basico_rph       = "001198"; $dsemanal_rph     = "";       $no_tributario_rph = "";
                    $hextra_rph       = "";       $onp_fondo_rph    = "";       $afp_fondo_rph     = "";
                    $afp_com_rph      = "";       $afp_pri_rph      = "";       $prestamos_rph     = "000513";
                    $comedor_rph      = "";       $sobretiempo_rph  = "";       $essalud_rph       = "";
                    $senati_rph       = "";       $sctr_salud_rph   = "";       $sctr_pension_rph  = "";
                    $asignacion_rph   = "";       $bonificacion_rph = "";       $tardanza_rph      = "";
                    $adelanto_rph     = "";       $retencion_rph    = "001190"; $monto_tardanza_rph = "";
                    $reintegro_rph    = "";       $hdoble_rph       = "";       $hora_extra_rph    = "";       
                    $reintegro_afecto_rph = "";   $dscto_adic_rph   = "";       $hora_extra_doble_rph = "";                    
                    $dscto_varios_rph = "001238"; $ing_varios_rph = "";         $reintegro_inafecto_rph = "001233";
                    $movilidad_rph    = "000093"; $viaticos_rph     = "001161"; $afp_com_mixta_rph      = "";
                    $vale_alimento_rph= "";
                    break;
            }
        }
        if(isset($filter->ccosto) && $filter->ccosto != '')              $filtroccosto       = " and p.Ccosto_Id='".$filter->ccosto."'";  
        if(isset($filter->ccosto_conta) && $filter->ccosto_conta != '')  $filtroccosto_conta = " and cc.Codigo_Auxiliar2='".$filter->ccosto_conta."'";  
        if(isset($filter->tcuenta) && $filter->tcuenta != ''){
            if($filter->tcuenta==1)
                $filtrocuenta = " and p.Nro_cta!=''";
            elseif($filter->tcuenta==2)
                $filtrocuenta = " and p.Nro_cta=''";
        }
        $sql = "
            (select
            distinct(k.Personal_Id),
            k.Planilla_Id,
            LTRIM(RTRIM(p1.Apellido_Paterno)) as Apellido_Paterno,LTRIM(RTRIM(p1.Apellido_Materno)) as Apellido_Materno,LTRIM(RTRIM(p1.Nombres)) as Nombres,p.Categoria2_Id,p.Tipo_Trabajador_Id,p.Nro_cta,p.Categoria2_id,p.Ccosto_Id,p1.Nro_Doc,p.Planilla_Id,cc.Codigo_Auxiliar,
            (select ISNULL(a.valor,0) from SCIRERH.dbo.calculos as a where a.personal_id = k.Personal_Id and a.concepto_id='".$basico."' and a.periodo_id = $filter->periodo) as basico_diario,
            (select ISNULL(b.valor,0) from SCIRERH.dbo.calculos as b where b.personal_id = k.Personal_Id and b.concepto_id='".$dsemanal."' and b.periodo_id = $filter->periodo) as dsemanal,
            (select ISNULL(c.valor,0) from SCIRERH.dbo.calculos as c where c.personal_id = k.Personal_Id and c.concepto_id='".$no_tributario."' and c.periodo_id = $filter->periodo) as no_tributario,
            (select ISNULL(n.valor,0) from SCIRERH.dbo.calculos as n where n.personal_id = k.Personal_Id and n.concepto_id='".$asignacion."' and n.periodo_id = $filter->periodo) as asignacion,
            (select ISNULL(o.valor,0) from SCIRERH.dbo.calculos as o where o.personal_id = k.Personal_Id and o.concepto_id='".$bonificacion."' and o.periodo_id = $filter->periodo) as bonificacion,
            (select ISNULL(s.valor,0) from SCIRERH.dbo.calculos as s where s.personal_id = k.Personal_Id and s.concepto_id='".$reintegro."' and s.periodo_id = $filter->periodo) as reintegro,
            (select ISNULL(t.valor,0) from SCIRERH.dbo.calculos as t where t.personal_id = k.Personal_Id and t.concepto_id='".$reintegro_afecto."' and t.periodo_id = $filter->periodo) as reintegro_afecto,
            (select ISNULL(t.valor,0) from SCIRERH.dbo.calculos as t where t.personal_id = k.Personal_Id and t.concepto_id='".$reintegro_inafecto."' and t.periodo_id = $filter->periodo) as reintegro_inafecto,
            (select ISNULL(d.valor,0) from SCIRERH.dbo.calculos as d where d.personal_id = k.Personal_Id and d.concepto_id='".$hextra."' and d.periodo_id = $filter->periodo) as montoh_extras,
            (select ISNULL(p.valor,0) from SCIRERH.dbo.calculos as p where p.personal_id = k.Personal_Id and p.concepto_id='".$tardanza."' and p.periodo_id = $filter->periodo) as tardanza,
            (select ISNULL(h.valor,0) from SCIRERH.dbo.calculos as h where h.personal_id = k.Personal_Id and h.concepto_id='".$onp_fondo."' and h.periodo_id = $filter->periodo) as onp_fondo,
            (select ISNULL(g.valor,0) from SCIRERH.dbo.calculos as g where g.personal_id = k.Personal_Id and g.concepto_id='".$afp_fondo."' and g.periodo_id = $filter->periodo) as afp_fondo,
            (select ISNULL(e.valor,0) from SCIRERH.dbo.calculos as e where e.personal_id = k.Personal_Id and e.concepto_id='".$afp_com."' and e.periodo_id = $filter->periodo) as afp_com_var,
            (select ISNULL(f.valor,0) from SCIRERH.dbo.calculos as f where f.personal_id = k.Personal_Id and f.concepto_id='".$afp_pri."' and f.periodo_id = $filter->periodo) as afp_pri_seg,
            (select ISNULL(r.valor,0) from SCIRERH.dbo.calculos as r where r.personal_id = k.Personal_Id and r.concepto_id='".$retencion."' and r.periodo_id = $filter->periodo) as retencion,
            (select ISNULL(q.valor,0) from SCIRERH.dbo.calculos as q where q.personal_id = k.Personal_Id and q.concepto_id='".$adelanto."' and q.periodo_id = $filter->periodo) as adelanto,
            (select ISNULL(j.valor,0) from SCIRERH.dbo.calculos as j where j.personal_id = k.Personal_Id and j.concepto_id='".$prestamos."' and j.periodo_id = $filter->periodo) as prestamos,
            (select ISNULL(i.valor,0) from SCIRERH.dbo.calculos as i where i.personal_id = k.Personal_Id and i.concepto_id='".$comedor."' and i.periodo_id = $filter->periodo) as dscto_comedor,
            (select ISNULL(l.valor,0) from SCIRERH.dbo.calculos as l where l.personal_id = k.Personal_Id and l.concepto_id='".$monto_tardanza."' and l.periodo_id = $filter->periodo) as monto_tardanza,
            (select ISNULL(m.valor,0) from SCIRERH.dbo.calculos as m where m.personal_id = k.Personal_Id and m.concepto_id='".$sobretiempo."' and m.periodo_id = $filter->periodo) as sobretiempo,
            (select ISNULL(w.valor,0) from SCIRERH.dbo.calculos as w where w.personal_id = k.Personal_Id and w.concepto_id='".$essalud."' and w.periodo_id = $filter->periodo) as essalud,
            (select ISNULL(x.valor,0) from SCIRERH.dbo.calculos as x where x.personal_id = k.Personal_Id and x.concepto_id='".$senati."' and x.periodo_id = $filter->periodo) as senati,
            (select ISNULL(y.valor,0) from SCIRERH.dbo.calculos as y where y.personal_id = k.Personal_Id and y.concepto_id='".$sctr_salud."' and y.periodo_id = $filter->periodo) as sctr_salud,
            (select ISNULL(z.valor,0) from SCIRERH.dbo.calculos as z where z.personal_id = k.Personal_Id and z.concepto_id='".$sctr_pension."' and z.periodo_id = $filter->periodo) as sctr_pension,
            (select ISNULL(u.valor,0) from SCIRERH.dbo.calculos as u where u.personal_id = k.Personal_Id and u.concepto_id='".$hdoble."' and u.periodo_id = $filter->periodo) as hdoble,
            (select ISNULL(v.valor,0) from SCIRERH.dbo.calculos as v where v.personal_id = k.Personal_Id and v.concepto_id='".$hora_extra."' and v.periodo_id = $filter->periodo) as hora_extra,
            (select ISNULL(r.valor,0) from SCIRERH.dbo.calculos as r where r.personal_id = k.Personal_Id and r.concepto_id='".$hora_extra_doble."' and r.periodo_id = $filter->periodo) as hora_extra_doble,                
            (select ISNULL(r.valor,0) from SCIRERH.dbo.calculos as r where r.personal_id = k.Personal_Id and r.concepto_id='".$dscto_adic."' and r.periodo_id = $filter->periodo) as dscto_adicional,
            (select ISNULL(aa.valor,0) from SCIRERH.dbo.calculos as aa where aa.personal_id = k.Personal_Id and aa.concepto_id='".$dscto_varios."' and aa.periodo_id = $filter->periodo) as dscto_4TA ,
            (select ISNULL(aa.valor,0) from SCIRERH.dbo.calculos as aa where aa.personal_id = k.Personal_Id and aa.concepto_id='".$ing_varios."' and aa.periodo_id = $filter->periodo) as ing_4TA,
            (select ISNULL(aa.valor,0) from SCIRERH.dbo.calculos as aa where aa.personal_id = k.Personal_Id and aa.concepto_id='".$movilidad."' and aa.periodo_id = $filter->periodo) as movilidad,
            (select ISNULL(aa.valor,0) from SCIRERH.dbo.calculos as aa where aa.personal_id = k.Personal_Id and aa.concepto_id='".$viaticos."' and aa.periodo_id = $filter->periodo) as viaticos,
            (select ISNULL(aa.valor,0) from SCIRERH.dbo.calculos as aa where aa.personal_id = k.Personal_Id and aa.concepto_id='".$afp_com_mixta."' and aa.periodo_id = $filter->periodo) as afp_com_mixta,
            (select ISNULL(aa.valor,0) from SCIRERH.dbo.calculos as aa where aa.personal_id = k.Personal_Id and aa.concepto_id='".$vale_alimento."' and aa.periodo_id = $filter->periodo) as vale_alimento,    
            per.descripcion as periodo
            from calculos as k 
            inner join personal_activo as p on (p.personal_id=k.personal_id and p.periodo_id='".$filter->periodo."')
            inner join personal as p1 on p1.personal_id=p.personal_id
            left join ccosto as cc on (cc.Ccosto_Id=p.Ccosto_Id and cc.Categoria2_Id=p.Categoria2_id)
            left join periodo as per on
            k.periodo_id = per.periodo_id
            where k.periodo_id='".$filter->periodo."'
            AND p.Categoria2_id = '".$this->entidad."'
            AND p1.nro_doc not in ('00544241')
            AND k.Planilla_Id = '01'
            ".$filtrocuenta."
            ".$filtroccosto."
            ".$filtroccosto_conta."
            ".$filtroproceso.")
            UNION
            (select
            distinct(k.Personal_Id),
            k.Planilla_Id,
            LTRIM(RTRIM(p1.Apellido_Paterno)) as Apellido_Paterno,LTRIM(RTRIM(p1.Apellido_Materno)) as Apellido_Materno,LTRIM(RTRIM(p1.Nombres)) as Nombres,p.Categoria2_Id,p.Tipo_Trabajador_Id,p.Nro_cta,p.Categoria2_id,p.Ccosto_Id,p1.Nro_Doc,p.Planilla_Id,cc.Codigo_Auxiliar,
            (select ISNULL(a.valor,0) from SCIRERH.dbo.calculos as a where a.personal_id = k.Personal_Id and a.concepto_id='".$basico_rph."' and a.periodo_id = $filter->periodo_rph) as basico_diario,
            (select ISNULL(b.valor,0) from SCIRERH.dbo.calculos as b where b.personal_id = k.Personal_Id and b.concepto_id='' and b.periodo_id = $filter->periodo_rph) as dsemanal,
            (select ISNULL(c.valor,0) from SCIRERH.dbo.calculos as c where c.personal_id = k.Personal_Id and c.concepto_id='' and c.periodo_id = $filter->periodo_rph) as no_tributario,
            (select ISNULL(n.valor,0) from SCIRERH.dbo.calculos as n where n.personal_id = k.Personal_Id and n.concepto_id='' and n.periodo_id = $filter->periodo_rph) as asignacion,
            (select ISNULL(o.valor,0) from SCIRERH.dbo.calculos as o where o.personal_id = k.Personal_Id and o.concepto_id='".$bonificacion_rph."' and o.periodo_id = $filter->periodo_rph) as bonificacion,
            (select ISNULL(r.valor,0) from SCIRERH.dbo.calculos as r where r.personal_id = k.Personal_Id and r.concepto_id='".$reintegro_rph."' and r.periodo_id = $filter->periodo_rph) as reintegro,
            (select ISNULL(t.valor,0) from SCIRERH.dbo.calculos as t where t.personal_id = k.Personal_Id and t.concepto_id='".$reintegro_afecto_rph."' and t.periodo_id = $filter->periodo_rph) as reintegro_afecto,
            (select ISNULL(t.valor,0) from SCIRERH.dbo.calculos as t where t.personal_id = k.Personal_Id and t.concepto_id='".$reintegro_inafecto_rph."' and t.periodo_id = $filter->periodo_rph) as reintegro_inafecto,
            (select ISNULL(d.valor,0) from SCIRERH.dbo.calculos as d where d.personal_id = k.Personal_Id and d.concepto_id='' and d.periodo_id = $filter->periodo_rph) as montoh_extras,
            (select ISNULL(p.valor,0) from SCIRERH.dbo.calculos as p where p.personal_id = k.Personal_Id and p.concepto_id='' and p.periodo_id = $filter->periodo_rph) as tardanza,    
            (select ISNULL(h.valor,0) from SCIRERH.dbo.calculos as h where h.personal_id = k.Personal_Id and h.concepto_id='' and h.periodo_id = $filter->periodo_rph) as onp_fondo,
            (select ISNULL(g.valor,0) from SCIRERH.dbo.calculos as g where g.personal_id = k.Personal_Id and g.concepto_id='' and g.periodo_id = $filter->periodo_rph) as afp_fondo,
            (select ISNULL(e.valor,0) from SCIRERH.dbo.calculos as e where e.personal_id = k.Personal_Id and e.concepto_id='' and e.periodo_id = $filter->periodo_rph) as afp_com_var,
            (select ISNULL(f.valor,0) from SCIRERH.dbo.calculos as f where f.personal_id = k.Personal_Id and f.concepto_id='' and f.periodo_id = $filter->periodo_rph) as afp_pri_seg,
            (select ISNULL(r.valor,0) from SCIRERH.dbo.calculos as r where r.personal_id = k.Personal_Id and r.concepto_id='".$retencion_rph."' and r.periodo_id = $filter->periodo_rph) as retencion,
            (select ISNULL(q.valor,0) from SCIRERH.dbo.calculos as q where q.personal_id = k.Personal_Id and q.concepto_id='".$adelanto_rph."' and q.periodo_id = $filter->periodo_rph) as adelanto,    
            (select ISNULL(j.valor,0) from SCIRERH.dbo.calculos as j where j.personal_id = k.Personal_Id and j.concepto_id='".$prestamos_rph."' and j.periodo_id = $filter->periodo_rph) as prestamos,
            (select ISNULL(i.valor,0) from SCIRERH.dbo.calculos as i where i.personal_id = k.Personal_Id and i.concepto_id='' and i.periodo_id = $filter->periodo_rph) as dscto_comedor,
            (select ISNULL(l.valor,0) from SCIRERH.dbo.calculos as l where l.personal_id = k.Personal_Id and l.concepto_id='' and l.periodo_id = $filter->periodo_rph) as monto_tardanza,
            (select ISNULL(m.valor,0) from SCIRERH.dbo.calculos as m where m.personal_id = k.Personal_Id and m.concepto_id='' and m.periodo_id = $filter->periodo_rph) as sobretiempo,
            (select ISNULL(w.valor,0) from SCIRERH.dbo.calculos as w where w.personal_id = k.Personal_Id and w.concepto_id='' and w.periodo_id = $filter->periodo_rph) as essalud,
            (select ISNULL(x.valor,0) from SCIRERH.dbo.calculos as x where x.personal_id = k.Personal_Id and x.concepto_id='' and x.periodo_id = $filter->periodo_rph) as senati,
            (select ISNULL(y.valor,0) from SCIRERH.dbo.calculos as y where y.personal_id = k.Personal_Id and y.concepto_id='' and y.periodo_id = $filter->periodo_rph) as sctr_salud,
            (select ISNULL(z.valor,0) from SCIRERH.dbo.calculos as z where z.personal_id = k.Personal_Id and z.concepto_id='' and z.periodo_id = $filter->periodo_rph) as sctr_pension,
            (select ISNULL(r.valor,0) from SCIRERH.dbo.calculos as r where r.personal_id = k.Personal_Id and r.concepto_id='".$hdoble_rph."' and r.periodo_id = $filter->periodo_rph) as hdoble,
            (select ISNULL(r.valor,0) from SCIRERH.dbo.calculos as r where r.personal_id = k.Personal_Id and r.concepto_id='".$hora_extra_rph."' and r.periodo_id = $filter->periodo_rph) as hora_extra,
            (select ISNULL(r.valor,0) from SCIRERH.dbo.calculos as r where r.personal_id = k.Personal_Id and r.concepto_id='".$hora_extra_doble_rph."' and r.periodo_id = $filter->periodo_rph) as hora_extra_doble,
            (select ISNULL(r.valor,0) from SCIRERH.dbo.calculos as r where r.personal_id = k.Personal_Id and r.concepto_id='".$dscto_adic_rph."' and r.periodo_id = $filter->periodo_rph) as dscto_adicional,
            (select ISNULL(aa.valor,0) from SCIRERH.dbo.calculos as aa where aa.personal_id = k.Personal_Id and aa.concepto_id='".$dscto_varios_rph."' and aa.periodo_id = $filter->periodo_rph) as dscto_4TA ,               
            (select ISNULL(aa.valor,0) from SCIRERH.dbo.calculos as aa where aa.personal_id = k.Personal_Id and aa.concepto_id='".$ing_varios_rph."' and aa.periodo_id = $filter->periodo_rph) as ing_4TA,               
            (select ISNULL(aa.valor,0) from SCIRERH.dbo.calculos as aa where aa.personal_id = k.Personal_Id and aa.concepto_id='".$movilidad_rph."' and aa.periodo_id = $filter->periodo_rph) as movilidad,
            (select ISNULL(aa.valor,0) from SCIRERH.dbo.calculos as aa where aa.personal_id = k.Personal_Id and aa.concepto_id='".$viaticos_rph."' and aa.periodo_id = $filter->periodo_rph) as viaticos,
            (select ISNULL(aa.valor,0) from SCIRERH.dbo.calculos as aa where aa.personal_id = k.Personal_Id and aa.concepto_id='".$afp_com_mixta_rph."' and aa.periodo_id = $filter->periodo_rph) as afp_com_mixta,
            (select ISNULL(aa.valor,0) from SCIRERH.dbo.calculos as aa where aa.personal_id = k.Personal_Id and aa.concepto_id='".$vale_alimento_rph."' and aa.periodo_id = $filter->periodo_rph) as vale_alimento,                    
            per.descripcion as periodo
            from calculos as k 
            inner join personal_activo as p on (p.personal_id=k.personal_id and p.periodo_id='".$filter->periodo_rph."')
            inner join personal as p1 on p1.personal_id=p.personal_id
            left join ccosto as cc on (cc.Ccosto_Id=p.Ccosto_Id and cc.Categoria2_Id=p.Categoria2_id)
            left join periodo as per on
            k.periodo_id = per.periodo_id
            where k.periodo_id='".$filter->periodo_rph."'
            AND p.Categoria2_id = '".$this->entidad."'
            AND p1.nro_doc not in ('00544241')
            AND k.Planilla_Id = '04'  
            ".$filtrocuenta."
            ".$filtroccosto."
            ".$filtroccosto_conta."
            ".$filtroproceso.")
            order by 2,3,4,5 ASC
        "; 
        $query = $this->scire->query($sql);
        return $query->result();
    }   
    
    public function get($filter,$filternot = ""){
        $where    = array("per.Categoria2_id" => $this->entidad);
        if(isset($filter->entidad)){
            if($filter->entidad == "")
                $where = array();
            else
                $where = array_merge($where,array("per.Categoria2_id"=>$filter->entidad));
        }
        $group_by = array("per.Ccosto_Id","c.Planilla_Id","per.Tipo_Trabajador_Id","c.Concepto_Id","c.Personal_Id","per1.Apellido_Paterno","per1.Apellido_Materno","cc.Codigo_Auxiliar2");
        $order_by = array("per.Tipo_Trabajador_Id"=>"asc","per1.Apellido_Paterno"=>"asc","per1.Apellido_Materno"=>"asc");
        //if(isset($filter->planilla) && $filter->planilla != '') $where = array_merge($where, array("c.Planilla_Id" => $filter->planilla));
        if(isset($filter->periodo) && $filter->periodo != '')   $where = array_merge($where, array("c.Periodo_Id" => $filter->periodo));
        if(isset($filter->mes) && $filter->mes != '')           $where = array_merge($where, array("p.Mes_Id" => $filter->mes));
        if(isset($filter->persona) && $filter->persona != '')   $where = array_merge($where, array("per.Personal_Id" => $filter->persona));
        //if(isset($filter->concepto) && $filter->concepto != '') $where = array_merge($where, array("c.Concepto_Id" => $filter->concepto));
        if(isset($filter->proceso) && $filter->proceso != '')   $where = array_merge($where, array("c.Proceso_Id" => $filter->proceso));
        if(isset($filter->ccosto) && $filter->ccosto != '')     $where = array_merge($where, array("per.Ccosto_Id" => $filter->ccosto));
        if(isset($filter->ccosto_conta) && $filter->ccosto_conta != '')     $where = array_merge($where, array("cc.Codigo_Auxiliar2" => $filter->ccosto_conta));
        /*filter_not*/
        if(isset($filternot->persona) && $filternot->persona != '')   $where = array_merge($where, array("per.Personal_Id!=" => $filternot->persona));
        $sql = "c.Personal_Id,c.Concepto_Id,sum(c.Valor) as valor,per1.Apellido_Paterno,per.Tipo_Trabajador_Id,per.Ccosto_Id,c.Planilla_Id,cc.Codigo_Auxiliar2";
        $this->scire->select($sql);
        $this->scire->from('calculos as c');
        $this->scire->join("periodo as p","p.Periodo_Id=c.Periodo_Id");
        $this->scire->join("personal_activo as per","per.Personal_Id=c.Personal_Id and per.Planilla_Id=c.Planilla_Id and per.Periodo_Id=c.Periodo_Id");
        $this->scire->join("personal as per1","per1.Personal_Id=c.Personal_Id");
        $this->scire->join("ccosto as cc","cc.Ccosto_Id=per.Ccosto_Id");
        $this->scire->where($where);
        if(isset($filter->concepto) && $filter->concepto!=''){
            if(is_array($filter->concepto) && count($filter->concepto)>0){
                $this->scire->where_in('c.Concepto_id',$filter->concepto);
            }
            else{
                $this->scire->where('c.Concepto_id',$filter->concepto);
            }            
        }    
        if(isset($filter->tipo) && $filter->tipo!=''){
            if(is_array($filter->tipo) && count($filter->tipo)>0){
                $this->scire->where_in('per.Tipo_Trabajador_Id',$filter->tipo);
            }
            else{
                $this->scire->where('per.Tipo_Trabajador_Id',$filter->tipo);
            }            
        }      
        if(isset($filter->planilla) && $filter->planilla!=''){
            if(is_array($filter->planilla) && count($filter->planilla)>0){
                $this->scire->where_in('c.Planilla_Id',$filter->planilla);
            }
            else{
                $this->scire->where('c.Planilla_Id',$filter->planilla);
            }            
        }         
        $this->scire->group_by($group_by);        
        if(isset($order_by) && count($order_by)>0 && $order_by!=""){
            foreach($order_by as $indice=>$value){
                $this->scire->order_by($indice,$value);
            }
        }          
        $query = $this->scire->get();
        return $query->result();
    }
    
    public function getConcepto($filter,$filternot = ""){
        $where    = array("per.Categoria2_id" => $this->entidad);
        $group_by = array("c.Concepto_Id","cc.Codigo_Auxiliar2","per.Ccosto_id","per1.Apellido_Paterno","per1.Apellido_Materno","per1.Nombres","per.Personal_Id,per.Tipo_Trabajador_Id");
        $order_by = array("c.Concepto_Id"=>"asc","cc.Codigo_Auxiliar2"=>"asc","per.Ccosto_id"=>"asc","per.Tipo_Trabajador_Id"=>"asc");        
        if(isset($filter->mes) && $filter->mes != '')           $where = array_merge($where, array("p.Mes_Id" => $filter->mes));
        if(isset($filter->proceso) && $filter->proceso != '')   $where = array_merge($where, array("c.Proceso_Id" => $filter->proceso));
        if(isset($filter->tipo) && $filter->tipo != '')         $where = array_merge($where, array("per.Tipo_Trabajador_Id" => $filter->tipo));
        if(isset($filter->ccosto) && $filter->ccosto != '')     $where = array_merge($where, array("per.Ccosto_Id" => $filter->ccosto));
        if(isset($filter->ccosto_conta) && $filter->ccosto_conta != '')     $where = array_merge($where, array("cc.Codigo_Auxiliar2" => $filter->ccosto_conta));
        if(isset($filter->concepto) && $filter->concepto != '') $where = array_merge($where, array("c.Concepto_Id" => $filter->concepto));
        if(isset($filter->planilla) && $filter->planilla != '') $where = array_merge($where, array("c.Planilla_Id" => $filter->planilla));
        if(isset($filter->persona) && $filter->persona != '')   $where = array_merge($where, array("per.Personal_Id" => $filter->persona));
        $sql = "c.Concepto_Id,cc.Codigo_Auxiliar2,per.Ccosto_id,per.Personal_Id,per1.Apellido_Paterno,per1.Apellido_Materno,per1.Nombres,per.Tipo_Trabajador_Id,sum(c.Valor) as monto";
        $this->scire->select($sql);
        $this->scire->from('calculos as c');
        $this->scire->join("periodo as p","p.Periodo_Id=c.Periodo_Id");
        $this->scire->join("personal_activo as per","per.Personal_Id=c.Personal_Id and per.Planilla_Id=c.Planilla_Id and per.Periodo_Id=c.Periodo_Id");
        $this->scire->join("personal as per1","per1.Personal_Id=c.Personal_Id");
        $this->scire->join("ccosto as cc","cc.Ccosto_Id=per.Ccosto_Id");
        $this->scire->where($where);
        $this->scire->group_by($group_by);        
        if(isset($order_by) && count($order_by)>0 && $order_by!=""){
            foreach($order_by as $indice=>$value){
                $this->scire->order_by($indice,$value);
            }
        }  
        $query = $this->scire->get();
        return $query->result();      
    }
    
   public function listar_totales($filter,$filternot = ""){
        $where    = array();
        if(isset($filter->planilla) && $filter->planilla != '') $where = array_merge($where, array("c.Planilla_Id" => $filter->planilla));
        if(isset($filter->periodo) && $filter->periodo != '')   $where = array_merge($where, array("c.Periodo_Id" => $filter->periodo));
        if(isset($filter->personal) && $filter->personal != '') $where = array_merge($where, array("c.Personal_Id" => $filter->personal));
        $sql = "c.Planilla_Id,c.Periodo_Id,c.Personal_Id,sum(c.Valor) as Valor";
        $this->scire->select($sql);
        $this->scire->from('calculos as c');
        $this->scire->where($where);
        if(isset($filter->concepto)){
            if(is_array($filter->concepto) && count($filter->concepto)>0){
                $this->scire->where_in('c.Concepto_id',$filter->concepto);
            }
            else{
                $this->scire->where('c.Concepto_id',$filter->concepto);
            }            
        }     
        if(isset($filter->proceso)){
            if(is_array($filter->proceso) && count($filter->proceso)>0){
                $this->scire->where_in('c.Proceso_Id',$filter->proceso);
            }
            else{
                $this->scire->where('c.Proceso_Id',$filter->proceso);
            }            
        }          
        $this->scire->group_by(array("c.Personal_Id","c.Periodo_Id","c.Planilla_Id"));        
        if(isset($order_by) && count($order_by)>0 && $order_by!=""){
            foreach($order_by as $indice=>$value){
                $this->scire->order_by($indice,$value);
            }
        }          
        $query = $this->scire->get();
        $resultado = array();
        if($query->num_rows>0)   $resultado = $query->result();
        if($query->num_rows==1)  $resultado = $query->row();
        return $resultado;  
        
    }    
}
?>
