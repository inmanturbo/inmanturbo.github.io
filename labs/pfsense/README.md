# Using pfsense for routing and local dns in a lab

## Installation

- First you must install pfsense. 
- #### You download here: https://www.pfsense.org/download/
  - Use the iso for installing in a VM or over ipmi virtual media
  - Use the memstick img for flashing a usb to install on hardware
- I recommend using default domain of `home.arpa` https://www.rfc-editor.org/rfc/rfc8375.html
  - Another reasonable option is to use something like `homelab.local`
  - You can also use the name of a domain that you own, but I often use both and you will often run into scenarios in the lab where you need [split dns](https://docs.netgate.com/pfsense/en/latest/nat/reflection.html#split-dns). 
  > I would not recommend using simply `.local` as it's overused by iot and other devices
 - Set your hostname under `System>General Setup`
 
## Setting up local dns
 
- Go to Services>DNS Resolver and check these options:
  - [x] Register DHCP leases in the DNS Resolver
  - [x] Register DHCP static mappings in the DNS Resolver
 
## Add custom options

- Go to Services>DNS Resolver>Click [Display Custom Options]
  - To avoid errors when adding other custom options first add this at the top:
    ```
    server:qname-minimisation: yes
    ```
- #### example for adding MX records for local test email server (optional)
  > MX records first need an A record. This can be done using `Host Overrides`
  ```
  local-data: "mail.example.com. IN MX 10 mail.example.com."
  ```
 
 ## Add Host overrides (And static mappings)
 
 > Often you will need to add static mappings and host overrides together
 
 - Add static mapping
   - Services>DHCP Server>DHCP Static Mappings for this Interface (All the way at the bottom)>Add
     - enter the mac address for your host
     - enter an ip address outside of your dhcp range
     - enter a hostname, e.g. `truenas`
     - check ARP Table Static Entry
       #### ARP Table Static Entry
       - [x] Create an ARP Table Static Entry for this MAC & IP Address pair
 - Add Host Override
   - Go to Services>DNS Resolver>Host Overrides>Add
     - Enter Hostname, e.g. `truenas`
     - Enter Domain, e.g. `home.arpa`
  
  ## Add Packages
  
  > To avoid having to fill in these forms all the time, a Rest api can be added to pfsense
  
  - Install rest api
    - Diagnostics>Command Prompt>Execute Shell Command
      ```bash
      pkg add https://github.com/jaredhendrickson13/pfsense-api/releases/latest/download/pfSense-2.6-pkg-API.txz && /etc/rc.restart_webgui
      ```
  - Use Curl for host overrides, e.g
    ```curl
    curl -u "admin:${PASSWORD}" -X POST http://pfsense.home.arpa/api/v1/services/unbound/host_override \
      -H 'Content-Type: application/json' \
      -d '{"domain":"home.arpa","host":"truenas", "ip":"192.168.1.99"}'
    ```
    