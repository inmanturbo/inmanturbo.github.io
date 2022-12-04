
### Notes on truenas labs infrastructure

These labs will mostly be based on a virtualized TrueNAS-SCALE. In my experience TrueNAS-SCALE is just better in the lab when virtualized on another, 
more flexible and mature platform. 
The reason for this is primarily because of the challenges with networking on TrueNAS-SCALE. Setting up TrueNAS-SCALE networking can be needlessly time consuming.
Especially in a lab environment where bridging is often ideal. <sup><sub>See this [issue](https://ixsystems.atlassian.net/browse/NAS-118915) for more info</sub></sup>

Typically in the labs explored here it's best that the bare metal infrastructure be as flexible and as transparant as possible so that we can focus on the software. To this end I usually prefer simply using the linux vfio module with qemu, kvm and libvirt on the bare metal host, 
but I will likely explore other virtualization platforms here as well, such as Proxmox and XCP-NG, and possibly even ESXI (using an LSI HBU).

> ### NOTE:
> Many of these labs can be applied on VMWare ESXI, however it lacks the flexibility of linux with `vfio`. 
> For instance it's not possible to passthrough to a trueNAS VM an onboard sata controller as a pcie device.
> It can be done using a discrete, dedicated pcie HBA, which IMO is just not quite as clean and elegant as simply installing
> a host OS on an embedded m.2 or nvme drive, then passing the sata controller through to TrueNAS VM.
