## Path Based Ingress

Using subdirectory paths to serve multiple apps under the same (sub)domain

> #### NOTE:
> This example will serve openspeedtest app under `https://apps.example.com/speedtest`
> And strip the `/speedtest` prefix before sending the request backend app

- #### Edit Traefik and define a stripprefix middleware fo the app
  #### Configure stripPrefixRegex [Add]
  #### Name*
  ```
  speedtest-stripprefix
  ```
  #### Configure Regex [Add]
  #### Regex*
  ```
  /speedtest
  ```
    
- #### Apps>Available Applications>openspeedtest
  - #### Enable Ingress
    #### Main Ingress
    - [x] Enable ingress

    #### Configure Hosts [Add]
    ```
    apps.example.com
    ```
  - #### Add path for subdirectory
    #### Configure Paths   [Add]
    #### Host
    Path *
    ```
    /speedtest
    ```
    Path Type *
    ```
    prefix
    ```
  - #### Add Path(s) for assets and other routes

    Because the openspeedtest app has no idea that it is being served from `https://apps.example.com/speedtest` instead of `https://apps.example.com/`,
    we will need to add some additional paths in order for it to work properly.
  
    > #### Pro Tip:
    > You can find asset paths by first deploying the app without adding any, 
    > then opening it in the browser looking at the 404 Errors in the browser console.
    
    > #### WARNING:
    > You may have conficts if more than one app uses the same paths! I don't have a solution for this (yet).
    
    #### Host
    Path *
    ```
    /assets
    ```
    Path Type *
    ```
    prefix
    ```
    
    #### Host
    Path *
    ```
    /upload
    ```
    Path Type *
    ```
    prefix
    ```
    
    #### Host
    Path *
    ```
    /download
    ```
    Path Type *
    ```
    prefix
    ```
    
  - #### Add host to tls settings
    #### Configure TLS-Settings   [Add]
    #### Host
    #### Configure Certificate Hosts [Add]
    #### Host*
    ```
    apps.example.com
    ```
    #### Select TrueNAS-SCALE Certificate
    ```
    example_com_cert   ˅
    ```
   - Add the stripprefix middleware
     #### Configure Traefik Middlewares [Add]
     #### Name*
     ```
     speedtest-stripprefix
     ```

- Repeat with different paths for as many apps as you like (librespeed example below)

> #### NOTE:
> This example will serve librespeed app under `https://apps.example.com/librespeed`
> And strip the `/librespeed` prefix before sending the request backend app

- #### Edit Traefik and define a stripprefix middleware fo the app
  #### Configure stripPrefixRegex [Add]
  #### Name*
  ```
  librespeed-stripprefix
  ```
  #### Configure Regex [Add]
  #### Regex*
  ```
  /librespeed
  ```
    
- #### Apps>Available Applications>librespeed
  - #### Enable Ingress
    #### Main Ingress
    - [x] Enable ingress

    #### Configure Hosts [Add]
    ```
    apps.example.com
    ```
  - #### Add path for subdirectory
    #### Configure Paths   [Add]
    #### Host
    Path *
    ```
    /librespeed
    ```
    Path Type *
    ```
    prefix
    ```
  - #### Add Path(s) for assets and other routes

    Because the librespeed app has no idea that it is being served from `https://apps.example.com/speedtest` instead of `https://apps.example.com/`,
    we will need to add some additional paths in order for it to work properly.
  
    > #### Pro Tip:
    > If you know a bit of code, you can try searching the app's repo for all the paths it uses.
    
 
    #### Host
    Path *
    ```
    /results/
    ```
    Path Type *
    ```
    prefix
    ```
    
    #### Host
    Path *
    ```
    /backend/
    ```
    Path Type *
    ```
    prefix
    ```
    
    #### Host
    Path *
    ```
    /speedtest_worker.js
    ```
    Path Type *
    ```
    prefix
    ```
    
    #### Host
    Path *
    ```
    /speedtest.js
    ```
    Path Type *
    ```
    prefix
    ```
  
  - #### Add host to tls settings
    #### Configure TLS-Settings   [Add]
    #### Host
    #### Configure Certificate Hosts [Add]
    #### Host*
    ```
    apps.example.com
    ```
    #### Select TrueNAS-SCALE Certificate
    ```
    example_com_cert   ˅
    ```
   - Add the stripprefix middleware
     #### Configure Traefik Middlewares [Add]
     #### Name*
     ```
     librespeed-stripprefix
     ```
