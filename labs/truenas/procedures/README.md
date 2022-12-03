## Deploying apps as subdomains with ingress and local DNS

> #### NOTE:
> - This example will use 192.168.1.99 for truenas static ip and example.com for parent domain as placeholders
> - This example will use openspeedtest as an example app

- Assign static ip to trueNAS-SCALE system
  - [Docs](https://www.truenas.com/docs/scale/scaletutorials/network/interfaces/settingupstaticips/)
- Add static mapping in dhcp server for trueNAS-SCALE system [pfsense example](../../pfsense#add-host-overrides-and-static-mappings)

- Install and setup traefik and letsencrypt

- Deploy The app 
  - Apps>Available Applications>openspeedtest>install
    - Enable Ingress
      #### 5. Ingress
      #### Main Ingress
      - [x] Enable Ingress
      #### Configure Hosts [Add]
      #### Host
      #### HostName*
      ```
      speedtest.example.com
      ```
      #### Configure Paths [Add]
      #### Host
      #### Path*
      ```
      /
      ```
      #### Path Type *
      ```
      prefix
      ```
    - Configure TLS (optional)
      > #### NOTE:
      > You should probably only do this if you have already set up letsencrypt!
      
      #### Configure TLS-Settings   [Add]
      #### Host
      #### Configure Certificate Hosts [Add]
      #### Host*
      ```
      speedtest.example.com
      ```
      #### Select TrueNAS-SCALE Certificate
      ```
      example_com_cert   ˅
      ```
      
- Add local DNS Entry for The app's subdomain to resolve trueNAS-SCALE system using one of the following options:
  - Entry in host file
     - On Mac or Linux
       ```bash
       echo "192.168.1.99 speedtest.example.com" | sudo tee -a /etc/hosts
       ```
     - On Windows
       - Open cmd terminal as Administrator
         - In taskbar search box type `cmd` then press `CTL+SHIFT+ENTER`
         - From Administrator cmd line open hosts file in notepad with elevated priviledges 
           ```cmd
            notepad c:\Windows\System32\Drivers\etc\hosts
           ```
         - Paste the following entry in an otherwise empty line at the end of the file:
           ```
           192.168.1.99 speedtest.example.com
           ```
  - Using Pfsense
    > See [Host Overrides](../../pfsense#add-host-overrides-and-static-mappings)
    > And [Pfsense API](../../pfsense#add-packages)
    ```
    curl -u "admin:${PASSWORD}" -X POST http://pfsense.home.arpa/api/v1/services/unbound/host_override \
      -H 'Content-Type: application/json' \
      -d '{"domain":"example.com","host":"speedtest", "ip":"192.168.1.99"}'
    ```
