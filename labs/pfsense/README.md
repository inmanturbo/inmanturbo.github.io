# Using pfsense for routing and local dns in a lab

## Installation

- First you must install pfsense. 
- #### You can download the installer here: [www.pfsense.org/download/](https://www.pfsense.org/download/)
  - Use the iso for installing in a VM or over ipmi virtual media
  - Use the memstick img for flashing a usb to install on hardware
  
    > Pfsense works well in a vm or on x86 hardware. On hardware you will need at least two NICs, 
    > unless you are using going to use vlans to setup a `router on a stick`. 
    > I don't have a guide for that yet, it it looks like there is one here: 
    > https://blog.spirotot.com/posts/pfsense-vlans-with-one-nic-nuc-a-tp-link-tl-sg108e/

- I recommend using default domain of `home.arpa` [rfc](https://www.rfc-editor.org/rfc/rfc8375.html)
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
  
 ## Active Directory
  
 - I use a Domain Override for Active Directory
  - Services>DNS Resolver>Domain Overrides [Add]
    - Domain, e.g: `ad-home.arpa`
    - IP Address: The ip address of your [Samba Active Directory Domain Controller](../samba-domain-controller)
  
 ## Add Packages
  
 > To avoid having to fill in these forms all the time, a Rest api can be added to pfsense
  
- Install rest api
  - Diagnostics>Command Prompt>Execute Shell Command
    ```bash
    pkg add https://github.com/jaredhendrickson13/pfsense-api/releases/latest/download/pfSense-2.6-pkg-API.txz && /etc/rc.restart_webgui
    ```
- Use Curl for host overrides, e.g
  - Add host override

    ```bash
    curl -u "admin:${PASSWORD}" -X POST http://pfsense.home.arpa/api/v1/services/unbound/host_override \
      -H 'Content-Type: application/json' \
      -d '{"domain":"example.com","host":"speedtest", "ip":"192.168.1.99"}'
    ```

  - Reload DNS resolver to apply changes

   ```bash
   curl -u "admin:${PASSWORD}" -X POST http://pfsense.home.arpa/api/v1/services/unbound/apply \
     -H 'Content-Type: application/json' \
     -d '{"async":"false"}'
   ```

