# Prime generation api

This is a small application for generating prime numbers, I used it for experimenting with [CodeIgniter 4](https://codeigniter.com).
Watch a live demo [here](https://primes.latana.rocks).

It features a development container useful for rapid environment setup as well as production container for deploying.
To build the dev environment image simply run
```bash
./scripts/build_image.sh
```
and then, one finished, run the container with
```bash
./scripts/dev_container.sh
```
This containers has exposes the application via Apache.
It has all the requirements needed to work with php and codeigniter (php, composer and xdebug for tests coverage).

The description of the routes can be found in the `assets/openapi.yml` file.

## Note:

The `/` path is redirected to `/docs` in which the documentation/swagger ui is expected to run
