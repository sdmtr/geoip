GeoIP
=====

The GeoIP PHP class provides a quick way to use the information inferred by mod_geoip2 about the current visitor based on the MaxMind geoip database.

Since we're using the free version of the database, we're limited to the following basic geoip information about the current visitor:

>`GeoIP::country_code()`: returns a two-letter country code
>
>`GeoIP::country_name()`: returns the full country name
>
>`GeoIP::continent_code()`: returns a two-letter continent code
>
>`GeoIP::addr()`: returns the IP address mod_geoip2 is analysing
>
>`GeoIP::ip()`: attempts to figure out if the user is behind a proxy, and what the original IP might be
>
>`GeoIP::is_blocked()`: determine if the current visitor is on a list of blocked countries (which you can configure to suit your needs)

For more information, see http://dev.maxmind.com/geoip/legacy/mod_geoip2/

## Installing on CentOS 5 / 6

### Enable the EPEL repository

If the EPEL repository is already enabled, you can skip this step.

#### RHEL / CentOS 5

```
# wget http://download.fedoraproject.org/pub/epel/5/i386/epel-release-5-4.noarch.rpm
# rpm -Uvh epel-release-5-4.noarch.rpm
Preparing...                ########################################### [100%]
   1:epel-release           ########################################### [100%]
```

#### RHEL / CentOS 6

```
# wget http://download.fedoraproject.org/pub/epel/6/i386/epel-release-6-7.noarch.rpm
# rpm -Uvh epel-release-6-7.noarch.rpm
Preparing...                ########################################### [100%]
   1:epel-release           ########################################### [100%]
```
   
### Install mod_geoip with yum

```
# yum install mod_geoip GeoIP GeoIP-devel GeoIP-data zlib-devel
```

### Download the latest GeoIP database

Once you've installed everything with yum the directory /usr/share/GeoIP should already contain the latest available version of GeoIP.dat, but you should update it just in case.

```
# cd /usr/share/GeoIP/
# mv GeoIP.dat GeoIP.dat.old
# wget http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz
# gunzip GeoIP.dat.gz
```

### Enable mod_geoip2 in Apache

In your apache conf file (usually located in `/etc/httpd/conf/httpd.conf`), add the following block at the end of the file:

```
<IfModule mod_geoip.c>
GeoIPEnable On
GeoIPDBFile /usr/share/GeoIP/GeoIP.dat
</IfModule>
```

### Restart apache

Usually like this:

```
# /etc/init.d/httpd restart
```

or

```
# service httpd restart
```

----

All done! You should now be able to use the GeoIP class in your PHP scripts like so:

```
<?php

  require_once '/path/to/geoip.php';
  
  print "Your country code is " . GeoIP::country_code();
  
?>
```