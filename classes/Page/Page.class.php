<?php

namespace Page;

class Page
{

    // Vlastnosti třídy Page
    public $content;
    public $nadpis;
    public $title;
    public $description;
    public $odsadenie = 5;
    public $list = 1;
    public $hlavneMenu;
    public $zobrazitBublinky = true;
    public $zobrazitTlacitka = true;
    public $stylyArray = [];
    public $stylySpecial = '';
    public $skriptyArray = [];
    public $skriptySpecial = '';
    protected $_nazovstranky;
    protected $link;
    private $aktivnemenu = false;
    private $LevelMenu;
    public $suborAvatara;
    
    public $bodyClassExtended = ''; //premenna sa používa v odvodených triedach
    public $bodyWidthExtended = ''; //premenna sa používa v odvodených triedach
    public $linkCisty; //premenna sa používa v odvodených triedach
    public $linkZoznam; //premenna sa používa v odvodených triedach

    // Metódy triedy Page
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    function __construct()
    {

        header('Content-Type: text/html; charset=utf-8');

        $this->link = $_SERVER['REQUEST_URI'];
        $this->linkCisty = $this->upravLink($this->link);
        $this->linkZoznam = $this->linkCisty . "zoznam";

        $this->list = (isset($_GET['p'])) ? $_GET['p'] : "1" ;;

        $premenne = new \Premenne($this->link, $this->linkZoznam);

        $this->title = $premenne->titulokStranky;
        $this->nadpis = $premenne->nadpisPrvejSekcie;
        $this->description = $premenne->popisStranky;
        $this->hlavneMenu = $premenne->menuHlavne;

        // ak uživateľ nemá oprávnenia na danú stránku presmeruje ho na hlavnú stránku
        // LEVEL = 0 neprihlásený uživateľ alebo bývalý zamestnanec
        // LEVEL = 1 read
        // LEVEL = 2 edit
        // LEVEL = 3 admin
        $this->LevelMenu =  $premenne->MenuLevel;
        if ($this->LevelMenu > 0) {
            if ( !isset($_SESSION['LEVEL']) OR $_SESSION['LEVEL'] < $this->LevelMenu ){
                header("Location: /");
                exit();
            }
        }

        // prebratie pripojenia na databazu z globálnej premennej
        global $db;
        
        $row = $db->query('SELECT `AvatarFILE` FROM `50_sys_users` WHERE `OsobneCislo` = ?', $_SESSION['LoginUser'])->fetchArray();
        $this->suborAvatara = "/dist/avatar/" . $row['AvatarFILE'];
        if ( is_null($row['AvatarFILE']) OR !file_exists($_SERVER['DOCUMENT_ROOT'] . $this->suborAvatara) ) {
            $this->suborAvatara = "/dist/img/ avatar-clear.svg";
        }

        // zadefinovanie základných štýlov
        $this->addStyles("Font Awesome", true);
        $this->addStyles("Ionicons", true);
        $this->addStyles("iCheck", true);
        $this->addStyles("Adminlte style", false);
        $this->addStyles("Google Font: Source Sans Pro", false);
        // zadefinovanie základných skriptov
        $this->addScripts("jQuery", true);
        $this->addScripts("Bootstrap 4-bundle", true);
        $this->addScripts("AdminLTE App", false);
        //$this->addScripts("AdminLTE for demo purposes", false);

    }

    public function display()
    {
        $this->PredvyplnenieKonstant(); // pre potreby odvodených tried
        $this->displayBegin();
        $this->displayTitle();
        $this->displayDescription();
        $this->displayIcons();
        
        $this->displayStyles();
        echo $this->stylySpecial;
        
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
        if ($this->zobrazitTlacitka) {
            $this->ContentHeaderZoznamTlacitka();
        }
        $this->ContentHeaderZoznam();
        echo $this->content;
        $this->ContentFooterZoznam();
        $this->displayContentFooter();
        
        $this->displayFooter();
        $this->displayBodyFooter();
        
        $this->displayScripts();
        echo $this->skriptySpecial;
        // vypíše upozornenie 
        if ($GLOBALS['odhlasenie']) {
            echo '<script>alert("Bohužiaľ ste boli neaktívny viac ako 30 minút." + "\n\n" + "Prihláste sa znovu.");</script>';
        }
        (VYVOJ) ? $this->VYVOJ() : '';
        echo "\n</body>\n</html>";
    }

