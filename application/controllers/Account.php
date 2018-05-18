<?php
 
/**
 * Description of Account
 *
 * @author DanBonix
 */
class Account extends CI_Controller
{

    function __construct() {
        parent::__construct();
        $this->load->library('aauth');
        $this->load->model('enseignantModel');
        $this->load->library('form_validation');
    }
    
    public function verification($idAauth, $keyVerif)
    {
        
    }
    
    public function create()
    {
        // todo : vérifier si bon emplacement des  from_validation (P7)
        LoadValidationRules($this->enseignantModel, $this->form_validation);
        $this->form_validation->set_rules('password','Password','required|max_length[100]');
        $this->form_validation->set_rules('confirm','Confirmez le mot de passe',"required|max_length[100]|callback_password_check");
        //$this->form_validation->set_rules('g-recaptcha-response','Captcha','callback_recaptcha_check');

        if ($this->form_validation->run())
        {
            $password=$this->input->post('password');
            $email=$this->input->post('login');
            // créer le aauth_user à ajouter au controle de validation
            $idAauthUser=$this->aauth->create_user($email,$password);
            $params=array(
                'nom'=>$this->input->post('nom'),
                'prenom'=>$this->input->post('prenom'),
                'login'=>$email,
                'idAuth'=>$idAauthUser
            );
            // on crée l'enseignant
            $enseignant_id=$this->enseignantModel->add_enseignant($params);
            // on l'affecte au groupe Enseignant
            $this->aauth->add_member($idAauthUser,'Enseignant');
            //redirect('Enseignant/Index');
            $this->attente_confirmation($email);
        }
        else
        {
            $data['title']='Inscription au Rallye Lecture';
            $this->load->view('AppHeader',$data);
            $this->load->view('AccountCreate');
            $this->load->view('AppFooter');
        }
        // echo "ok";
    }
    
    public function password_check()
    {
        $password = $this->input->post('password');
        $passwordConfirmation = $this->input->post('confirm');
        if($password != $passwordConfirmation)
        {
            $this->form_validation->set_message('password_check','Attention ! Les deux mots de passe doivent être identiques');
            return false;
        }
        else
        {
            return true;
        }
    }
    
    public function recaptcha_check($resp)
    {
        if(empty($resp)){
            $this->form_validation->set_message('recaptcha_check','Vous ? Un robot ? Non... mais réessayez tout de même pour voir...');
            return false;
        }else{
            return true;
        }
    }
    
    public function edit()
    {
        
    }
    
    public function attente_confirmation($email)
    {
        $data['title'] = "Confirmation de votre inscription";
        $data['login'] = $email;
        $this->load->view('AppHeader',$data);
        $this->load->view('AccountCreate',$data);
        $this->load->view('AppFooter');
    }
    
    
    
    
}
