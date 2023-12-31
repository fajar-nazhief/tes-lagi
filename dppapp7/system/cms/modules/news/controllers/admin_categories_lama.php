<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package  	PyroCMS
 * @subpackage  Categories
 * @category  	Module
 * @author  	Phil Sturgeon - PyroCMS Dev Team
 */
class Admin_Categories extends Admin_Controller
{
	/**
	 * Array that contains the validation rules
	 * @access protected
	 * @var array
	 */
	protected $validation_rules;
	
	/** 
	 * The constructor
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::Admin_Controller();
		$this->load->model('news_categories_m');
		$this->load->models(array('files/file_m', 'files/file_folders_m'));
		$this->lang->load('categories');
		$this->load->model('navigation/navigation_m');
		$this->lang->load('news');
		
	    $this->template->set_partial('shortcuts', 'admin/partials/shortcuts');
	
		// Set the validation rules
		$this->validation_rules = array(
			array(
				'field' => 'title',
				'label' => lang('categories.title_label'),
				'rules' => 'trim|required'
			),
		);
		
		// Load the validation library along with the rules
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->validation_rules);
	}
	
	/**
	 * Index method, lists all categories
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$this->pyrocache->delete_all('modules_m');
		// Create pagination links
		$total_rows = $this->news_categories_m->count_all();
		$pagination = create_pagination('admin/news/categories/index', $total_rows,20,5);
			
		// Using this data, get the relevant results
		$categories = $this->news_categories_m->limit($pagination['limit'])->get_all();
		
		if(($_POST['search'])){
			redirect('admin/news/categories/search/'.$_POST['search']);
		}
		$this->template
			->title($this->module_details['name'], lang('cat_list_title'))
			->set('categories', $categories)
			->set('pagination', $pagination)
			->build('admin/categories/index', $this->data);
	}
	
	public function search()
	{
		$this->pyrocache->delete_all('modules_m');
		// Create pagination links
		$total_rows = $this->news_categories_m->count_data();
		$pagination = create_pagination('admin/news/categories/search/'.$this->uri->segment(5), $total_rows,10,6);
			
		// Using this data, get the relevant results
		$categories = $this->news_categories_m->get_all($pagination['limit']);

		$this->template
			->title($this->module_details['name'], lang('cat_list_title'))
			->set('categories', $categories)
			->set('pagination', $pagination)
			->build('admin/categories/index', $this->data);
	}
	
	/**
	 * Create method, creates a new category
	 * @access public
	 * @return void
	 */
	public function create()
	{
		
		// Validate the data
		if ($this->form_validation->run())
		{
			$this->file_folders_m->insert(array(
					'name'			=> $_POST['title'],
					'slug'=>url_title(strtolower(convert_accented_characters($_POST['title']))),
					'parent_id'		=> 1,
					'date_added'	=> now()
				));
			$this->news_categories_m->insert($_POST)
				? $this->session->set_flashdata('success', sprintf( lang('cat_add_success'), $this->input->post('title')) )
				: $this->session->set_flashdata(array('error'=> lang('cat_add_error')));
				
				
			redirect('admin/news/categories');
		}
		
		// Loop through each validation rule
		foreach($this->validation_rules as $rule)
		{
			$category->{$rule['field']} = set_value($rule['field']);
		}
		
		// Render the view
		$this->data->navigation_tree = $this->navigation_m->get_links();
		$this->data->category =& $category;	
		$this->template->title($this->module_details['name'], lang('cat_create_title'))
						->build('admin/categories/form', $this->data);	
	}
	
