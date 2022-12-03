## Traefik with Letsencrypt
- #### Setup letsencrypt on TrueNAS-SCALE
  - ##### add email to root account
    - credentials>local users>root>edit
  - ##### setup acme dns authenticator
    - credentials>certificates>acme dns authenticators>add
    - name: cloudflare
    - authenticator: cloudflare
    - email: blank
    - ##### token: token from cloudflare
      - cloudflare>login>account>profile>api tokens to left>create token>create custom token
      - name: eg TrueNAS-SCALE
      - permissions: 
        - `Zone` `Zone` `Read`
        - `Zone` `DNS` `Edit`   

### Add csr
- ##### credentials>certificates>Certificate Signing Requests>add
  - name: eg example_com_csr
  - fill in required fields (use something like homelab for organization)
  - Subject Alternate Names: `*.example.com`
  - Save
  - ##### Create Acme Certificate
    - credentials>certificates>Certificate Signing Requests>example_com_csr>click the wrench
      - identifier: example_com_cert
      - ACME Server Directory URI: Production
      - Authenticator: Cloudflare

## Setup Traefik on TrueNAS-SCALE
- ##### Change Ports for truenas web interface to 83 and 444
  - ##### system settings>general>GUI>settings
    - Web Interface HTTP Port: `83`
    - Web Interface HTTPS Port: `444`
- ##### Install traefik
  - Apps>Available Applications>traefik>install
    - #### web Entrypoint Configuration
      Entrypoints Port *
      ```
      80
      ```
    - #### websecure Entrypoint Configuration
      Entrypoints Port *
      ```
      443
      ```
