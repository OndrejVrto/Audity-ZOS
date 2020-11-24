<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";
    
    // ak uživateľ nieje prihlásený, presmeruje ho na hlavnú stránku
    if ( !isset($_SESSION['LEVEL']) OR $_SESSION['LEVEL'] < 1 ){
        header("Location: /");
        exit();
    }

    $page = new \Page\PageClear();

    $user = (isset($_SESSION['LoginUser'])) ? $_SESSION['LoginUser'] : "" ;

    // TODO dopracovať presmerovanie v zmysle
    // TODO --Po vložení Avatara do databázy sa vráť na pôvodnú stránku... napr: /user/detail
    // if (isset($_SERVER['HTTP_REFERER'])) {
    //     if (substr($_SERVER['HTTP_REFERER'], -6, 6) === "signup" || substr($_SERVER['HTTP_REFERER'], -5, 5) === "login") {
    //         $referer = "/user/login";
    //     } else {
            $referer = "/";
    //     }
    // }

ob_start();  // Začiatok definície hlavného obsahu
?>

    <div class="register-box" style="max-width: 1000px;">

        <div class="register-logo">
            <a href="/"><b>Audity</b>ŽOS</a>
        </div>

        <div class="card">
            <div class="card-body register-card-body">

                <input type="hidden" ID="ValueLoginUser" value="<?= $user ?>">
                <input type="hidden" ID="LoginReferer" value="<?= $referer ?>">

                <div id="svgAvatars"></div>

            </div>

        </div>
    </div>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

ob_start();  // css
?>
    
    <!-- START - CSS Avatar -->
	<!-- <link href="/svgavatars/css/normalize.css" rel="stylesheet"> -->
	<link href="/svgavatars/css/spectrum.css" rel="stylesheet">
	<link href="/svgavatars/css/svgavatars.css" rel="stylesheet">
    <link href="/svgavatars/css/google-webfonts--roboto-condensed.css" rel="stylesheet">
    <link href="/svgavatars/css/google-webfonts--roboto.css" rel="stylesheet">
    <!-- END - CSS Avatar -->

<?php
$page->stylySpecial = ob_get_clean();  // /css

ob_start();  // scripts
?>
    
    <!-- START - SCRIPT Avatar -->
    <script src="/svgavatars/js/svgavatars.tools.js"></script>
    <script src="/svgavatars/js/svgavatars.defaults.js"></script>
    <script src="/svgavatars/js/languages/svgavatars.sk.js"></script>
    <script src="/svgavatars/js/svgavatars.core.min.js"></script>
    <!-- END - SCRIPT Avatar -->
<?php
$page->skriptySpecial = ob_get_clean();  // scripts

$page->display();  // vykreslenie stranky