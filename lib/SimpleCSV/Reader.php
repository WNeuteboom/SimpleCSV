<?php namespace SimpleCSV;

class Reader extends GlobalCSV {

    private $headers               = array();
	private $headers_in_file       = true;
    private $user_headers_as_index = false;
    private $line                  = 0;
    private $init                  = false;

    public function __construct($path, $mode = 'r+', $headers_in_file = true, $use_headers_as_index = false)
    {
        parent::__construct($path, $mode);
        
        $this->headers_in_file = $headers_in_file;
        $this->use_headers_as_index = $use_headers_as_index;
    }

    public function headers()
    {
        $this->init();
        return $this->headers;
    }
    
    public function row()
    {
        $this->init();
        if (($row = fgetcsv($this->handle, 0, $this->delimiter, $this->enclosure)) !== false) {
            $this->line++;
            return ($this->headers && $this->use_headers_as_index) ? $this->convert_indexes($row) : $row;
        } 
        else {
            return false;
        }
    }

    public function all_to_array()
    {
        $data = array();
        while ($row = $this->row()) {
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
        foreach ($this->headers as $index => $value) {
            $new_row[$value] = isset($row[$index]) ? $row[$index] : "";
        }
        return $new_row;
    }
    
    private function init()
    {
        if (true === $this->init) {
            return;
        }
        $this->init = true;
        $this->headers = $this->headers_in_file === true ? $this->row() : false;
    }

}