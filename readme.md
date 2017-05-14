Symfony Primes Command
======================

Preqrequisites
--------------
Docker native or Docker Toolbox

Running
-------

    # To create the container
    ./start.sh
    
    # To bash into the container
    docker run -v "$PWD:/var/www" -it shrwdflrst_primes /bin/bash
    
Inside the docker container:    
    
    primes/bin/console app:primes
    primes/bin/console app:primes --count=20
    
    # Execute unit tests
    primes/vendor/bin/phpunit -c phpunit.xml
    
Example
-------
 
![Example](https://github.com/ShrwdFlrst/symfony-primes/raw/master/example.gif)
    
Clean up
--------

    # Remove image
    docker rmi -f shrwdflrst_primes
    
    # Remove all unused
    docker system prune
    