PersonaBundle
=============

Mozilla Persona Authentication for Symfony2

With this bundle you can easily setup a Mozilla Persona authentication for your Symfony2 project.

## Prerequisites

This version of the bundle requires Symfony 2.1+ and FOSUserBundle. 

## Installation
Add PersonaBundle in your composer.json:
```js
{
    "require": {
        "proxiweb/persona-bundle": "*"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update proxiweb/persona-bundle
```

Composer will install the bundle to your project's `vendor/proxiweb` directory.

## Configuration

1. Configure FOSUserBundle
2. Login By Email
3. Authentication urls configuration
4. Add Persona's assets

###2.Login by Email
Persona authenticates users with their email, so you must configure FOSUserBundle to enable login with email :

```yaml
# app/config/security.yml
security:
    providers:
        fos_userbundle:
            # replace fos_user.user_provider.username with fos_user.user_provider.username_email
            id: fos_user.user_provider.username_email
```
###3. Authentication urls configuration
Persona logs the user by perfoming an ajax request to an url which performs authentication. 
PersonaBundle achieves the authentication process started by accessing a secured url

```yaml
# app/config/security.yml
security:
    firewalls:

        person_secured:
            pattern: ^/persona/login    # the secured url which performs authentication
            persona: true
            context: primary_auth       # use the same context as the FOSUserBundle
            anonymous:    true          # so the persona & login authentication will share the same 
        main:                           # security session
            pattern: ^/
            form_login:
                provider: fos_userbundle
                csrf_provider: form.csrf_provider
            logout:       true
            anonymous:    true
            context: primary_auth

    access_control:
        - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/persona/login, role: ROLE_ADMIN }
        - { path: ^/persona/demo, role: IS_AUTHENTICATED_ANONYMOUSLY }   # optional demo page
        - { path: ^/persona/logout, role: IS_AUTHENTICATED_ANONYMOUSLY } # logout url
```
###4. Add Persona's assets

Add the script to the pages which performs authentication and pages you wants Persona to autolog.
```
<script src="https://login.persona.org/include.js"></script> 
```
After this script, include the script `persona_auth.js` provided with this bundle. You can use the stylesheet `persona-buttons.css` to style login and logout button.

PersonaBundle provides a demo page at /persona/demo. It uses assetic, so if you wants load this page, you have to add PersonaBundle to Assetic's bundles parameter.

