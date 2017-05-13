Symfony Primes Command
======================

Preqrequisites
--------------
Docker native or Docker Toolbox

Running
-------

    # Create custom image
    docker-compose up --build
    
    # Start container with bash using this image
    docker run -v "$PWD:/var/www"  -it shrwdflrst_primes /bin/bash
    
    bin/console app:primes
    
    ./vendor/bin/phpunit -c phpunit.xml
    
    
Clean up
--------

    # Remove image
    docker rmi -f shrwdflrst_primes
    
    # Remove all unused
    docker system prune
    