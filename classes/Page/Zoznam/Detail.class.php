<?php

namespace Page\Zoznam;

class Detail extends \Page\Page
{

    function __construct()
    {
        parent::__construct();

        if ( ! isset($_POST['detail']) ) {
            header("Location: $this->linkZoznam");
            exit;
        }

        $premenne = new \Premenne($this->linkZoznam);

        $this->title = $premenne->titulokStranky;
        $this->nadpis = $premenne->nadpisPrvejSekcie;
        $this->description = $premenne->popisStranky;
        $this->hlavneMenu = $premenne->menuHlavne;        
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