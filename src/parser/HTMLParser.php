<?php

require_once 'src/parser/XMLParser.php';

class HTMLParser extends XMLParser
{

    public function parseDOM(Session $session)
    {
        $session->dom->loadHTML($session->bytes);
    }

}

?>
