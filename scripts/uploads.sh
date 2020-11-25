
cd /var/www
sudo mkdir uploads
sudo chmod -R 777 uploads

cd /var/www/uploads
sudo mkdir exemptions
sudo chmod -R 777 exemptions

sudo ln -s /var/www/uploads /var/www/delta/deltamarine/web/uploads
