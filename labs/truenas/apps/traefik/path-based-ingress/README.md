## Path based Ingress

Using subdirectory paths to serve multiple apps under the same domain (Without using subdomains)

> #### NOTE:
> This example will serve openspeedtest app under `https://example.com/speedtest`
> And strip the /speedtest prefix before sending the request backend app

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
    example.com
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

    Because the openspeedtest app has no idea that it is being served from `/speedtest`,
    we will need to add some additional paths in order for it to work properly.
  
    > #### Pro Tip:
    > You can find asset paths by first deploying the app without adding any, 
    > then opening it in the browser looking at the 404 Errors in the browser console.
    
    > #### WARNING:
    > You may have conficts of more than one app uses the same paths! I don't have a solution for this (yet).
    
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
    example.com
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

- Repeat with different paths for as many apps as you like
