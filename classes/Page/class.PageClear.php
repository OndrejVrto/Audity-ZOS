<?php

namespace Page;

class PageClear extends \Page\Page
{
    public $link;
    public $classBodySpecial;
    
    function __construct()
    {
        parent::__construct();
        $this->link = trim(strtok($_SERVER['REQUEST_URI'], '?"'));
    }
    
    public function display()
    {
        ob_start(); // celá stránka sa načíta najskôr do pamäte pre potreby minimalizácie na konci tejto funkcie 

        if (isset($_SESSION['ALERT'])) {
            $this->alert = $_SESSION['ALERT'];
            unset($_SESSION['ALERT']);
        }

        $this->displayBegin();
        $this->displayTitle();
        $this->displayDescription();
        $this->displayIcons();
        $this->displayStyles();
        echo $this->stylySpecial;
        echo "\n</head>\n\n";
        echo '<body class="' . $this->classBodySpecial . '">'."\n";
        echo $this->content;
        // vypíše upozornenie pri zobrazení stránky
        if ($this->alert) {
            echo "\n\t<!--  Okno s upozornením  -->\n\t";
            echo '<script>alert(' . $this->alert . ');</script>' . PHP_EOL;
        }
        $this->displayScripts();
        echo $this->skriptySpecial;
        
        if (VYVOJ OR $this->levelUser >= 20) {
            echo '<dim class="overflow-auto">';
            echo $this->VYVOJ();
            echo "</dim>";
        }

        echo "\n</body>\n</html>\n";

        // vloží kompletnú stránku s buferu do premennej
        $CelaStranka = ob_get_clean();

        // aktivuje triedu na minimalizáciu kódu
        if ( VYVOJ OR $this->levelUser >= 20 OR !$this->zbalHTML) {
            // pošle verziu bez minifikácie
        } else {
            $CelaStranka =  \Minifier\Minify::html($CelaStranka);
        }
        // zbalí stránku pred odoslaním do zip-u
        $stranka = gzencode($CelaStranka, 6, ZLIB_ENCODING_GZIP);
        $velkostStrankyVbitoch = strlen($stranka);
        // nastaví hlavičku súboru s veľkosťou stránky
        header("Content-Length: $velkostStrankyVbitoch");
        // nastaví hlavičku o zbalení
        header('Content-Encoding: gzip');
        // vykreslí stránku
        echo $stranka;
    }
}