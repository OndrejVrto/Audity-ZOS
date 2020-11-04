<?php

class Page
{
    // Vlastnosti třídy Page
    public $content;
    public $nadpis;
    public $title;
    public $description;
    public $bubbleMenu = array();
    public $odsadenie = 5;
    public $list = 1;
    private $link;
    public $hlavneMenu;
    protected $_nazovstranky;
    private $aktivnemenu = false;
    public $zobrazitBublinky = true;
    public $skriptySpecial = '';

    public $styles = '
    <!-- START - CSS štandard -->

    <!-- Font Awesome -->
    <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/dist/css/adminlte.css">
    <!-- Ionicons -->
    <!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
    <link rel="stylesheet" href="/dist/css/ionicons/css/ionicons.min.css">
    <!-- Google Font: Source Sans Pro -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> -->
    <link rel="stylesheet" href="/dist/css/www/fonts.googleapis.css">    

    <!-- END - CSS štandard -->    
';

    public $skripty = '
    <!-- START - skripty štandard -->

    <!-- jQuery -->
    <script src="/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/dist/js/adminlte.js"></script>    
    <!-- AdminLTE for demo purposes -->
    <script src="/dist/js/demo.js"></script>

    <!-- END - skripty štandard -->    
';

    // Metody třídy Page
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    function __construct($link, $listStranky)
    {
        session_start();

        header('Content-Type: text/html; charset=utf-8');

        $path = $_SERVER['DOCUMENT_ROOT'] . "/include/";
        // Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
        spl_autoload_register(function ($class_name) {
            include_once  $path . "class/class.".$class_name.'.php';
        });
        
        // $conn pripojenie do databazy
        require $path . 'inc.dBconnect.php';
        require $path . 'inc.dBfunction.php';
        // funkcie rozne 
        require $path . 'inc.funkction.php';
        // konstanty stránok
        require $path . '_variables.php';

        $this->link = $link;

        $premenne = new Premenne($link);
        $this->title = $premenne->titulokStranky;
        $this->nadpis = $premenne->nadpisPrvejSekcie;
        $this->description = $premenne->popisStranky;
        $this->bubbleMenu = $premenne->bublinkoveMenu;
        $this->hlavneMenu = $premenne->menuHlavne;
        $this->list = $listStranky;
    }

    public function display()
    {
        $this->displayBegin();
        $this->displayTitle();
        $this->displayDescription();
        $this->displayIcons();

        echo $this->styles;
        
        $this->displayBodyHeader();
        
        $this->displayTopMenu();
        
        $this->displayLeftMenuHeader();
        echo $this->displayLeftMenuSitebar($this->hlavneMenu);
        $this->displayLeftMenuFooter();
        
        if ($this->zobrazitBublinky) {
            $this->displayBubleMenuHeader();
            echo $this->displayBubleMenu($this->hlavneMenu);
            $this->displayBubleMenuFooter();
        }
       
        $this->displayContentHeader();
        echo $this->content;
        $this->displayContentFooter();
        
        $this->displayFooter();
        $this->displayBodyFooter();
        
        echo $this->skripty;
        echo $this->skriptySpecial;
        
        //$this->VYVOJ();
        echo "\n</body>\n</html>\n";
    }




    public function displayBegin()
    {
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="language" content="Slovak">
    <meta name="google" content="noimageindex, notranslate">
    <meta name="robots" content="noindex, nofollow">
    <meta name="author" content="Ing. Ondrej VRŤO, IWE">
<?php
    }

    public function displayTitle()
    {
        echo "\n\t<title>" . $this->title . "</title>\n";
    }

    public function displayDescription()
    {
        echo "\n\t<meta name='description' content='" . $this->description . "'/>\n";
    }

    public function displayIcons()
    {
?>

    <!--  Ikony stránky generované cez realfavicongenerator.net -->
    <link rel="shortcut icon" href="/dist/ikon/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/dist/ikon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/dist/ikon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/dist/ikon/favicon-16x16.png">
    <link rel="manifest" href="/dist/ikon/site.webmanifest">
    <link rel="mask-icon" href="/dist/ikon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="apple-mobile-web-app-title" content="Audity ŽOS">
    <meta name="application-name" content="Audity ŽOS">
    <meta name="msapplication-TileColor" content="#2d89ef">
    <meta name="msapplication-config" content="/dist/ikon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

<?php
    }

