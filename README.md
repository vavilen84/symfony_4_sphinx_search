# Symfony 4.* + Sphinxsearch 3.11. Application includes:
- Docker (php + nginx + postgres + adminer + sphinxsearch)
- Codeception 
- XDebug
- [Implementation docs](https://github.com/vavilen84/symfony_4_sphinx_search/tree/master/docs)

##  Install Docker 

https://docs.docker.com/install/

### Installation on Ubuntu

https://docs.docker.com/install/linux/docker-ce/ubuntu/

```
sudo apt-get install \
    apt-transport-https \
    ca-certificates \
    curl \
    software-properties-common
```

```
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
```

```
sudo add-apt-repository \
       "deb [arch=amd64] https://download.docker.com/linux/ubuntu \
       $(lsb_release -cs) \
       stable"
```

```
sudo apt-get update
```

```
sudo apt-get -y install docker-ce
```

## Create the docker group

```
sudo groupadd docker
sudo usermod -aG docker $USER
```

##  Install docker-compose 

https://docs.docker.com/compose/install/

### Installation on Ubuntu

```
sudo curl -L https://github.com/docker/compose/releases/download/1.19.0/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose
```

```
sudo chmod +x /usr/local/bin/docker-compose
```

##  Install docker-hostmanager

https://github.com/iamluc/docker-hostmanager

### Installation on Ubuntu

https://github.com/iamluc/docker-hostmanager#linux

```
docker run -d --name docker-hostmanager --restart=always -v /var/run/docker.sock:/var/run/docker.sock -v /etc/hosts:/hosts iamluc/docker-hostmanager
```

##  Add ENV file

create .env file from .env.dist file and set correct vars values in it


##  Start with Docker

```
docker-compose up -d --build
```

##  run commands after setup


install composer libs
```
docker exec -it --user 1000 symfony4sphinxsearch_php_1 composer install
```

run migrations
```
docker exec -it --user 1000 symfony4sphinxsearch_php_1 bin/console doctrine:migrations:migrate
```

where symfony4sphinxsearch_php_1 php container name

## XDEBUG
set alias 10.254.254.254 to 127.0.0.1 network interface for XDEBUG
```
$ sudo ifconfig lo:0 10.254.254.254 up
```

## URLs:
"http://site.symfony4sphinxsearch_local/" - website<br>
"http://adminer.symfony4sphinxsearch_local:8080/" - adminer

## Codeception
 
create db schema (if not created yet)
```
$ docker exec -it --user 1000 symfony4sphinxsearch_php_1 bin/console doctrine:schema:create 
```

upload fixtures
```
$ docker exec -it --user 1000 symfony4sphinxsearch_php_1 bin/console doctrine:fixtures:load 
```

goto container
```
$ docker exec -it --user 1000 symfony4sphinxsearch_php_1 bash
$ cd codeception
```
run all tests
```
$ php ../vendor/bin/codecept run tests
```

run all tests under folder
```
$ php ../vendor/bin/codecept run tests/Functional
```

run one test in debug mode
```
$ php ../vendor/bin/codecept run tests/Functional/BaseFunctionalCest.php --debug
```

build tester classes
```
$ php ../vendor/bin/codecept build
```
