## Setup Traefik on TrueNAS-SCALE
- Setup letsencrypt with cloudflare for https (Optional)
  - [Guide](https)
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
