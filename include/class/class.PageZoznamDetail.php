<?php

class PageZoznamDetail extends Page
{



    function ContentHeaderZoznam (){

?>
    <div class="row justify-content-center">
        <div class="<?= $this->bodyClassExtended ?>" style="<?= $this->bodyWidthExtended ?>">

            <form>
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
                <a href="<?= $this->link ?>" name="vzad" class="btn btn-secondary mx-1">Späť</a>
            </div>

        </div>
    </div>

<?php
    }

}