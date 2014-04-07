<?php

  /**
   * Interface to mod_geoip2 
   * 
   * Provides a quick way to harness the information inferred by mod_geoip2
   * about the current visitor based on the maxmind geoip database. For more
   * information, see http://dev.maxmind.com/geoip/legacy/mod_geoip2/ and 
   * https://github.com/sdmtr/geoip
   * 
   * @author Savvas Dimitriou <savvas@sdmtr.com>
   * @copyright Public Domain 
   * @license http://creativecommons.org/publicdomain/zero/1.0/
   * 
   */
  class GeoIP {
    /**
     * See http://en.wikipedia.org/wiki/ISO_3166-1
     */
    private static $_blocked_country_code = array('NG',  # nigeria
                                                  'EG',  # egypt
                                                  'IR',  # iran
                                                  'AF'); # afghanistan

    /**
     * Returns a two-character country code for the current visitor.
     * 
     * In addition to the ISO 3166-1 country codes (available at
     * http://en.wikipedia.org/wiki/ISO_3166-1), may also return:
     * A1 – an anonymous proxy.
     * A2 – a satellite provider.
     * EU – an IP in a block used by multiple European countries.
     * AP – an IP in a block used by multiple Asia/Pacific region countries.
     * 
     * @return string
     */
    public static function country_code() {
      if (getenv('GEOIP_COUNTRY_CODE')) return getenv('GEOIP_COUNTRY_CODE');
      else return null;
    }

    /**
     * Returns the full country name for the current visitor.
     * @return type
     */
    public static function country_name() {
      if (getenv('GEOIP_COUNTRY_NAME')) return getenv('GEOIP_COUNTRY_NAME');
      else return null;
    }

    /**
     * Returns a two-character continent code for the current visitor.
     * 
     * AF – Africa
     * AS – Asia
     * EU – Europe
     * NA – North America
     * OC – Oceania
     * SA – South America
     * 
     * @return string
     */
    public static function continent_code() {
      if (getenv('GEOIP_CONTINENT_CODE')) return getenv('GEOIP_CONTINENT_CODE');
      else return null;
    }

    /**
     * Returns the IP address that mod_geoip2 is analysing, may differ from
     * REMOTE_HOST due to mod_geoip2's ability to detect proxy information.
     * @return string
     */
    public static function addr() {
      if (getenv('GEOIP_ADDR')) return getenv('GEOIP_ADDR');
      else return null;
    }

    /**
     * Checks the current country code against the list of blocked country
     * codes defined in this class file.
     * @return bool
     */
    public static function is_blocked() {
      if (getenv('GEOIP_COUNTRY_CODE'))
        return in_array(getenv('GEOIP_COUNTRY_CODE'), self::$_blocked_country_code);
      else
        return false;
    }

    /**
     * Attempts to work out if the user is behind a proxy, and what the correct
     * IP address might be.
     * @return array
     */
    public static function ip() {
      if (getenv('HTTP_X_FORWARDED_FOR')) {
        $proxy = true;
        $originalip = getenv('HTTP_X_FORWARDED_FOR');
        $proxyip = getenv('REMOTE_ADDR');
      } else {
        $proxy = false;
        $originalip = getenv('REMOTE_ADDR');
        $proxyip = null;
      }

      return array('proxy' => $proxy, 'ip' => $originalip, 'proxyip' => $proxyip);
    }
  }