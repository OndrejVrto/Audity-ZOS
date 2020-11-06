<?php

class PageZoznamNovy extends Page
{

    function __construct()
    {
        parent::__construct();
        $this->link = upravLink($_SERVER['REQUEST_URI']);
    }

    function ContentHeaderZoznam (){

?>
    <div class="row justify-content-center">
        <div class="<?= $this->bodyClassExtended ?>" style="<?= $this->bodyWidthExtended ?>">

            <form action="<?= $this->linkCisty ?>novy" method="post">
                <div class="card" >

                    <div class="card-header">
                        Editovať položku
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
                    <a href="<?= $this->linkCisty ?>zoznam" name="vzad" class="btn btn-secondary mx-1">Späť</a>
                    <button type="submit" name="submit" class="btn btn-outline-warning mx-1">Uložiť</button>
                </div>

            </form>

        </div>
    </div>

<?php
    }

}