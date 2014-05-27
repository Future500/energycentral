energycentral
===============

To start a local virtual machine with the EnergyCentral project, run the following command in the project folder:

    vagrant up

When the box is up, Ansible will start provisioning the machine.

Once the vagrant box is started, it will be available on the following IPs:

192.168.30.49 (development)
192.168.30.50 (production simulation)

To access EnergyCentral more easily, you should add the following lines to your hosts file:

    # EnergyCentral
    192.168.30.49 energycentral.loc
    192.168.30.50 testprod.energycentral.loc

Verify that it works by opening http://energycentral.loc in your browser.
The database can be accessed on 192.168.30.49:3306.