<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Form\Test\TypeTestCase;

class UserTypeTest extends TypeTestCase
{

    private $roles;
    private $validator;

    protected function getExtensions()
    {
        $hierarchyRoles = [
            "ROLE_USER" => [
                0 => "ROLE_USER"
            ],
            "ROLE_ADMIN" => [
                0 => "ROLE_USER",
                1 => "ROLE_ADMIN"
            ]
        ];

        $this->roles = $hierarchyRoles;
        $type = new UserType($this->roles);

        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->validator
            ->method('validate')
            ->will($this->returnValue(new ConstraintViolationList()));
        $this->validator
            ->method('getMetadataFor')
            ->will($this->returnValue(new ClassMetadata(Form::class)));

        return array(
            new PreloadedExtension(array($type), array()),
            new ValidatorExtension($this->validator)
        );
    }

    public function testSubmitValidData()
    {
        $formData = array(
            'username' => 'UserName',
            'password' => array('first' => 'Password', 'second' => 'Password'),
            'email' => 'user@mail.com',
            'roles' => ['ROLE_USER']
        );

        $form = $this->factory->create(UserType::class);

        $object = new User();
        $object->setUsername($formData['username']);
        $object->setPassword($formData['password']['first']);
        $object->setEmail($formData['email']);
        $object->setRoles($formData['roles']);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($object->getUsername(), $form->get('username')->getData());
        $this->assertEquals($object->getPassword(), $form->get('password')->getData());
        $this->assertEquals($object->getEmail(), $form->get('email')->getData());
        $this->assertEquals($object->getRoles(), $form->get('roles')->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
