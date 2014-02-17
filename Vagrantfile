VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

	config.vm.define "dev", primary: true do |config_dev|
	  config_dev.vm.box = "debian64"
	  config_dev.vm.box_url = "http://vagrantboxes.future500.nl/vagrant-debian64.box"
	  config_dev.vm.provision :shell, :path => "provisioning/provision.sh"
	  config_dev.vm.network :private_network, ip: "192.168.30.49"

	  config_dev.vm.provider :virtualbox do |vb|
		 vb.name = "energycentral_dev"
		 vb.customize ["modifyvm", :id, "--memory", "1024"]
	  end
	end

end
