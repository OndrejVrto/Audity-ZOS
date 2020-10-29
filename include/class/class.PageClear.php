<?php
    
// Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
spl_autoload_register(function ($class_name) {
    include_once $_SERVER['DOCUMENT_ROOT'] . "/include/class/class.".$class_name.'.php';
});

class PageClear extends Page
{

    public $styles = '
    <!-- START - CSS clear štandard -->

    <!-- Font Awesome -->
    <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
    <link rel="stylesheet" href="/dist/css/ionicons/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/dist/css/adminlte.css">
    <!-- Google Font: Source Sans Pro -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> -->
    <link rel="stylesheet" href="/dist/css/www/fonts.googleapis.css">

    <!-- END - CSS clear štandard -->    
    ';

    public $skripty = '
    <!-- START - Skripty clear štandard -->

    <!-- jQuery -->
    <script src="/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>    
    <!-- AdminLTE App -->
    <script src="/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="/dist/js/demo.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function () {
            bsCustomFileInput.init();
        });
    </script>
    
    <!-- END - Skripty clear štandard -->    
    ';


    public function display()
    {
        $this->displayBegin();
        $this->displayTitle();
        $this->displayDescription();
        $this->displayIcons();
        echo $this->styles;
        $this->displayBodyHeader();
        echo $this->content;
        $this->displayBodyFooter();
        echo $this->skripty;
        echo "\n</body>\n</html>\n";
    }

    public function displayBodyHeader()
    {
?>
</head>

<body class="hold-transition register-page">
<?php
    }

    public function displayBodyFooter()
    {
?>
<!-- nic -->
<?php
    }
}