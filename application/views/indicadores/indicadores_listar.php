<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="<?php echo css;?>estilos.css" type="text/css">
        <link rel="stylesheet" href="<?php echo css;?>jquery-ui.css" type="text/css">
        <script type="text/javascript" src="<?php echo js;?>jquery-1.9.1.js"></script>
        <script type="text/javascript" src="<?php echo js;?>jquery-ui.js"></script>
        <script type="text/javascript" src="<?php echo js;?>highcharts.js"></script>
        <script type="text/javascript" src="<?php echo js;?>exporting.js"></script>	
        <script type="text/javascript" src="<?php echo js;?>indicadores/indicadores.js"></script>
    </head>
    <body>
        <?php // echo "<pre>" . print_r($kpi) . "</pre>" . exit; ?>
        <div class="container">
            <input id="entidad" type="hidden" value="<?php echo $this->session->userdata('entidad') ?>" />
            <div class="header">INDICADORES POR &Aacute;REA</div>
            <div class="div_fondo">
                <div class="case_middle">
                    <ul class="tabs">
                        <?php $i = 2; ?>
                        <li id="01"><a href="#tab1">Cargar Datos</a></li>
                        <?php foreach ($areas as $key => $value): ?>
                            <li>
                                <a href="<?php echo "#tab" . $i; ?>"><?php echo $value->are_name; ?></a>
                                <?php $i++; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="tab_container">
                        <div class="container">
                            <div id="tab1" class="tab_content">
                                <div style="float:left; border:0px solid #000; width:20%">
                                    <div style="border: 0px solid #000;background: #ccc;">
                                        <ul class="glossymenu" id="menu">
                                            <?php foreach ($areas as $key => $value): ?>
                                                <li class="glossymenutitle">
                                                    <a href="#"><?php echo $value->are_name; ?></a>
                                                    <ul>
                                                         <?php foreach ($kpi as $k => $v): ?>
                                                             <?php if ($v->are_code == $value->are_code): ?>
                                                                 <li><a href="#" id="<?php echo "formbtn" . $v->kpi_code ?>"><?php echo $v->kpi_title ?></a></li>
                                                             <?php endif; ?>
                                                         <?php endforeach; ?>
                                                    </ul>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div id="tab2" class="tab_content">
                                <div style="float:left; border:0px solid #000; width:20%">
                                    <div style="border: 0px solid #000;background: #ccc;">
                                        <ul class="glossymenu" id="menu">
                                            <?php foreach ($kpi as $k => $v): ?>
                                                <?php if ($v->are_code == 1): ?>
                                                    <li class="glossymenutitle"><a href="#" id="<?php echo "btn" . $v->kpi_code ?>"><?php echo $v->kpi_title ?></a></li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                                <div id="tab_container2" style="float:left; border:1px solid #000; width:70%">
                                    
                                </div>
                            </div>
                            <div id="tab3" class="tab_content">
                                <div style="float:left;border:0px solid #000;width:20%">
                                    <div style="border: 0px solid #000;background: #ccc;">
                                        <ul class="glossymenu" id="menu">
                                            <?php foreach ($kpi as $k => $v): ?>
                                                <?php if ($v->are_code == 2): ?>
                                                    <li class="glossymenutitle"><a href="#" id="<?php echo "btn" . $v->kpi_code ?>"><?php echo $v->kpi_title ?></a></li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                                <div id="tab_container3" style="float:left; border:1px solid #000; width:70%">
                                    
                                </div>
                            </div>
                            <div id="tab4" class="tab_content">
                                <div style="float:left;border:0px solid #000;width:20%">
                                    <div style="border: 0px solid #000; background: #ccc;">
                                        <ul class="glossymenu" id="menu">
                                            <?php foreach ($kpi as $k => $v): ?>
                                                <?php if ($v->are_code == 3): ?>
                                                    <li class="glossymenutitle"><a href="#" id="<?php echo "btn" . $v->kpi_code ?>"><?php echo $v->kpi_title ?></a></li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div id="tab5" class="tab_content">
                                <div style="float:left;border:0px solid #000;width:20%">
                                    <div style="border: 0px solid #000; background: #ccc;">
                                        <ul class="glossymenu" id="menu">
                                            <?php foreach ($kpi as $k => $v): ?>
                                                <?php if ($v->are_code == 4): ?>
                                                    <li class="glossymenutitle"><a href="#" id="<?php echo "btn" . $v->kpi_code ?>"><?php echo $v->kpi_title ?></a></li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div id="tab6" class="tab_content">
                                <div style="float:left;border:0px solid #000;width:20%">
                                    <div style="border: 0px solid #000; background: #ccc;">
                                        <ul class="glossymenu" id="menu">
                                            <?php foreach ($kpi as $k => $v): ?>
                                                <?php if ($v->are_code == 5): ?>
                                                    <li class="glossymenutitle"><a href="#" id="<?php echo "btn" . $v->kpi_code ?>"><?php echo $v->kpi_title ?></a></li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div id="tab7" class="tab_content">
                                <div style="float:left;border:0px solid #000;width:20%">
                                    <div style="border: 0px solid #000; background: #ccc;">
                                        <ul class="glossymenu" id="menu">
                                            <?php foreach ($kpi as $k => $v): ?>
                                                <?php if ($v->are_code == 6): ?>
                                                    <li class="glossymenutitle"><a href="#" id="<?php echo "btn" . $v->kpi_code ?>"><?php echo $v->kpi_title ?></a></li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php foreach ($kpi as $k => $v): ?>
