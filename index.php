<?php
    // Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
    spl_autoload_register(function ($class_name) {
        include  $_SERVER['DOCUMENT_ROOT'] . "/include/class/class.".$class_name.'.php';
    });
    
    // založenie novej triedy na stranku
    $homepage = new Page('index', 1);
    
    // prepísanie hodnôt stránky ručne. Štandardne sa hodnoty načítavajú z _variables.php
    // $homepage->nadpis = 'Nadpis';

    // inicializácia konštánt formulára v prípade volania metódou GET
/*     $validation_values['login-osobne-cislo'] = $validation_classes['login-osobne-cislo'] = $validation_feedback['login-osobne-cislo'] = '';
    $validation_values['login-pasword'] = $validation_classes['login-pasword'] = $validation_feedback['login-pasword'] = ''; */

    $request_method = strtoupper($_SERVER['REQUEST_METHOD']);

    if ($request_method === 'GET') {
        // spustí sa ak existuje GET, teda aj pri prvom spustení
        // program na vyplnenie formulára údajmi (napr:dotaz do databazy cez najaký class)
    } elseif ($request_method === 'POST') {
        // spustí sa ak existuje POST
        if (isset($_POST['submit'])) {
            // spustí sa ak bolo stlačené tlačítko ->  name="submit"

            // inicializácia class Validate
/*          $validation = new ValidatorSignup($_POST);

            $validation->odsadenie = 5;  // odsadzuje HTML kod o 5 tabulátorov
            $result = $validation->validateForm();  // validuje formulár - !! kľúče validovaných polí musia byť v zadefinované v triede
            $validation_values = $validation->validateFormGetValues();   // vracia hodnoty polí pre každý kľúč
            $validation_classes = $validation->validateFormGetClasses();  // vracia triedy:  is-valid / is-invalid pre každý kľúč
            $validation_feedback = $validation->validateFormGetFeedback();  // vracia správy pre každý kľúč

            // if result is TRUE (1) --> save data to db  OR  reditect page
            if ($result == 1) {
                header("Location: /index.php");
            } */
        }
    }




    ob_start();  // Začiatok definície CSS pre túto stránku 
?>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
    <link rel="stylesheet" href="/dist/css/ionicons/css/ionicons.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="/plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/dist/css/adminlte.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="/plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="/plugins/summernote/summernote-bs4.css">
    <!-- Google Font: Source Sans Pro -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> -->
    <link rel="stylesheet" href="/dist/css/www/fonts.googleapis.css">
<?php
    $homepage->styles = ob_get_clean();  // Koniec definícií CSS




    ob_start();  // Začiatok definície SKRIPTov pre túto stránku
?>
    <!-- START - skripty len pre túto podstránku -->

    <!-- jQuery -->
    <script src="/plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="/plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="/plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="/plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="/plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="/plugins/moment/moment.min.js"></script>
    <script src="/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="/plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/dist/js/adminlte.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>

    <!-- END - skripty len pre túto podstránku -->
<?php
    $homepage->skripty = ob_get_clean();  // Koniec SKRIPTov








    ob_start();  // Začiatok definície hlavného obsahu
