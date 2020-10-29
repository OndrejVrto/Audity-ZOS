<?php

class Menu {

    protected $odsadenie = 4;
    protected $_premenne;
    private $kod_stranky;
    private $odsad;


    // Metody třídy Page
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __construct($nazovStranky)
    {
        // načíta konštanty zo súboru _variables
        $this->_premenne = new Premenne($nazovStranky);
        $this->kod_stranky = $nazovStranky;
        //print_r ($this->_premenne->menuHlavne);
    }

    public function getMenu ()
    {
        $this->odsad = str_repeat("\t", $this->odsadenie );
        
        foreach ($this->_premenne->menuHlavne as $key => $value) {
            if (!is_array($value)) {
                $this->displayHeader($value);
            } else {
                $this->displayPrimaryMenu($value);
            }
        }

    }

    protected function displayHeader($nazov)
    {
        echo "\n".$this->odsad.'<li class="nav-header">';
        echo "\n\t".$this->odsad.htmlspecialchars($nazov);
        echo "\n".$this->odsad.'</li>';
    }

    protected function displayPrimaryMenu($nazov)
    {
        $htmlSubmenu = '';
        $aktivneSubmenu = false;

        if ($nazov['SUBMENU'] <> false) {
            foreach ($nazov['SUBMENU'] as $key => $value) {
                $htmlSubmenu .= "\n\t" . $this->odsad . '<ul class="nav nav-treeview">';
                $htmlSubmenu .= "\n\t\t" . $this->odsad . '<li class="nav-item">';
                $htmlSubmenu .= "\n\t\t\t" . $this->odsad . '<a href="' . htmlspecialchars($value['Link']) . '" class="nav-link';
                if ($value['KodStranky'] == $this->kod_stranky) {
                    $htmlSubmenu .= ' active';
                    $aktivneSubmenu = true;
                }
                $htmlSubmenu .= '">';
                $htmlSubmenu .= "\n\t\t\t\t" . $this->odsad . '<i class="far fa-circle nav-icon"></i>';
                $htmlSubmenu .= "\n\t\t\t\t" . $this->odsad . '<p>';
                $htmlSubmenu .= "\n\t\t\t\t\t" . $this->odsad . htmlspecialchars($value['Nazov']);
                if ($value['Doplnok'] <> false) {
                    $htmlSubmenu .= "\n\t\t\t\t\t" . $this->odsad . '<span class="right ' . htmlspecialchars($value['Doplnok']) . '">';
                    $htmlSubmenu .= htmlspecialchars($value['PopisDoplnku']) . '</span>';
                }
                $htmlSubmenu .= "\n\t\t\t\t" . $this->odsad . '</p>';
                $htmlSubmenu .= "\n\t\t\t" . $this->odsad . '</a>';
                $htmlSubmenu .= "\n\t\t" . $this->odsad . '</li>';
                $htmlSubmenu .= "\n\t" . $this->odsad . '</ul>';
            }
        }

        if ($aktivneSubmenu == true) {
            echo "\n" . $this->odsad . '<li class="nav-item has-treeview menu-open">';
        } else {
            echo "\n" . $this->odsad . '<li class="nav-item has-treeview">';
        }

        if ($aktivneSubmenu == true || $nazov['KodStranky'] == $this->kod_stranky) {
            echo "\n\t" . $this->odsad . '<a href="' . htmlspecialchars($nazov['Link']) . '" class="nav-link active">';
        } else {
            echo "\n\t" . $this->odsad . '<a href="' . htmlspecialchars($nazov['Link']) . '" class="nav-link">';
        }

        echo "\n\t\t" . $this->odsad . '<i class="nav-icon ' . htmlspecialchars($nazov['Ikona']) . '"></i>';
        echo "\n\t\t" . $this->odsad . '<p>';
        echo "\n\t\t\t" . $this->odsad . htmlspecialchars($nazov['Nazov']);
        if ($nazov['SUBMENU'] <> false) {
            echo "\n\t\t\t" . $this->odsad . '<i class="right fas fa-angle-left"></i>';
        }
        if ($nazov['Doplnok'] <> false) {
            echo "\n\t\t\t" . $this->odsad . '<span class="right ' . htmlspecialchars($nazov['Doplnok']) . '">';
            echo htmlspecialchars($nazov['PopisDoplnku']) . '</span>';
        }
        echo "\n\t\t" . $this->odsad . '</p>';
        echo "\n\t" . $this->odsad . '</a>';
        echo $htmlSubmenu;
        echo "\n" . $this->odsad . '</li>';
        echo "\n";
    }
}

