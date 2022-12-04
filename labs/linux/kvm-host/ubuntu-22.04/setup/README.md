# Graphical workstation kvm lab

## First install ubuntu deskop with zfs-on-root* (Optional)
- #### Get the desktop iso
  ```bash
  curl https://releases.ubuntu.com/22.04/ubuntu-22.04.1-desktop-amd64.iso -o ubuntu-22.04.1-desktop-amd64.iso
  ```
- #### flash USB using balena or `dd` (`dd` example below)
  
  > **This could destroy your system** if you flash to the wrong drive! Identify your flash drive first.
  > The hash at the start of the command here is for safety purposes if your are copy/pasting.
  > You must fist edit the command and remove the hash in order to run it!
  
  ```
  # sudo dd if=ubuntu-22.04.1-desktop-amd64.iso of=/dev/sdx bs=1024k status=progress
  ```

## Install Packages and setup shell
- #### Install packages using `apt`
  ```bash
  sudo apt install -y qemu-kvm virt-manager libvirt-daemon-system virtinst libvirt-clients \
    bridge-utils zsys cockpit cockpit-machines net-tools openssh-server \
    zsh software-properties-common curl git libnss3-tools jq xsel xrdp
  ```
  > NOTE:
  > Alternatively, you may wish to first simply install openssh-server in the graphical environment,
  > then do the rest over ssh, so that you can copy/paste.

- #### (Optionally) Allow passwordless sudo for your user
  ```bash
  echo "${USER} ALL=(ALL) NOPASSWD: ALL" |sudo tee -a /etc/sudoers
  ```
- #### (Optionally) Setup `zsh` with [oh-my-zsh](https://github.com/ohmyzsh/ohmyzsh) and [syntax highlighting](https://github.com/zsh-users/zsh-syntax-highlighting) and [autocompletion](https://github.com/marlonrichert/zsh-autocomplete)
  ```bash
  sh -c "$(curl -fsSL https://raw.githubusercontent.com/robbyrussell/oh-my-zsh/master/tools/install.sh)"
  ```
  ```bash
  git clone https://github.com/zsh-users/zsh-autosuggestions.git $ZSH_CUSTOM/plugins/zsh-autosuggestions \
    && git clone https://github.com/larkery/zsh-histdb $HOME/.oh-my-zsh/custom/plugins/zsh-histdb \
    && git clone https://github.com/zsh-users/zsh-syntax-highlighting.git $ZSH_CUSTOM/plugins/zsh-syntax-highlighting \
    && sed -i '/plugins=(git)/c\plugins=(git zsh-autosuggestions zsh-syntax-highlighting)' ~/.zshrc \
    && source ~/.zshrc
  ```
## (Optionally) Disable wayland
- #### Edit `/etc/gdm3/custom.conf`
  ```bash
  sudo nano /etc/gdm3/custom.conf
  ```
  uncomment `WaylandEnabled=false`

## Setup grub
- #### Edit `/etc/default/grub`
  ```bash
  sudo nano /etc/default/grub
  ```
  ```bash
  GRUB_CMDLINE_LINUX_DEFAULT="intel_iommu=on"
  ```
- #### Update grub
  ```bash
  sudo update-grub
  ```
## Add required Modules
- #### Edit `etc/modules`
  ```bash
  sudo nano /etc/modules
  ```
  Add the following modules:
  ```
  vfio
  vfio_iommu_type1
  vfio_pci
  vfio_virqfd
  ```

## Enable SATA Controller to be passed through to TrueNAS VM

> This requires a discrete Sata controller, and a seperate drive/controller for the host.
> The board being used in this example has a sata controller and a seperate nvme drive.
> The ubuntu host is installed on the nvme drive, on which the default libvirt pool will
> also be used to store the image for the TrueNAS VM's boot drive.

- #### First locate your SATA Controller
  ```bash
  lspci | grep SATA
  ```
  Example output:
  ```
  07:00.2 SATA controller: Advanced Micro Devices, Inc. [AMD] FCH SATA Controller [AHCI mode] (rev 51)
  ```
- #### Add your the SATA Controller to `/etc/modprobe.d/vfio.conf`
  ```bash
  echo "options vfio-pci ids=07:00.2" | sudo tee -a /etc/modprobe.d/vfio.conf
  ```
> ### NOTE:
> These steps will also work for an LSI HBA installed in a pcie slot

## Reboot System and verify changes
- #### Reboot
  ``` bash
  sudo reboot now
  ```
- #### Check for iommu
  ```bash
  sudo dmesg | grep -e DMAR -e IOMMU
  ```

## Configure the system to never poweroff or autosuspend

> If you are running your host headless you can access the graphical 
> environment using reminna over xrdp if you install `xrd` and disabled wayland

- #### Login to the desktop environment 
  - Navigate to `Settings>Power` 
  - Set `Screen Blank` to **Never**
  - Set `Automatic Suspend` to **Off** 

## Create a bridge for VM Networking

> If you want to have VMs on the regular LAN and want to allow Host/VM communication
> you will need to create a bridge interface for your VMs. One of the simplets way to 
> do this is using by using cockpit

- #### Open cockpit in web browser (https://${server_ip}:9090) 
  - First enable administrative access (I advise logging out and back in after enabling it for your user)
  - Navigate to Networking>[Add Bridge]
    #### Bridge Settings
    #### Name `br0`
    #### Ports
    - [x] eno1 <--use whichever port you want to use to connect VM's to the internet
- #### Repeat for as many seperate ports as you want to give to your VM(s)

> In the case of this example, the host will have a single TrueNAS-SCALE VM
> which will host all other VMs using nested virtualization. In this case it is a good idea
> to give the VM at least two network ports -- one for management and another for everything else

- Navigate to Networking>[Add Bridge]
  #### Bridge Settings
  #### Name `br1`
  #### Ports
  - [x] eno2 <--use whichever port you want to use to connect VM's to the internet

## Create a virtual network with the bridge

> The instructions below are to replace the default virtual network
> with a bridged network. If you wish to create a seperate bridged network
> and leave the default network as is skip steps 3-4 below, and use another
> name for the network in place of `default`.

1. #### create an xml file
  ```bash
  nano bridged-network.xml
  ```
2. #### Paste in the following:
  
   ```xml
   <network>
   <name>default</name>
   <forward mode="bridge"/>
   <bridge name="br0"/>
   </network>
   ```

3. #### Stop the default network
  ```bash
  virsh net-destroy default
  ```
4. #### Undefine the default network
  ```bash
  virsh net-undefine default
  ```
4. #### Re-define the network
  ```bash
  sudo virsh net-define bridged-network.xml
  ```
6. #### Start the network
  ```bash
  sudo virsh net-start default
  ```
7. #### set the network to autostart
  ```bash
  sudo virsh net-autostart default
  ```
> ### NOTE:
> This can also be done in virt manager gui by going to Edit>Preferences and ticking "Enable XML Editing".

![xml-editing](https://user-images.githubusercontent.com/47095624/205191891-8bcaf8ca-e80c-4325-b72e-530f1cc003b5.png)
![net-xml](https://user-images.githubusercontent.com/47095624/205192127-aad2147d-c1f4-4f22-8b44-701c223bb274.png)
