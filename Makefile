IMAGE_NAME := mileschou/$(shell basename $(shell pwd))

.PHONY: test
test:
	php -d memory_limit=-1 -d xdebug.mode=off vendor/bin/pest --no-coverage

.PHONY: lint
lint:
	php -d memory_limit=-1 -d xdebug.mode=off vendor/bin/pint

.PHONY: build
build:
	docker build -t ${IMAGE_NAME} .

.PHONY: rebuild
rebuild:
	docker build --no-cache -t ${IMAGE_NAME} .

.PHONY: shell
shell:
	docker run --rm -it -v $(shell pwd):/app ${IMAGE_NAME} sh

.PHONY: run
run:
	docker run --rm -it -v $(shell pwd):/app ${IMAGE_NAME}
