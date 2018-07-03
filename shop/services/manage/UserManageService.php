<?php
namespace shop\services\manage;


use shop\forms\manage\UserCreateForm;
use shop\forms\manage\UserEditForm;
use shop\repositories\UserRepository;
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

    public function edit($id, UserEditForm $form): void
    {
        $user = $this->repository->get($id);
        $user->edit(
            $form->username,
            $form->email
        );
        $this->repository->save($user);
    }

    public function remove($id): void
    {
        $user = $this->repository->get($id);
        if(!$user->delete()) {
            throw new \DomainException('Delete error');
        }
    }
}