jQuery(document).ready(function() {
    
    function checkController(vmin, vmax, vreal, period, fecreg, feccre) {
        var parametros = {
            "valorMin" : vmin,
            "valorMax" : vmax,
            "valorReal" : vreal,
            "periodo" : period,
            "fecregistro" : fecreg,
            "feccreacion" : feccre
        }
        
        $.ajax({
            data:  parametros,
            url:   'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
            type:  'post'
        });
    }
    
    //Default Action
    $(".tab_content").hide(); //Hide all content
    $("ul.tabs li:first").addClass("active").show(); //Activate first tab
    $(".tab_content:first").show(); //Show first tab content
    //On Click Event
    $("ul.tabs li").click(function() {
        $("ul.tabs li").removeClass("active"); //Remove any "active" class
        $(this).addClass("active"); //Add "active" class to selected tab
        $(".tab_content").hide(); //Hide all tab content
        var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
        //$(this).find("a").remove(); //Find the rel attribute value to identify the active tab + content
        $(activeTab).fadeIn(); //Fade in the active content
    });
    
    $('#menu li a').click(function(event){
                var elem = $(this).next();
                if(elem.is('ul')){
                        event.preventDefault();
                        $('#menu ul:visible').not(elem).slideUp();
                        elem.slideToggle();
                }
    });
    
    $( "#dialog-form1" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode1").val(),
                    "valorMin": $("#vminimo1").val(),
                    "valorMax": $("#vmaximo1").val(),
                    "valorReal": $("#vreal1").val(),
                    "perCode": $("#period1").val(),
                    "fecRegistro": $("#fecreg1").val(),
                    "fecCreacion": $("#feccrea1").val(),
                    "codRespon": $("#feccrea1").val()
                };
                
                if($("#vreal1").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea1").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form2" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode2").val(),
                    "valorMin": $("#vminimo2").val(),
                    "valorMax": $("#vmaximo2").val(),
                    "valorReal": $("#vreal2").val(),
                    "perCode": $("#period2").val(),
                    "fecRegistro": $("#fecreg2").val(),
                    "fecCreacion": $("#feccrea2").val(),
                    "codRespon": $("#feccrea2").val()
                };
                
                if($("#vreal2").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea2").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form3" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode3").val(),
                    "valorMin": $("#vminimo3").val(),
                    "valorMax": $("#vmaximo3").val(),
                    "valorReal": $("#vreal3").val(),
                    "perCode": $("#period3").val(),
                    "fecRegistro": $("#fecreg3").val(),
                    "fecCreacion": $("#feccrea3").val(),
                    "codRespon": $("#feccrea3").val()
                };
                
                if($("#vreal3").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea3").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form4" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode4").val(),
                    "valorMin": $("#vminimo4").val(),
                    "valorMax": $("#vmaximo4").val(),
                    "valorReal": $("#vreal4").val(),
                    "perCode": $("#period4").val(),
                    "fecRegistro": $("#fecreg4").val(),
                    "fecCreacion": $("#feccrea4").val(),
                    "codRespon": $("#feccrea4").val()
                };
                
                if($("#vreal4").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea4").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form5" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode5").val(),
                    "valorMin": $("#vminimo5").val(),
                    "valorMax": $("#vmaximo5").val(),
                    "valorReal": $("#vreal5").val(),
                    "perCode": $("#period5").val(),
                    "fecRegistro": $("#fecreg5").val(),
                    "fecCreacion": $("#feccrea5").val(),
                    "codRespon": $("#feccrea5").val()
                };
                
                if($("#vreal5").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea5").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form6" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode6").val(),
                    "valorMin": $("#vminimo6").val(),
                    "valorMax": $("#vmaximo6").val(),
                    "valorReal": $("#vreal6").val(),
                    "perCode": $("#period6").val(),
                    "fecRegistro": $("#fecreg6").val(),
                    "fecCreacion": $("#feccrea6").val(),
                    "codRespon": $("#feccrea6").val()
                };
                
                if($("#vreal6").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea6").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form7" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode7").val(),
                    "valorMin": $("#vminimo7").val(),
                    "valorMax": $("#vmaximo7").val(),
                    "valorReal": $("#vreal7").val(),
                    "perCode": $("#period1").val(),
                    "fecRegistro": $("#fecreg7").val(),
                    "fecCreacion": $("#feccrea7").val(),
                    "codRespon": $("#feccrea7").val()
                };
                
                if($("#vreal7").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea7").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form8" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode8").val(),
                    "valorMin": $("#vminimo8").val(),
                    "valorMax": $("#vmaximo8").val(),
                    "valorReal": $("#vreal8").val(),
                    "perCode": $("#period8").val(),
                    "fecRegistro": $("#fecreg8").val(),
                    "fecCreacion": $("#feccrea8").val(),
                    "codRespon": $("#feccrea8").val()
                };
                
                if($("#vreal8").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea8").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form9" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode9").val(),
                    "valorMin": $("#vminimo9").val(),
                    "valorMax": $("#vmaximo9").val(),
                    "valorReal": $("#vreal9").val(),
                    "perCode": $("#period9").val(),
                    "fecRegistro": $("#fecreg9").val(),
                    "fecCreacion": $("#feccrea9").val(),
                    "codRespon": $("#feccrea9").val()
                };
                
                if($("#vreal9").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea9").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form10" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode10").val(),
                    "valorMin": $("#vminimo10").val(),
                    "valorMax": $("#vmaximo10").val(),
                    "valorReal": $("#vreal10").val(),
                    "perCode": $("#period10").val(),
                    "fecRegistro": $("#fecreg10").val(),
                    "fecCreacion": $("#feccrea10").val(),
                    "codRespon": $("#feccrea1").val()
                };
                
                if($("#vreal10").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea10").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form11" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode11").val(),
                    "valorMin": $("#vminimo11").val(),
                    "valorMax": $("#vmaximo11").val(),
                    "valorReal": $("#vreal11").val(),
                    "perCode": $("#period11").val(),
                    "fecRegistro": $("#fecreg11").val(),
                    "fecCreacion": $("#feccrea11").val(),
                    "codRespon": $("#feccrea11").val()
                };
                
                if($("#vreal11").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea11").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form12" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode12").val(),
                    "valorMin": $("#vminimo12").val(),
                    "valorMax": $("#vmaximo12").val(),
                    "valorReal": $("#vreal12").val(),
                    "perCode": $("#period12").val(),
                    "fecRegistro": $("#fecreg12").val(),
                    "fecCreacion": $("#feccrea12").val(),
                    "codRespon": $("#feccrea12").val()
                };
                
                if($("#vreal12").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea12").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form13" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode13").val(),
                    "valorMin": $("#vminimo13").val(),
                    "valorMax": $("#vmaximo13").val(),
                    "valorReal": $("#vreal13").val(),
                    "perCode": $("#period13").val(),
                    "fecRegistro": $("#fecreg13").val(),
                    "fecCreacion": $("#feccrea13").val(),
                    "codRespon": $("#feccrea13").val()
                };
                
                if($("#vreal13").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea13").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form14" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode14").val(),
                    "valorMin": $("#vminimo14").val(),
                    "valorMax": $("#vmaximo14").val(),
                    "valorReal": $("#vreal14").val(),
                    "perCode": $("#period14").val(),
                    "fecRegistro": $("#fecreg14").val(),
                    "fecCreacion": $("#feccrea14").val(),
                    "codRespon": $("#feccrea14").val()
                };
                
                if($("#vreal1").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea1").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form15" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode15").val(),
                    "valorMin": $("#vminimo15").val(),
                    "valorMax": $("#vmaximo15").val(),
                    "valorReal": $("#vreal15").val(),
                    "perCode": $("#period15").val(),
                    "fecRegistro": $("#fecreg15").val(),
                    "fecCreacion": $("#feccrea15").val(),
                    "codRespon": $("#feccrea15").val()
                };
                
                if($("#vreal15").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea15").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form16" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode16").val(),
                    "valorMin": $("#vminimo16").val(),
                    "valorMax": $("#vmaximo16").val(),
                    "valorReal": $("#vreal16").val(),
                    "perCode": $("#period16").val(),
                    "fecRegistro": $("#fecreg16").val(),
                    "fecCreacion": $("#feccrea16").val(),
                    "codRespon": $("#feccrea16").val()
                };
                
                if($("#vreal16").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea16").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form17" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode17").val(),
                    "valorMin": $("#vminimo17").val(),
                    "valorMax": $("#vmaximo17").val(),
                    "valorReal": $("#vreal17").val(),
                    "perCode": $("#period17").val(),
                    "fecRegistro": $("#fecreg17").val(),
                    "fecCreacion": $("#feccrea17").val(),
                    "codRespon": $("#feccrea17").val()
                };
                
                if($("#vreal17").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea17").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form18" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode18").val(),
                    "valorMin": $("#vminimo18").val(),
                    "valorMax": $("#vmaximo18").val(),
                    "valorReal": $("#vreal18").val(),
                    "perCode": $("#period18").val(),
                    "fecRegistro": $("#fecreg18").val(),
                    "fecCreacion": $("#feccrea18").val(),
                    "codRespon": $("#feccrea18").val()
                };
                
                if($("#vreal18").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea18").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form19" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode19").val(),
                    "valorMin": $("#vminimo19").val(),
                    "valorMax": $("#vmaximo19").val(),
                    "valorReal": $("#vreal19").val(),
                    "perCode": $("#period19").val(),
                    "fecRegistro": $("#fecreg19").val(),
                    "fecCreacion": $("#feccrea19").val(),
                    "codRespon": $("#feccrea19").val()
                };
                
                if($("#vreal19").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea19").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form20" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode20").val(),
                    "valorMin": $("#vminimo20").val(),
                    "valorMax": $("#vmaximo20").val(),
                    "valorReal": $("#vreal20").val(),
                    "perCode": $("#period20").val(),
                    "fecRegistro": $("#fecreg20").val(),
                    "fecCreacion": $("#feccrea20").val(),
                    "codRespon": $("#feccrea20").val()
                };
                
                if($("#vreal20").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea20").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form21" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode21").val(),
                    "valorMin": $("#vminimo21").val(),
                    "valorMax": $("#vmaximo21").val(),
                    "valorReal": $("#vreal21").val(),
                    "perCode": $("#period21").val(),
                    "fecRegistro": $("#fecreg21").val(),
                    "fecCreacion": $("#feccrea21").val(),
                    "codRespon": $("#feccrea21").val()
                };
                
                if($("#vreal21").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea21").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form22" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode22").val(),
                    "valorMin": $("#vminimo22").val(),
                    "valorMax": $("#vmaximo22").val(),
                    "valorReal": $("#vreal22").val(),
                    "perCode": $("#period22").val(),
                    "fecRegistro": $("#fecreg22").val(),
                    "fecCreacion": $("#feccrea22").val(),
                    "codRespon": $("#feccrea22").val()
                };
                
                if($("#vreal22").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea22").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form23" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode23").val(),
                    "valorMin": $("#vminimo23").val(),
                    "valorMax": $("#vmaximo23").val(),
                    "valorReal": $("#vreal23").val(),
                    "perCode": $("#period23").val(),
                    "fecRegistro": $("#fecreg23").val(),
                    "fecCreacion": $("#feccrea23").val(),
                    "codRespon": $("#feccrea23").val()
                };
                
                if($("#vreal23").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea23").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form24" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode24").val(),
                    "valorMin": $("#vminimo24").val(),
                    "valorMax": $("#vmaximo24").val(),
                    "valorReal": $("#vreal24").val(),
                    "perCode": $("#period24").val(),
                    "fecRegistro": $("#fecreg24").val(),
                    "fecCreacion": $("#feccrea24").val(),
                    "codRespon": $("#feccrea24").val()
                };
                
                if($("#vreal24").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea24").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form25" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode25").val(),
                    "valorMin": $("#vminimo25").val(),
                    "valorMax": $("#vmaximo25").val(),
                    "valorReal": $("#vreal25").val(),
                    "perCode": $("#period25").val(),
                    "fecRegistro": $("#fecreg25").val(),
                    "fecCreacion": $("#feccrea25").val(),
                    "codRespon": $("#feccrea25").val()
                };
                
                if($("#vreal25").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea25").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form26" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode26").val(),
                    "valorMin": $("#vminimo26").val(),
                    "valorMax": $("#vmaximo26").val(),
                    "valorReal": $("#vreal26").val(),
                    "perCode": $("#period26").val(),
                    "fecRegistro": $("#fecreg26").val(),
                    "fecCreacion": $("#feccrea26").val(),
                    "codRespon": $("#feccrea26").val()
                };
                
                if($("#vreal26").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea26").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form27" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode27").val(),
                    "valorMin": $("#vminimo27").val(),
                    "valorMax": $("#vmaximo27").val(),
                    "valorReal": $("#vreal27").val(),
                    "perCode": $("#period27").val(),
                    "fecRegistro": $("#fecreg27").val(),
                    "fecCreacion": $("#feccrea27").val(),
                    "codRespon": $("#feccrea27").val()
                };
                
                if($("#vreal27").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea27").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form28" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode28").val(),
                    "valorMin": $("#vminimo28").val(),
                    "valorMax": $("#vmaximo28").val(),
                    "valorReal": $("#vreal28").val(),
                    "perCode": $("#period28").val(),
                    "fecRegistro": $("#fecreg28").val(),
                    "fecCreacion": $("#feccrea28").val(),
                    "codRespon": $("#feccrea28").val()
                };
                
                if($("#vreal28").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea28").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form29" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode29").val(),
                    "valorMin": $("#vminimo29").val(),
                    "valorMax": $("#vmaximo29").val(),
                    "valorReal": $("#vreal29").val(),
                    "perCode": $("#period29").val(),
                    "fecRegistro": $("#fecreg29").val(),
                    "fecCreacion": $("#feccrea29").val(),
                    "codRespon": $("#feccrea29").val()
                };
                
                if($("#vreal29").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea29").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form30" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode30").val(),
                    "valorMin": $("#vminimo30").val(),
                    "valorMax": $("#vmaximo30").val(),
                    "valorReal": $("#vreal30").val(),
                    "perCode": $("#period30").val(),
                    "fecRegistro": $("#fecreg30").val(),
                    "fecCreacion": $("#feccrea30").val(),
                    "codRespon": $("#feccrea30").val()
                };
                
                if($("#vreal30").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea30").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form31" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode31").val(),
                    "valorMin": $("#vminimo31").val(),
                    "valorMax": $("#vmaximo31").val(),
                    "valorReal": $("#vreal31").val(),
                    "perCode": $("#period31").val(),
                    "fecRegistro": $("#fecreg31").val(),
                    "fecCreacion": $("#feccrea31").val(),
                    "codRespon": $("#feccrea31").val()
                };
                
                if($("#vreal31").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea31").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form32" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode32").val(),
                    "valorMin": $("#vminimo32").val(),
                    "valorMax": $("#vmaximo32").val(),
                    "valorReal": $("#vreal32").val(),
                    "perCode": $("#period32").val(),
                    "fecRegistro": $("#fecreg32").val(),
                    "fecCreacion": $("#feccrea32").val(),
                    "codRespon": $("#feccrea32").val()
                };
                
                if($("#vreal32").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea32").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form33" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode33").val(),
                    "valorMin": $("#vminimo33").val(),
                    "valorMax": $("#vmaximo33").val(),
                    "valorReal": $("#vreal33").val(),
                    "perCode": $("#period33").val(),
                    "fecRegistro": $("#fecreg33").val(),
                    "fecCreacion": $("#feccrea33").val(),
                    "codRespon": $("#feccrea33").val()
                };
                
                if($("#vreal33").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea33").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form34" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode34").val(),
                    "valorMin": $("#vminimo34").val(),
                    "valorMax": $("#vmaximo34").val(),
                    "valorReal": $("#vreal34").val(),
                    "perCode": $("#period34").val(),
                    "fecRegistro": $("#fecreg34").val(),
                    "fecCreacion": $("#feccrea34").val(),
                    "codRespon": $("#feccrea34").val()
                };
                
                if($("#vreal34").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea34").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form35" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode35").val(),
                    "valorMin": $("#vminimo35").val(),
                    "valorMax": $("#vmaximo35").val(),
                    "valorReal": $("#vreal35").val(),
                    "perCode": $("#period35").val(),
                    "fecRegistro": $("#fecreg35").val(),
                    "fecCreacion": $("#feccrea35").val(),
                    "codRespon": $("#feccrea35").val()
                };
                
                if($("#vreal35").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea35").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form36" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode36").val(),
                    "valorMin": $("#vminimo36").val(),
                    "valorMax": $("#vmaximo36").val(),
                    "valorReal": $("#vreal36").val(),
                    "perCode": $("#period36").val(),
                    "fecRegistro": $("#fecreg36").val(),
                    "fecCreacion": $("#feccrea36").val(),
                    "codRespon": $("#feccrea36").val()
                };
                
                if($("#vreal36").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea36").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form37" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode37").val(),
                    "valorMin": $("#vminimo37").val(),
                    "valorMax": $("#vmaximo37").val(),
                    "valorReal": $("#vreal37").val(),
                    "perCode": $("#period37").val(),
                    "fecRegistro": $("#fecreg37").val(),
                    "fecCreacion": $("#feccrea37").val(),
                    "codRespon": $("#feccrea37").val()
                };
                
                if($("#vreal37").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea37").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form38" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode38").val(),
                    "valorMin": $("#vminimo38").val(),
                    "valorMax": $("#vmaximo38").val(),
                    "valorReal": $("#vreal38").val(),
                    "perCode": $("#period38").val(),
                    "fecRegistro": $("#fecreg38").val(),
                    "fecCreacion": $("#feccrea38").val(),
                    "codRespon": $("#feccrea38").val()
                };
                
                if($("#vreal38").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea38").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form39" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode39").val(),
                    "valorMin": $("#vminimo39").val(),
                    "valorMax": $("#vmaximo39").val(),
                    "valorReal": $("#vreal39").val(),
                    "perCode": $("#period39").val(),
                    "fecRegistro": $("#fecreg39").val(),
                    "fecCreacion": $("#feccrea39").val(),
                    "codRespon": $("#feccrea39").val()
                };
                
                if($("#vreal39").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea39").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form40" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode40").val(),
                    "valorMin": $("#vminimo40").val(),
                    "valorMax": $("#vmaximo40").val(),
                    "valorReal": $("#vreal40").val(),
                    "perCode": $("#period40").val(),
                    "fecRegistro": $("#fecreg40").val(),
                    "fecCreacion": $("#feccrea40").val(),
                    "codRespon": $("#feccrea40").val()
                };
                
                if($("#vreal40").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea40").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form41" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode41").val(),
                    "valorMin": $("#vminimo41").val(),
                    "valorMax": $("#vmaximo41").val(),
                    "valorReal": $("#vreal41").val(),
                    "perCode": $("#period41").val(),
                    "fecRegistro": $("#fecreg41").val(),
                    "fecCreacion": $("#feccrea41").val(),
                    "codRespon": $("#feccrea41").val()
                };
                
                if($("#vreal41").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea41").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form42" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode42").val(),
                    "valorMin": $("#vminimo42").val(),
                    "valorMax": $("#vmaximo42").val(),
                    "valorReal": $("#vreal42").val(),
                    "perCode": $("#period42").val(),
                    "fecRegistro": $("#fecreg42").val(),
                    "fecCreacion": $("#feccrea42").val(),
                    "codRespon": $("#feccrea42").val()
                };
                
                if($("#vreal42").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea42").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form43" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode43").val(),
                    "valorMin": $("#vminimo43").val(),
                    "valorMax": $("#vmaximo43").val(),
                    "valorReal": $("#vreal43").val(),
                    "perCode": $("#period43").val(),
                    "fecRegistro": $("#fecreg43").val(),
                    "fecCreacion": $("#feccrea43").val(),
                    "codRespon": $("#feccrea43").val()
                };
                
                if($("#vreal43").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea43").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form44" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode44").val(),
                    "valorMin": $("#vminimo44").val(),
                    "valorMax": $("#vmaximo44").val(),
                    "valorReal": $("#vreal44").val(),
                    "perCode": $("#period44").val(),
                    "fecRegistro": $("#fecreg44").val(),
                    "fecCreacion": $("#feccrea44").val(),
                    "codRespon": $("#feccrea44").val()
                };
                
                if($("#vreal44").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea44").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form45" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode45").val(),
                    "valorMin": $("#vminimo45").val(),
                    "valorMax": $("#vmaximo45").val(),
                    "valorReal": $("#vreal45").val(),
                    "perCode": $("#period45").val(),
                    "fecRegistro": $("#fecreg45").val(),
                    "fecCreacion": $("#feccrea45").val(),
                    "codRespon": $("#feccrea45").val()
                };
                
                if($("#vreal45").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea45").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form46" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode46").val(),
                    "valorMin": $("#vminimo46").val(),
                    "valorMax": $("#vmaximo46").val(),
                    "valorReal": $("#vreal46").val(),
                    "perCode": $("#period46").val(),
                    "fecRegistro": $("#fecreg46").val(),
                    "fecCreacion": $("#feccrea46").val(),
                    "codRespon": $("#feccrea46").val()
                };
                
                if($("#vreal46").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea46").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form47" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode47").val(),
                    "valorMin": $("#vminimo47").val(),
                    "valorMax": $("#vmaximo47").val(),
                    "valorReal": $("#vreal47").val(),
                    "perCode": $("#period47").val(),
                    "fecRegistro": $("#fecreg47").val(),
                    "fecCreacion": $("#feccrea47").val(),
                    "codRespon": $("#feccrea47").val()
                };
                
                if($("#vreal47").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea47").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form48" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode48").val(),
                    "valorMin": $("#vminimo48").val(),
                    "valorMax": $("#vmaximo48").val(),
                    "valorReal": $("#vreal48").val(),
                    "perCode": $("#period48").val(),
                    "fecRegistro": $("#fecreg48").val(),
                    "fecCreacion": $("#feccrea48").val(),
                    "codRespon": $("#feccrea48").val()
                };

                if ($("#vreal48").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }

                if ($("#feccrea48").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $( "#dialog-form49" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode49").val(),
                    "valorMin": $("#vminimo49").val(),
                    "valorMax": $("#vmaximo49").val(),
                    "valorReal": $("#vreal49").val(),
                    "perCode": $("#period49").val(),
                    "fecRegistro": $("#fecreg49").val(),
                    "fecCreacion": $("#feccrea49").val(),
                    "codRespon": $("#feccrea49").val()
                };
                
                if($("#vreal49").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea49").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form50" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode50").val(),
                    "valorMin": $("#vminimo50").val(),
                    "valorMax": $("#vmaximo50").val(),
                    "valorReal": $("#vreal50").val(),
                    "perCode": $("#period50").val(),
                    "fecRegistro": $("#fecreg50").val(),
                    "fecCreacion": $("#feccrea50").val(),
                    "codRespon": $("#feccrea50").val()
                };
                
                if($("#vreal50").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea50").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form51" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode51").val(),
                    "valorMin": $("#vminimo51").val(),
                    "valorMax": $("#vmaximo51").val(),
                    "valorReal": $("#vreal51").val(),
                    "perCode": $("#period51").val(),
                    "fecRegistro": $("#fecreg51").val(),
                    "fecCreacion": $("#feccrea51").val(),
                    "codRespon": $("#feccrea51").val()
                };
                
                if($("#vreal51").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea51").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form52" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode52").val(),
                    "valorMin": $("#vminimo52").val(),
                    "valorMax": $("#vmaximo52").val(),
                    "valorReal": $("#vreal52").val(),
                    "perCode": $("#period52").val(),
                    "fecRegistro": $("#fecreg52").val(),
                    "fecCreacion": $("#feccrea52").val(),
                    "codRespon": $("#feccrea52").val()
                };
                
                if($("#vreal52").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea52").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form53" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode53").val(),
                    "valorMin": $("#vminimo53").val(),
                    "valorMax": $("#vmaximo53").val(),
                    "valorReal": $("#vreal53").val(),
                    "perCode": $("#period53").val(),
                    "fecRegistro": $("#fecreg53").val(),
                    "fecCreacion": $("#feccrea53").val(),
                    "codRespon": $("#feccrea53").val()
                };
                
                if($("#vreal53").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea53").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $( "#dialog-form54" ).dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "kpiCode": $("#kpicode54").val(),
                    "valorMin": $("#vminimo54").val(),
                    "valorMax": $("#vmaximo54").val(),
                    "valorReal": $("#vreal54").val(),
                    "perCode": $("#period54").val(),
                    "fecRegistro": $("#fecreg54").val(),
                    "fecCreacion": $("#feccrea54").val(),
                    "codRespon": $("#feccrea54").val()
                };
                
                if($("#vreal54").val() == "") {
                    window.alert("El campo valor real es obligatorio")
                    return false;
                }
                
                if($("#feccrea54").val() == "") {
                    window.alert("Se necesita la fecha del indicador")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success:  function (response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    $("#dialog-btn1").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio1").val(),
                    "ffin": $("#ffin1").val(),
                    "valorReal": $("#vreal1").val(),
                    "kpiCode": $("#kpicode1").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn2").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio2").val(),
                    "ffin": $("#ffin2").val(),
                    "valorReal": $("#vreal2").val(),
                    "kpiCode": $("#kpicode2").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn3").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio3").val(),
                    "ffin": $("#ffin3").val(),
                    "valorReal": $("#vreal3").val(),
                    "kpiCode": $("#kpicode3").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn4").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio4").val(),
                    "ffin": $("#ffin4").val(),
                    "valorReal": $("#vreal4").val(),
                    "kpiCode": $("#kpicode4").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn5").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio5").val(),
                    "ffin": $("#ffin5").val(),
                    "valorReal": $("#vreal5").val(),
                    "kpiCode": $("#kpicode5").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn6").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio6").val(),
                    "ffin": $("#ffin6").val(),
                    "valorReal": $("#vreal6").val(),
                    "kpiCode": $("#kpicode6").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn7").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio7").val(),
                    "ffin": $("#ffin7").val(),
                    "valorReal": $("#vreal7").val(),
                    "kpiCode": $("#kpicode7").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn8").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio8").val(),
                    "ffin": $("#ffin8").val(),
                    "valorReal": $("#vreal8").val(),
                    "kpiCode": $("#kpicode8").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn9").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio9").val(),
                    "ffin": $("#ffin9").val(),
                    "valorReal": $("#vreal9").val(),
                    "kpiCode": $("#kpicode9").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn10").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio10").val(),
                    "ffin": $("#ffin10").val(),
                    "valorReal": $("#vreal10").val(),
                    "kpiCode": $("#kpicode10").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn11").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio11").val(),
                    "ffin": $("#ffin11").val(),
                    "valorReal": $("#vreal11").val(),
                    "kpiCode": $("#kpicode11").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn12").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio12").val(),
                    "ffin": $("#ffin12").val(),
                    "valorReal": $("#vreal12").val(),
                    "kpiCode": $("#kpicode12").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn13").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio13").val(),
                    "ffin": $("#ffin13").val(),
                    "valorReal": $("#vreal13").val(),
                    "kpiCode": $("#kpicode13").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn14").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio14").val(),
                    "ffin": $("#ffin14").val(),
                    "valorReal": $("#vreal14").val(),
                    "kpiCode": $("#kpicode14").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn15").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio15").val(),
                    "ffin": $("#ffin15").val(),
                    "valorReal": $("#vreal15").val(),
                    "kpiCode": $("#kpicode15").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn16").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio16").val(),
                    "ffin": $("#ffin16").val(),
                    "valorReal": $("#vreal16").val(),
                    "kpiCode": $("#kpicode16").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn17").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio17").val(),
                    "ffin": $("#ffin17").val(),
                    "valorReal": $("#vreal17").val(),
                    "kpiCode": $("#kpicode17").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn18").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio18").val(),
                    "ffin": $("#ffin18").val(),
                    "valorReal": $("#vreal18").val(),
                    "kpiCode": $("#kpicode18").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn19").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio19").val(),
                    "ffin": $("#ffin19").val(),
                    "valorReal": $("#vreal19").val(),
                    "kpiCode": $("#kpicode19").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn20").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio20").val(),
                    "ffin": $("#ffin20").val(),
                    "valorReal": $("#vreal20").val(),
                    "kpiCode": $("#kpicode20").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn21").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio21").val(),
                    "ffin": $("#ffin21").val(),
                    "valorReal": $("#vreal21").val(),
                    "kpiCode": $("#kpicode21").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn22").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio22").val(),
                    "ffin": $("#ffin22").val(),
                    "valorReal": $("#vreal22").val(),
                    "kpiCode": $("#kpicode22").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn23").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio23").val(),
                    "ffin": $("#ffin23").val(),
                    "valorReal": $("#vreal23").val(),
                    "kpiCode": $("#kpicode23").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn24").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio24").val(),
                    "ffin": $("#ffin24").val(),
                    "valorReal": $("#vreal24").val(),
                    "kpiCode": $("#kpicode24").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn25").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio25").val(),
                    "ffin": $("#ffin25").val(),
                    "valorReal": $("#vreal25").val(),
                    "kpiCode": $("#kpicode25").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn26").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio26").val(),
                    "ffin": $("#ffin26").val(),
                    "valorReal": $("#vreal26").val(),
                    "kpiCode": $("#kpicode26").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn27").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio27").val(),
                    "ffin": $("#ffin27").val(),
                    "valorReal": $("#vreal27").val(),
                    "kpiCode": $("#kpicode27").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn28").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio28").val(),
                    "ffin": $("#ffin28").val(),
                    "valorReal": $("#vreal28").val(),
                    "kpiCode": $("#kpicode28").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn29").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio29").val(),
                    "ffin": $("#ffin29").val(),
                    "valorReal": $("#vreal29").val(),
                    "kpiCode": $("#kpicode29").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn30").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio30").val(),
                    "ffin": $("#ffin30").val(),
                    "valorReal": $("#vreal30").val(),
                    "kpiCode": $("#kpicode30").val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn31").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn32").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn33").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn34").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn35").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn36").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn37").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn38").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn39").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn40").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn41").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn42").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn43").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn44").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn45").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn46").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn47").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn48").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn49").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn50").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn51").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn52").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn53").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#dialog-btn54").dialog({
        autoOpen: false,
        height: 230,
        width: 350,
        modal: true,
        buttons: {
            "Aceptar": function() {
                var parametros = {
                    "finicio": $("#finicio" + i).val(),
                    "ffin": $("#ffin" + i).val(),
                    "valorReal": $("#vreal" + i).val(),
                    "kpiCode": $("#kpicode" + i).val(),
                };

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha de inicio")
                    return false;
                }

                if ($("#finicio").val() == "") {
                    window.alert("Se necesita fecha fin")
                    return false;
                }

                $.ajax({
                    data: parametros,
                    url: 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/save',
                    type: 'post',
                    success: function(response) {
                        window.alert("Registro guardado con exito");
                        location.href = 'http://nazca/mimco_dev_3/index.php/indicadores/indicadores/listar';
                    }
                });
            },
            "Cancelar": function() {
                $(this).dialog("close");
            }
        }
    });
    
    $("#formbtn1").click(function() {
        $("#dialog-form1").dialog("open");
    });
    
    $("#formbtn2").click(function() {
        $("#dialog-form2").dialog("open");
    });
    
    $("#formbtn3").click(function() {
        $("#dialog-form3").dialog("open");
    });
    
    $("#formbtn4").click(function() {
        $("#dialog-form4").dialog("open");
    });
    
    $("#formbtn5").click(function() {
        $("#dialog-form5").dialog("open");
    });
    
    $("#formbtn6").click(function() {
        $("#dialog-form6").dialog("open");
    });
    
    $("#formbtn7").click(function() {
        $("#dialog-form7").dialog("open");
    });
    
    $("#formbtn8").click(function() {
        $("#dialog-form8").dialog("open");
    });
    
    $("#formbtn9").click(function() {
        $("#dialog-form9").dialog("open");
    });
    
    $("#formbtn10").click(function() {
        $("#dialog-form10").dialog("open");
    });
    
    $("#formbtn11").click(function() {
        $("#dialog-form11").dialog("open");
    });
    
    $("#formbtn12").click(function() {
        $("#dialog-form12").dialog("open");
    });
    
    $("#formbtn13").click(function() {
        $("#dialog-form13").dialog("open");
    });
    
    $("#formbtn14").click(function() {
        $("#dialog-form14").dialog("open");
    });
    
    $("#formbtn15").click(function() {
        $("#dialog-form15").dialog("open");
    });
    
    $("#formbtn16").click(function() {
        $("#dialog-form16").dialog("open");
    });
    
    $("#formbtn17").click(function() {
        $("#dialog-form17").dialog("open");
    });
    
    $("#formbtn18").click(function() {
        $("#dialog-form18").dialog("open");
    });
    
    $("#formbtn19").click(function() {
        $("#dialog-form19").dialog("open");
    });
    
    $("#formbtn20").click(function() {
        $("#dialog-form20").dialog("open");
    });
    
    $("#formbtn21").click(function() {
        $("#dialog-form21").dialog("open");
    });
    
    $("#formbtn22").click(function() {
        $("#dialog-form22").dialog("open");
    });
    
    $("#formbtn23").click(function() {
        $("#dialog-form23").dialog("open");
    });
    
    $("#formbtn24").click(function() {
        $("#dialog-form24").dialog("open");
    });
    
    $("#formbtn25").click(function() {
        $("#dialog-form25").dialog("open");
    });
    
    $("#formbtn26").click(function() {
        $("#dialog-form26").dialog("open");
    });
    
    $("#formbtn27").click(function() {
        $("#dialog-form27").dialog("open");
    });
    
    $("#formbtn28").click(function() {
        $("#dialog-form28").dialog("open");
    });
    
    $("#formbtn29").click(function() {
        $("#dialog-form29").dialog("open");
    });
    
    $("#formbtn30").click(function() {
        $("#dialog-form30").dialog("open");
    });
    
    $("#formbtn31").click(function() {
        $("#dialog-form31").dialog("open");
    });
    
    $("#formbtn32").click(function() {
        $("#dialog-form32").dialog("open");
    });
    
    $("#formbtn33").click(function() {
        $("#dialog-form33").dialog("open");
    });
    
    $("#formbtn34").click(function() {
        $("#dialog-form34").dialog("open");
    });
    
    $("#formbtn35").click(function() {
        $("#dialog-form35").dialog("open");
    });
    
    $("#formbtn36").click(function() {
        $("#dialog-form36").dialog("open");
    });
    
    $("#formbtn37").click(function() {
        $("#dialog-form37").dialog("open");
    });
    
    $("#formbtn38").click(function() {
        $("#dialog-form38").dialog("open");
    });
    
    $("#formbtn39").click(function() {
        $("#dialog-form39").dialog("open");
    });
    
    $("#formbtn40").click(function() {
        $("#dialog-form40").dialog("open");
    });
    
    $("#formbtn41").click(function() {
        $("#dialog-form41").dialog("open");
    });
    
    $("#formbtn42").click(function() {
        $("#dialog-form42").dialog("open");
    });
    
    $("#formbtn43").click(function() {
        $("#dialog-form43").dialog("open");
    });
    
    $("#formbtn44").click(function() {
        $("#dialog-form44").dialog("open");
    });
    
    $("#formbtn45").click(function() {
        $("#dialog-form45").dialog("open");
    });
    
    $("#formbtn46").click(function() {
        $("#dialog-form46").dialog("open");
    });
    
    $("#formbtn47").click(function() {
        $("#dialog-form47").dialog("open");
    });
    
    $("#formbtn48").click(function() {
        $("#dialog-form48").dialog("open");
    });
    
    $("#formbtn49").click(function() {
        $("#dialog-form49").dialog("open");
    });
    
    $("#formbtn50").click(function() {
        $("#dialog-form50").dialog("open");
    });
    
    $("#formbtn51").click(function() {
        $("#dialog-form51").dialog("open");
    });
    
    $("#formbtn52").click(function() {
        $("#dialog-form52").dialog("open");
    });
    
    $("#formbtn53").click(function() {
        $("#dialog-form53").dialog("open");
    });
    
    $("#formbtn54").click(function() {
        $("#dialog-form54").dialog("open");
    });
    
    $( "#feccrea1" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea2" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea3" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea4" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea5" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea6" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea7" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea8" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea9" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea10" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea11" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea12" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea13" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea14" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea15" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea16" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea17" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea18" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea19" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea20" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea21" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea22" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea23" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea24" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea25" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea26" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea27" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea28" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea29" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea30" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea31" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea32" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea33" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea34" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea35" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea36" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea37" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea38" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea39" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea40" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea41" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea42" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea43" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea44" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea45" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea46" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea47" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea48" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea50" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea51" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea52" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea53" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#feccrea54" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#finicio8" ).datepicker({
        showButtonPanel: true
    });
    
    $( "#ffin8" ).datepicker({
        showButtonPanel: true
    });
    
    for(i = 1; i <= 54 ; i++){
        $("#finicio" + i).datepicker({
            showButtonPanel: true
        });
        $("#finicio" + i).datepicker({
            showButtonPanel: true
        });
    }
    
    for (j = 1; j <= 54; j++) {
        $("#btn" + j).click(function() {
            window.alert("#btn" + j);
            $("#dialog-btn" + j).dialog("open");
        });
    }
    
    $("#btn1").click(function(){
        $("#dialog-btn1").dialog("open");
    });
    
    $("#btn2").click(function(){
        $("#dialog-btn2").dialog("open");
    });
    
    $("#btn3").click(function(){
        $("#dialog-btn3").dialog("open");
    });
    
    $("#btn4").click(function(){
        $("#dialog-btn4").dialog("open");
    });
    
    $("#btn5").click(function(){
        $("#dialog-btn5").dialog("open");
    });
    
    $("#btn6").click(function(){
        $("#dialog-btn6").dialog("open");
    });
    
    $("#btn7").click(function(){
        $("#dialog-btn7").dialog("open");
    });
    
    $("#btn8").click(function(){
        $("#dialog-btn8").dialog("open");
    });
    
    $("#btn9").click(function(){
        $("#dialog-btn9").dialog("open");
    });
    
    $("#btn10").click(function(){
        $("#dialog-btn10").dialog("open");
    });
    
    $("#btn11").click(function(){
        $("#dialog-btn10").dialog("open");
    });
    
    $("#btn12").click(function(){
        $("#dialog-btn10").dialog("open");
    });
    
    $("#btn13").click(function(){
        $("#dialog-btn10").dialog("open");
    });
    
    $("#btn14").click(function(){
        $("#dialog-btn10").dialog("open");
    });
    
    $("#btn15").click(function(){
        $("#dialog-btn10").dialog("open");
    });
    
    $("#btn16").click(function(){
        $("#dialog-btn10").dialog("open");
    });
