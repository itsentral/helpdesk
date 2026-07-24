<?php defined('BASEPATH') or exit('No direct script access allowed');

class Documents extends Admin_Controller
{
    protected $id_user;
    protected $datetime;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'projects_management/Project_model',
            'projects_management/Document_model'
        ));

        date_default_timezone_set('Asia/Bangkok');
        $this->id_user  = $this->auth->user_id();
        $this->datetime = date('Y-m-d H:i:s');
    }

    public function index()
    {
        $this->template->title('Document & File Management');
        $this->template->page_icon('fa fa-folder');

        $project_id         = $this->input->get('project_id');
        $category           = $this->input->get('category');
        $data['project_id'] = $project_id;
        $data['projects']   = $this->Project_model->get_projects();
        $data['documents']  = $this->Document_model->get_documents($project_id, $category);

        $this->template->set($data);
        $this->template->render('documents/index');
    }

    public function upload()
    {
        $project_id = $this->input->post('project_id');
        $task_id    = $this->input->post('task_id');
        $category   = $this->input->post('category');
        $version    = $this->input->post('version');

        if (empty($project_id) || empty($_FILES['document_file']['name'])) {
            echo json_encode(array('status' => 0, 'pesan' => 'Pilih project dan file dokumen!'));
            return;
        }

        // Enforce strict file extension whitelist: pdf, doc, docx, xls, xlsx, jpg, jpeg, png
        $allowed_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png');
        $original_filename  = $_FILES['document_file']['name'];
        $ext                = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed_extensions)) {
            echo json_encode(array('status' => 0, 'pesan' => 'Format file tidak diizinkan. Hanya file pdf, doc, docx, xls, xlsx, jpg, jpeg, png yang diperbolehkan!'));
            return;
        }

        // Upload folder path
        $upload_dir = FCPATH . 'uploads/projects_management/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $config['upload_path']   = $upload_dir;
        $config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|jpg|jpeg|png';
        $config['max_size']      = 10240; // 10MB
        $config['encrypt_name']  = TRUE; // Generate unique random name

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('document_file')) {
            echo json_encode(array('status' => 0, 'pesan' => strip_tags($this->upload->display_errors())));
            return;
        }

        $upload_data = $this->upload->data();

        $insert_data = array(
            'project_id'  => $project_id,
            'task_id'     => !empty($task_id) ? $task_id : NULL,
            'file_name'   => htmlspecialchars($original_filename, ENT_QUOTES, 'UTF-8'),
            'file_path'   => $upload_data['file_name'],
            'file_type'   => $ext,
            'file_size'   => $upload_data['file_size'] * 1024,
            'category'    => $category ? $category : 'Other',
            'version'     => $version ? $version : 'v1.0',
            'uploaded_by' => $this->id_user,
            'created_at'  => $this->datetime
        );

        $this->db->insert('pm_documents', $insert_data);
        echo json_encode(array('status' => 1, 'pesan' => 'Dokumen berhasil di-upload!'));
    }

    public function download($id)
    {
        $doc = $this->db->get_where('pm_documents', array('id' => $id))->row_array();
        if (!$doc) {
            show_404();
            return;
        }

        $file_path = FCPATH . 'uploads/projects_management/' . basename($doc['file_path']);
        if (!file_exists($file_path)) {
            show_error('File tidak ditemukan di server.', 404);
            return;
        }

        $this->load->helper('download');
        force_download($doc['file_name'], file_get_contents($file_path));
    }
}
