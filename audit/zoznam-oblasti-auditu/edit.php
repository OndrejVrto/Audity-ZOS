<?php
    // Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
    spl_autoload_register(function ($class_name) {
        include_once  $_SERVER['DOCUMENT_ROOT'] . "/include/class/class." . $class_name . '.php';
    });
    require $_SERVER['DOCUMENT_ROOT'] . "/include/inc.dBconnect.php";

    $uri = "/audit/zoznam-oblasti-auditu/";
    $list = (isset($_GET['p'])) ? $_GET['p'] : "1" ;

    $page = new Page($uri , $list);

    // inicializácia konštánt formulára v prípade volania metódou GET
    $mena_vsetkych_poli = array ('oblast-auditu', 'oblast-auditu-poznamka');
    foreach ($mena_vsetkych_poli as $key => $value) {
        $validation_values[$value] = $validation_classes[$value] = $validation_feedback[$value] = '';
    }

    if (isset($_POST['submit'])) {
        // inicializácia class Validate
        $validation = new ValidatorOblastAuditu($_POST);

        $validation->odsadenie = 8;  // odsadzuje HTML kod o 5 tabulátorov
        $result = $validation->validateForm();  // validuje formulár - !! kľúče validovaných polí musia byť v zadefinované v triede
        $validation_values = $validation->validateFormGetValues();   // vracia hodnoty polí pre každý kľúč
        $validation_classes = $validation->validateFormGetClasses();  // vracia triedy:  is-valid / is-invalid pre každý kľúč
        $validation_feedback = $validation->validateFormGetFeedback();  // vracia správy pre každý kľúč

        (int)$id = mysqli_real_escape_string($conn, $_POST['submit']);

        // if result is TRUE (1) --> save data to db  OR  reditect page
        if ($result == 1) {
            $oblast = mysqli_real_escape_string($conn, $validation_values['oblast-auditu']);
            $poznamka = mysqli_real_escape_string($conn, $validation_values['oblast-auditu-poznamka']);

            $sql = "UPDATE `30_zoznam_oblast_auditu` 
                    SET `OblastAuditovania`='".$oblast."', 
                        `Poznamka`='".$poznamka."' 
                    WHERE `ID30`=".$id.";";
            dBzoznam2($sql, $uri);
            header("Location: $uri");
            exit();
        }
    } else {
        (int)$id = mysqli_real_escape_string($conn, $_POST['edit']);
        $sql = "SELECT * FROM 30_zoznam_oblast_auditu WHERE ID30='".$id."'; ";
        $data = dBzoznam($sql, $uri);
        $validation_values['oblast-auditu'] = $data[0]['OblastAuditovania'];
        $validation_values['oblast-auditu-poznamka'] = $data[0]['Poznamka'];        
    }

    $id = htmlspecialchars($id);

ob_start();  // Začiatok definície hlavného obsahu
?>

    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-9 col-lg-7" style="max-width: 600px;">

            <form action="<?= $uri ?>edit" method="post">
                <div class="card" >

                    <div class="card-header">
                        Editácia položky
                    </div>

                    <div class="card-body register-card-body">

                        <?php $meno_pola = 'oblast-auditu'; ?><!-- FORM - Oblasť -->
                        <div class="form-group ">
                            <label>Názov oblasti</label>
                            <div class="input-group">
                                <input type="text" class="form-control<?= $validation_classes[$meno_pola] ?>" value="<?= $validation_values[$meno_pola] ?>" name="<?= $meno_pola ?>" placeholder="Položka">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-id-card"></span>
                                    </div>
                                </div>
                                <!-- <small class="d-block w-100 mb-n2 text-muted">Poznámka k tomuto poľu</small> --><?= PHP_EOL.$validation_feedback[$meno_pola] ?>
                            </div>
                        </div>

                        <?php $meno_pola = 'oblast-auditu-poznamka'; ?><!-- FORM - Poznámka -->
                        <div class="form-group ">
                            <label>Poznámka</label>
                            <textarea class="form-control<?= $validation_classes[$meno_pola] ?>" name="<?= $meno_pola ?>"><?= $validation_values[$meno_pola] ?></textarea>
                            <!-- <small class="d-block w-100 mb-n2 text-muted">Poznámka k tomuto poľu</small> --><?= PHP_EOL.$validation_feedback[$meno_pola] ?>
                        </div>

                    </div>

                </div>
                
                <div class="row justify-content-center">
                    <a href="<?= $uri ?>" name="vzad" class="btn btn-secondary mx-1">Späť</a>
                    <button type="submit" name="submit" value="<?= $id ?>" class="btn btn-outline-success mx-1">Uložiť</button>
                </div>

            </form>

        </div>
    </div>

<?php
    $page->content = ob_get_clean();  // Koniec hlavného obsahu

    $page->display();  // vykreslenie stranky