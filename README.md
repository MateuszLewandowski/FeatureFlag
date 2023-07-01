# FeatureFlag

Standalone application for features access managing.

## Install

> git clone <br>
> app:install-json-database

## Available rules

    bool        forceGrantAccess        true
    string[]    dateThreshold           [date => '2023-06-01', timeZone => 'Europe/Warsaw']
    string[]    userEmailDomainNames    ['gmail@com', 'wp.pl']
    int[]       userIds                 [1, 100, 1001]    
    int[]       userRoles               [1, 4, 7]
    int         moduloUserId            5 

## Usage

##### Feature flag crud routes

    GET         /feature-flag/{featureFlagId}
    GET         /feature-flags
    POST        /feature-flag
    PUT         /feature-falg/{featureFlagId}
    DELETE      /feature-flag/{featureFlagId}


##### Feature flag access verifier

    GET         /access/verify/{featureFlagId}?userId={id}&userRole={role}&userEmail={email}

    Optional
    > int userId    
    > int userRole
    > string userEmail
