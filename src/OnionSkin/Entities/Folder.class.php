<?php

namespace OnionSkin\Entities;

/**
 * @Entity
 * @Table(name="folders")
 * @HasLifecycleCallbacks
 */
class Folder {
	/**
     * @var int
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="integer", name="folder_id", nullable=false, options = {"unsigned"=true})
     */
	public $id;
	/**
	 * @Column(type="string", name="folder_name", length=128, nullable=false)
     * @var string
	 */
	public $name;

	/**
	 * @ManyToOne(targetEntity="User", fetch="LAZY")
	 */
	public $user;
	/**
     * @ManyToOne(targetEntity="Folder", fetch="LAZY")
     * @var User
	 */
	public $parentFolder;
    /**
     * @OneToMany(targetEntity="Folder",mappedBy="parentFolder", fetch="EXTRA_LAZY")
     * @var User[]
     */
    public $childsFolders;


    /**
     * @PrePersist
     * @PreUpdate
     */
    public function validate()
    {
        $errors=new \OnionSkin\Exceptions\ErrorModel();
        if(!is_int($this->id) && !is_null($this->id))
            $errors->addError(true,"id","","ID is of type:"+gettype($this->id));

        if(is_null($this->name))
            $errors->addError(false,"name","errorNameNull");
        elseif(!is_string($this->name))
            $errors->addError(false,"name","errorNameNotString");
        elseif(strlen($this->name)>128)
            $errors->addError(false,"name","errorNameLenght");

        if(is_null($this->user))
            $errors->addError(true,"text","errorUserNull");

        if(!is_null($this->parentFolder) && $this->parentFolder->user->id==$this->user->id)
            $errors->addError(true,"user","errorUserNotMatchParent");



        if($errors->hasErrors())
            throw new \OnionSkin\Exceptions\ValidationException($errors,"Errors during validation of snippet");
    }

}