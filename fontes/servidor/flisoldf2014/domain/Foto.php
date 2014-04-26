<?php

/**
 * @Entity
 * @Table(name="foto")
 */
class Foto {

    /**
     * @Id
     * @Column(type="bigint")
     * @GeneratedValue
     */
    public $id;

    /**
     * @Column(type="datetime", nullable=false)
     */
    public $cadastro;

    /**
     * @Column(type="string", length=30, nullable=false)
     */
    public $nome;

    /**
     * @Column(type="string", length=50, nullable=false)
     */
    public $autor;

    /**
     * @Column(type="string", length=255, nullable=false)
     */
    public $arquivo;
    
    /**
     * @Column(type="float", nullable=false)
     */
    public $latitude;
    
    /**
     * @Column(type="float", nullable=false)
     */
    public $longitude;

}
