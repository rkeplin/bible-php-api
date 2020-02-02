# Bible PHP API
[![Build Status](https://travis-ci.org/rkeplin/bible-php-api.svg?branch=master)](https://travis-ci.org/rkeplin/bible-php-api)
[![codecov](https://codecov.io/gh/rkeplin/bible-php-api/branch/master/graph/badge.svg)](https://codecov.io/gh/rkeplin/bible-php-api)

Bible PHP API is an open source REST API.  It contains multiple translations of The Holy Bible, as well as cross-references. 
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY.

### Live Demo
A live demo of this application can be viewed [here](https://bible-php-api.rkeplin.com/v1/books/1/chapters/1).

### Getting Everything Running
```bash
git clone https://www.github.com/rkeplin/bible-php-api
cd bible-php-api && docker-compose up -d
```
Note: Upon first start, the volume containing the MySQL data may take several seconds to load.

You should then be able to access [http://localhost:8083](http://localhost:8083) for the REST API and [http://localhost:8082](http://localhost:8082) for the UI (AngularJS).

### API Specifications
#### List of available translations
```bash
GET http://localhost:8083/translations
GET http://localhost:8083/translations/[TranslationID]
```

#### List of Genres
```bash
GET http://localhost:8083/genres
GET http://localhost:8083/genres/[GenreID]
```

#### Content
```bash
GET http://localhost:8083/books
GET http://localhost:8083/books/[BookID]
GET http://localhost:8083/books/[BookID]/chapters/[ChapterID]
GET http://localhost:8083/books/[BookID]/chapters/[ChapterID]
GET http://localhost:8083/books/[BookID]/chapters/[ChapterID]/[VerseID]
```
Note: In order to get content for a specific translation, supply `translation` as a Query Parameter.  For example,
`http://localhost:8083/books/1/chapters/1/1001002?translation=ASV`

#### Cross References
```bash
GET http://localhost:8083/verse/[VerseID]/relations 
```

#### Registering
```bash
curl -XPOST -H "Content-Type: application/json" -d '{"email":"something@example.com", "password":"something", "passwordConf": "something"}' http://localhost:8083/register
```

#### Authenticating
```bash
# Logging in
curl -XPOST -H "Content-Type: application/json" -d '{"email":"something@example.com", "password":"something"}' http://localhost:8083/authenticate
 
# Logging out
curl -XGET -H "Content-Type: application/json" -H "Cookie: token=[TOKEN]" http://localhost:8083/authenticate/logout
 
# Getting current user information
curl -XGET -H "Content-Type: application/json" -H "Cookie: token=[TOKEN]" http://localhost:8083/authenticate/me
```

#### Managing lists
Registered users may create a list of verses
```bash
# Getting my lists
curl -XGET -H "Content-Type: application/json" -H "Cookie: token=[TOKEN]" http://localhost:8083/lists
 
# Creating a list
curl -XPOST -H "Content-Type: application/json" -H "Cookie: token=[TOKEN]" -d '{"name": "test list"}' http://localhost:8083/lists
 
# Getting a specific list
curl -XGET -H "Content-Type: application/json" -H "Cookie: token=[TOKEN]" http://localhost:8083/lists/[ListID]
 
# Updating a specific list 
curl -XPUT -H "Content-Type: application/json" -H "Cookie: token=[TOKEN]" -d '{"name": "test list updated"}' http://localhost:8083/lists/[ListID]
 
# Deleting a list
curl -XDELETE -H "Content-Type: application/json" -H "Cookie: token=[TOKEN]" http://localhost:8083/lists/[ListID]
 
# Get the verses on a list
curl -XGET -H "Content-Type: application/json" -H "Cookie: token=[TOKEN]" 127.0.0.1:8083/lists/[ListID]/verses
 
# Add a verse to a list
curl -XPUT -H "Content-Type: application/json" -H "Cookie: token=[TOKEN]" 127.0.0.1:8083/lists/[ListID]/verses/[VerseID]
 
# Remove a verse from a list
curl -XDELETE -H "Content-Type: application/json" -H "Cookie: token=[TOKEN]" 127.0.0.1:8083/lists/[ListID]/verses/[VerseID]
```

### Running The Test Suite
```bash
make test
```

### Related Projects
* [Bible Go API](https://www.github.com/rkeplin/bible-go-api)
* [Bible PHP API](https://www.github.com/rkeplin/bible-php-api)
* [Bible AngularJS UI](https://www.github.com/rkeplin/bible-angularjs-ui)
* [Bible MariaDB Docker Image](https://www.github.com/rkeplin/bible-mariadb)

### Credits
Data for this application was gathered from the following repositories.
* [scrollmaper/bible_database](https://github.com/scrollmapper/bible_databases)
* [honza/bibles](https://github.com/honza/bibles)
