<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class url_shortner extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        // load models
        $this->load->model('url_shortner_model');
        $this->load->model('analytics_model');
    }

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
    {

        $data = array(
            'error' => false,
            'show_details' => false,
        );
        $post = $this->input->post(NULL, TRUE);

        
        if($post){
        	
            $url = $post['long_url'];
            
            // validate url
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                
                $id = $this->url_shortner_model->add_url( $url );
                
                $url_data = $this->url_shortner_model->get_url_by_id( $id );
                if($url_data){
        
            $this->analytics_model->add_log( $url_data[0]['id'] );
        
        }
                
                //echo "<pre>"; print_r($url_data);exit;
                $data['show_details'] = true;
                $data['url_data'] = $url_data;
                
                
            } else {
                $data['error'] = "Invalid URL!";
            }
            
        }
        
        // load view and assign data array
        $this->load->view('home', $data);
    }
    public function redirect( $alias )
    {
       $url_data = $this->url_shortner_model->get_url( $alias );
        
        // check if there's an url with this alias
        if($url_data==NULL){
        
            header("HTTP/1.0 404 Not Found");
            $this->load->view('error');
        
        }else{
            
            $this->analytics_model->add_log( $url_data[0]['id'] );
            
            header('Location: ' . $url_data->url, true, 302);
            exit();
        } 
    }
    
    
    public function stats( $alias )
    {
      $url_data = $this->url_shortner_model->get_url( $alias );
        
        // check if there's an url with this alias
        if($url_data==NULL){

            header("HTTP/1.0 404 Not Found");
            $this->load->view('error');

        }else{
            
            $logs = $this->analytics_model->get_logs( $url_data[0]['id'] );
            
            $data = array(
                'url_data'  => $url_data,
                'logs'      => $logs,
            );
            
            $this->load->view('analytics', $data);

        }  
    }
}
