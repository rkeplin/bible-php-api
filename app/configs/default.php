<?php
/**
 * Configuration options for the app.
 *
 * These variables can be accessed via Core\Application::$config
 **/
return array(
    'environment' => getenv('ENVIRONMENT'),
    'mysql' => array(
        'db_host' => getenv('DB_HOST'),
        'db_name' => getenv('DB_NAME'),
        'db_user' => getenv('DB_USER'),
        'db_pass' => getenv('DB_PASS'),
    ),
    'routes' => array(
        /* Translations */
        '/translations' => array('translation', 'get'),
        '/translations/([0-9]+)' => array('translation', 'get', array('translationId')),

        /* Genres */
        '/genres' => array('genre', 'get'),
        '/genres/([0-9]+)' => array('genre', 'get', array('genreId')),

        /* Books */
        '/books' => array('book', 'get'),
        '/books/([0-9]+)' => array('book', 'get', array('bookId')),
        '/books/([0-9]+)/chapters' => array('book', 'chapters', array('bookId')),
        '/books/([0-9]+)/chapters/([0-9]+)' => array('text', 'get', array('bookId', 'chapterId')),
        '/books/([0-9]+)/chapters/([0-9]+)/([0-9]+)' => array('text', 'get', array('bookId', 'chapterId', 'verseId')),

        /* Cross Reference */
        '/verse/([0-9]+)/relations' => array('relation', 'get', array('verseId'))
    )
);
