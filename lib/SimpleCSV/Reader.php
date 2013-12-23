<?php namespace SimpleCSV;

class Reader extends GlobalCSV {

    private $headers;
	private $headers_in_file = true;
    private $line = 0;
    private $init = false;

    public function __construct($path, $mode = 'r+', $headers_in_file = true)
    {
        parent::__construct($path, $mode);

        $this->headers_in_file = $headers_in_file;
    }

    public function get_headers()
    {
        $this->init();
        return $this->headers;
    }
    
    public function get_row()
    {
        $this->init();
        if (($row = fgetcsv($this->handle, 1000, $this->delimiter, $this->enclosure)) !== false) {
            $this->line++;
            return $this->headers ? $this->generate_indexes($row) : $row;
        } else {
            return false;
        }
    }

    private function generate_indexes($row) {
    	$new_row = array();
    	foreach ($this->headers as $index => $value) {
    		$new_row[$value] = $row[$index];
    	}
    	return $new_row;
    }

    public function get_all()
    {
        $data = array();
        while ($row = $this->get_row()) {
            $data[] = $row;
        }
        return $data;
    }

    public function get_line_number()
    {
        return $this->line;
    }
    
    protected function init()
    {
        if (true === $this->init) {
            return;
        }
        $this->init = true;
        $this->headers = $this->headers_in_file === true ? $this->get_row() : false;
    }

}