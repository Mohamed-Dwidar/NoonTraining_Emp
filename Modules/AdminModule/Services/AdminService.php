<?php

namespace Modules\AdminModule\Services;


use Modules\AdminModule\Repository\AdminRepository;


class AdminService
{
    private $adminRepository;
    
    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }
 
    public function updatePassword($data)
    {
        //dd($data->role);
        $new_password = bcrypt($data->password);
         return $this->adminRepository->update(['password' => $new_password], $data->id);  
    }

    public function findAll()
    {
        return $this->adminRepository->get();;
    }

   
   
}
