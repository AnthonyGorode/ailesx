<?php

namespace App\Service;

use Twig\Environment;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginatorDql{
    private $entityClass;
    private $entityClassPage;
    private $function;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;
    private $twig;
    private $route;
    private $templatePath;
    private $column;
    private $order;
    private $concat;

    public function __construct(ObjectManager $manager, Environment $twig, RequestStack $request, $templatePath){
        $this->manager = $manager;
        $this->twig = $twig;
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
        $this->templatePath = $templatePath;
    }

    public function setConcat($concat){
        $this->concat = $concat;

        return $this;
    }

    public function getConcat(){
        return $this->concat;
    }

    public function setFunction($function){
        $this->function = $function;

        return $this;
    }

    public function getFunction(){
        return $this->function;
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

    public function setEntityClassPage($entityClassPage){
        $this->entityClassPage = $entityClassPage;

        return $this;
    }

    public function getEntityClassPage(){
        return $this->entityClassPage;
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
            'order' => $this->order,
            'concat' => $this->concat
        ]);
    }

    public function getPages(){
        // Dans cette condition si il n'y a pas de recherhes effectués alors récupére le nombre total de pages
        // Sinon récupére le nombre total de pages en fonction de la recherche
        if(empty($this->concat)){
            if($this->entityClassPage == ""){
                $this->entityClassPage = $this->entityClass;
            }
            if(empty($this->entityClassPage)){
                throw new \Exception("Vouns n'avez pas spécifié l'entité sur laquelle nous devons paginer ! Utilisez la méthode setEntityClass() de votre objet Paginator !");
            }
            // 1) Connaître le total des enregistrements de la table
            $repo = $this->manager->getRepository($this->entityClassPage);
            $total = count($repo->findAll());
    
            // 2) Faire la division, l'arrondi et le renvoyer
            $pages = ceil($total / $this->limit);
            dump($pages);
        }else{
            if($this->entityClassPage == ""){
                $this->entityClassPage = $this->entityClass;
            }
            if(empty($this->entityClassPage)){
                throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer ! Utilisez la méthode setEntityClass() de votre objet Paginator !");
            }
            // 1) Calculer l'offset
            $offset = $this->currentPage * $this->limit - $this->limit;

            // 2) Connaître le total des enregistrements de la table
            $repo = $this->manager->getRepository($this->entityClassPage);
            $total = count($repo->countArray($this->column,$this->order,$offset,$this->concat));
            dump($total);
    
            // 3) Faire la division, l'arrondi et le renvoyer
            $pages = ceil($total / $this->limit);
        }

        return $pages;
    }

    public function getData(){
        if(empty($this->entityClass)){
            throw new \Exception("Vouns n'avez pas spécifié l'entité sur laquelle nous devons paginer ! Utilisez la méthode setEntityClass() de votre objet Paginator !");
        }
        // 1) Calculer l'offset
        $offset = $this->currentPage * $this->limit - $this->limit;
        
        // 2) Demander au repository de trouve les éléments
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->adminArray($this->column,$this->order,$this->limit,$offset,$this->concat);

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