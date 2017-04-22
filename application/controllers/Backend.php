<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Backend Controller
 *
 * @package Controllers
 */
class Backend extends CI_Controller {

    /**
     * Class Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->load->library('session');

    }

    /**
     * Display the backend customers page.
     *
     * In this page the user can manage all the customer records of the system.
     */
    public function customers() {

        if (isset($_FILES['file'])) {
            $this->load->helper(array('form', 'url'));

            $this->load->library('form_validation');

            if (empty($_FILES['file']['name'])) {
                $this->form_validation->set_rules('file', 'File', 'required');
            }

            $config['upload_path'] = sys_get_temp_dir();
            $config['allowed_types'] = 'xlsx|csv|xls';

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('file')) {
                $view['errors'] = $this->upload->display_errors();
            } else {
                // ignore user abort and set execution time to inifnite.
                ignore_user_abort(true);
                set_time_limit(0);

                $this->load->model('roles_model');
                $role_id = (int) $this->roles_model->get_role_id(DB_SLUG_CUSTOMER);

                $data = $this->upload->data();
                $this->load->library('bulk_upload');

                $verifiedData = $this->bulk_upload->verify($data['full_path']);
                $fields_mapping = $this->bulk_upload->get_fields_mapping();
                $finalData = [];

                if ($verifiedData['message'] !== '') {
                    $view['message'] = $verifiedData['message'];
                } else {

                    // prepare data 
                    foreach ($verifiedData['validData'] as $row) {
                        $array = [];
                        foreach ($row as $key => $value) {
                            $array[$fields_mapping[$key]] = $value;
                        }
                        $array['id_roles'] = $role_id;
                        $finalData[] = $array;
                    }

                    if (count($finalData) > 0) {
                        $user_id = (int) $this->session->userdata('user_id');
                        if ($this->customers_model->insert_batch($finalData, $user_id)) {
                            $view['message'] = $this->lang->line('bulk_data_import_succeed');
                        } else {
                            $view['message'] = $this->lang->line('bulk_data_import_failed');
                        }

                        if (count($verifiedData['invalidData']) > 0) {
                            $view['invalidData'] = $this->lang->line('bulk_data_invalid_data_not_inserted') . '<br>';
                            $view['invalidData'] .= implode(', ', $verifiedData['invalidData']);
                        }
                    } else {
                        $view['message'] = $this->lang->line('bulk_data_0_rows_are_valid');
                    }
                }
            }
        }

        $this->load->view('bulk_upload_view', $view);
    }

}

/* End of file backend.php */
/* Location: ./application/controllers/backend.php */
