<?php

namespace Entities;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository{
    
    public function myFirstFunction(){
        
        return 'it works !';
        
    }
    
    
}


?>
