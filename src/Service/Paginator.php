<?php

namespace App\Service;

use Twig\Environment;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

class Paginator{
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;
    private $twig;
    private $route;
    private $templatePath;
    private $column;
    private $order;

    public function __construct(ObjectManager $manager, Environment $twig, RequestStack $request, $templatePath){
        $this->manager = $manager;
        $this->twig = $twig;
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
        $this->templatePath = $templatePath;
    }

    public function setColumn($column){
        $this->column = $column;

        return $this;
    }

    public function getColumn(){
        return $this->column;
    }

    public function setOrder($order){
        $this->order = $order;

        return $this;
    }

    public function getOrder(){
        return $this->order;
    }

    public function setTemplatePath($templatePath){
        $this->templatePath = $templatePath;

        return $this;
    }

    public function getTemplatePath(){
        return $this->templatePath;
    }

    public function setRoute($route){
        $this->route = $route;

        return $this;
    }

    public function getRoute(){
        return $this->route;
    }


    public function display(){
        $this->twig->display($this->templatePath, [
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route,
            'column' => $this->column,
            'order' => $this->order
        ]);
    }

    public function getPages(){
        if(empty($this->entityClass)){
            throw new \Exception("Vouns n'avez pas spécifié l'entité sur laquelle nous devons paginer ! Utilisez la méthode setEntityClass() de votre objet Paginator !");
        }
        // 1) Connaître le total des enregistrements de la table
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());

        // 2) Faire la division, l'arrondi et le renvoyer
        $pages = ceil($total / $this->limit);

        return $pages;
    }

    public function getDataDql(){
        if(empty($this->entityClass)){
            throw new \Exception("Vouns n'avez pas spécifié l'entité sur laquelle nous devons paginer ! Utilisez la méthode setEntityClass() de votre objet Paginator !");
        }
        // 1) Calculer l'offset
        $offset = $this->currentPage * $this->limit - $this->limit;

        // 2) Demander au repository de trouve les éléments
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->adminCountBooker($this->limit,$offset);

        // 3) Renvoyer les éléments en question
        return $data;
    }

    public function getData(){
        if(empty($this->entityClass)){
            throw new \Exception("Vouns n'avez pas spécifié l'entité sur laquelle nous devons paginer ! Utilisez la méthode setEntityClass() de votre objet Paginator !");
        }
        // 1) Calculer l'offset
        $offset = $this->currentPage * $this->limit - $this->limit;

        // 2) Demander au repository de trouve les éléments
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([],[$this->column => $this->order],$this->limit,$offset);

        // 3) Renvoyer les éléments en question
        return $data;
    }

    public function setEntityClass($entityClass){
        $this->entityClass = $entityClass;

        return $this;
    }

    public function getEntityClass(){
        return $this->entityClass;
    }

    public function setLimit($limit){
        $this->limit = $limit;

        return $this;
    }

    public function getLimit(){
        return $this->limit;
    }

    public function setPage($page){
        $this->currentPage = $page;

        return $this;
    }

    public function getPage(){
        return $this->currentPage;
    }

       
}

?>