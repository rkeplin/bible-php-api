<?php
/**
 * Configuration options for the app.
 *
 * These variables can be accessed via Core\Application::$config
 **/
return array(
    'environment' => getenv('ENVIRONMENT'),
    'session' => array(
        'name' => 'token'
    ),
    'mysql' => array(
        'db_host' => getenv('DB_HOST'),
        'db_name' => getenv('DB_NAME'),
        'db_user' => getenv('DB_USER'),
        'db_pass' => getenv('DB_PASS'),
    ),
    'mongo' => array(
        'host' => getenv('MONGO_HOST'),
        'db'   => getenv('MONGO_DB'),
        'user' => getenv('MONGO_USER'),
        'pass' => getenv('MONGO_PASS'),
    ),
    'routes' => array(
        /* Authentication */
        '/authenticate' => array(
            'POST' => array('authenticate', 'login')
        ),
        '/authenticate/logout' => array(
            'GET' => array('authenticate', 'logout')
        ),
        '/authenticate/me' => array(
            'GET' => array('authenticate', 'me')
        ),

        /* Register */
        '/register' => array(
            'POST' => array('user', 'register')
        ),

        /* Lists */
        '/lists' => array(
            'GET' => array('list', 'getAll'),
            'POST' => array('list', 'create'),
        ),

        '/lists/([0-9a-zA-Z]+)' => array(
            'GET' => array('list', 'getOne', array('listId')),
            'PUT' => array('list', 'update', array('listId')),
            'DELETE' => array('list', 'delete', array('listId'))
        ),

        '/lists/([0-9a-zA-Z]+)/verses' => array(
            'GET' => array('list', 'getAllVerses', array('listId'))
        ),

        '/lists/([0-9a-zA-Z]+)/verses/([0-9]+)' => array(
            'PUT' => array('list', 'putVerse', array('listId', 'verseId')),
            'DELETE' => array('list', 'deleteVerse', array('listId', 'verseId'))
        ),

        /* Translations */
        '/translations' => array(
            'GET' => array('translation', 'get')
        ),
        '/translations/([0-9]+)' => array(
            'GET' => array('translation', 'get', array('translationId'))
        ),

        /* Genres */
        '/genres' => array(
            'GET' => array('genre', 'get')
        ),
        '/genres/([0-9]+)' => array(
            'GET' => array('genre', 'get', array('genreId'))
        ),

        /* Books */
        '/books' => array(
            'GET' => array('book', 'get')
        ),
        '/books/([0-9]+)' => array(
            'GET' => array('book', 'get', array('bookId'))
        ),
        '/books/([0-9]+)/chapters' => array(
            'GET' => array('book', 'chapters', array('bookId'))
        ),
        '/books/([0-9]+)/chapters/([0-9]+)' => array(
            'GET' => array('text', 'get', array('bookId', 'chapterId'))
        ),
        '/books/([0-9]+)/chapters/([0-9]+)/([0-9]+)' => array(
            'GET' => array('text', 'get', array('bookId', 'chapterId', 'verseId'))
        ),

        /* Cross Reference */
        '/verse/([0-9]+)/relations' => array(
            'GET' => array('relation', 'get', array('verseId'))
        )
    )
);
