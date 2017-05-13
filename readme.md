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
    
    primes/bin/console app:primes
    primes/bin/console app:primes --count=20
    
    ./primes/vendor/bin/phpunit -c phpunit.xml
    
Example
-------
 
![Example](https://github.com/ShrwdFlrst/symfony-primes/raw/master/example.gif)
    
Clean up
--------

    # Remove image
    docker rmi -f shrwdflrst_primes
    
    # Remove all unused
    docker system prune
    