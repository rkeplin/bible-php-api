NS ?= bible

.PHONY: up down build test

up:
	docker-compose up -d

down:
	docker-compose down

build:
	docker-compose build

test:
	docker-compose exec php-api sh -c "cd tests && /usr/local/bin/phpunit --coverage-clover=coverage/coverage.xml"

push:
	bin/push.sh

.PHONY: k8s-namespace
k8s-namespace:
	kubectl apply -f infra/k8s/namespace.yaml

.PHONY: k8s-secret
k8s-secret:
	kubectl create secret generic bible-env --from-env-file=.env -n $(NS) --dry-run=client -o yaml | kubectl apply -f -

.PHONY: k8s-deploy
k8s-deploy: k8s-namespace k8s-secret
	kubectl apply -f infra/k8s/mongo.yaml
	kubectl apply -f infra/k8s/redis.yaml
	kubectl apply -f infra/k8s/deployment.yaml
	kubectl apply -f infra/k8s/ingress.yaml

.PHONY: k8s-delete
k8s-delete:
	-kubectl delete -f infra/k8s/ingress.yaml
	-kubectl delete -f infra/k8s/deployment.yaml
	-kubectl delete -f infra/k8s/redis.yaml
	-kubectl delete -f infra/k8s/mongo.yaml
	-kubectl delete secret bible-env -n $(NS) || true

.PHONY: k8s-status
k8s-status:
	kubectl get all -n $(NS)
