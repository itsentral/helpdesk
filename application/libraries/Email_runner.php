<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_runner {

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    /**
     * Memasukkan pesan email ke antrean (tabel email_queues)
     * dan memanggil eksekutor background.
     * 
     * @param string|array $to_email (bisa array/string dipisah koma)
     * @param string $subject
     * @param string $message (Isi HTML)
     * @param int $company_id (Optional)
     * @param string $action_url (Optional - Link langsung ke dokumen/tombol)
     */
    public function queue($to_email, $subject, $message, $company_id = null, $action_url = null)
    {
        if (is_array($to_email)) {
            $to_email = implode(',', $to_email);
        }

        if (!$company_id) {
            $company_id = isset($this->CI->session->company->id_perusahaan) ? $this->CI->session->company->id_perusahaan : 1;
        }

        $data = [
            'company_id' => $company_id,
            'to_email'   => $to_email,
            'subject'    => $subject,
            'message'    => $message,
            'action_url' => $action_url,
            'status'     => 'PND',
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Masukkan ke database queue
        $this->CI->db->insert('email_queues', $data);

        // Panggil script background agar email mulai dikirim tanpa membuat layar loading
        $this->_trigger_background_worker();
    }

    /**
     * Public wrapper agar controller lain dapat memicu background worker secara manual.
     * Digunakan saat resend email dari halaman queue list.
     */
    public function trigger_worker()
    {
        $this->_trigger_background_worker();
    }

    /**
     * Memanggil controller Cron secara asynchronous tanpa menunggu respons (CURLOPT_TIMEOUT 1 dtk)
     */
    private function _trigger_background_worker()
    {
        $url = base_url('cron/process_email_queue');
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1); // Timeout super singkat agar jalan di background
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_exec($ch);
        curl_close($ch);
    }
}
