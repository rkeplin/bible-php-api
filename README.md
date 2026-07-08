# Bible PHP API

[![Unit Tests](https://github.com/rkeplin/bible-php-api/actions/workflows/tests.yml/badge.svg)](https://github.com/rkeplin/bible-php-api/actions/workflows/tests.yml)
[![codecov](https://codecov.io/gh/rkeplin/bible-php-api/branch/master/graph/badge.svg)](https://codecov.io/gh/rkeplin/bible-php-api)

Bible PHP API is an open source REST API containing multiple translations of The Holy Bible, as well as cross-references.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY.

### Live Demo
A live demo of this application can be viewed [here](https://bible-php-api.rkeplin.com/v1/books/1/chapters/1).

---

## Local Development

### Prerequisites
- Docker
- Docker Compose

### Running Locally

```bash
git clone https://github.com/rkeplin/bible-php-api
cd bible-php-api
docker-compose up -d
```

> Upon first start, the MariaDB volume may take several seconds to initialize.

The REST API will be available at [http://localhost:8083](http://localhost:8083).

### Running Unit Tests

```bash
make test
```

This runs PHPUnit inside the running container with coverage enabled. To run only the unit test suite without coverage:

```bash
docker-compose exec php-api sh -c "cd tests && /usr/local/bin/phpunit --testsuite 'Unit Tests'"
```

### Stopping Services

```bash
make down
```

---

## Deploying to Kubernetes

### Prerequisites
- `kubectl` configured and pointing at your target cluster
- A `.env` file at the project root with the required secrets (see `.env.example`)

### Required `.env` Variables

```
BIBLE_DB_NAME=bible
BIBLE_DB_USER=bible
BIBLE_DB_PASS=changeme
MONGO_DB=app
MONGO_USER=bible
MONGO_PASS=changeme
```

### Deploy

```bash
# Create the namespace, apply secrets, and deploy all resources
make k8s-deploy
```

This runs the following steps in order:
1. Creates the `bible` namespace (`infra/k8s/namespace.yaml`)
2. Creates/updates the `bible-env` secret from your `.env` file
3. Applies Mongo, Redis, Deployment/Service, and Ingress manifests

### Check Status

```bash
make k8s-status
```

### Tear Down

```bash
make k8s-delete
```

### Pushing a New Image

Build and push the Docker image to Docker Hub before deploying:

```bash
make push
```

The deployment uses `rkeplin/bible-php-api:latest`. After pushing, restart the deployment to pull the new image:

```bash
kubectl rollout restart deployment/bible-php-api -n bible
```

---

## API Reference

### Translations

```bash
GET /translations
GET /translations/{translationId}
```

### Genres

```bash
GET /genres
GET /genres/{genreId}
```

### Books & Chapters

```bash
GET /books
GET /books/{bookId}
GET /books/{bookId}/chapters/{chapterId}
GET /books/{bookId}/chapters/{chapterId}/{verseId}
```

To retrieve content for a specific translation, pass `translation` as a query parameter:

```bash
GET /books/1/chapters/1/1001002?translation=ASV
```

### Cross References

```bash
GET /verse/{verseId}/relations
```

### Authentication

```bash
# Register
curl -XPOST -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"secret","passwordConf":"secret"}' \
  http://localhost:8083/register

# Login
curl -XPOST -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"secret"}' \
  http://localhost:8083/authenticate

# Logout
curl -XGET -H "Cookie: token=[TOKEN]" http://localhost:8083/authenticate/logout

# Current user
curl -XGET -H "Cookie: token=[TOKEN]" http://localhost:8083/authenticate/me
```

### Verse Lists

Registered users can manage personal lists of verses.

```bash
# List all lists
curl -XGET -H "Cookie: token=[TOKEN]" http://localhost:8083/lists

# Create a list
curl -XPOST -H "Content-Type: application/json" -H "Cookie: token=[TOKEN]" \
  -d '{"name":"My List"}' http://localhost:8083/lists

# Get a list
curl -XGET -H "Cookie: token=[TOKEN]" http://localhost:8083/lists/{listId}

# Update a list
curl -XPUT -H "Content-Type: application/json" -H "Cookie: token=[TOKEN]" \
  -d '{"name":"Updated Name"}' http://localhost:8083/lists/{listId}

# Delete a list
curl -XDELETE -H "Cookie: token=[TOKEN]" http://localhost:8083/lists/{listId}

# Get verses on a list
curl -XGET -H "Cookie: token=[TOKEN]" http://localhost:8083/lists/{listId}/verses

# Add a verse to a list
curl -XPUT -H "Cookie: token=[TOKEN]" http://localhost:8083/lists/{listId}/verses/{verseId}

# Remove a verse from a list
curl -XDELETE -H "Cookie: token=[TOKEN]" http://localhost:8083/lists/{listId}/verses/{verseId}
```

---

## Related Projects

- [Bible Go API](https://github.com/rkeplin/bible-go-api)
- [Bible UI (React)](https://github.com/rkeplin/bible-ui)
- [Bible MariaDB Docker Image](https://github.com/rkeplin/bible-mariadb)

## Credits

Bible data sourced from:
- [scrollmapper/bible_databases](https://github.com/scrollmapper/bible_databases)
- [honza/bibles](https://github.com/honza/bibles)
