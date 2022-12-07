- Create Dataset for Unix shares, i.e `tank/Shares/Unix`
- Create Dataset for docker app i.e `tank/Shares/Unix/NginxProxyManager` 
  - at time of creation, under advanced>acl select nfsv4
- Create user in in truenas scale
  - any name uid of `1000`
- Set permissions
  - Select View Permissions>Edit Permissions for `tank/Shares/Unix/NginxProxyManager`
  - set user to user created above
  - set group to user created above
- Create NFS share
  - Under advance set maproot user to `root`
  - set maproot group to `root`

- Create an ubuntu VM with a single sudo capable user
- install Docker
  ```bash
   curl -fsSL https://get.docker.com -o get-docker.sh \
   && sudo sh get-docker.sh
   ```
- install docker-compose
  ```bash
  sudo apt install -y docker-compose
  ```
- add user to docker group
  ```bash 
  sudo usermod -aG docker $USER
  ```
  ```bash
  newgrp docker
  ```
- test installation
  ```bash
  docker run hello-world
  ```
- install nfs client
  ```bash
  sudo apt install -y nfs-common
  ```
- prepare mount point
  ```bash
  sudo mkdir -p /nginx_proxy_manager
  ```
- add mount point to fstab, e.g:
  ```bash
  echo "truenas.lab.arpa:/mnt/tank/Shares/Unix/NginxProxyManager /nginx_proxy_manager nfs  rw,async,noatime,hard   0    0" \
    | sudo tee -a /etc/fstab
  ```
 - mount share
   ```bash
   sudo mount -a
   ```
 - set permissions
   ```bash
   sudo chown ${USER}:${USER} /nginx_proxy_manager
   ```
 - symlink to home (optional)
   ```bash
   mkdir -p ~/docker-compose
   ln -s /nginx_proxy_manager ${HOME}/docker-compose
   ```

