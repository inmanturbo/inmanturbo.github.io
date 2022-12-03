## Deploying TrueNAS-SCALE VM to KVM Host

> #### NOTE:
> Requires a linux host running libvirt with qemu-kvm! For instruction on setting one up, you can follow [this guide](../../../linux/kvm-host/ubuntu-22.04/setup)


- #### Download the iso from [truenas.com/download-truenas-scale](https://www.truenas.com/download-truenas-scale/)
  ```bash
  curl https://download.truenas.com/TrueNAS-SCALE-Angelfish/22.02.4/TrueNAS-SCALE-22.02.4.iso -o TrueNAS-SCALE-22.02.4.iso
  ```
- Add the iso to the default storage pool
  ```bash
  sudo cp TrueNAS-SCALE-22.02.4.iso /var/lib/libvirt/images
  ```
- Use latest debian flavor
- `20G` disk is more than enough
- Give it at least one bridged network device
- Set cpu model to `host-passthrough`
- At least 4 vcpus
- At least 8G RAM
- Attach a SATA Controller or LSI HBA (Instructions for setting up vfio can be found [here](../../../linux/kvm-host/ubuntu-22.04/setup/README.md#setup-grub)
![pci-device](https://user-images.githubusercontent.com/47095624/205194531-9c5f0229-b776-4816-a538-6094f9f2e153.png)
- Complete install via graphical spice or vnc
