<?php
namespace shop\services\manage;


use shop\forms\manage\UserCreateForm;
use shop\repository\UserRepository;
use shop\entities\User\User;

class UserManageService
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(UserCreateForm $form):User
    {
        $user = User::create($form->username, $form->email, $form->password);
        $this->repository->save($user);
        return $user;
    }
}