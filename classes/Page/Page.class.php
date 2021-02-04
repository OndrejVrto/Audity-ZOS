<?php

namespace Page;

class Page
{
    
    use \Funkcie;

    // Vlastnosti třídy Page
    public $content;
    public $nadpis;
    public $title;
    public $description;
    public $odsadenie = 5;
    public $list = 1;
    public $hlavneMenu;
    public $bubleMenu;
    public $zobrazitBublinky = true;
    public $zobrazitTlacitka = true;
    public $stylyArray = [];
    public $stylySpecial = '';
    public $skriptyArray = [];
    public $skriptySpecial = '';
    public $todo = false;
    public $alert = false;
    public $searchValue = FALSE;
    
    protected $_nazovstranky;
    public $link;
    
    private $zbalHTML = false;
    private $aktivnemenu = false;
    private $levelStranky;
    private $starttime;
    private $endtime;

    // premenné k aktuálne prihlásenému uživateľovi
    public $levelUser;
    public $LoginUser;
    public $userNameShort;
    public $userName;
    public $suborAvatara;
    public $typKonta;

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
        $this->starttime = microtime(true); // Začiatok merania času

        // header('Content-Type: text/html; charset=utf-8');

        $this->link = $_SERVER['REQUEST_URI'];
        $this->linkCisty = $this->upravLink($this->link);
        $this->linkZoznam = $this->linkCisty . "zoznam";

        // priradenie všetkých SESSION premenných do vlastností triedy
        $this->LoginUser = ( isset($_SESSION['LoginUser']) ) ? $_SESSION['LoginUser'] : "" ;
        $this->levelUser = ( isset($_SESSION['LEVEL']) ) ? $_SESSION['LEVEL'] : "0" ;
        $this->userNameShort = ( isset($_SESSION['userNameShort']) ) ? $_SESSION['userNameShort'] : "" ;
        $this->userName = ( isset($_SESSION['userName']) ) ? $_SESSION['userName'] : "" ;

        $this->list = (isset($_GET['p'])) ? $_GET['p'] : "1" ;
        $this->searchValue = (isset($_GET['search'])) ? $_GET['search'] : "" ;
        
        if (isset($_SESSION['ALERT'])) {
            $this->alert = $_SESSION['ALERT'];
            unset($_SESSION['ALERT']);
        }

        $premenne = new \Premenne($this->link, $this->linkZoznam);

        $this->title = $premenne->titulokStranky;
        $this->nadpis = $premenne->nadpisPrvejSekcie;
        $this->description = $premenne->popisStranky;
        $this->hlavneMenu = $premenne->konstantyStrankyMenu;
        $this->bubleMenu = $premenne->konstantyStranokKomplet;

        // ak uživateľ nemá oprávnenia na danú stránku presmeruje ho na hlavnú stránku
        $this->levelStranky =  $premenne->levelStranky;

        if ($this->levelStranky > 1) {
            if ( $this->levelUser < $this->levelStranky ){
                $_SESSION['ALERT'] = ' "Stránka ktorú ste požadovali existuje, ale keďže nemáte dostatočné oprávnenia," +
                    "\n" + "boli ste presmerovaný na hlavnú stránku." +
                    "\n\n" + "Skúste sa prihlásiť, alebo kontaktujte administrátora." ';
                header("Location: /");
                exit();
            }
        }

        // prebratie pripojenia na databazu z globálnej premennej
        global $db;
        $row = $db->query('SELECT * FROM `50_sys_users`, `53_sys_levels` 
                            WHERE `OsobneCislo` = ? AND `ID53_sys_levels` = `ID53`',
                            $this->LoginUser)->fetchArray();
        $this->typKonta = $row['NazovCACHE'];
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
        ob_start(); // celá stránka sa načíta najskôr do pamäte pre potreby minimalizácie na konci tejto funkcie 

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
        echo $this->displayLeftMenuSitebar($this->hlavneMenu, $this->odsadenie);
        $this->displayLeftMenuFooter();
        
        if ($this->zobrazitBublinky) {
            $this->displayBubleMenuHeader();
            echo $this->displayBubleMenu($this->bubleMenu);
            $this->displayBubleMenuFooter();
        }

