FROM dunglas/frankenphp:static-builder

ENV GOPROXY=direct

WORKDIR /go/src/app/dist/app
COPY . .

RUN composer install --optimize-autoloader --ignore-platform-req=ext-mysqli

WORKDIR /go/src/app/

RUN EMBED=dist/app/ PHP_EXTENSIONS=mysqli ./build-static.sh