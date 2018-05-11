<?php

/** @property AuteurModel $auteurModel  */
class Auteur extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (!in_array($this->session->groupe,array('Admin','Enseignant'))) {
            echo "Vous devez être logué en tant qu'administrateur ou Enseignant pour accéder à cette page";
            exit;
        }
        
        // on fait référence au modèle dans le controleur
        $this->load->model('auteurModel');
        
        $this->load->library('pagination');
    }

    function index() {
        $config['base_url'] = site_url().'/Auteur/index/page';
        $page = $this->uri->segment(4,0);
        $config['total_rows'] = $this->auteurModel->get_count();
        $config['per_page'] = 10;
        $config['uri_segment'] = 4;
        $config['num_links'] = 10;
        $config['full_tag_open'] = '<b>';
        $config['full_tag_close'] = '</b>';
        $config['num_tag_open'] = ' ';
        $config['num_tag_close'] = ' ';
        $config['first_link'] = 'Première page ';
        $config['last_link'] = ' Dernière page';
        $config['prev_link'] = 'Précédent';
        $config['next_link'] = 'Suivant';
        
        $this->pagination->initialize($config);
        
        // récupération de tous les auteurs
        $data['auteurs']=$this->auteurModel->get_all_auteurs($page,$config['per_page']);
        $links = $this->pagination->create_links();
        // prépartion des données à transmettre à la vue
        $data['title']='Les Auteurs';
        $data['count'] = $this->auteurModel->get_count();
        $data['links'] = $links;
        
        $this->load->view('AppHeader',$data);
        $this->load->view('AuteurIndex',$data);
        $this->load->view('AppFooter');
    }

    function add() {
        // chargement lib de validation
        $this->load->library('form_validation');
        
        // on définit la liste des règles de validation des données
        // provenant du modèle. 
        // Todo A replacer en tant que méthode de controleur
        LoadValidationRules($this->auteurModel, $this->form_validation);

        if ($this->form_validation->run()) {
            $params=array(
                'nom'=>$this->input->post('nom')
            );

            $auteur_id=$this->auteurModel->add_auteur($params);
            redirect('Auteur/index');
        }
        else {
            $data['title']='Créer un auteur';
            $this->load->view('AppHeader',$data);
            $this->load->view('AuteurAdd');
            $this->load->view('AppFooter');
        }
    }

    function edit($id) {
        // verifie que l'auteur existe avant de le modifier
        $auteur=$this->auteurModel->get_auteur($id);
        if (isset($auteur['id'])) {
            // chargement lib de validation
            $this->load->library('form_validation');
            // définition des règles de validation
             LoadValidationRules($this->auteurModel, $this->form_validation);

            if ($this->form_validation->run()) {
                $params=array(
                    'nom'=>$this->input->post('nom'),
                );
                $this->auteurModel->update_auteur($id,$params);
                redirect('Auteur/index');
            }
            else {
                $data['auteur']=$this->auteurModel->get_auteur($id);
                $data['title']='Modifier un Auteur';
                $this->load->view('AppHeader',$data);
                $this->load->view('AuteurEdit',$data);
                $this->load->view('AppFooter');
            }
        }
        else
            show_error("l'auteur que vous essayez de modifier n'existe pas.");
    }

    function remove($id) {
        $auteur=$this->auteurModel->get_auteur($id);
        // vérification que l'auteur existe avant de le supprimer
        if (isset($auteur['id'])) {
            $this->auteurModel->delete_auteur($id);
            redirect('auteur/index');
        }
        else
            show_error("l'auteur que vous essayez de supprimer n'existe pas");
    }

}