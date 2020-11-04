<?php
    // Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
    spl_autoload_register(function ($class_name) {
        include_once  $_SERVER['DOCUMENT_ROOT'] . "/include/class/class." . $class_name . '.php';
    });
    require $_SERVER['DOCUMENT_ROOT'] . "/include/inc.dBconnect.php";

    $uri = "/audit/zoznam-oblasti-auditu/";
    $list = (isset($_GET['p'])) ? $_GET['p'] : "1" ;

    $homepage = new Page($uri , $list);

    $id = mysqli_real_escape_string($conn, $_POST['detail']);

    // vyberovy dotaz na data
    $sql = "SELECT * FROM 30_zoznam_oblast_auditu WHERE ID30='".$id."'; ";
    $data = dBzoznam($sql, $uri);

ob_start();  // Začiatok definície hlavného obsahu
?>

<div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-9 col-lg-7" style="max-width: 600px;">

        <form>
        <fieldset disabled>
            <div class="card" >
                <div class="card-header">
                    Detailné informácie o položke zoznamu
                </div>
                <div class="card-body register-card-body">

                    <!-- FORM - osobne cislo -->
                    <div class="form-group ">
                        <label>Názov oblasti</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="<?= $data[0]['OblastAuditovania']; ?>" placeholder="Položka">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-id-card"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FORM - osobne cislo -->
                    <div class="form-group ">
                        <label>Poznámka</label>
                        <textarea class="form-control"><?= $data[0]['Poznamka']; ?> </textarea>
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->

            </div>
            <!-- /.card -->
        </fieldset>
        </form>

        </div>
        <div class="row justify-content-center">
            <a href="<?= $uri ?>" type="submit" name="vzad" class="btn btn-secondary mx-1">Späť</a>
        </div> 
    </div>

<?php
$homepage->content = ob_get_clean();  // Koniec hlavného obsahu
$homepage->display();  // vykreslenie stranky
?>