	function buat_folder(){
		 
		
		$get_id=$this->file_folders_m->get_where('file_folders',array('slug'=>$this->uri->segment(5),'parent_id'=>'1'))->row();
		if(($get_id)){
			
			$data = array(
			        
			       'folder_id' => $this->uri->segment(5)
			    );

			$this->db->where('slug', $this->uri->segment(5));
			$this->db->update('news_categories', $data); 
		}else{
			$this->file_folders_m->insert(array(
					'name'			=> $this->uri->segment(5),
					'slug'=>$this->uri->segment(5),
					'parent_id'		=> 1,
					'date_added'	=> now()
				));
			
			$data = array(
			        
			       'folder_id' => $this->uri->segment(5)
			    );

			$this->db->where('slug', $this->uri->segment(5));
			$this->db->update('news_categories', $data); 
		}
		 redirect('admin/files#!path=artikel/'.$this->uri->segment(5).'&filter=');
	}
	/**
	 * Edit method, edits an existing category
	 * @access public
	 * @param int id The ID of the category to edit 
	 * @return void
	 */
	public function edit($id = 0)
	{	
		// Get the category
		$category = $this->news_categories_m->get($id);
		
		// ID specified?
		$category or redirect('admin/news/categories/index');
		
		// Validate the results
		if ($this->form_validation->run())
		{		
			$this->news_categories_m->update($id, $_POST)
				? $this->session->set_flashdata('success', sprintf( lang('cat_edit_success'), $this->input->post('title')) )
				: $this->session->set_flashdata(array('error'=> lang('cat_edit_error')));
			
			redirect('admin/news/categories/index');
		}
		
		// Loop through each rule
		foreach($this->validation_rules as $rule)
		{
			if($this->input->post($rule['field']) !== FALSE)
			{
				$category->{$rule['field']} = $this->input->post($rule['field']);
			}
		}

		// Render the view
		$this->data->navigation_tree = $this->navigation_m->get_links();
		$this->data->category =& $category;
		$this->template->title($this->module_details['name'], sprintf(lang('cat_edit_title'), $category->title))
						->build('admin/categories/form', $this->data);
	}	

	/**
	 * Delete method, deletes an existing category (obvious isn't it?)
	 * @access public
	 * @param int id The ID of the category to edit 
	 * @return void
	 */
	public function delete($id = 0)
	{	
		$id_array = (($id)) ? array($id) : $this->input->post('action_to');
		
		// Delete multiple
		if(($id_array))
		{
			$deleted = 0;
			$to_delete = 0;
			foreach ($id_array as $id) 
			{
				if($this->news_categories_m->delete($id))
				{
					$deleted++;
				}
				else
				{
					$this->session->set_flashdata('error', sprintf($this->lang->line('cat_mass_delete_error'), $id));
				}
				$to_delete++;
			}
			
			if( $deleted > 0 )
			{
				$this->session->set_flashdata('success', sprintf($this->lang->line('cat_mass_delete_success'), $deleted, $to_delete));
			}
		}		
		else
		{
			$this->session->set_flashdata('error', $this->lang->line('cat_no_select_error'));
		}
		
		redirect('admin/news/categories/index');
	}
		
	/**
	 * Callback method that checks the title of the category
	 * @access public
	 * @param string title The title to check
	 * @return bool
	 */
	public function _check_title($title = '')
	{
		if ($this->news_categories_m->check_title($title))
		{
			$this->form_validation->set_message('_check_title', sprintf($this->lang->line('cat_already_exist_error'), $title));
			return FALSE;
		}

		return TRUE;
	}
	
	/**
	 * Create method, creates a new category via ajax
	 * @access public
	 * @return void
	 */
	public function create_ajax()
	{
		// Loop through each validation rule
		foreach($this->validation_rules as $rule)
		{
			$category->{$rule['field']} = set_value($rule['field']);
		}
		
		$this->data->method = 'create';
		$this->data->category =& $category;
		
		if ($this->form_validation->run())
		{
			$id = $this->news_categories_m->insert_ajax($_POST);
			
			if($id > 0)
			{
				$message = sprintf( lang('cat_add_success'), $this->input->post('title'));
			}
			else
			{
				$message = lang('cat_add_error');
			}
			
			$json = array('message' => $message,
					'title' => $this->input->post('title'),
					'category_id' => $id,
					'status' => 'ok'
					);
			echo json_encode($json);
		}	
		else
		{		
			// Render the view
			$errors = validation_errors();
			$form = $this->load->view('admin/categories/form', $this->data, TRUE);
			if(empty($errors))
			{
				
				echo $form;
			}
			else
			{
				$json = array('message' => $errors,
					      'status' => 'error',
					      'form' => $form
					     );
				echo json_encode($json);
			}
		}
	}
}