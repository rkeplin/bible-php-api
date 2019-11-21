<?php
include '../app/bootstrap.php';

use Domain\Mapper\BookMapper;
use Domain\Mapper\TextMapper;

function createIndex($index, $definition) {
    $ch = curl_init(sprintf('http://bible-es:9200/%s', $index));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $definition);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($definition))
    );

    $result = curl_exec($ch);

    print_r($result);
    echo "\n\n";
}

function deleteIndex($index) {
    $ch = curl_init(sprintf('http://bible-es:9200/%s', $index));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'
    ));

    $result = curl_exec($ch);

    print_r($result);
    echo "\n\n";
}

function insertDoc($index, $entity)
{
    $doc = array(
        'id'          => $entity['id'],
        'testament'   => $entity['book']['testament'],
        'bookId'      => $entity['book']['id'],
        'bookName'    => $entity['book']['name'],
        'chapterId'   => $entity['chapterId'],
        'verseId'     => $entity['verseId'],
        'verse'       => $entity['verse']
    );

    $doc = json_encode($doc);

    $ch = curl_init(sprintf('http://bible-es:9200/%s/_doc/%d', $index, $entity['id']));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $doc);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($doc))
    );

    $result = curl_exec($ch);

    print_r($result);
    echo "\n\n";
}

$translations = array(
    't_asv' => 'asv',
    't_bbe' => 'bbe',
    't_dby' => 'dby',
    't_esv' => 'esv',
    't_kjv' => 'kjv',
    't_niv' => 'niv',
    't_nlt' => 'nlt',
    't_wbt' => 'wbt',
    't_web' => 'web',
    't_ylt' => 'ylt'
);

$definition = array(
  'mappings' => array(
      'properties' => array(
          'id'          => array('type' => 'integer'),
          'testament'   => array('type' => 'keyword'),
          'bookId'      => array('type' => 'integer'),
          'bookName'    => array('type' => 'keyword'),
          'chapterId'   => array('type' => 'integer'),
          'verseId'     => array('type' => 'integer'),
          'verse'       => array('type' => 'text')
      )
  )
);

$definition = json_encode($definition);

$config = include APP_PATH . 'configs' . DS . 'default.php';
$db = new PDO("mysql:host={$config['mysql']['db_host']};dbname={$config['mysql']['db_name']}", $config['mysql']['db_user'], $config['mysql']['db_pass']);

$textMapper = new TextMapper($db);
$bookMapper = new BookMapper($db);

foreach ($translations as $table => $index) {
    /* Create index with definition */
    createIndex($index, $definition);

    /* Insert docs */
    $textMapper->setTable($table);

    for ($i = 1; $i <= 66; $i++) {
        $chapters = $bookMapper->findChapters($i);

        foreach ($chapters as $chapter) {
            $collection = $textMapper->findAll(array(
                'bookId' => $i,
                'chapterId' => $chapter->id
            ));

            $items = $collection->getItems();

            foreach($items as $item) {
                insertDoc($index, $item);
            }
        }
    }
}
