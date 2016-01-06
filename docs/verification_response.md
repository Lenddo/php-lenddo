# Example Verification Response
This example response is provided in JSON for illustrative purposes.

## REST API Response
The response body from the REST API will look similar to the javascript payload below.

## Webhook "verification_complete" result payload
Inside of the **result** payload provided in the **POST**ed body of the webhook this structure will be represented as a www/urlencoded string. PHP will automatically receive this in the _$_POST_ superglobal and parse it out for you. Because of this, you will be able to access these values naturally, for example:

```php
// Name Verification Result
$_POST['result']['verifications']['name']
// First name that was provided to Lenddo (to verify against)
$_POST['result']['probes']['name'][0]
```

# Example Verification Payload (presented in JSON)
```javascript
{
    // unix timestamp when this verification was last updated
    "updated": 0,
    // unix timestamp when this verification was created
    "updated": 0,
    // Array of strings indicating reasons for the verifications
    "flags": [
        "EM03",
        "NM02"
    ],
    // The verification results for each category. 1 == Verified
    "verifications" : {
        "name": 1,
        "university": 0,
        "employer": 1,
        "facebook_verified": 1,
        "birthday": 1,
        "top_employer": 0
    },
    // The client ID you provided to retrieve this result.
    "client_id": "string",
    // Probes are the values provided by you initially to us to verify against.
    "probes": {
        "name": [
            "first",
            "middle",
            "last"
        ],
        "university": {
            "university": "university_name"
        },
        "employer": {
            "employer": "employer_name"
        },
        "facebook_verified": [
            "verified_facebook_id"
        ],
        "birthday": [
            1900, // year
            12, // month
            31 // day
        ],
        "top_employer": "top_employer_name"
    }
}
```
