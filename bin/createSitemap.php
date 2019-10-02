<?php
use Core\Application;
use Domain\Mapper\BookMapper;

include '../app/bootstrap.php';

function xmlDecorator ($inner, $node)
{
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
         . '<' . $node . ' xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n"
         . $inner
         . '</' . $node . '>';

    return $xml;
}

function sitemapDecorator ($url, $date)
{
    $xml = "\t" . '<sitemap>' . "\n"
         . "\t\t" . '<loc>' . $url . '</loc>' . "\n"
         . "\t\t" . '<lastmod>' . $date . '</lastmod>' . "\n"
         . "\t" . '</sitemap>' . "\n";

    return $xml;
}

function getBookSlug ($book)
{
    return $book['id'] . '-' . strtolower(str_replace(' ', '', $book['name']));
}

function getSitemapUrl ($book)
{
    $slug = getBookSlug($book);

    return 'https://bible-ui.rkeplin.com/sitemaps/' . $slug . '.xml';
}

function getUrl ($book, $chapterId)
{
    return 'https://bible-ui.rkeplin.com/books/' . $book['id'] . '/' . $chapterId;
}

function createFile ($file, $content)
{
    if (file_exists($file)) {
        unlink($file);
    }

    file_put_contents($file, $content);
}

$config = include APP_PATH . 'configs' . DS . 'default.php';

$app = new Application();
$app->setup($config);

$mapper = new BookMapper();
$books = $mapper->findAll();

$date = '2019-10-02T00:00:00+00:00';
$xml = '';

$sitemapDir = dirname(__FILE__) . '/sitemaps';

if (!is_dir($sitemapDir)) {
    mkdir($sitemapDir, 0755);
}

foreach ($books as $book) {
    $url = getSitemapUrl($book);
    $xml .= sitemapDecorator($url, $date);

    $wrapped = '';
    $bookXml = '';
    $bookSitemapFile = $sitemapDir . '/' . getBookSlug($book) . '.xml';

    $chapters = $mapper->findChapters($book['id']);

    foreach ($chapters as $chapter) {
        $url = getUrl($book, $chapter->id);
        $bookXml .= sitemapDecorator($url, $date);
    }

    $wrapped .= xmlDecorator($bookXml, 'urlset');
    createFile($bookSitemapFile, $wrapped);
}

$xml = xmlDecorator($xml, 'sitemapindex');
$sitemapIndexFile = $sitemapDir . '/sitemap_index.xml';

createFile($sitemapIndexFile, $xml);
