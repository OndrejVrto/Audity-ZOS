<?php

namespace Page\Zoznam;

class Delete extends \Page\Page
{

    public $id;
    public $pocet;

    function __construct()
    {

        if ( !isset($_SESSION['Admin']) && !$_SESSION['Admin'] == 1 ){
            // ak je uživateľ neprihlásený, alebo nieje v roli admin ukončí nahrávanie triedy a tým zobrazí prázdnu stránku
            header('HTTP/1.0 401 Unauthorized');
            header("Location: /errorpages/401.php");
            exit();
        }

        parent::__construct();
        
        if ( !isset($_POST['delete'])) {
            if ( !isset($_POST['submit'])) {
                header("Location: $this->linkZoznam");
                exit;                
            }
        }

        $premenne = new \Premenne($this->linkZoznam);

        $this->title = $premenne->titulokStranky;
        $this->nadpis = $premenne->nadpisPrvejSekcie;
        $this->description = $premenne->popisStranky;
        $this->hlavneMenu = $premenne->menuHlavne;        
    }

    function ContentHeaderZoznam ()
    {
        if ($this->pocet > 0) {
?>
    <div class="row justify-content-center">
        <div class="<?= $this->bodyClassExtended ?>" style="<?= $this->bodyWidthExtended ?>">

            <div class="card" >

                <div class="card-header">
                    Upozornenie
                </div>

                <div class="card-body register-card-body">
<?php
        } elseif($this->pocet <= 0) {
?>
    <div class="row justify-content-center">
        <div class="<?= $this->bodyClassExtended ?>" style="<?= $this->bodyWidthExtended ?>">

            <form action="<?= $this->linkCisty ?>delete" method="post" novalidate>
                <div class="card" >

                    <div class="card-header">
                        Zmazanie položky
                    </div>

                    <div class="card-body register-card-body">
<?php
        }
    }

    function ContentFooterZoznam()
    {
        if ($this->pocet > 0) {
?>
                </div>
            </div>
            <div class="row justify-content-center">
                <a href="<?= $this->linkZoznam ?>" type="submit" name="vzad" class="btn btn-secondary">Späť</a>                    
            </div>
        </div>
    </div>

<?php
        } elseif($this->pocet <= 0) {
?>

                    </div>

                </div>

                <div class="row justify-content-center">
                    <a href="<?= $this->linkZoznam ?>" name="vzad" class="btn btn-secondary mx-1">Späť</a>
                    <button type="submit" name="submit" value="<?= $this->id ?>" class="btn btn-outline-danger mx-1">Zmazať</button>
                </div>

            </form>

        </div>
    </div>

<?php
        }
    }

}