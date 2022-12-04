## Ubuntu desktop
### Getting the desktop iso
```bash
curl https://releases.ubuntu.com/22.04/ubuntu-22.04.1-desktop-amd64.iso -o ubuntu-22.04.1-desktop-amd64.iso
```
```bash
mv ubuntu-22.04.1-desktop-amd64.iso /mnt/tank/VM/ISO
```
### Windows example
download here:
https://www.microsoft.com/software-download/windows10

```bash
scp ~/Downloads/Win10_21H2_English_x64.iso root@truenas.lab.arpa:/mnt/tank/VM/ISO
```

Join Vm to domain:

- right click start (window icon) > system > rename this pc (advanced)
- Install `rsat` https://www.microsoft.com/en-us/download/details.aspx?id=45520
- launch mmc
- add snapins for ad and save as on desktop