?>

        <!-- Default box -->
        <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th style="width: 1%">
                            P.č.
                        </th>
                        <th style="width: 20%">
                            Audit
                        </th>
                        <th style="width: 20%">
                            Zodpovedný
                        </th>
                        <th>
                            Progres
                        </th>
                        <th style="width: 8%" class="text-center">
                            Stav
                        </th>
                        <th style="width: 30%">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            1.
                        </td>
                        <td>
                            <a>
                                ISO 9001
                            </a>
                            <br/>
                            <small>
                                Dňa 01.10.2019
                            </small>
                        </td>
                        <td>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <img alt="Avatar" class="table-avatar" src="/dist/img/avatar.png">
                                </li>
                                <li class="list-inline-item">
                                    <img alt="Avatar" class="table-avatar" src="/dist/img/avatar2.png">
                                </li>
                                <li class="list-inline-item">
                                    <img alt="Avatar" class="table-avatar" src="/dist/img/avatar3.png">
                                </li>
                                <li class="list-inline-item">
                                    <img alt="Avatar" class="table-avatar" src="/dist/img/avatar04.png">
                                </li>
                            </ul>
                        </td>
                        <td class="project_progress">
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-red" role="progressbar" aria-volumenow="34" aria-volumemin="0" aria-volumemax="100" style="width: 34%">
                                </div>
                            </div>
                            <small>
                                Spracovaný na 34%
                            </small>
                        </td>
                        <td class="project-state">
                            <span class="badge badge-danger">Nekompletný</span>
                        </td>
                        <td class="project-actions text-right">
                            <a class="btn btn-primary btn-sm" href="#">
                                <i class="fas fa-folder">
                                </i>
                                Detaily
                            </a>
                            <a class="btn btn-info btn-sm" href="#">
                                <i class="fas fa-pencil-alt">
                                </i>
                                Editovať
                            </a>
                            <a class="btn btn-danger btn-sm" href="#">
                                <i class="fas fa-trash">
                                </i>
                                Vymazať
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            2.
                        </td>
                        <td>
                            <a>
                                ISO 14001
                            </a>
                            <br/>
                            <small>
                                Dňa 01.10.2019
                            </small>
                        </td>
                        <td>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <img alt="Avatar" class="table-avatar" src="/dist/img/avatar3.png">
                                </li>
                                <li class="list-inline-item">
                                    <img alt="Avatar" class="table-avatar" src="/dist/img/avatar.png">
                                </li>
                            </ul>
                        </td>
                        <td class="project_progress">
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-orange" role="progressbar" aria-volumenow="84" aria-volumemin="0" aria-volumemax="100" style="width: 84%">
                                </div>
                            </div>
                            <small>
                                Spracovaný na 84%
                            </small>
                        </td>
                        <td class="project-state">
                            <span class="badge badge-warning">Skoro hotový</span>
                        </td>
                        <td class="project-actions text-right">
                            <a class="btn btn-primary btn-sm" href="#">
                                <i class="fas fa-folder">
                                </i>
                                Detaily
                            </a>
                            <a class="btn btn-info btn-sm" href="#">
                                <i class="fas fa-pencil-alt">
                                </i>
                                Editovať
                            </a>
                            <a class="btn btn-danger btn-sm" href="#">
                                <i class="fas fa-trash">
                                </i>
                                Vymazať
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            3.
                        </td>
                        <td>
                            <a>
                                Interný
                            </a>
                            <br/>
                            <small>
                                Dňa 01.06.2019
                            </small>
                        </td>
                        <td>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <img alt="Avatar" class="table-avatar" src="/dist/img/avatar2.png">
                                </li>
                            </ul>
                        </td>
                        <td class="project_progress">
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-green" role="progressbar" aria-volumenow="100" aria-volumemin="0" aria-volumemax="100" style="width: 100%">
                                </div>
                            </div>
                            <small>
                                Spracovaný na 100%
                            </small>
                        </td>
                        <td class="project-state">
                            <span class="badge badge-success">Kompletný</span>
                        </td>
                        <td class="project-actions text-right">
                            <a class="btn btn-primary btn-sm" href="#">
                                <i class="fas fa-folder">
                                </i>
                                Detaily
                            </a>
                            <a class="btn btn-info btn-sm" href="#">
                                <i class="fas fa-pencil-alt">
                                </i>
                                Editovať
                            </a>
                            <a class="btn btn-danger btn-sm" href="#">
                                <i class="fas fa-trash">
                                </i>
                                Vymazať
                            </a>
                        </td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        </div>
        <!-- /.card -->
<?php
    $homepage->content = ob_get_clean();  // Koniec hlavného obsahu
    $homepage->display();  // vykreslenie stranky
?>