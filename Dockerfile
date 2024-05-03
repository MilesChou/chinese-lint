ARG APP_VERSION=dev-master
FROM ghcr.io/mileschou/composer:8.3 AS build

WORKDIR /source

COPY composer.* .
RUN composer install

COPY . .
RUN php tclint app:build --build-version=${APP_VERSION} tclint

FROM php:8.3-alpine

LABEL org.opencontainers.image.source="https://github.com/MilesChou/traditional-chinese-lint" \
    repository="https://github.com/MilesChou/traditional-chinese-lint" \
    maintainer="MilesChou <jangconan@gmail.com>, Ban <jim515jim@gmail.com>, Gson Liang <yuanyu90221@gmail.com>"

COPY --from=build /source/builds/tclint /usr/local/bin/tclint

ENTRYPOINT ["tclint", "lint"]
