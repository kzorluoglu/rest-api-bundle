# Swagger\Client\DefaultApi

All URIs are relative to *https://demo.chameleon-system.de/*

Method | HTTP request | Description
------------- | ------------- | -------------
[**kzorluogluRestApiBundleControllerLoginControllerLogin**](DefaultApi.md#kzorluogluRestApiBundlecontrollerlogincontrollerlogin) | **POST** /api/login | Login as user
[**kzorluogluRestApiBundleControllerProductProductControllerIndex**](DefaultApi.md#kzorluogluRestApiBundlecontrollerproductproductcontrollerindex) | **POST** /api/product | Product List

# **kzorluogluRestApiBundleControllerLoginControllerLogin**
> \Swagger\Client\Model\InlineResponse200 kzorluogluRestApiBundleControllerLoginControllerLogin($body)

Login as user

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new Swagger\Client\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$body = new \Swagger\Client\Model\Body(); // \Swagger\Client\Model\Body | 

try {
    $result = $apiInstance->kzorluogluRestApiBundleControllerLoginControllerLogin($body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->kzorluogluRestApiBundleControllerLoginControllerLogin: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\Swagger\Client\Model\Body**](../Model/Body.md)|  | [required]

### Return type

[**\Swagger\Client\Model\InlineResponse200**](../Model/InlineResponse200.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **kzorluogluRestApiBundleControllerProductProductControllerIndex**
> \Swagger\Client\Model\InlineResponse2001 kzorluogluRestApiBundleControllerProductProductControllerIndex($body)

Product List

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new Swagger\Client\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$body = new \Swagger\Client\Model\Body1(); // \Swagger\Client\Model\Body1 | 

try {
    $result = $apiInstance->kzorluogluRestApiBundleControllerProductProductControllerIndex($body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->kzorluogluRestApiBundleControllerProductProductControllerIndex: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\Swagger\Client\Model\Body1**](../Model/Body1.md)|  | [optional]

### Return type

[**\Swagger\Client\Model\InlineResponse2001**](../Model/InlineResponse2001.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

