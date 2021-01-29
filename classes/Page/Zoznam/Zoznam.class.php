<?php

namespace Page\Zoznam;

class Zoznam extends \Page\Zoznam\ZoznamSkripty
{

    function ContentHeaderZoznamTlacitka (){

?>
    <div class='row justify-content-center pb-3'>
        <div id="UpravaDat" class='form-inline'>
<?php if ( $this->levelUser >= 12 ){ ?>
            <form action="<?= $this->linkCisty ?>novy" method="post">
                <button type="submit" name="novy" ID='novy-zaznam' class="btn btn-primary">Pridať položku</button>
            </form>
<?php } ?>
            <form action="<?= $this->linkCisty ?>detail" method="post" class="mx-1">
                <button type="submit" name="detail" ID='button-detail' value="" class="btn btn-warning" disabled>Detaily</button>
            </form>
<?php if ( $this->levelUser >= 12 ){ ?>
            <form action="<?= $this->linkCisty ?>edit" method="post" class="mr-1">
                <button type="submit" name="edit" ID='button-edit' value="" class="btn btn-success" disabled>Editovať</button>
            </form>
            <form action="<?= $this->linkCisty ?>delete" method="post">
                <button type="submit" name="delete" ID='button-delete' value="" class="btn btn-danger" disabled>Zmazať</button>
            </form>
<?php } ?>
        </div>
    </div>
<?php
    }

    function ContentHeaderZoznam (){

?>

    <div class="row justify-content-center">
        <div class="<?= $this->bodyClassExtended ?>" style="<?= $this->bodyWidthExtended ?>">

            <div class='card'>
                <div class='card-body p-2'>

                    <table class='table table-sm hover compact' width="100%" id='tabulka'>

<?php
    }

    function ContentFooterZoznam()
    {
?>

                    </table>

                </div>
            </div>

        </div>
    </div>

<?php
    }
}