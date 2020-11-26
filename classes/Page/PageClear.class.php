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
        $this->displayBegin();
        $this->displayTitle();
        $this->displayDescription();
        $this->displayIcons();
        $this->displayStyles();
        echo $this->stylySpecial;
        echo "\n</head>\n\n";
        echo '<body class="' . $this->classBodySpecial . '">'."\n";
        echo $this->content;
        $this->displayScripts();
        echo $this->skriptySpecial;
        echo "\n</body>\n</html>\n";
    }
}