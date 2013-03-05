<?php

namespace Resources\Entities;

/**
 * @Entity(repositoryClass="Resources\Entities\BlogPostRepository") @Table(name="blog_post") 
 */
class BlogPost {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Admin")
     * @JoinColumn(name="admin")
     */
    private $admin;

    /**
     * @Column(type="string", length=100, name="seo_title")
     */
    private $seoTitle;

    /**
     * @Column(type="string", length=100, name="seo_h1")
     */
    private $seoH1;

    /**
     * @Column(type="string", length=180, name="seo_description")
     */
    private $seoDescription;

    /**
     * @Column(type="string", length=240, name="seo_url", unique=true)
     */
    private $seoUrl;

    /**
     * @Column(type="string", length=150) 
     */
    private $titre;

    /**
     *
     * @Column(type="text", name="texte") 
     */
    private $texte;

    /**
     * @Column(type="datetime", name="date_add")
     */
    private $dateAdd;

    /**
     * @Column(type="datetime", name="date_edit")
     */
    private $dateEdit;

    /**
     *
     * @Column(type="string", name="statut") 
     */
    private $statut;

    /**
     * @Column(type="string", length=200, name="template_url") 
     */
    private $templateUrl;

    /**
     * @ManyToOne(targetEntity="BlogCategory")
     * @JoinColumn(name="blog_category", nullable=true)
     */
    private $blogCategory;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getAdmin() {
        return $this->admin;
    }

    public function setAdmin($admin) {
        $this->admin = $admin;
    }

    public function getSeoTitle() {
        return $this->seoTitle;
    }

    public function setSeoTitle($seoTitle) {
        $this->seoTitle = $seoTitle;
    }

    public function getSeoH1() {
        return $this->seoH1;
    }

    public function setSeoH1($seoH1) {
        $this->seoH1 = $seoH1;
    }

    public function getSeoDescription() {
        return $this->seoDescription;
    }

    public function setSeoDescription($seoDescription) {
        $this->seoDescription = $seoDescription;
    }

    public function getSeoUrl() {
        return $this->seoUrl;
    }

    public function setSeoUrl($seoUrl) {
        $this->seoUrl = $seoUrl;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function setTitre($titre) {
        $this->titre = $titre;
    }

    public function getTexte() {
        return $this->texte;
    }

    public function setTexte($texte) {
        $this->texte = $texte;
    }

    public function getDateAdd() {
        return \DateUtils::getDateText($this->dateAdd->format('Y-m-d H:i'));
    }

    public function setDateAdd($dateAdd) {
        $this->dateAdd = $dateAdd;
    }

    public function getDateEdit() {
        return \DateUtils::getDateText($this->dateEdit->format('Y-m-d H:i'));
    }

    public function setDateEdit($dateEdit) {
        $this->dateEdit = $dateEdit;
    }

    public function getStatut() {
        return $this->statut;
    }

    public function setStatut($statut) {
        $this->statut = $statut;
    }

    public function getTemplateUrl() {
        return $this->templateUrl;
    }

    public function setTemplateUrl($templateUrl) {
        $this->templateUrl = $templateUrl;
    }

    public function getIsDelete() {
        return $this->isDelete;
    }

    public function setIsDelete($isDelete) {
        $this->isDelete = $isDelete;
    }

    public function getBlogCategory() {
        return $this->blogCategory;
    }

    public function setBlogCategory($blogCategory) {
        $this->blogCategory = $blogCategory;
    }

}