#!/usr/bin/env bash
docker-compose down
docker rmi -f shrwdflrst_primes

docker-compose --verbose up --build
#docker run -it shrwdflrst_primes /bin/sh -c 'cd primes; composer install --prefer-dist'
#docker run -it shrwdflrst_primes /bin/sh -c 'primes/bin/console app:primes'
#docker run -it shrwdflrst_primes /bin/sh -c 'primes/bin/console app:primes --count=20'
#docker run -it shrwdflrst_primes /bin/sh -c 'primes/vendor/bin/phpunit -c primes/phpunit.xml'

docker run -v "$PWD:/var/www" -it shrwdflrst_primes /bin/sh -c 'cd primes; composer install --prefer-dist'
docker run -v "$PWD:/var/www" -it shrwdflrst_primes /bin/sh -c 'primes/bin/console app:primes'
docker run -v "$PWD:/var/www" -it shrwdflrst_primes /bin/sh -c 'primes/bin/console app:primes --count=20'
docker run -v "$PWD:/var/www" -it shrwdflrst_primes /bin/sh -c 'primes/vendor/bin/phpunit -c primes/phpunit.xml'
