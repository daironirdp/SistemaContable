
<?php
//carga las clases en el modelo para listar 
require_once '../Modelo/Conexion.php';
require_once '../Modelo/CM_Contabilidad.php';
require_once '../Modelo/CM_Cuentas.php';
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Sistema contable para la gestion de taxistas</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum‐scale=1.0, user‐scalable=no">
        <meta name="description" content="">
        <meta name="author" content="">


        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]-->

        <!--[endif]-->

        <!-- Fav and touch icons -->

        <!-- incluimos los ficheros js-->
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/miEstilo.css">

        <script src="js/jquery-3.4.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/miCodigo.js"></script>
    </head>
    <?php
    $opcion = $_GET['opcion'];
//pagina activa en dependencia de la variable $opcion
    $clientes = '';
    $cuentas = '';
    $annos = '';

    if (ctype_digit($opcion)) {
        switch ($opcion) {
            case 0: $page = './paginas/Clientes.php';
                $clientes = 'active';
                break;
            case 1: $page = './paginas/Cuentas.php';
                $cuentas = 'active';
                break;
            case 2: $page = './paginas/Annos.php';
                $annos = 'active';
                break;
            case 3: $page = './paginas/SubPaginas/Detalles_Cliente.php';
                $clientes = 'active';
                break;
            case 4: $page = './paginas/SubPaginas/Detalles_Cliente_Mes.php';
                $clientes = 'active';
                break;
            case 5: $page = './paginas/SubPaginas/Vista_Contabilidad.php';
                $clientes = 'active';
                break;
            default: $annos = './paginas/error.php';
                break;
        }
    } else {
        $page = './paginas/error.php';
    }
    ?>
    <body class="">



        <!-- presentacion de la pagina !-->



        <div class="container-fluid">

            <!-- menu de navegacion de la pagina !-->

            <nav class="navbar navbar-default" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"><span class="sr-only ">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
                    <a class="navbar-brand active" href="?opcion=0">Inicio</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">

                        <li class=" <?php echo $clientes ?>"><a class="navbar-brand " href="?opcion=0">Clientes</a></li>
                        <li class="<?php echo $cuentas ?>"><a href="?opcion=1">Administrar cuentas</a></li>
                        <li class="<?php echo $annos; ?>"><a href="?opcion=2">Administrar annos</a></li>

                    </ul>


                </div>

            </nav>



            <!-- contenido principal de la pagina!-->
            <div class="container" id="seccion1_content">

                <div class="container" id="seccion1" style="">

                    <?php
                    include($page);
                    ?>


                </div>

            </div>


        </div>



    </body>


    <footer class="panel-footer page-footer font-small ">

        <div class="container">

            <p> &REG; Sitio para gestionar choferes.</p>
            <ul class="redesSociales">
                <li><a href="www.facebook.com"><i class="fa fa-facebook" title="facebook"></i></a></li>
                <li><a href="www.twitter.com"><i class="fa fa-twitter" title="twitter"></i></a></li>
                <li><a href="www.youtube.com"><i class="fa fa-youtube" title="youtube"></i></a></li>
            </ul>

        </div>

    </footer>

    <script>
        var opcion =<?php echo $opcion?>;
        switch(opcion){
            case 0:{
                 $("footer").addClass("navbar-fixed-bottom");   
            }
                break;
            case 1:{$("footer").removeClass("navbar-fixed-bottom");}
                break;
            case 2:{$("footer").addClass("navbar-fixed-bottom");}
                break;
           case 3:{ $("footer").addClass("navbar-fixed-bottom"); }
                break;
            case 4:{ $("footer").addClass("navbar-fixed-bottom"); }
                break;
            case 5:{$("footer").removeClass("navbar-fixed-bottom");}
                break;
             default:{$("footer").removeClass("navbar-fixed-bottom");}
           
        }
    
    
    </script>
</html>