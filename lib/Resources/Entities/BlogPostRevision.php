<?php

namespace Resources\Entities;

/**
 * @Entity(repositoryClass="Resources\Entities\BlogPostRevisionRepository") @Table(name="blog_post_revision")
 */
class BlogPostRevision {

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
   * @ManyToOne(targetEntity="BlogPost")
   * @JoinColumn(name="blog_post")
   */
  private $blogPost;

  /**
   *
   * @Column(type="text", name="texte")
   */
  private $texte;

  /**
   * @Column(type="datetime", name="date_add")
   */
  private $dateAdd;

  public function setAdmin($admin) {
    $this->admin = $admin;
  }

  public function getAdmin() {
    return $this->admin;
  }

  public function setBlogPost($blogPost) {
    $this->blogPost = $blogPost;
  }

  public function getBlogPost() {
    return $this->blogPost;
  }

  public function setDateAdd($dateAdd) {
    $this->dateAdd = $dateAdd;
  }

  public function getDateAdd() {
    return $this->dateAdd;
  }

  public function getDateAddLitteral() {
    return \DateUtils::getDateText($this->dateAdd->format('Y-m-d H:i'));
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function getId() {
    return $this->id;
  }

  public function setTexte($texte) {
    $this->texte = $texte;
  }

  public function getTexte() {
    return $this->texte;
  }

}