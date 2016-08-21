<?php

namespace App\Geolite;

class Import
{
    use Network;

    const ROWS_AT_ONCE = 1000;

    /**
     * @var string
     */
    private $network;

    /**
     * @var int|string
     */
    private $geoname_id;

    /**
     * @var array
     */
    private $db_rows = [];

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @param string $csv_file
     *
     * @return $this
     */
    public function ranges($csv_file = 'GeoLite2-City-Blocks-IPv4.csv')
    {
        $keys = [];
        foreach ($this->csv_lines($csv_file) as $lineNo => $line) {
            $values = str_getcsv($line);

            if ($lineNo == 0) {
                $keys = $values;
                continue;
            }

            $row = array_combine($keys, $values);

            // ignore the ones that don't have an id
            if (!trim($row['geoname_id'])) {
                continue;
            }

            $this->setGeoNameId($row['geoname_id']);

            $this->setNetwork($row['network']);

            $this->addDbRow();

            $this->saveIpRanges();
        }

        $this->saveIpRanges(true);

        return $this;
    }

    /**
     * @param string $csv_file
     *
     * @return $this
     */
    public function locations($csv_file = 'GeoLite2-City-Locations-en.csv')
    {
        $keys = [];
        foreach ($this->csv_lines($csv_file) as $lineNo => $line) {
            $values = str_getcsv($line);

            if ($lineNo == 0) {
                $keys = $values;
                continue;
            }

            $row = array_combine($keys, $values);

            $this->db_rows[] = [
                'geoname_id' => $row['geoname_id'],
                'country' => $row['country_name'],
                'state' => $row['subdivision_1_name'],
                'city' => $row['city_name'],
            ];

            $this->saveIpLocations();
        }

        $this->saveIpLocations(true);

        return $this;
    }

    /**
     * @param string $csv_file
     *
     * @return mixed
     */
    private function csv_lines($csv_file)
    {
        $file_path = (storage_path().'/geolite/'.$csv_file);
        if (!file_exists($file_path)) {
            echo 'File: '.$file_path.' does not exist';

            exit;
        }

        return file($file_path);
    }

    /**
     * @param bool $force
     *
     * @return $this
     */
    private function saveIpLocations($force = false)
    {
        $total_rows = count($this->db_rows);
        if ($total_rows && ($force || $total_rows == static::ROWS_AT_ONCE)) {
            Location::insert($this->db_rows);
            $this->db_rows = [];
        }

        return $this;
    }

    /**
     * @param bool $force
     *
     * @return $this
     */
    private function saveIpRanges($force = false)
    {
        $total_rows = count($this->db_rows);
        if ($total_rows && ($force || $total_rows == static::ROWS_AT_ONCE)) {
            Range::insert($this->db_rows);
            $this->db_rows = [];
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function addDbRow()
    {
        $range = self::cidrToIpRange($this->network);

        $this->db_rows[] = [
            'geoname_id' => $this->geoname_id,
            'ip_network' => $this->network,
            'ip_start' => self::ipAton($range['start']),
            'ip_end' => self::ipAton($range['end']),
        ];

        return $this;
    }

    /**
     * @param string $network
     *
     * @return $this
     */
    private function setNetwork($network)
    {
        if (stripos($network, '/') == false) {
            exit($network." does not have a '/'");
        }

        $this->network = $network;

        return $this;
    }

    /**
     * @param int|string $geoname_id
     *
     * @return $this
     */
    private function setGeoNameId($geoname_id)
    {
        $this->geoname_id = $geoname_id;

        return $this;
    }
}
