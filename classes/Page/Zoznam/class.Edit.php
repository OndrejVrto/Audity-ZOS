<?php

namespace Page\Zoznam;

class Edit extends \Page\Page
{

    public $id;

    function __construct()
    {

        parent::__construct();

        // ak uživateľ nieje prihlásený alebo nemá oprávnenia, presmeruje ho na chybovú stránku
        if ( $this->levelUser <= 2 ){
            header("Location: $this->linkZoznam");
            exit();
        } 

        if ( ! isset($_POST['edit']) ) {
            if ( !isset($_POST['submit'])) {
                header("Location: $this->linkZoznam");
                exit;                
            }
        }

    }
    
    function ContentHeaderZoznam (){

?>
    <div class="row justify-content-center">
        <div class="<?= $this->bodyClassExtended ?>" style="<?= $this->bodyWidthExtended ?>">

            <form action="<?= $this->linkCisty ?>edit" method="post" novalidate>
                <div class="card" >

                    <div class="card-header">
                        Editácia položky
                    </div>

                    <div class="card-body register-card-body">

<?php
    }

    function ContentFooterZoznam()
    {
?>

                    </div>

                </div>

                <div class="row justify-content-center">
                    <a href="<?= $this->linkZoznam ?>" name="vzad" class="btn btn-secondary mx-1">Späť</a>
                    <button type="submit" name="submit" value="<?= $this->id ?>" class="btn btn-outline-success mx-1">Uložiť</button>
                </div>

            </form>

        </div>
    </div>

<?php
    }

}