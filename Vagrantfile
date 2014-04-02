VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

	config.vm.define "ec-client-dev", primary: true do |config_client_dev|
	  config_client_dev.vm.box     = "debian64"
	  config_client_dev.vm.box_url = "http://vagrantboxes.future500.nl/vagrant-debian64.box"
	  config_client_dev.vm.network :private_network, ip: "192.168.30.49"
	  config_client_dev.vm.synced_folder ".", "/vagrant", type: "rsync"

	  config_client_dev.vm.provider :virtualbox do |vb|
		 vb.name = "ec_client_dev"
		 vb.customize ["modifyvm", :id, "--memory", "768"]
	  end

      config_client_dev.vm.provision :ansible do |ansible|
         ansible.inventory_path = "ansible/hosts"
         ansible.playbook       = "ansible/provision-ecclient-dev.yml"
         ansible.limit          = "development"
      end
	end

	config.vm.define "ec-client-simulate-prod", primary: false do |config_client_simulate_prod|
	  config_client_simulate_prod.vm.box     = "debian64"
	  config_client_simulate_prod.vm.box_url = "http://vagrantboxes.future500.nl/vagrant-debian64.box"
	  config_client_simulate_prod.vm.network :private_network, ip: "192.168.30.50"

	  config_client_simulate_prod.vm.provider :virtualbox do |vb|
		 vb.name = "ec_client_simulate_prod"
		 vb.customize ["modifyvm", :id, "--memory", "768"]
	  end

      config_client_simulate_prod.vm.provision :ansible do |ansible|
         ansible.inventory_path = "ansible/hosts"
         ansible.playbook       = "ansible/provision-ecclient-simulate-prod.yml"
         ansible.limit          = "production"
      end
	end

end
