<!DOCTYPE html>
<html>
<head>
    <title><?php echo titulo;?></title>        
    <!-- Calendario -->
    <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>theme.css" type="text/css">
    <link rel="stylesheet" href="<?php echo css;?>calendario/calendar-win2k-2.css" type="text/css" media="all" title="win2k-cold-1"/>	
    <!-- Calendario -->	
    <script type="text/javascript" src="<?php echo js;?>constants.js"></script>    
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-es.js"></script>
    <script type="text/javascript" src="<?php echo js;?>calendario/calendar-setup.js"></script>
    <script type="text/javascript" src="<?php echo js;?>jquery.js"></script>
    <script type="text/javascript" src="<?php echo js;?>ventas/presupuesto.js"></script>
</head>
<body>
    <div class="container">
        <div class="header">GENERACI&Oacute;N DE PRESUPUESTOS</div>
        <div class="div_fondo">
            <div class="case_middle">
                <ul class="tabs">
                    <li id2="01"><a href="#tab1">Presupuesto</a></li>
                    <li id2="02"><a href="#tab2">Fabricacion</a></li>
                    <li id2="03"><a href="#tab3">Montaje(A)</a></li>
                    <li id2="04"><a href="#tab4">Obras Civiles(B)</a></li>
                    <li id2="05"><a href="#tab5">Transporte(C)</a></li>
                    <li id2="06"><a href="#tab6">Servicios Ing(D)</a></li>
                    <li id2="07"><a href="#tab7">Proyectos Esp(E)</a></li>
                    <li id2="08"><a href="#tab8">Otros(F)</a></li>
                    <li id2="09"><a href="#tab9">Proyectos Ing(G)</a></li>
                </ul>
                <div class="tab_container">
                    <div style="width:78%;border:0px;float:left;">
                    <div class="case_botones">
                        <ul class="lista_botones"><li id="excel">Excel</li></ul>
<!--                            <ul class="lista_botones"><li id="pdf">Pdf</li></ul>-->
                        <ul class="lista_botones"><li id="nuevo">Nuevo Presupuesto</li></ul>
<!--                            <ul class="lista_botones"><li id="buscar">Buscar</li></ul>   -->
                    </div>  
                    </div>
                    <div style="width:21.5%;border:0px;float:left;">
                        <div class="case_botones">
                            <ul class="lista_botones"><li id="salir">Salir</li></ul>
                        </div> 
                    </div>                    
                    <div class="container">
                        <div id="tab1" class="tab_content" style="width:65%;border: 1px solid #ccc;float:left;height:617px; overflow:auto;"><?php require_once "presupuesto_listar.php";?></div>
                        <div id="tab2" class="tab_content" style="width:65%;border: 1px solid #000;float:left;height:617px; overflow:auto;"><?php $tipo="02";require "presupuesto_partida.php";?></div>
                        <div id="tab3" class="tab_content" style="width:65%;border: 1px solid #000;float:left;height:617px; overflow:auto;"><?php $tipo="03";require "presupuesto_partida.php";?></div>
                        <div id="tab4" class="tab_content" style="width:65%;border: 1px solid #000;float:left;height:617px; overflow:auto;"><?php $tipo="04";require "presupuesto_partida.php";?></div>
                        <div id="tab5" class="tab_content" style="width:65%;border: 1px solid #000;float:left;height:617px; overflow:auto;"><?php $tipo="05";require "presupuesto_partida.php";?></div>
                        <div id="tab6" class="tab_content" style="width:65%;border: 1px solid #000;float:left;height:617px; overflow:auto;"><?php $tipo="06";require "presupuesto_partida.php";?></div>
                        <div id="tab7" class="tab_content" style="width:65%;border: 1px solid #000;float:left;height:617px; overflow:auto;"><?php $tipo="07";require "presupuesto_partida.php";?></div>
                        <div id="tab8" class="tab_content" style="width:65%;border: 1px solid #000;float:left;height:617px; overflow:auto;"><?php $tipo="08";require "presupuesto_partida.php";?></div>
                        <div id="tab9" class="tab_content" style="width:65%;border: 1px solid #000;float:left;height:617px; overflow:auto;"><?php $tipo="09";require "presupuesto_partida.php";?></div>                        
                        <div style="width:31%;border: 1px solid #ccc;float:left; padding-top: 0px;"><?php require_once "presupuesto_nuevo.php";?></div>
                        <!--    <div style="width:100%;border: 1px solid #000;float:left;">c</div>-->
                    </div>
                </div>                
           </div>  
        </div> 
    </div>               
</body>
<script type="text/javascript">
    $('#tblPresupuesto tr')
            .mouseover(function(){
               $(this).css("background: #bbbbbb;");
            });  
</script> 
</html>