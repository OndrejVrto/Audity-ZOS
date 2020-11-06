<?php

class PageClear extends Page
{

    public function display()
    {
        $this->displayBegin();
        $this->displayTitle();
        $this->displayDescription();
        $this->displayIcons();
        $this->displayStyles();
        echo $this->stylySpecial;
        echo "\n</head>\n\n";
        echo '<body class="hold-transition register-page vh-100">'."\n";
        echo $this->content;
        $this->displayScripts();
        echo $this->skriptySpecial;
        echo "\n</body>\n</html>\n";
    }
}