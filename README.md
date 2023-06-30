# FeatureFlag

Standalone application for features access managing.

## Install

> git clone <br>
> app:install-json-database

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
