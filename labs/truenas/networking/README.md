
## Setup a bridge on Truenas scale

> NOTE:
> First log into truenas over graphical spice or vnc (on vm), or ipmi or physical keyboard-video on bare metal

- Locate a physical interface
  ```bash
  ls -l /sys/class/net/ | grep -v virtual
  ```
  ```
  ...
  lrwxrwxrwx 1 root root 0 Dec  3 13:42 enp3s0 -> ../../devices/pci0000:00/0000:00:02.1/0000:03:00.0/virtio1/net/enp3s0
  ...
  ```
 
- create bridge in truenas scale, e.g. (Use vnc or spice console instead of ssh!*)
   ```bash
   cli -c "network interface create name=br0 type=BRIDGE bridge_members=enp3s0 aliases=\"192.168.1.99/24\" "
   ```
 - commit changes
    ```bash
    cli -c "network interface commit"
    ```
- persist changes
  ```bash
  cli -c "network interface checkin"
  ```
- reboot TrueNAS-SCALE!
  
****due to the horrendous state of networking in scale, these changes must be done over direct console via spice or vnc and all network connectivity (to truenas scale) will be lost until reboot!***
https://ixsystems.atlassian.net/browse/NAS-118915
