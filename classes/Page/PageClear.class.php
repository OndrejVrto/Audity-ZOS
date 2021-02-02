<?php

namespace Page;

class PageClear extends \Page\Page
{
    public $link;
    public $classBodySpecial;
    
    function __construct()
    {
        parent::__construct();
        $this->link = $_SERVER['REQUEST_URI'];
    }
    
    public function display()
    {
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
        echo "\n</body>\n</html>\n";
    }
}