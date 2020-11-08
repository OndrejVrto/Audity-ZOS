<?php

class PageZoznamEdit extends Page
{

    public $id;

    function __construct()
    {
        parent::__construct();

        // prepisujem premennú $link ktorá sa polužíva pri generovaní menu, bublemenu a aj v konštantach
        // aby sa stránka zobrazovala rovnako
        $this->link = $this->linkCisty."zoznam";

        $premenne = new Premenne($this->link);

        $this->title = $premenne->titulokStranky;
        $this->nadpis = $premenne->nadpisPrvejSekcie;
        $this->description = $premenne->popisStranky;
        $this->hlavneMenu = $premenne->menuHlavne;        
    }
    
    function ContentHeaderZoznam (){

?>
    <div class="row justify-content-center">
        <div class="<?= $this->bodyClassExtended ?>" style="<?= $this->bodyWidthExtended ?>">

            <form action="<?= $this->linkCisty ?>edit" method="post">
                <div class="card" >

                    <div class="card-header">
                        Vytvorenie novej položky
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
                    <button type="submit" name="submit" value="<?= $this->id ?>" class="btn btn-outline-success mx-1">Uložiť</button>
                </div>

            </form>

        </div>
    </div>

<?php
    }

}