<!--                                <div id="<?php echo "dialog-form" . $v->kpi_code ?>" title="<?php echo $v->kpi_title ?>">
                                    <form style="padding: 0;">
                                        <label for="name">Valor M&iacute;nimo</label>
                                        <input type="text" name="<?php echo "vminimo" . $v->kpi_code; ?>" id="<?php echo "vminimo" . $v->kpi_code; ?>" class="text ui-widget-content ui-corner-all" />
                                        <br/>
                                        <br/>
                                        <label for="email">Valor M&aacute;ximo</label>
                                        <input type="text" name="<?php echo "vmaximo" . $v->kpi_code; ?>" id="<?php echo "vmaximo" . $v->kpi_code; ?>" value="" class="text ui-widget-content ui-corner-all" />
                                        <br/>
                                        <br/>
                                        <label for="password">Valor Real</label>
                                        <input type="text" name="<?php echo "vreal" . $v->kpi_code; ?>" id="<?php echo "vreal" . $v->kpi_code; ?>" value="" class="text ui-widget-content ui-corner-all" />
                                        <br/>
                                        <br/>
                                        <label for="<?php echo "feccrea" . $v->kpi_code ?>">Fecha Indicador</label>
                                        <input type="text" id="<?php echo "feccrea" . $v->kpi_code ?>" class="text ui-widget-content ui-corner-all">
                                        <br/>
                                        <br/>
                                        label for="<?php echo "fecreg" . $v->kpi_code ?>">Fecha Registro</label
                                        <input type="hidden" id="<?php echo "fecreg" . $v->kpi_code ?>" class="text ui-widget-content ui-corner-all" value="<?php echo date('d/m/Y') ?>">
                                        <label for="<?php echo "period" . $v->kpi_code ?>">Periodicidad</label>
                                        <input readonly="true" type="text" id="<?php echo "period" . $v->kpi_code ?>" value="<?php echo $v->per_code ?>" class="text ui-widget-content ui-corner-all">
                                        <input type="hidden" id="<?php echo "kpicode" . $v->kpi_code ?>" value="<?php echo $v->kpi_code ?>">
                                    </form>
                                </div>-->
                                <div id="<?php echo "dialog-btn" . $v->kpi_code ?>" title="<?php echo $v->kpi_title ?>">
                                    <form style="padding: 0;">
                                        <!--fieldset-->
                                            <input type="text" value="Llene los siguientes campos" readonly="1" size="30">
                                            <br/>
                                            <br/>
                                            <label for="name">Fecha Inicio</label>
                                            <input type="text" name="<?php echo "finicio" . $v->kpi_code; ?>" id="<?php echo "finicio" . $v->kpi_code; ?>" class="text ui-widget-content ui-corner-all" />
                                            <br/>
                                            <br/>
                                            <label for="email">Fecha Fin</label>
                                            <input type="text" name="<?php echo "ffin" . $v->kpi_code; ?>" id="<?php echo "ffin" . $v->kpi_code; ?>" value="" class="text ui-widget-content ui-corner-all" />
                                            <br/>
                                            <br/>
                                            <label for="password">Valor Real</label>
                                            <input type="text" name="<?php echo "vreal" . $v->kpi_code; ?>" id="<?php echo "vreal" . $v->kpi_code; ?>" value="" class="text ui-widget-content ui-corner-all" />
                                            <input type="hidden" id="<?php echo "kpicode" . $v->kpi_code ?>" value="<?php echo $v->kpi_code ?>">
                                        <!--/fieldset-->
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>