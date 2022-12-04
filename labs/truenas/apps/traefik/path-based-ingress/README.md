- #### Apps>Available Applications>openspeedtest
  - #### Enable Ingress
    #### Main Ingress
    - [x] Enable ingress

    #### Configure Hosts [Add]
    ```
    example.com
    ```
    #### Configure Paths   [Add]
    #### Host
    Path *
    ```
    /speedtest
    ```
    Path Type *
    ```
    prefix
    
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
