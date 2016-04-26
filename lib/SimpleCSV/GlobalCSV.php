<?php namespace SimpleCSV;

class GlobalCSV
{
    protected $file;
    protected $mode;
    protected $handle;
    
    protected $delimiter    = 'comma';
    protected $enclosure    = '"';

    protected $delimiters   = array(
        'comma'             => ',',
        'semicolon'         => ';',
        'tab'               => "\t",
        'pipe'              => '|',
        'colon'             => ':'
    );

    public function __construct($file, $mode = 'r+')
    {
        $this->mode = $mode;

        if(is_resource($file))
        {
            $this->handle = $file;
            rewind($this->handle);
        }
        else
        {
            $this->file = $file;
        }
    }

    public function __destruct()
    {
        $this->close();
    }

    public function open()
    {
        if (!is_resource($this->handle))
        {
            $this->handle = fopen($this->file, $this->mode);
        }

        return $this->handle;
    }

    public function close()
    {
        if (is_resource($this->handle))
        {
            fclose($this->handle);
        }
    }

    public function delimiter($delimiter)
    {
        if (!empty($delimiter))
        {
            $this->delimiter = strtolower($delimiter);
        }
    }

    public function enclosure($enclosure)
    {
        if (!empty($enclosure))
        {
            $this->enclosure = strtolower($enclosure);
        }
    }
}