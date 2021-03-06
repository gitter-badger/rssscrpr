<?php

//Feed-Validator
//http://validator.w3.org/feed/

/*
 * TODO Facebook
 * TODO Rechenzentrum
 * TODO Rektoratsnachrichten
 * TODO: BITOnline Pipe (low prio)
 http://www.b-i-t-online.de/bitrss.xml
 mit "b.i.t.online - Ausgabe" anfangen herausfiltern
 auf Webseite verlinken

 */

 /*
 Examples calls:

 index.php?url=http://www.ub.uni-dortmund.de/listen/inetbib/date1.html&noanswers=true&nojobs

 index.php?url=http://www.handle.net/mail-archive/handle-info/

 index.php?url=https://twitter.com/UBMannheim&nofb&noretweet
 index.php?url=https://twitter.com/hashtag/zotero
  */

// TODO correct date handling (use DateTime)
date_default_timezone_set('UTC');

require_once 'src/Utils.php';
require_once 'src/RetrieverFactory.php';

function echoRSS($feed)
{
    $dom = $feed->asRSS();
    $xml = $dom->saveXML();
    header('Content-Type: application/rss+xml');
    echo $xml;
}
set_error_handler("warning_handler", E_WARNING);

function warning_handler($errno, $errstr)
{

    // WARNING:2: DOMDocument::loadHTML(): Attribute data-referrer redefined in Entity, line: 17
    if (Utils::contains($errstr, 'redefined')) return;
    if (Utils::contains($errstr, 'already defined')) return;
    if (Utils::contains($errstr, "htmlParseEntityRef: expecting ';'")) return;
    if (Utils::contains($errstr, "invalid in Entity")) return;
    error_log("WARNING: " . $errstr);
    if (Utils::contains($errstr, 'Unexpected end tag')) return;
    // Utils::throw400("WARNING:$errno: $errstr");
}

if (!isset($_GET['action']))
{
    Utils::throw400("Must set 'action'!");
}

if ($_GET['action'] === 'scrape-html')
{
    $retriever = RetrieverFactory::createHtmlScraperFromQueryParams($_GET);
    $feed = $retriever->go();
    echoRSS($feed);
}
else if ($_GET['action'] === 'reflect')
{
    header('Content-Type: application/json');
    echo Utils::reflectComponents();
}
else
{
    Utils::throw400("Undefine action '{$_GET['action']}'!");
}
?>
