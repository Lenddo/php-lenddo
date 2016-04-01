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
    "partner_script_id": "{{YOUR_PARTNER_SCRIPT_ID}}",
    // Unix timestamps when the verification was created/updated
    "updated": 1451972986,
    "created": 1451972986,
    // A facebook profile image
    "facebook_photo_url": "https://graph.facebook.com/{{FB_ID}}/picture?type=large",
    "verified_by_facebook": true,
    // Flags are an array of strings indicating the reasoning behind the verification results.
    "flags": [
        "EM02",
        "NM01"
    ],
    "verifications": {
        // Verification results for each category.
        // True = Verified
        // False = Not Verified
        // Null = No probe to compare against
        "name": true,
        "university": false,
        "employer": null,
        "facebook_verified": true,
        "birthday": true,
        "top_employer": null,
        "phone": true
    },
    // The Client/Application ID you provided to return this result.
    "client_id": "{{YOUR_CLIENT/APPLICATION_ID}}",
    // Probes are the values you initially provded to us to verify for the user.
    "probes": {
        "name": [
            "{{FIRST_NAME}}",
            "{{MIDDLE_NAME}}",
            "{{LAST_NAME}}"
        ],
        "university": {
            "university": "{{UNIVERSITY_NAME}}"
        },
        "employer": {
            "employer": "{{EMPLOYER_NAME}"
        },
        "facebook_verified": [
            "{{VERIFIED_FACEBOOK_ID}}"
        ],
        "birthday": [
            1988, // year
            5, // month
            4 // day
        ],
        "top_employer": null,
        // The phone number is the number that the user asked us to verify.
        "phone": "{{USER_ENTER_PHONE_NUMBER}}"
    }
}
```