        $this->displayContentHeader();
        if ($this->todo) {
            $this->displayTODOtext();
        }
        if ($this->zobrazitTlacitka) {
            $this->ContentHeaderZoznamTlacitka();
        }
        $this->ContentHeaderZoznam();
        echo $this->content;
        $this->ContentFooterZoznam();
        $this->displayContentFooter();
        
        // ukončenie merania času musí byť skôr ako je zobrazenie footer. Inak zobrazí nesprávnu hodnotu.
        // zvyšok stránky trvá načítať krátko tak tam prirátam natvrdo majhoršiu hodnotu ktorú nameral
        $this->endtime = microtime(true) + 0.0003;

        $this->displayFooter();
        $this->displayBodyFooter();
        
        // vypíše upozornenie pri zobrazení stránky
        if ($this->alert) {
            echo "\n\t<!--  Okno s upozornením zobrazené pri neautorizovanej požiadavke -->\n\t";
            echo '<script nonce="' . $GLOBALS["nonce"] . '">alert(' . $this->alert . ');</script>' . PHP_EOL;
        }
        $this->displayScripts();
        if ( strlen($this->LoginUser) > 1 ) {
            $this->displayScriptsTime();
        }
        echo $this->skriptySpecial;

        echo ( VYVOJ OR $this->levelUser >= 20 ) ? $this->VYVOJ() : '';
        echo "\n</body>\n</html>";
        
        // vloží kompletnú stránku s buferu do premennej
        $CelaStranka = ob_get_clean();
        // aktivuje triedu na minimalizáciu kódu
        echo ( VYVOJ OR $this->levelUser >= 20 OR !$this->zbalHTML) ? $CelaStranka : \Minifier\Minify::html($CelaStranka);

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

<body class="hold-transition sidebar-mini layout-navbar-fixed layout-fixed layout-footer-fixed">
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

            <li class="nav-item dropdown d-none d-sm-inline-block text-nowrap">
                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">
                    <i class="nav-icon mr-2 fas fa-address-book"></i>About
                </a>
                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                    <li>
                        <a href="/kontakty" class="dropdown-item">
                            <i class="nav-icon mr-3 fas fa-info-circle"></i>Kontakty firmy
                        </a>
                    </li>
                    <li class="dropdown-divider"></li>
                    <li>
                        <a href="/klapky" class="dropdown-item">
                            <i class="nav-icon mr-3 fas fa-tty"></i>Interné klapky ŽOS
                        </a>
                    </li>
                    <li class="dropdown-divider"></li>
                    <li>
                        <a href="/about" class="dropdown-item">
                            <i class="nav-icon mr-3 fas fa-award"></i>O tomto programe ..
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown d-none d-sm-inline-block text-nowrap">
                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">
                    <i class="nav-icon mr-2 fas fa-box-open"></i>Doplnky
                </a>
                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                    <li>
                        <a href="/kalkulacka" class="dropdown-item">
                            <i class="nav-icon mr-3 fas fa-calculator"></i>Kalkulačka
                        </a>   <!--  http://www.dematte.at/calculator/#download  -->
                    </li>
                    <li class="dropdown-divider"></li>
                    <li>
                        <a href="/kalendar/rocny-kalendar" class="dropdown-item">
                            <i class="nav-icon mr-3 far fa-calendar-alt"></i>Celoročný kalendár
                        </a>
                    </li>
                    <li>
                        <a href="/kalendar/kalendar-udalosti" class="dropdown-item">
                            <i class="nav-icon mr-3 fas fa-calendar-week"></i>Udalosti 1
                        </a>
                    </li>
                    <li>
                        <a href="/kalendar/kalendar-udalosti-2" class="dropdown-item">
                            <i class="nav-icon mr-3 fas fa-calendar"></i>Udalosti 2
                        </a>
                    </li>
                </ul>
            </li>

<?php if (VYVOJ OR $this->levelUser >= 2 ) { ?>
            <li class="nav-item d-none d-sm-inline-block text-nowrap">
                <a href="/timeline" class="nav-link"><i class="nav-icon mr-2 fas fa-hourglass-start"></i>Časová os</a>
            </li>

            <li class="nav-item d-none d-sm-inline-block text-nowrap">
                <a href="/spravca-suborov/" class="nav-link"><i class="nav-icon mr-2 fas fa-folder-open"></i>Správca súborov</a>   <!--  https://github.com/prasathmani/tinyfilemanager  a  https://tinyfilemanager.github.io/docs/#line2  -->
            </li>
<?php } ?>
        </ul>

