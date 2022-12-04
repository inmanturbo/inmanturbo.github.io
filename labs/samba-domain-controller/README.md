# Samba domain controller

### Install cockpit for easy interface configuration

```
sudo apt install -y cockpit
```

Install NetworkManager on Ubuntu:

```bash
sudo apt-get install network-manager
```

Open the .yaml config file inside the /etc/netplan directory and replace the existing configuration with following:

```yaml
network:
  version: 2
  renderer: NetworkManager
  ```
  
Generate backend specific configuration files for NetworkManager with netplan command:

```bash
sudo netplan generate
```

Start the NetworkManager Service:

```bash
sudo systemctl unmask NetworkManager
sudo systemctl enable NetworkManager
sudo systemctl start NetworkManager
```

Now the NetworkManager is enabled, interface configurations can be done via the GUI or from the command line, using the nmcli command.

Open cockpit in web browser (`https://${server_ip}:9090`) and set up static ip:

- set the server's static ip as primary dns
- make sure to make all the proper infra configurations for your environment (eg static mappings in `pfsense`)
- set your preferred dns server as secondary
- ex:

![Screenshotdc1](https://user-images.githubusercontent.com/47095624/195656686-1381983f-0963-4414-aa1d-a12925a0db14.png)

## Install Dependecies

```bash
sudo apt install -y samba winbind krb5-config smbclient dnsutils net-tools
```

When prompted for Default Kerberos version 5 realm:

> enter in ALL CAPS the domain that will be your active directory domain, eg `HOME.ARPA`

When prompted for the Kerberos servers for your realm:

> enter in all lowercase the fqdn of your samba/ad instance, eg `dc1.home.arpa`

When prompted for the Administrative server for your Kerberos realm:

> enter in all lowercase the fqdn of your samba/ad instance, eg `dc1.home.arpa`

## Configure services

Backup `/etc/smb.conf`

```bash
sudo  mv /etc/samba/smb.conf /etc/samba/smb.conf.bak
```

Run `samba-tool domain provision --use-rfc2307 --interactive`

```bash
sudo samba-tool domain provision --use-rfc2307 --interactive
```

When prompted for Realm:

> enter in ALL CAPS the domain that will be your active directory domain, eg `HOME.ARPA`

When prompted for domain:

> Enter in ALL CAPS your domain name, without tld, eg `HOME`

When prompted for server role

> enter `dc`

When prompted for DNS backend:

> enter `SAMBA_INTERNAL`

When prompted for DNS forwarder IP address:

> enter your preferred or upstream dns server, e.g. the ip for a google or cloudflare dns server, or isp dns server, or the ip address for pfsense (if running unbound)

Next copy kerbeos config into `/etc` directory:

```bash
sudo cp /var/lib/samba/private/krb5.conf /etc
```

Disable services that will now be handled by samba active directory domain controller:

```
sudo systemctl disable --now smbd nmbd winbind systemd-resolved.service
```

Unmask the active directory service:

```bash
sudo systemctl unmask samba-ad-dc.service
```

Start and enable the active directory service:

```bash
sudo systemctl enable --now samba-ad-dc.service
```

Verify services are running:

```bash
sudo netstat -antp | egrep 'smbd|samba'
```

expected output:

<pre>tcp        0      0 0.0.0.0:445             0.0.0.0:*               LISTEN      3387/<font color="#EF2929"><b>smbd</b></font>
tcp        0      0 0.0.0.0:389             0.0.0.0:*               LISTEN      3395/<font color="#EF2929"><b>samba</b></font>: task[ld
tcp        0      0 0.0.0.0:464             0.0.0.0:*               LISTEN      3405/<font color="#EF2929"><b>samba</b></font>: task[kd
tcp        0      0 0.0.0.0:135             0.0.0.0:*               LISTEN      3392/<font color="#EF2929"><b>samba</b></font>: task[rp
tcp        0      0 0.0.0.0:139             0.0.0.0:*               LISTEN      3387/<font color="#EF2929"><b>smbd</b></font>
tcp        0      0 0.0.0.0:3269            0.0.0.0:*               LISTEN      3395/<font color="#EF2929"><b>samba</b></font>: task[ld
tcp        0      0 0.0.0.0:3268            0.0.0.0:*               LISTEN      3395/<font color="#EF2929"><b>samba</b></font>: task[ld
tcp        0      0 0.0.0.0:53              0.0.0.0:*               LISTEN      3428/<font color="#EF2929"><b>samba</b></font>: task[dn
tcp        0      0 0.0.0.0:49154           0.0.0.0:*               LISTEN      3392/<font color="#EF2929"><b>samba</b></font>: task[rp
tcp        0      0 0.0.0.0:49153           0.0.0.0:*               LISTEN      3392/<font color="#EF2929"><b>samba</b></font>: task[rp
tcp        0      0 0.0.0.0:49152           0.0.0.0:*               LISTEN      3386/<font color="#EF2929"><b>samba</b></font>: task[rp
tcp        0      0 0.0.0.0:88              0.0.0.0:*               LISTEN      3405/<font color="#EF2929"><b>samba</b></font>: task[kd
tcp        0      0 0.0.0.0:636             0.0.0.0:*               LISTEN      3395/<font color="#EF2929"><b>samba</b></font>: task[ld
tcp6       0      0 :::445                  :::*                    LISTEN      3387/<font color="#EF2929"><b>smbd</b></font>
tcp6       0      0 :::389                  :::*                    LISTEN      3395/<font color="#EF2929"><b>samba</b></font>: task[ld
tcp6       0      0 :::464                  :::*                    LISTEN      3405/<font color="#EF2929"><b>samba</b></font>: task[kd
tcp6       0      0 :::135                  :::*                    LISTEN      3392/<font color="#EF2929"><b>samba</b></font>: task[rp
tcp6       0      0 :::139                  :::*                    LISTEN      3387/<font color="#EF2929"><b>smbd</b></font>
tcp6       0      0 :::3269                 :::*                    LISTEN      3395/<font color="#EF2929"><b>samba</b></font>: task[ld
tcp6       0      0 :::3268                 :::*                    LISTEN      3395/<font color="#EF2929"><b>samba</b></font>: task[ld
tcp6       0      0 :::53                   :::*                    LISTEN      3428/<font color="#EF2929"><b>samba</b></font>: task[dn
tcp6       0      0 :::49154                :::*                    LISTEN      3392/<font color="#EF2929"><b>samba</b></font>: task[rp
tcp6       0      0 :::49153                :::*                    LISTEN      3392/<font color="#EF2929"><b>samba</b></font>: task[rp
tcp6       0      0 :::49152                :::*                    LISTEN      3386/<font color="#EF2929"><b>samba</b></font>: task[rp
tcp6       0      0 :::88                   :::*                    LISTEN      3405/<font color="#EF2929"><b>samba</b></font>: task[kd
tcp6       0      0 :::636                  :::*                    LISTEN      3395/<font color="#EF2929"><b>samba</b></font>: task[ld
</pre>
