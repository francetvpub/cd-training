<?php
/**
 * Created by PhpStorm.
 * User: fred
 * Date: 08/03/16
 * Time: 11:47
 */

namespace Application\Service;


use Application\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Application\Service\FileStorage\FileStorageInterface;

class UserManager
{
    /** @var  EntityRepository */
    protected $repository;
    
    /** @var EntityManagerInterface */
    protected $entityManager;
    
    /** @var FileStorageInterface */
    protected $fileStorage;
    
    public function setRepository(EntityRepository $repository)
    {
        $this->repository = $repository;
        
        return $this;
    }
    
    /**
     * @param mixed $entityManager
     * @return UserManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @param FileStorageInterface $fileStorage
     * @return UserManager
     */
    public function setFileStorage(FileStorageInterface $fileStorage)
    {
        $this->fileStorage = $fileStorage;
        return $this;
    }

    public function getList()
    {
        if (!$this->repository) {
            throw new Exception;
        }
        
        return $this->repository->findAll();
    }
    
    public function get($id)
    {
        if (!$this->repository) {
            throw new Exception;
        }
        
        return $this->repository->find($id);
    }
    
    public function save(User $user)
    {
        if ($user->getTemporaryAvatar()) {
            $this->fileStorage->filePutContent(
                '/' . $user->getId() . '/' . basename($user->getTemporaryAvatar()),
                file_get_contents($user->getTemporaryAvatar()
            ));
        }
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}