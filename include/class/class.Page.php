<?php

class Page
{
    // Vlastnosti třídy Page
    public $content;
    public $nadpis;
    public $title;
    public $description;
    public $bubbleMenu = array();
    public $list = 1;
    public $hlavneMenu = array();

    public $styles = '
    <!-- START - CSS štandard -->

    <!-- Font Awesome -->
    <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/dist/css/adminlte.css">
    <!-- Ionicons -->
    <!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
    <link rel="stylesheet" href="/dist/css/ionicons/css/ionicons.min.css">

    <!-- END - CSS štandard -->    
    ';

    public $skripty = '
    <!-- START - skripty štandard -->

    <!-- jQuery -->
    <script src="/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="/dist/js/demo.js"></script>

    <!-- END - skripty štandard -->    
    ';

    // Metody třídy Page
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    function __construct($nazovStranky, $listStranky)
    {
        session_start();

        header('Content-Type: text/html; charset=utf-8');
        
        $path = $_SERVER['DOCUMENT_ROOT'] . "/include/";
        // $conn pripojenie do databazy
        require $path . 'inc.dBconnect.php';
        require $path . 'inc.dBfunction.php';
        // funkcie rozne 
        require $path . 'inc.funkction.php';
        // konstanty stránok
        require $path . '_variables.php';

        $premenne = new Premenne($nazovStranky);
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
        $this->displayLeftMenuSitebar();
        $this->displayLeftMenuFooter();
        //$this->displayMenu($this->buttons);
        $this->displayBubleMenu();
        $this->displayContentHeader();
        echo $this->content;
        $this->displayContentFooter();
        $this->displayFooter();
        $this->displayBodyFooter();
        echo $this->skripty;
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
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="/" class="nav-link">Domov</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="/kontakt" class="nav-link">Kontakt</a>
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
				<a class="btn btn-danger" href="/pages/login">Login</a>
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
                <a href="/pages/user-detail" class="d-block">Ing. Ondrej VRŤO</a>
            </div>
<?php } else { ?>
            <div class="image">
                <img src="/dist/img/user-anonymous.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="/pages/login" class="d-block text-warning">NEPRIHLASENÝ</a>
            </div>
<?php } ?>

<?php
    }

    public function displayLeftMenuHeader()
    {
?>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
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
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                        with font-awesome or any other icon font library -->

<?php
    }

    public function displayLeftMenuSitebar()
    {
?>
                    <li class="nav-header">AUDITY</li>                            
                    <li class="nav-item has-treeview menu-open">
                        <a href="#" class="nav-link active">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Prehľady
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/" class="nav-link active">
                                    <i class="fas fa-circle nav-icon text-danger"></i>
                                    <p>Prehľad v1</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Report v2</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/" class="nav-link">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Výstup v3</p>
                                </a>
                            </li>
                        </ul>
                    </li>
<?php
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
                <div class="col-sm-6">
                <h1 class="m-0 text-dark"><?php echo $this->nadpis; ?></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
<?php
    }

    public function displayBubleMenu()
    {
?>

                    <!-- START Include - Bublinkové menu -->
                    <ol class="breadcrumb float-sm-right">
<?php
    $pocetBubliniek = count($this->bubbleMenu);
    $pocitadlo = 0;
    echo "\t\t\t\t\t\t<li class=\"breadcrumb-item\"><a href=\"/\"><i class=\"fa fa-home\" aria-hidden=\"true\"></i><span class=\"sr-only\">Domov</span></a></li>";
    foreach ((array)$this->bubbleMenu as $MYbublinkoveMenu) {
        $pocitadlo++;
        echo "\n\t\t\t\t\t\t<li class=\"breadcrumb-item";
        if ($pocitadlo == $pocetBubliniek) {
            echo " active";
        }
        echo "\">";
        if ($MYbublinkoveMenu[0] !==''){
            echo '<a href="' . str_replace(" ", '%20', $MYbublinkoveMenu[0]) . '">';
            echo $MYbublinkoveMenu[1];
            echo '</a>';
        } else {
            echo $MYbublinkoveMenu[1];
        }
        echo "</li>";
    }
    if ($this->list > 1){
        echo "\n\t\t\t\t\t\t<li class=\"breadcrumb-item active\">List " .$this->list. "</li>";
    }
    echo "\n";
?>
                    </ol>
                    <!-- END Include - Bublinkové menu -->
<?php 
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
            <b>Version</b> 0.0.5
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







/*     public function displayMenu($buttons)
    {
        echo "<!-- nabídka -->
    <nav>";

        while (list($name, $url) = each($buttons)) {
            $this->displayButton(
                $name,
                $url,
                !$this->isURLCurrentPage($url)
            );
        }
        echo "</nav>\n";
    }

    public function isURLCurrentPage($url)
    {
        if (strpos($_SERVER['PHP_SELF'], $url) === false) {
            return false;
        } else {
            return true;
        }
    }

    public function displayButton($name, $url, $active = true)
    {
        if ($active) { ?>
            <div class="menuitem">
                <a href="<?= $url ?>">
                    <img src="s-logo.gif" alt="" height="20" width="20" />
                    <span class="menutext"><?= $name ?></span>
                </a>
            </div>
        <?php
        } else { ?>
            <div class="menuitem">
                <img src="side-logo.gif">
                <span class="menutext"><?= $name ?></span>
            </div>
        <?php
        }
    } */
}