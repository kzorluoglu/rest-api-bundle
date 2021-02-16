# REST API Bundle (WIP)

This bundle provide REST API interface for Chameleon Shop. All Business-Features (not yet) be possible.

Actually Completed Features:
* Login
* Basic Product List with sorting, offset and limit 

## installation

* Install the Bundle via Composer
```
 composer.sh require kzorluoglu/rest-api-bundle:dev-master
```

* Add the Bundle in app/AppKernel.php
```
new \kzorluoglu\RestApiBundle\kzorluogluRestApiBundle(),
```

* Add the AnnotationReader::addGlobalIgnoredNamespace("OA") in app/autoload.php

```
$loader = require __DIR__.'/../vendor/autoload.php';

// OA (open AI) Annotations adding Doctorine Ingored Namespaces List
\Doctrine\Common\Annotations\AnnotationReader::addGlobalIgnoredNamespace("OA");
```

* Add the API Route in app/config/routing.yml
```
api:
    resource: "@kzorluogluRestApiBundle/Resources/config/routing.yml"
    type:     yaml
    prefix:   /api
```

## Documentation for API Endpoints

All URIs are relative to *https://demo.chameleon-system.de/*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*DefaultApi* | [**kzorluogluRestApiBundleControllerLoginControllerLogin**](docs/Api/DefaultApi.md#kzorluogluRestApiBundlecontrollerlogincontrollerlogin) | **POST** /api/login | Login as user
*DefaultApi* | [**kzorluogluRestApiBundleControllerProductProductControllerIndex**](docs/Api/DefaultApi.md#kzorluogluRestApiBundlecontrollerproductproductcontrollerindex) | **POST** /api/product | Product List

## Documentation For Models

- [LoginBody](docs/Model/LoginBody.md)
- [Product Body](docs/Model/ProductBody.md)
- [Login Response (Status Code: 200)](docs/Model/LoginResponse200.md)
- [Product Response (Status Code: 200)](docs/Model/ProductResponse200.md)


# Tests

## Prepare
```bash
cd /vendor/kzorluoglu/rest-api-bundle
composer install
```
and Run Tests
```bash

./vendor/bin/phpunit -c phpunit.xml
```
# Development

### Create a new openapi yaml after changes
```
./vendor/bin/openapi -o api.yml  /your/root/path/vendor/kzorluoglu/rest-api-bundle/Controller
```
Example:
```bash
./vendor/bin/openapi -o api.yml  /usr/local/apache2/htdocs/customer/vendor/kzorluoglu/rest-api-bundle/Controller
```
1. Check if api.yml exists
```
../customer
..../app
..../web
..../api.yml
```

2. For Preview; copy the contents of the api.yml file and paste it on https://editor.swagger.io

### Documentation generating

### Preview

1. copy the contents of the api.yml file
2. paste it on https://editor.swagger.io

#### Alternative 1
1. Copy the contents of the api.yml file and paste it on https://editor.swagger.io
2. Click *Generate Client*
3. Choose one of the Client Software Language
4. extract the generated zip file
5. copy the "## Documentation for API Endpoints" Section from readme.md in your readme.md 
6. copy the docs folder from zip to this bundle

#### Alternative 2
1. Download https://github.com/swagger-api/swagger-ui - folder of interest is "dist"
2. Copy your api YAML into the dist folder
3. Open index.html and change the value of URL inside the tag at the bottom of the file to ./swagger.json (or whatever your swagger json is called)
4. Host online! (or start a local server to view output).