    public function displayBodyHeader()
    {
?>
</head>

<body class="hold-transition sidebar-mini layout-navbar-fixed layout-fixed">
<div class="wrapper">
<?php
    }

    public function displayTopMenu()
    {
?>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="/" class="nav-link">Domov</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">Kontakt</a>
            </li>
        </ul>

        <!-- SEARCH FORM -->
        <form class="form-inline ml-3">
            <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Hľadaj ..." aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-navbar" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Messages Dropdown Menu -->

            <!-- Notifications Dropdown Menu -->

            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                    <i class="fas fa-th-large"></i>
                </a>
            </li>

            <!-- LogIn/LogOut -->
            <li class="nav-item">
<?php if (isset($_SESSION['userId'])) { ?>
				<form class="" action="/include/inc.logout.php" method="post">
					<input class="btn btn-primary" type="submit" name="logout-submit" value="LogOut">
				</form>
<?php } else { ?>
				<a class="btn btn-danger" href="/login">Login</a>
<?php } ?>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->
<?php
    }

    public function displayLogin()
    {
?>
<?php if (isset($_SESSION['userId'])) { ?>
            <div class="image">
                <img src="/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="/user-detail" class="d-block">Ing. Ondrej VRŤO</a>
            </div>
<?php } else { ?>
            <div class="image">
                <img src="/dist/img/user-anonymous.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="/login" class="d-block text-warning">NEPRIHLASENÝ</a>
            </div>
<?php } ?>

<?php
    }

    public function displayLeftMenuHeader()
    {
?>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-warning elevation-4">
        <!-- Brand Logo -->
        <a href="/" class="brand-link">
            <img src="/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Audity ŽOS</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
<?php $this->displayLogin(); ?>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                        with font-awesome or any other icon font library -->
<?php
    }

    public function displayLeftMenuSitebar($vstup)
    {

        $odsad = str_repeat("\t", $this->odsadenie );
        $html = '';
        $this->aktivnemenu = false;

        foreach ($vstup as $key => $value) {
            if (array_key_exists('Hlavicka', $value)) {
                    $html .= "\n".$odsad.'<li class="nav-header">';
                    $html .= "\n\t".$odsad.htmlspecialchars($value['Hlavicka']);
                    $html .= "\n".$odsad.'</li>';
            } else {
                if (is_array($value['SUBMENU'])) {
                    // rekurzívna funkcia - volá sama seba pri každej ďalšej vrste Menu !!!
                    $submenu = $this->displayLeftMenuSitebar($value['SUBMENU']);

                    $html .= "\n".$odsad.'<li class="nav-item has-treeview'.(($this->aktivnemenu === true) ? ' menu-open' : '' ).'">';
                    $html .= "\n\t".$odsad.'<a href="'. (($value['Link'] === false) ? '#' : $value['Link'] ).'" ';
                    $html .= 'class="nav-link'.(($this->aktivnemenu === true) ? ' active' : '' ).'">';
                    $html .= "\n\t\t".$odsad.'<i class="nav-icon '.(($value['Ikona'] === false) ? 'far fa-circle' : $value['Ikona'] ).'"></i>';
                    $html .= "\n\t\t".$odsad.'<p>';
                    $html .= "\n\t\t\t".$odsad.$value['Nazov'];
                    $html .= "\n\t\t\t".$odsad.'<i class="right fas fa-angle-left"></i>';
                    if ($value['Doplnok'] !== false) {
                        $html .= "\n\t\t\t".$odsad.'<span class="right '.$value['Doplnok'].'">';
                        $html .= "\n\t\t\t\t".$odsad.$value['PopisDoplnku'];
                        $html .= "\n\t\t\t".$odsad.'</span>';
                    }
                    $html .= "\n\t\t".$odsad.'</p>';
                    $html .= "\n\t".$odsad.'</a>';
                    $html .= "\n\t".$odsad.'<ul class="nav nav-treeview">';
                    
                    $html .= $submenu;

                    $html .= "\n\t".$odsad.'</ul>';

                } else {
                    if ($value['Link'] == $this->link) {
                        $this->aktivnemenu = true;
                        $activSubMenu = true;
                    } else {
                        $activSubMenu = false;
                    }
                    $html .= "\n".$odsad.'<li class="nav-item">';
                    $html .= "\n\t".$odsad.'<a href="'.(($value['Link'] === false) ? '#' : $value['Link'] ).'" ';
                    $html .= 'class="nav-link'.(($activSubMenu === true) ? ' active' : '' ).'">';
                    $html .= "\n\t\t".$odsad.'<i class="nav-icon '.(($value['Ikona'] === false) ? 'far fa-circle' : $value['Ikona'] ).'"></i>';
                    $html .= "\n\t\t".$odsad.'<p>';
                    $html .= "\n\t\t\t".$odsad.$value['Nazov'];
                    if ($value['Doplnok'] !== false) {
                        $html .= "\n\t\t\t".$odsad.'<span class="right '.$value['Doplnok'].'">';
                        $html .= "\n\t\t\t\t".$odsad.$value['PopisDoplnku'];
                        $html .= "\n\t\t\t".$odsad.'</span>';
                    }
                    $html .= "\n\t\t".$odsad.'</p>';
                    $html .= "\n\t".$odsad.'</a>';
                    $html .= "\n".$odsad.'</li>';
                }
            }
        }
        return $html;
    }

