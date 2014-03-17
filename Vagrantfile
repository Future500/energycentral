VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
	
	config.vm.define "dev", primary: true do |config_dev|
	  config_dev.vm.box = "debian64"
	  config_dev.vm.box_url = "http://vagrantboxes.future500.nl/vagrant-debian64.box"
	  config_dev.vm.provision :shell, :path => "provisioning/provision_dev.sh"
	  config_dev.vm.network :private_network, ip: "192.168.30.49"
	  config_dev.vm.synced_folder ".", "/vagrant", type: "rsync"

	  config_dev.vm.provider :virtualbox do |vb|
		 vb.name = "energycentral_dev"
		 vb.customize ["modifyvm", :id, "--memory", "768"]
	  end
	end

=begin	
		config.vm.define "prod", primary: false do |config_prod|
		  config_prod.vm.box = "debian64"
		  config_prod.vm.box_url = "http://vagrantboxes.future500.nl/vagrant-debian64.box"
		  config_prod.vm.provision :shell, :path => "provisioning/provision_prod.sh"
		  config_prod.vm.network :private_network, ip: "192.168.30.50"
		  config_prod.vm.synced_folder ".", "/vagrant", type: "rsync"

		  config_prod.vm.provider :virtualbox do |vb|
			 vb.name = "energycentral_prod"
			 vb.customize ["modifyvm", :id, "--memory", "768"]
		  end
		end
=end
	
end
