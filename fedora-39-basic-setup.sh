#!/bin/bash
dnf -y install nfs-utils bind chrony fail2ban vsftpd rsync clamav clamd
clamav-update bind-utils httpd php php-mysqlnd mariadb-server
phpmyadmin mod_ssl
sudo dnf update -y
sudo dnf install httpd -y
sudo systemctl start httpd
sudo systemctl enable httpd
sudo dnf install php php-mysqlnd php-fpm -y
sudo systemctl restart httpd
sudo dnf install mariadb-server mariadb -y
sudo systemctl start mariadb
sudo systemctl enable mariadb
sudo mysql_secure_installation <<EOF
y
rootpassword
rootpassword
y
y
y
y
EOF
cat <<EOL > /etc/httpd/conf.d/phpMyAdmin.conf
Alias /phpmyadmin /usr/share/phpMyAdmin
<Directory /usr/share/phpMyAdmin/>
AddDefaultCharset UTF-8
Require all granted
</Directory>
<Directory /usr/share/phpMyAdmin/setup/>
Require local
</Directory>
<Directory /usr/share/phpMyAdmin/libraries/>
Require all denied
</Directory>
<Directory /usr/share/phpMyAdmin/templates/>
Require all denied
</Directory>
<Directory /usr/share/phpMyAdmin/setup/lib/>
Require all denied
</Directory>
7
<Directory /usr/share/phpMyAdmin/setup/frames/>
Require all denied
</Directory>
EOL
sudo mysql -u root <<EOF
CREATE USER 'security'@'%' IDENTIFIED BY 'secuwu123*';
GRANT ALL PRIVILEGES ON *.* TO 'security'@'%';
FLUSH PRIVILEGES;
EOF
echo "exit" | mysql -u root
service mysql restart
sudo sed -i 's/Listen 80/Listen 0.0.0.0:80/' /etc/httpd/conf/httpd.conf
sudo sed -i 's/Listen 443/Listen 0.0.0.0:443/'
/etc/httpd/conf/httpd.conf
sudo firewall-cmd --zone=drop --add-service=ssh --permanent;
sudo firewall-cmd --set-default-zone=drop --permanent;
sudo firewall-cmd --reload;
sudo firewall-cmd --zone=drop --add-rich-rule='rule family="ipv4"
accept protocol value="icmp"' --permanent;
sudo firewall-cmd --add-service=http --permanent;
sudo firewall-cmd --add-service=https --permanent;
sudo firewall-cmd --add-service=mysql --permanent
firewall-cmd --reload;
sudo systemctl restart httpd