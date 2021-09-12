#!/bin/bash


init () {
    cd /data/app
    if [[ -f .env ]]; then
      if [[ $(diff .env .env.example | wc -l) > 0 ]]; then
        echo "updating composer packages"
        php /data/composer.phar update --working-dir=/data/app 
        php /data/composer.phar install --working-dir=/data/app 
        if [[ -d /data/app/vendor ]]; then
            echo "generating app key"
            php artisan key:generate #needs .env
            touch /data/composer_has_been_updated
            run
        else 
            echo "Error installing composer packages"
            exit 1
        fi
      else 
        echo ".env not changed"
      fi
    else 
      echo ".env missing"
    fi
}

run () {
    cd /data/app
    if [[ -f .env ]]; then
      if [[ $(diff .env .env.example | wc -l) > 0 ]]; then
        if [[ -f /data/composer_has_been_updated ]]; then
            echo "php artisan config:cache"
            php artisan config:cache
            echo "php artisan migrate"
            php artisan migrate
            echo "php artisan serve"
            php artisan serve --port=8080 --host=app
        else 
            init
        fi
      else 
        echo ".env not changed"
      fi
      
    else 
      echo ".env missing"
    fi
}

if [ "$1" = 'init' ]; then
  init
elif [ "$1" = 'run' ]; then
  run
elif [ "$1" = 'composer' ]; then
  shift #shift and remove arg 'composer' 
  php /data/composer.phar --working-dir=/data/app "${@}"
elif [ "$1" = 'artisan' ]; then
  shift #shift and remove arg 'artisan' 
  cd /data/app
  php /data/app/artisan "${@}"
else
  echo "Not sure what to do; k tnx bye"
fi