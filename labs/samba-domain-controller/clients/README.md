> #### NOTE:
> Make sure your client installation can find your domain controller via dns. 
> The simplest way is Setting up a [Domain Override](../../pfsense#active-directory)

## Ubuntu desktop

### Getting the desktop iso
```bash
curl https://releases.ubuntu.com/22.04/ubuntu-22.04.1-desktop-amd64.iso -o ubuntu-22.04.1-desktop-amd64.iso
```
```bash
mv ubuntu-22.04.1-desktop-amd64.iso /mnt/tank/VM/ISO
```

When the installer asks **Who Are You?** Check the box for Active Directory 

- [x] Use Active Directory

Enter the details and credentials for your domain controller in the next page

## Windows Desktop
download here:
https://www.microsoft.com/software-download/windows10

```bash
scp ~/Downloads/Win10_21H2_English_x64.iso root@truenas.lab.arpa:/mnt/tank/VM/ISO
```

Join VM to domain and use to manage it via RSAT:

- right click start (window icon) > system > rename this pc (advanced)
- Install `rsat` https://www.microsoft.com/en-us/download/details.aspx?id=45520
- launch mmc
- add snapins for AD and `save as` on desktop
