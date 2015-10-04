```json
{
    # unix timestamp when this verification was last updated
    "updated": 0,
    # unix timestamp when this verification was created
    "updated": 0,
    # Array of strings indicating reasons for the verifications
    "flags": [
        "EM03",
        "NM02"
    ],
    # The verification results for each category. 1 == Verified
    "verifications" : {
        "name": 1,
        "university": 0,
        "employer": 1,
        "facebook_verified": 1,
        "birthday": 1,
        "top_employer": 0
    },
    # The client ID you provided to retrieve this result.
    "client_id": "string",
    # Probes are the values provided by you initially to us to verify against.
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