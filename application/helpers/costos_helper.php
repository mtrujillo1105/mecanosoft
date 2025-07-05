<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    include_once(APPPATH.'models/almacen/nsalida_model.php');
    include_once(APPPATH.'models/compras/requiser_model.php');
    include_once(APPPATH.'models/produccion/tareo_model.php');
    include_once(APPPATH.'models/siddex/parte_model.php');
    include_once(APPPATH.'models/finanzas/caja_model.php');
    include_once(APPPATH.'models/finanzas/voucher_model.php');
    include_once(APPPATH.'models/balanza/constancia_model.php');
    function costomateriales($filter){
        $arrMateriales = array();    
        $moneda        = $filter->moneda;
        unset($filter->moneda);
        $nsalida = new Nsalida_model();         
        $Omateria_prima  = $nsalida->listar_salidas($filter,new stdClass());
        foreach($Omateria_prima as $indice3=>$value3){
            $codigo  = @trim($value3->codot);
            $filter  = new stdClass();
            $filter->costosoles   = @$value3->soles;
            $filter->costodolares = @$value3->dolares;
            if($moneda=='S'){
                $filter->costo = @$value3->soles;    
            }
            elseif($moneda=='D'){
                $filter->costo = @$value3->dolares;    
            }
            $arrMateriales[$codigo] = $filter;
        }
        return $arrMateriales;
    }
    
    function costoservicios($filter,$filternot){
        $arrServicios = array();
        $moneda        = $filter->moneda;
        unset($filter->moneda);        
        $requis = new Requiser_model(); 
        $oServicios = $requis->listar_totales($filter,$filternot,"");
        foreach($oServicios as $indice6 => $value6){
            $codigo  = trim($value6->codot);
            $filter  = new stdClass();
            $filter->tipser          = $value6->tipser;
            if($moneda=='S'){
                $filter->costo = $value6->subtotalsoles;    
            }
            elseif($moneda=='D'){
                $filter->costo = $value6->subtotaldolares;    
            }            
            $arrServicios[$codigo]   = $filter;
        }        
        return $arrServicios;
    }    
    
    function costomanoobra($filter,$filternot){
        $arrManoObra = array();
        $moneda      = $filter->moneda;
        unset($filter->moneda);  
        if(substr($filter->fechaf,6,4)<= 2013){
            $manoobra  = new Tareo_model(); 
            $oManoObra = $manoobra->listar_totales($filter,$filternot);
            foreach($oManoObra as $item => $value){
                $codigo  = str_replace("-","",trim($value->nroot));
                $filter  = new stdClass();    
                if($moneda=='S'){
                    $filter->costo     = $value->real;
                }
                elseif($moneda=='D'){
                    $filter->costo     = $value->realD;
                }
                $arrManoObra[$codigo]  = $filter;
            }
        }
        else{
            $manoobra  = new Parte_model(); 
            $oManoObra = $manoobra->listar_totales2($filter);    
            foreach($oManoObra as $item => $value){
                $codigo  = trim($value->numeroorden);
                $filter  = new stdClass(); 
                if($moneda=='S'){
                    $filter->costo = $value->Monto;
                }
                elseif($moneda=='D'){
                    $filter->costo = 0;
                }
                $arrManoObra[$codigo]  = $filter;
            }
        }
        return $arrManoObra;
    }
    
    function costocaja($filter,$filternot){
        $arrCaja    = array();
        $moneda     = $filter->moneda;
        unset($filter->moneda);  
        $caja       = new Caja_model(); 
        $oCaja      = $caja->listar_totales($filter,new stdClass());
        foreach($oCaja as $item => $value){
            $codigo  = trim($value->codot);
            $filter  = new stdClass();
            if($moneda=='S'){
                $filter->costo = $value->subSoles;    
            }
            elseif($moneda=='D'){
                $filter->costo = $value->subDolar;    
            }            
            $arrCaja[$codigo]   = $filter;
        }        
        return $arrCaja;         
    }
    
    function costotesoreria($filter,$filternot){
        $arrTesoreria    = array();
        $moneda     = $filter->moneda;
        unset($filter->moneda);  
        $tesoreria  = new Voucher_model(); 
        $oTesoreria = $tesoreria->listar_totales2($filter,$filternot);
        foreach($oTesoreria as $item => $value){
            $codigo  = trim($value->codot);
            $filter  = new stdClass();
            if($moneda=='S'){
                $filter->costo = $value->ImpSoles;    
            }
            elseif($moneda=='D'){
                $filter->costo = $value->ImpDolares;    
            }            
            $arrTesoreria[$codigo]   = $filter;
        }        
        return $arrTesoreria;         
    }
    
    function costogalvanizado($filter,$filternot){
        $arrGalvanizado  = array();
        $moneda      = $filter->moneda;
        unset($filter->moneda);  
        $constancia  = new Constancia_model(); 
        $oConstancia = $constancia->listar_totales($filter,$filternot);
        foreach($oConstancia as $item => $value){
            $codigo  = trim($value->codot);
            $filter  = new stdClass();
            if($moneda=='S'){
                $filter->costo = $value->imp_soles;    
            }
            elseif($moneda=='D'){
                $filter->costo = $value->imp_dolares;    
            }            
            $arrGalvanizado[$codigo]   = $filter;
        }        
        return $arrGalvanizado;   
    }
?>