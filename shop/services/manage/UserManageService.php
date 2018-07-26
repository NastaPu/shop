<?php
namespace shop\services\manage;


use shop\access\Rbac;
use shop\forms\manage\UserCreateForm;
use shop\forms\manage\UserEditForm;
use shop\repositories\UserRepository;
use shop\entities\User\User;
use shop\services\newsletter\Newsletter;
use shop\services\RoleManager;

class UserManageService
{
    private $repository;
    private $transaction;
    private $roles;
    private $newsletter;

    public function __construct(UserRepository $repository, TransactionManager $transaction, RoleManager $roles, Newsletter $newsletter)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
        $this->roles = $roles;
        $this->newsletter = $newsletter;
    }

    public function create(UserCreateForm $form):User
    {
        $user = User::create($form->username, $form->email, $form->password, $form->phone);
        $this->transaction->wrap(function () use ($user) {
            $this->repository->save($user);
            $this->roles->assign($user->id, Rbac::ROLE_USER);
        });
        return $user;
    }

    public function edit($id, UserEditForm $form): void
    {
        $user = $this->repository->get($id);
        $user->edit(
            $form->username,
            $form->email,
            $form->phone
        );
        $this->transaction->wrap(function () use ($user) {
            $this->repository->save($user);
            $this->roles->assign($user->id, Rbac::ROLE_USER);
        });
    }

    public function assignRole($id, $role):void
    {
        $user = $this->repository->get($id);
        $this->roles->assign($user->id, $role);
    }

    public function remove($id): void
    {
        $user = $this->repository->get($id);
        if(!$user->delete()) {
            throw new \DomainException('Delete error');
        }
        $this->newsletter->unsubscribe($user->email);
    }
}