    protected function PredvyplnenieKonstant(){} // pre potreby odvodených tried
    protected function ContentHeaderZoznam(){} // pre potreby odvodených tried
    protected function ContentHeaderZoznamTlacitka(){} // pre potreby odvodených tried
    protected function ContentFooterZoznam(){} // pre potreby odvodených tried

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
            <!-- LogIn/LogOut -->
            <li class="nav-item">
<?php if (isset($_SESSION['LoginUser'])): ?>
				<form class="" action="/user/logout.php" method="post">
					<input class="btn btn-warning" type="submit" name="logout-submit" value="LogOut">
				</form>
<?php else: ?>
				<a class="btn btn-danger" href="/user/login">Login</a>
<?php endif; ?>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->
<?php
    }

    public function displayLogin()
    {
?>
<?php if (isset($_SESSION['LoginUser'])): ?>
            <div class="image">
                <img src="<?= $this->suborAvatara ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="/user/detail" class="d-block text-warning"><?= htmlspecialchars($_SESSION['userNameShort']) ?></a>
            </div>
<?php else: ?>
            <div class="image">
                <img src="/dist/img/ avatar-clear.svg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="/user/login" class="d-block text-warning">NEPRIHLASENÝ</a>
            </div>
<?php endif; ?>

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
            <nav class="mt-2 pb-5">
                <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-compact" data-widget="treeview" role="menu" data-accordion="false">
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
            //  ak na zobrazenie položky menu je potrebná určitá úrovať LEVELu uživateľa preskočí nezobrazí ju v MENU
            //  neprihlásený uživateľ: LEVEL = 0
            if (isset($value['MinUserLEVEL'])) {
                if (isset($_SESSION['LEVEL'])) { $localLEVEL = $_SESSION['LEVEL']; } else { $localLEVEL = 0; }
                if ($localLEVEL < $value['MinUserLEVEL'] ) { continue; }
            }

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
                    if ($value['Link'] == $this->link || $value['Link'] == $this->linkZoznam) {
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

    <div class="content-wrapper">

        <div class="content-header">
            <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-8 order-2 order-md-1">
                <h1 class="m-0 text-dark"><?php echo $this->nadpis; ?></h1>
                </div><!-- /.col -->
                <div class="col-md-4 order-1 order-md-2">
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
                    if ($value['Link'] == $this->link || $value['Link'] == $this->linkZoznam) {
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

        <!-- Main content -->
        <div class="content">

<!-- START MAIN - Hlavný obsah stránky -->
<!-- =============================================================================================================================== -->

<?php 
    }

    public function displayContentFooter()
    {
?>        

<!-- ============================================================================================================================== -->
<!-- END MAIN - Hlavný obsah stránky -->

        </div>
        <!-- /Main content -->

    </div>

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

<?php
    }

    public function clearStyles(){
        $this->stylyArray = [];
    }
    
    public function clearScripts(){
        $this->skriptyArray = [];
    }

    public function displayStyles()
    {
        echo TAB1.'<!-- START - CSS štandard -->'.PHP_EOL;
        foreach ($this->stylyArray as $key => $value)
        {
            echo PHP_EOL.TAB1.$value;
        }
        echo PHP_EOL.PHP_EOL.TAB1.'<!-- END - CSS štandard -->'.PHP_EOL;
    }

    public function displayScripts()
    {
        echo TAB1.'<!-- START - SCRIPT štandard -->'.PHP_EOL;
        foreach ($this->skriptyArray as $key => $value)
        {
            echo PHP_EOL.TAB1.$value;
        }
        echo PHP_EOL.PHP_EOL.TAB1.'<!-- END - SCRIPT štandard -->'.PHP_EOL;
    }

    public function addStyles($kniznica, $min = true)
    {
        $cssComment = $kniznica;

        switch ($kniznica) {
            case "Font Awesome":
                $cssLink = '/plugins/fontawesome-free/css/all';
                break;
            case "Adminlte style":
                $cssLink = '/dist/css/adminlte';
                break;
            case "Ionicons":
                $cssLink = '/dist/css/ionicons/css/ionicons';
                break;
            case "Google Font: Source Sans Pro":
                $cssLink = '/dist/css/fonts.googleapis.SourceSansPro/fonts.googleapis';
                break;
            case "DataTables-jQuery":
                $cssLink = '/plugins/datatables/css/jquery.dataTables';
                break;
            case "DataTables-Bootstrap":
                $cssComment = '';
                $cssLink = '/plugins/datatables/css/dataTables.bootstrap4';
                break;
            case "DataTables-Select":
                $cssComment = '';
                $cssLink = '/plugins/datatables-select/css/select.bootstrap4';
                break;
            case "DataTables-Responsive":
                $cssComment = '';
                $cssLink = '/plugins/datatables-responsive/css/responsive.bootstrap4';
                break;
            case "DataTables-Buttons":
                $cssComment = '';
                $cssLink = '/plugins/datatables-buttons/css/buttons.bootstrap4';
                break;
            case "Tempusdominus Bbootstrap 4":
                $cssLink = '/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4';
                break;                
            case "iCheck":
                $cssLink = '/plugins/icheck-bootstrap/icheck-bootstrap';
                break;                
            case "JQVMap":
                $cssLink = '/plugins/jqvmap/jqvmap';
                break;                
            case "overlayScrollbars":
                $cssLink = '/plugins/overlayScrollbars/css/OverlayScrollbars';
                break;                
            case "Daterange picker":
                $cssLink = '/plugins/daterangepicker/daterangepicker';
            break;                
            case "summernote":
                $cssLink = '/plugins/summernote/summernote-bs4';
            break;
        }

        $this->stylyArray[] = ($cssComment != '' ? '<!-- '.$cssComment.' -->'.PHP_EOL.TAB1 : '').'<link rel="stylesheet" href="'.$cssLink.($min ? '.min' : '').'.css">';
    }
    
    public function addScripts($kniznica, $min = true){
        $jsComment = $kniznica;

        switch ($kniznica) {
            case "jQuery":
                $jsLink = '/plugins/jquery/jquery';
                break;
            case "Bootstrap 4-bundle":
                $jsLink = '/plugins/bootstrap/js/bootstrap.bundle';
                break;
            case "AdminLTE App":
                $jsLink = '/dist/js/adminlte';
                break;
            case "AdminLTE for demo purposes":
                $jsLink = '/dist/js/demo';
            break;

            case "DataTables":
                $jsLink = '/plugins/datatables/js/jquery.dataTables';
            break;
            case "DataTables-Bootstrap4":
                $jsComment = '';
                $jsLink = '/plugins/datatables/js/dataTables.bootstrap4';
            break;
            case "DataTables-Select":
                $jsLink = '/plugins/datatables-select/js/dataTables.select';
            break;
            case "DataTables-Select-Bootstrap4":
                $jsComment = '';
                $jsLink = '/plugins/datatables-select/js/select.bootstrap4';
            break;
            case "DataTables-Responsive":
                $jsLink = '/plugins/datatables-responsive/js/dataTables.responsive';
            break;
            case "DataTables-Responsive-Bootstrap":
                $jsComment = '';                
                $jsLink = '/plugins/datatables-responsive/js/responsive.bootstrap4';
            break;
            case "DataTables-Buttons":
                $jsComment = '';                
                $jsLink = '/plugins/datatables-buttons/js/dataTables.buttons';
            break;
            case "DataTables-Buttons-Bootstrap":
                $jsComment = '';                
                $jsLink = '/plugins/datatables-buttons/js/buttons.bootstrap4';
            break;
            case "DataTables-Buttons-HTML5":
                $jsComment = '';                
                $jsLink = '/plugins/datatables-buttons/js/buttons.html5';
            break;
        }

        $this->skriptyArray[] = ($jsComment != '' ? '<!-- '.$jsComment.' -->'.PHP_EOL.TAB1 : '').'<script src="'.$jsLink.($min ? '.min' : '').'.js"></script>';
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
        'ORIG_PATH_INFO',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_CLIENT_IP') ;
        
        echo "\n\n". '<!--  LEN Pre potreby vývoja tejto stránky. Po vývoji ZMAZať !!!!!!!!!  -->' ;

        echo "\n\n". '<hr><footer class="main-footer pt-5"> <h3 class="text-success">Vývoj: VZORY</h3>' ;
            echo '<a href="/_vzor/index.html">audity.zoszv.adminlte/_vzor</a>';
        echo '</footer>' ;
        
        echo "\n\n". '<footer class="main-footer"> <h3 class="text-success">Vývoj: php Info</h3>' ;
            echo '<a href="/test/phpinfo">PHPinfo()</a>';
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
                    echo '<tr><td>'.$arg.'</td><td>' . $_SERVER[$arg] . '</td></tr>'.PHP_EOL ;
                }
                else {
                    echo '<tr><td>'.$arg.'</td><td>-</td></tr>'.PHP_EOL ;
                }
            }
            echo '</table> </div>';
        echo "</footer>\n\n";

    }

    // funkcia  upravLink  vymaže posledný podadresár z cesty
    // sample:  $uri = upravLink($_SERVER['REQUEST_URI']);
    // sample:  /test/prvy/druhy -> /test/prvy
    private function upravLink($linkCely){
        $position = strripos($linkCely, "/", 0);  
        if ($position == true){
            return substr($linkCely, 0, $position + 1);
        }
        else{
            return $linkCely;
        }
    }

}