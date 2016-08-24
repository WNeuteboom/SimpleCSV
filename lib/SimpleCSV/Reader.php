<?php namespace SimpleCSV;

class Reader extends GlobalCSV {

    protected $headers          = array();
    protected $headers_in_file  = true;
    protected $headers_as_index = false;
    
    private $line               = 0;
    private $init               = false;

    public function __construct($file, $mode = 'r+', $headers_in_file = true, $headers_as_index = false)
    {
        parent::__construct($file, $mode);
        
        $this->headers_in_file  = (bool)$headers_in_file;
        $this->headers_as_index = (bool)$headers_as_index;
    }

    public function headers()
    {
        $this->init();

        return $this->headers;
    }
    
    public function row()
    {
        $this->init();

        if (($row = fgetcsv($this->handle, 0, (string)$this->delimiters[$this->delimiter], (string)$this->enclosure)) !== false)
        {
            $this->line++;

            return ($this->headers && $this->headers_as_index) ? $this->convert_indexes($row) : $row;
        } 
        else
        {
            return false;
        }
    }

    public function all_to_array()
    {
        $data = array();

        while ($row = $this->row())
        {
            $data[] = $row;
        }
        
        return $data;
    }

    public function current_line_number()
    {
        return $this->line;
    }

    private function convert_indexes($row) 
    {
        $new_row = array();

        foreach ($this->headers as $index => $value)
        {
            $new_row[$value] = isset($row[$index]) ? $row[$index] : "";
        }

        return $new_row;
    }
    
    private function init()
    {
        if (true === $this->init)
        {
            return;
        }

        $this->init = true;
        
        // Open the file
        $this->open();

        // Fetch the headers
        if($this->headers_in_file === true)
        {
            $this->headers = $this->row();
        } 
        else
        {
            $this->headers = array_keys($this->row());
            rewind($this->handle);
        }
    }

}