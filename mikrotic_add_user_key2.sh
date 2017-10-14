#!/bin/bash
ansible-playbook service/mikrotik_user.yml --extra-vars "ansible_ssh_user=$USER" --limit $1

