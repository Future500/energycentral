VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "debian_7_0"
  config.vm.provision :shell, :path => "provisioning/provision.sh"
  config.vm.box_url = ""
  config.vm.network :private_network, ip: "192.168.30.49"

  config.vm.provider :virtualbox do |vb|
     vb.name = "energycentral_dev"
     vb.customize ["modifyvm", :id, "--memory", "1024"]
  end
end