        <!-- SEARCH FORM -->
        <form class="form-inline ml-3" action="/vyhladavanie" method="GET">
            <div class="input-group input-group-sm">
                <input name="search" value="<?= vycistiText($this->searchValue) ?>" class="form-control form-control-navbar" type="search" placeholder="Hľadaj ..." aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-navbar" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
<?php if (VYVOJ OR $this->levelUser >= 20 ) { ?>
        <ul class="ml-4 pl-4 navbar-nav">
            <li class="nav-item d-none d-sm-inline-block text-nowrap">
                <a href="#" class="nav-link">
                    <small>Level stranka: </small><span class="text-warning h5 ml-1"><?= $this->levelStranky ?></span>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block text-nowrap">
                <a href="#" class="nav-link">
                    <small>Level user: </small><span class="text-warning h5 ml-1"><?= $this->levelUser ?></span>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block text-nowrap">
                <a href="/include/rucna-aktualizacia-databazy" class="nav-link">
                    <i class="nav-icon mr-2 fas fa-database"></i>Aktualizuj Databázu
                </a>
            </li>
        </ul>
<?php } ?>
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- LogIn/LogOut -->
            <li class="nav-item">
<?php if ( $this->levelUser >= 3): 
    $time = strftime("%M:%S", TIME_OUT*60);
?>
				<a href="/user/logout" class="btn btn-warning ml-2" type="submit">Odhlásiť<span class="ml-2 small text-secondary" id="time"><?= $time ?></span></a>
<?php else: ?>
				<a class="btn btn-danger ml-2" href="/user/login">Login</a>
<?php endif; ?>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->
<?php
    }

    public function displayLogin()
    {

    if ($this->levelUser >= 3): 
?>
            <div class="image">
                <img src="<?= $this->suborAvatara ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="/user/detail" class="d-block text-warning"><?= vycistiText($this->userNameShort).PHP_EOL ?>
                    <span class="text-danger ml-2 align-top"><small>[ <?= vycistiText($this->typKonta) ?> ]</small></span>
                </a>
            </div>
<?php else: ?>
            <div class="image">
                <img src="/dist/img/ avatar-clear.svg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="/user/login" class="d-block text-warning">NEPRIHLASENÝ</a>
            </div>
<?php endif;
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
                <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-compact" data-widget="treeview" role="menu" data-accordion="true" data-animation-speed="200">
                    <!-- Add icons to the links using the .nav-icon class
                        with font-awesome or any other icon font library -->
<?php
    }

    public function displayLeftMenuSitebar($vstup, $odsadenie)
    {

        $odsad = str_repeat("\t", $odsadenie );
        $html = '';
        $this->aktivnemenu = false;

        foreach ($vstup as $key => $value) {
            //  ak na zobrazenie položky menu je potrebná určitá úrovať LEVELu uživateľa preskočí nezobrazí ju v MENU
            //  neprihlásený uživateľ: LEVEL = 0
            if (isset($value['LEVEL'])) {
                if ($this->levelUser < $value['LEVEL'] ) { continue; }
            }

            if (array_key_exists('Hlavicka', $value)) {
                    $html .= "\n".$odsad.'<li class="nav-header">';
                    $html .= "\n\t".$odsad.vycistiText($value['Hlavicka']);
                    $html .= "\n".$odsad.'</li>';
            } else {
                if (is_array($value['SUBMENU'])) {
                    // rekurzívna funkcia - volá sama seba pri každej ďalšej vrste Menu !!!
                    $submenu = $this->displayLeftMenuSitebar($value['SUBMENU'], $odsadenie + 2);

                    $html .= "\n".$odsad.'<li class="nav-item has-treeview'.(($this->aktivnemenu === true) ? ' menu-open' : '' ).'">';
                    $html .= "\n\t".$odsad.'<a href="'. (($value['Link'] === false) ? '#' : $value['Link'] ).'" ';
                    $html .= 'class="nav-link'.(($this->aktivnemenu === true) ? ' active' : '' ).'">';
                    $html .= "\n\t\t".$odsad.'<i class="nav-icon '.(($value['Ikona'] === false) ? 'far fa-circle' : $value['Ikona'] ).'"></i>';
                    $html .= "\n\t\t".$odsad.'<p>';
                    $html .= "\n\t\t\t".$odsad.$value['NazovMENU'];
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

                    $html .= "\t".$odsad.'</ul>';
                    $html .= "\n".$odsad.'</li>';

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
                    $html .= "\n\t\t\t".$odsad.$value['NazovMENU'];
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
        $html .= "\n";
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
                            $html .= '<a href="'.$value['Link'].'">'.$value['NazovMENU'].'</a>';
                        } else {
                            $html .= $value['NazovMENU'];
                        }
                        $html .= '</li>';
                        $html .= $submenu;
                    }
                } else {
                    if ($value['Link'] == $this->link || $value['Link'] == $this->linkZoznam) {
                        $this->aktivnemenu = true;
                        $html .= "\n".$odsad.'<li class="breadcrumb-item active">';
                        $html .= $value['NazovMENU'];
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
    private function displayTODOtext(){
?>
        <div class="text-center mt-n2">
            <hr class="mt-0">
            <span class="h4 text-danger">Na tejto stránke pracujeme usilovne ako včielky.</span>
            <br>
            Keď bude stránka úplne funkčná, tento text zmizne.<span class="ml-3 h4 text-success"><i class="far fa-grin-wink"></i></span>
            <hr>
        </div>
<?php
    }

    public function displayFooter()
    {
    $cas = $this->endtime - $this->starttime;
?>
    <footer class="main-footer bg-gradient-dark">
        <strong>Copyright <i class="far fa-copyright"></i> 2020-<?= date("Y") ?><a href="mailto:ondrej.vrto&#64;gmail.com" class="mx-3">Ing. Ondrej VRŤO, IWE</a></strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <!-- <b>Version</b> 0.0.9 -->
            <b>Zobrazenie trvalo:</b> <?= round($cas, 4) ?>s<br>
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
            case "CalcSS3":
                $cssLink = '/plugins/CalcSS3/CalcSS3';
            break;
            case "CalcSS3-index":
                $cssLink = '/plugins/CalcSS3/index';
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
            case "CalcSS3":
                $jsLink = '/plugins/CalcSS3/CalcSS3';
            break;
        }

        $this->skriptyArray[] = ($jsComment != '' ? '<!-- '.$jsComment.' -->'.PHP_EOL.TAB1 : '').'<script src="'.$jsLink.($min ? '.min' : '').'.js" nonce="' . $GLOBALS["nonce"] . '"></script>';
    }

    private function displayScriptsTime(){
?>

    <!-- BEGIN - SCRIPT - TIME LogOut -->
    <script nonce="<?= $GLOBALS["nonce"] ?>">
        var Timer = function(opts) {
            var self = this;

            self.opts     = opts || {};
            self.element  = opts.element || null;
            self.minutes  = opts.minutes || 0;
            self.seconds  = opts.seconds || 0;

            self.start = function() {
                self.interval = setInterval(countDown, 1000);
            };

            self.stop = function() {
                clearInterval(self.interval);
            };

            function countDown() {
                self.seconds--; //Changed Line
                if (self.minutes == 0 && self.seconds == 0) {
                self.stop();
                }

                if (self.seconds < 0) { //Changed Condition. Not include 0
                self.seconds = 59;
                self.minutes--;
                }

                if (self.seconds <= 9) { self.seconds = '0' + self.seconds; }

                self.element.textContent = ("0" + self.minutes).slice(-2) + ':' + self.seconds;
            }
        };

        var myTimer = new Timer({
            minutes: <?= TIME_OUT ?>,
            seconds: 0,
            element: document.querySelector('#time')
        });

        myTimer.start();
    </script>
    <!-- END - SCRIPT - TIME LogOut -->

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
        'ORIG_PATH_INFO',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_CLIENT_IP') ;
        
        $html = "";
        $html .= "\n\n". '<!--  LEN Pre potreby vývoja tejto stránky. Po vývoji ZMAZať !!!!!!!!!  -->' ;
        $html .= "\n\n". '<div>';
        // celkový počet vykonaných dotazov do databazy
        global $db;
        $dotazov = $db->query_count;
        $data = $db->query("SELECT *, TIMESTAMPDIFF( MINUTE, PoslednaAktualizacia, NOW() ) AS Rozdiel FROM `52_sys_cache_cron_and_clean`;")->fetchAll();

        $html .= "\n\n". '<footer class="main-footer pt-5"> <h3 class="text-warning">Štatistika</h3>' ;
        $html .= "\n\t".'<b>Dotazov do databázy:</b> ' . $dotazov . '<br><br>';
        foreach ($data as $key => $value) {
            $html .= "\n\t".'Posledná aktualizácia <b>' . $value['NazovCACHE'] . '</b> pred <b>' . $value['Rozdiel'] . '</b> minútami ( ' . $value['PoslednaAktualizacia'] . ' )' . '<br>';
        }
        $html .= "\n".'</footer>' ;

        $html .= "\n\n". '<footer class="main-footer"> <h3 class="text-success">VZORY</h3>' ;
            $html .= '<a href="/_vzor/index.html">audity.zoszv.adminlte/_vzor</a>';
        $html .= '</footer>' ;
        
        $html .= "\n\n". '<footer class="main-footer"> <h3 class="text-danger">php Info</h3>' ;
            $html .= '<a href="/test/phpinfo">PHPinfo()</a>';
        $html .= '</footer>' ;  

        $html .= "\n\n". '<footer class="main-footer"> <h3 class="text-warning">$_GET</h3>' ;
            $html .= vycistiText(print_r($_GET, true));
        $html .= '</footer>' ;

        $html .= "\n\n". '<footer class="main-footer"> <h3 class="text-danger">$_POST</h3>' ;
            $html .= vycistiText(print_r($_POST, true));
        $html .= '</footer>' ;

        $html .= "\n\n". '<footer class="main-footer"> <h3 class="text-secondary">$_REQUEST</h3>' ;
            $html .= vycistiText(print_r($_REQUEST, true));
        $html .= '</footer>' ;

        $html .= "\n\n". '<footer class="main-footer"> <h3 class="text-info">$_COOKIE</h3>' ;
            $html .= vycistiText(print_r($_COOKIE, true));
        $html .= '</footer>' ;

        $html .= "\n\n". '<footer class="main-footer"> <h3 class="text-info">$_SESSION</h3>' ;
            $html .= vycistiText(print_r($_SESSION, true));
        $html .= '</footer>' ;     

        $html .= "\n\n". '<footer class="main-footer"> <h3 class="text-secondary">$_FILES</h3>' ;
            $html .= vycistiText(print_r($_FILES, true));
        $html .= '</footer>' ;
        $html .= "\n\n". '<footer class="main-footer"> <h3 class="text-primary">$_SERVER</h3>';
            $html .= '<div class="table-responsive"> <table class="table table-sm table-borderless table-hover">' ;
            foreach ($indicesServer as $arg) {
                if (isset($_SERVER[$arg])) {
                    $html .= '<tr><td>'.$arg.'</td><td>' . vycistiText($_SERVER[$arg]) . '</td></tr>'.PHP_EOL ;
                }
                else {
                    $html .= '<tr><td>'.$arg.'</td><td>-</td></tr>'.PHP_EOL ;
                }
            }
            $html .= '</table> </div>';
        $html .= "</footer>\n\n";
        
        $caskonecny = microtime(true) - $this->starttime;
        $html .= "\n\n". '<footer class="main-footer pb-5"> <h3 class="text-warning">Presný ČAS spracovania stránky</h3>';
            $html .= vycistiText(print_r(round($caskonecny, 4), true)); $html .= "s";
        $html .= '</footer>' ;

        $html .= "\n\n". '</div><hr>' . "\n\n";

        return $html;
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