    public function displayLeftMenuFooter()
    {
?>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 order-2 order-md-1">
                <h1 class="m-0 text-dark"><?php echo $this->nadpis; ?></h1>
                </div><!-- /.col -->
                <div class="col-md-6 order-1 order-md-2">
<?php
    }

    public function displayBubleMenuHeader(){
        echo '
                <!-- START Include - Bublinkové menu -->
                <ol class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="/"><i class="fa fa-home" aria-hidden="true"></i><span class="sr-only">Domov</span></a></li>';
    }

    public function displayBubleMenu($vstup)
    {
        $this->odsadenie = 5;
        $odsad = str_repeat("\t", $this->odsadenie );
        $html = '';
        $this->aktivnemenu = false;

        foreach ($vstup as $key => $value) {
            if (array_key_exists('Hlavicka', $value)) {
                // nerob nič
            } else {
                if (is_array($value['SUBMENU'])) {
                    // rekurzívna funkcia - volá sama seba pri každej ďalšej vrste Menu !!!
                    $submenu = $this->displayBubleMenu($value['SUBMENU']);
                    if ($this->aktivnemenu === true) {
                        $html .= "\n".$odsad.'<li class="breadcrumb-item">';
                        if ($value['Link'] !== false) {
                            $html .= '<a href="'.$value['Link'].'">'.$value['Nazov'].'</a>';
                        } else {
                            $html .= $value['Nazov'];
                        }
                        $html .= '</li>';
                        $html .= $submenu;
                    }
                } else {
                    if ($value['Link'] == $this->link) {
                        $this->aktivnemenu = true;
                        $html .= "\n".$odsad.'<li class="breadcrumb-item active">';
                        $html .= $value['Nazov'];
                        $html .= '</li>';
                    }
                }
            }
        }
        return $html;
    }


    public function displayBubleMenuFooter()
    {
    if ($this->list > 1){
        echo '
                    <li class="breadcrumb-item active">List '.$this->list.'</li>';
    }
        echo '
                </ol>
                <!-- END Include - Bublinkové menu -->';        
    }

    public function displayContentHeader()
    {
?>        
                </div><!-- /.col -->
            </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">

<!-- START MAIN - Hlavný obsah stránky -->
<!-- =============================================================================================================================== -->

<?php 
    }

    public function displayContentFooter()
    {
?>        

<!-- ============================================================================================================================== -->
<!-- END MAIN - Hlavný obsah stránky -->

        </section>
        <!-- /.content -->

    </div>
    <!-- /.content-wrapper -->
<?php 
    }

