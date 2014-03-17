#!/usr/bin/env bash

# Install ansible
sudo apt-get update
sudo apt-get install -y python-pip python-dev git libmysqlclient-dev
sudo pip install PyYAML MySQL-python jinja2 paramiko
git clone https://github.com/ansible/ansible.git
cd ansible
sudo make install
sudo mkdir /etc/ansible

# Copy ansible hosts
sudo cp /vagrant/provisioning/ansible/hosts /etc/ansible
sudo chmod 644 /etc/ansible/hosts

# Run playbook
ansible-playbook /vagrant/provisioning/ansible/provision_dev.yml