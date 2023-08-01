# FeatureFlag

Standalone application for features access managing.

## Install

> git clone <br>
> app:install-json-database <br>
> docker-compose up <br>
> symfony server:start <br>

## Available rules

    bool        forceGrantAccess        true
    string      startsAt                2024-01-01 10:00:00
    string      endsAt                  2025-01-01 12:00:00
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