//    $("#btn1").click(function() {
//        $('#graf_container').html("");
//        $('#graf_container').highcharts({
//            
//            chart: {
//                type: 'line',
//                marginRight: 130,
//                marginBottom: 25
//            },
//            title: {
//                text: 'Monthly Average Temperature',
//                x: -20 //center
//            },
//            subtitle: {
//                text: 'Source: WorldClimate.com',
//                x: -20
//            },
//            xAxis: {
//                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
//                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
//            },
//            yAxis: {
//                title: {
//                    text: 'Temperature (°C)'
//                },
//                plotLines: [{
//                    value: 0,
//                    width: 1,
//                    color: '#808080'
//                }]
//            },
//            tooltip: {
//                valueSuffix: '°C'
//            },
//            legend: {
//                layout: 'vertical',
//                align: 'right',
//                verticalAlign: 'top',
//                x: -10,
//                y: 100,
//                borderWidth: 0
//            },
//            series: [{
//                name: 'Tokyo',
//                data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5]
//            }, {
//                name: 'New York',
//                data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1]
//            }, {
//                name: 'Berlin',
//                data: [-0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9]
//            }, {
//                name: 'London',
//                data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
//            }]
//        });
//    });
    
//    $("#btn2").click(function() {
//        $('#graf_container').html("");
//        $('#graf_container').highcharts({
//            exporting: {
//                enabled: false
//            },
//            chart: {
//                type: 'spline',
//                inverted: true,
//                width: 500,
//                style: {
//                    margin: '0 auto'
//                }
//            },
//            title: {
//                text: 'Atmosphere Temperature by Altitude'
//            },
//            subtitle: {
//                text: 'According to the Standard Atmosphere Model'
//            },
//            xAxis: {
//                reversed: false,
//                title: {
//                    enabled: true,
//                    text: 'Altitude'
//                },
//                labels: {
//                    formatter: function() {
//                        return this.value +'km';
//                    }
//                },
//                maxPadding: 0.05,
//                showLastLabel: true
//            },
//            yAxis: {
//                title: {
//                    text: 'Temperature'
//                },
//                labels: {
//                    formatter: function() {
//                        return this.value + 'Â°';
//                    }
//                },
//                lineWidth: 2
//            },
//            legend: {
//                enabled: false
//            },
//            tooltip: {
//                headerFormat: '<b>{series.name}</b><br/>',
//                pointFormat: '{point.x} km: {point.y}Â°C'
//            },
//            plotOptions: {
//                spline: {
//                    marker: {
//                        enable: false
//                    }
//                }
//            },
//            series: [{
//                name: 'Temperature',
//                data: [[0, 15], [10, -50], [20, -56.5], [30, -46.5], [40, -22.1],
//                    [50, -2.5], [60, -27.7], [70, -55.7], [80, -76.5]]
//            }]
//        });
//    });
});

