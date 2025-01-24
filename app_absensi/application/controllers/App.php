<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class App extends Wfh_Controller
{
    // Constructor
    function __construct()
    {
        parent::__construct();
    }

    // Index method
    public function index()
    {
        $this->view_content();
    }
    
    // Private method to handle content display logic
    private function view_content()
    {
        redirect('pengajuan', 'location');
    }
}
