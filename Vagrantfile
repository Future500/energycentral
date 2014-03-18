VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
	
	config.vm.define "dev", primary: true do |config_dev|
	  config_dev.vm.box     = "debian64"
	  config_dev.vm.box_url = "http://vagrantboxes.future500.nl/vagrant-debian64.box"
	  config_dev.vm.network :private_network, ip: "192.168.30.49"
	  config_dev.vm.synced_folder ".", "/vagrant", type: "rsync"

	  config_dev.vm.provider :virtualbox do |vb|
		 vb.name = "energycentral_dev"
		 vb.customize ["modifyvm", :id, "--memory", "768"]
	  end

      config_dev.vm.provision :ansible do |ansible|
         ansible.inventory_path = "ansible/hosts"
         ansible.playbook       = "ansible/provision_dev.yml"
         ansible.limit          = "dev"
      end
	end

	config.vm.define "prod_client", primary: true do |config_prod_client|
	  config_prod_client.vm.box     = "debian64"
	  config_prod_client.vm.box_url = "http://vagrantboxes.future500.nl/vagrant-debian64.box"
	  config_prod_client.vm.network :private_network, ip: "192.168.30.50"
	  config_prod_client.vm.synced_folder ".", "/vagrant", type: "rsync"

	  config_prod_client.vm.provider :virtualbox do |vb|
		 vb.name = "energycentral_prod_client"
		 vb.customize ["modifyvm", :id, "--memory", "768"]
	  end

      config_prod_client.vm.provision :ansible do |ansible|
         ansible.inventory_path = "ansible/hosts"
         ansible.playbook       = "ansible/provision_prod_client.yml"
         ansible.limit          = "prod"
         ansible.verbose        = "vvvv"
      end
	end

end
