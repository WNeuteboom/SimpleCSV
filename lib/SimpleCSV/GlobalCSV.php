<?php namespace SimpleCSV;

class GlobalCSV
{
    protected $handle;
    protected $delimiter = ',';
    protected $enclosure = '"';

    public function __construct($path, $mode = 'r+')
    {
        if (!file_exists($path)) {
            touch($path);
        }
        
        $this->handle = fopen($path, $mode);
    }

    public function __destruct()
    {
        if (is_resource($this->handle)) {
            fclose($this->handle);
        }
    }

    public function delimiter($delimiter)
    {
        $this->delimiter = $delimiter;
    }

    public function enclosure($enclosure)
    {
        $this->enclosure = $enclosure;
    }
}