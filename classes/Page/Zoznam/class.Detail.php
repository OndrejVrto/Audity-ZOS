<?php

namespace Page\Zoznam;

class Detail extends \Page\Page
{

    function __construct()
    {
        parent::__construct();

        if ( !isset($_POST['detail']) AND !isset($_GET['id']) ) {
            header("Location: $this->linkZoznam");
            exit;
        }

        if (isset($_GET['id'])) {
            // vyberie z predaného parametra len prvé číslo a vloží ho do POST metódy s ktorou počíta zvyšný kod
            preg_match("/[1-9]+[0-9]*/", $_GET['id'], $cleanID );
            if($cleanID[0]) {
                $_POST['detail'] = $cleanID[0];
            } else {
                header("Location: $this->linkZoznam");
                exit;
            }
        }
    }

    function ContentHeaderZoznam ()
    {
?>
    <div class="row justify-content-center">
        <div class="<?= $this->bodyClassExtended ?>" style="<?= $this->bodyWidthExtended ?>">

            <form novalidate>
            <fieldset disabled>
                <div class="card" >

                    <div class="card-header">
                        Detailné informácie o položke zoznamu
                    </div>
                    <div class="card-body register-card-body">
<?php
    }

    function ContentFooterZoznam()
    {
?>

                    </div>

                </div>

            </fieldset>
            </form>

            <div class="row justify-content-center">
                <a href="<?= $this->linkZoznam ?>" name="vzad" class="btn btn-warning mx-1">Späť</a>
            </div>

        </div>
    </div>

<?php
    }

}