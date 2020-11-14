<?php

namespace Page\Zoznam;

class Novy extends \Page\Page
{

    function __construct()
    {
        parent::__construct();

        $premenne = new \Premenne($this->linkZoznam);

        $this->title = $premenne->titulokStranky;
        $this->nadpis = $premenne->nadpisPrvejSekcie;
        $this->description = $premenne->popisStranky;
        $this->hlavneMenu = $premenne->menuHlavne;        
    }

    function ContentHeaderZoznam (){

?>
    <div class="row justify-content-center">
        <div class="<?= $this->bodyClassExtended ?>" style="<?= $this->bodyWidthExtended ?>">

            <form action="<?= $this->linkCisty ?>novy" method="post" novalidate>
                <div class="card" >

                    <div class="card-header">
                        Vytvoriť novú položku
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
                    <button type="submit" name="submit" class="btn btn-outline-warning mx-1">Uložiť</button>
                </div>

            </form>

        </div>
    </div>

<?php
    }

}