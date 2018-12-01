# API for convert currency

##Demo
Check the demo version on this link bellow: <br>
<a href="http://convertor.mitrevski.work/">Demo link</a>

**Third Party Api** <br>
https://exchangeratesapi.io/

**Dependency** <br>
- Docker

## Installation
- Run in your terminal
```
$ source aliases.sh
$ docker_build // building docker images and if you ahve problems with the premittions try with "sudo"
$ docker_up // run docker images
$ docker_artisan migrate:refresh --seed // to create user
```
- test user credentials
```
{
	"email": "toni@stativa.com.mk",
	"password": "toni1234",
}
```

- Documentation

<a href="http://doc.convertor.mitrevski.work">http://doc.converter.mitrevski.work</a>