    public function displayFooter()
    {
?>

    <footer class="main-footer">
        <strong>Copyright &copy; 2020-2021 <a href="#">Ing. Ondrej VRŤO, IWE</a></strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 0.0.6
        </div>
    </footer>
<?php
    }

    public function displayBodyFooter()
    {
?>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

</div>
<!-- ./wrapper -->

<?php
    }

    protected function VYVOJ () {

        $indicesServer = array('PHP_SELF',
        'argv',
        'argc',
        'GATEWAY_INTERFACE',
        'SERVER_ADDR',
        'SERVER_NAME',
        'SERVER_SOFTWARE',
        'SERVER_PROTOCOL',
        'REQUEST_METHOD',
        'REQUEST_TIME',
        'REQUEST_TIME_FLOAT',
        'QUERY_STRING',
        'DOCUMENT_ROOT',
        'HTTP_ACCEPT',
        'HTTP_ACCEPT_CHARSET',
        'HTTP_ACCEPT_ENCODING',
        'HTTP_ACCEPT_LANGUAGE',
        'HTTP_CONNECTION',
        'HTTP_HOST',
        'HTTP_REFERER',
        'HTTP_USER_AGENT',
        'HTTPS',
        'REMOTE_ADDR',
        'REMOTE_HOST',
        'REMOTE_PORT',
        'REMOTE_USER',
        'REDIRECT_REMOTE_USER',
        'SCRIPT_FILENAME',
        'SERVER_ADMIN',
        'SERVER_PORT',
        'SERVER_SIGNATURE',
        'PATH_TRANSLATED',
        'SCRIPT_NAME',
        'REQUEST_URI',
        'PHP_AUTH_DIGEST',
        'PHP_AUTH_USER',
        'PHP_AUTH_PW',
        'AUTH_TYPE',
        'PATH_INFO',
        'ORIG_PATH_INFO') ;
        
        echo "\n\n". '<!--  LEN Pre potreby vývoja tejto stránky. Po vývoji ZMAZať !!!!!!!!!  -->' ;

        echo "\n\n". '<hr><footer class="main-footer pt-5"> <h3 class="text-success">Vývoj: VZORY</h3>' ;
            echo '<a href="/_vzor/index.html">audity.zoszv.adminlte/_vzor</a>';
        echo '</footer>' ;

        echo "\n\n". '<footer class="main-footer"> <h3 class="text-warning">Vývoj: $_GET</h3>' ;
            print_r($_GET);
        echo '</footer>' ;

        echo "\n\n". '<footer class="main-footer"> <h3 class="text-danger">Vývoj: $_POST</h3>' ;
            print_r($_POST);
        echo '</footer>' ;

        echo "\n\n". '<footer class="main-footer"> <h3 class="text-secondary">Vývoj: $_REQUEST</h3>' ;
            print_r($_REQUEST);
        echo '</footer>' ;

        echo "\n\n". '<footer class="main-footer"> <h3 class="text-info">Vývoj: $_COOKIE</h3>' ;
            print_r($_COOKIE);
        echo '</footer>' ;

        echo "\n\n". '<footer class="main-footer"> <h3 class="text-info">Vývoj: $_SESSION</h3>' ;
            print_r($_SESSION);
        echo '</footer>' ;     

        echo "\n\n". '<footer class="main-footer"> <h3 class="text-secondary">Vývoj: $_FILES</h3>' ;
            print_r($_FILES);
        echo '</footer>' ;
        echo "\n\n". '<footer class="main-footer"> <h3 class="text-primary">Vývoj: $_SERVER</h3>';
            echo '<div class="table-responsive"> <table class="table table-sm table-borderless table-hover">' ;
            foreach ($indicesServer as $arg) {
                if (isset($_SERVER[$arg])) {
                    echo '<tr><td>'.$arg.'</td><td>' . $_SERVER[$arg] . '</td></tr>' ;
                }
                else {
                    echo '<tr><td>'.$arg.'</td><td>-</td></tr>' ;
                }
            }
            echo '</table> </div>';
        echo "</footer>\n\n";
    }


}