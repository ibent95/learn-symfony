<?php

namespace App\Controller;

use App\Entity\ItemEntity;

use App\Repository\ItemEntityRepository;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Psr\Log\LoggerInterface;

class ItemsController extends AbstractController
{

    protected $statusCode;
    protected $collection;
    protected $entityManager;
    protected $doctrine;

    protected ItemEntityRepository $itemRepository;

    public function __construct(ManagerRegistry $managerRegistry, EntityManagerInterface $doctrine, ItemEntityRepository $itemRepo) {
        $this->statusCode = Response::HTTP_NO_CONTENT;
        $this->collection = new ArrayCollection();
        $this->entityManager = $managerRegistry->getManager();
        $this->doctrine = $doctrine;
        $this->itemRepository = $itemRepo;
    }

    public function index(LoggerInterface $logger, ValidatorInterface $validator, Request $request): Response
    {
        $response = ['Nothing happend...'];
        try {
            $items = $this->itemRepository->findAll();
            //dd($items);

            $response = [
                'info' => 'success',
                'message' => 'Success on get all data.',
                'data' => $items,
            ];
            $this->statusCode = Response::HTTP_OK;
        } catch (Exception $e) {
            $response = [
                'info' => 'error',
                'message' => 'Error on get all data.'
            ];
            $this->statusCode = Response::HTTP_BAD_REQUEST;
            $logger->info('Error on get all data, ' . $e);
        }
        return $this->json($response, $this->statusCode);
    }

    public function getItemByUUID(LoggerInterface $logger, ItemEntityRepository $itemRepo, String $uuid = null): Response
    {
        $response = ['Nothing happend...'] ;
        try {
            if (!$uuid || $uuid === '400') throw new Exception("UUID not sended..!", Response::HTTP_NOT_FOUND);

            $this->collection = new ArrayCollection([1, 2, 3]);

            $filteredCollection = $this->collection->filter(function($element) {
                return $element > 1;
            });

            $item = $itemRepo->findOneBy(['uuid' => $uuid]);

            $response = [
                'info' => 'success',
                'message' => 'Success on get item data.',
                'data' => $item,
            ];
            $this->statusCode = Response::HTTP_OK;
        } catch (Exception $e) {
            $response = [
                'info' => 'error',
                'message' => 'Error on get item data.'
            ];
            $this->statusCode = Response::HTTP_BAD_REQUEST;
            $logger->info('Error on get item data, ' . $e);
        }
        return $this->json($response, $this->statusCode);
    }

    public function createItem(LoggerInterface $logger, ValidatorInterface $validator, Request $request): Response
    {
        $response = ['Nothing happend...'] ;
        $connection = $this->doctrine->getConnection();
        try {
            $connection->beginTransaction();
            $item = new ItemEntity();

            $item->ItemEntityV2($request->get('name'), $request->get('value'), $request->get('description'));

            $errors = $validator->validate($item);
            if (count($errors) > 0) throw new Exception("Error validation..!" . $errors, Response::HTTP_BAD_REQUEST);

            $this->itemRepository->insertOne($item);

            $connection->commit();
            $response = ['HTTP_OK...'];

            $this->statusCode = Response::HTTP_OK;
        } catch (Exception $e) {
            //$connection->rollback();
            $response = ['HTTP_BAD_REQUEST...'];
            $this->statusCode = Response::HTTP_BAD_REQUEST;
            $logger->info('Error on create item data, ' . $e);
        }
        return $this->json($response, $this->statusCode);
    }

    public function changeItem(LoggerInterface $logger, ValidatorInterface $validator, Request $request): Response
    {
        $response = ['Nothing happend...'];
        $connection = $this->doctrine->getConnection();
        try {
            $connection->beginTransaction();
            $item = new ItemEntity();

            $item->ItemEntityV2($request->get('name'), $request->get('value'), $request->get('description'));

            $errors = $validator->validate($item);
            if (count($errors) > 0) throw new Exception("Error validation..!" . $errors, Response::HTTP_BAD_REQUEST);

            $this->itemRepository->updateOne($item);

            $connection->commit();

            $response = ['HTTP_OK...'];
            $this->statusCode = Response::HTTP_OK;
        } catch (Exception $e) {
            //$connection->rollback();
            $response = ['HTTP_BAD_REQUEST...'];
            $this->statusCode = Response::HTTP_BAD_REQUEST;
            $logger->info('Error on create item data, ' . $e);
        }
        return $this->json($response, $this->statusCode);
    }

    public function deleteItem(LoggerInterface $logger, ValidatorInterface $validator, ItemEntity $itemInput): Response
    {
        $response = ['Nothing happend...'];
        try {
            $item = new ItemEntity();
            //$item->ItemEntityV1('Ibnu Rohan Tuharea', 'Programmer', 'Umur 26', new DateTime('now'), new DateTime('now'), 'ibnu', 'ibnu');
            $item->ItemEntityV2('Ibnu Rohan Tuharea', 'Programmer', 'Umur 26');

            //$item->setName('Ibnu Rohan Tuharea');
            //$item->setValue('Programmer');
            //$item->setDescription('Umur 26');
            //$item->setTglInput(new DateTime('now'));
            //$item->setTglUpdate(new DateTime('now'));
            //$item->setUserInput('ibnu');
            //$item->setUserUpdate('ibnu');

            dd('Item`s uuid', $item->getUuid());

            $errors = $validator->validate($item);
            if (count($errors) > 0) throw new Exception("UUID not sended..!", Response::HTTP_BAD_REQUEST);

            $this->entityManager->persist($item);
            $this->entityManager->flush();

            $response = ['HTTP_OK...'];
            $this->statusCode = Response::HTTP_OK;
        } catch (Exception $e) {
            $response = ['HTTP_BAD_REQUEST...'];
            $this->statusCode = Response::HTTP_BAD_REQUEST;
            $logger->info('Error on get item data, ' . $e);
        }
        return $this->json($response, $this->statusCode);
    }

    public function createUpdateItem(LoggerInterface $logger, ValidatorInterface $validator, ItemEntity $itemInput): Response
    {
        $response = ['Nothing happend...'];
        try {
            $item = new ItemEntity();
            //$item->ItemEntityV1('Ibnu Rohan Tuharea', 'Programmer', 'Umur 26', new DateTime('now'), new DateTime('now'), 'ibnu', 'ibnu');
            $item->ItemEntityV2('Ibnu Rohan Tuharea', 'Programmer', 'Umur 26');

            //$item->setName('Ibnu Rohan Tuharea');
            //$item->setValue('Programmer');
            //$item->setDescription('Umur 26');
            //$item->setTglInput(new DateTime('now'));
            //$item->setTglUpdate(new DateTime('now'));
            //$item->setUserInput('ibnu');
            //$item->setUserUpdate('ibnu');

            dd('Item`s uuid', $item->getUuid());

            $errors = $validator->validate($item);
            if (count($errors) > 0) throw new Exception("UUID not sended..!", Response::HTTP_BAD_REQUEST);

            $this->entityManager->persist($item);
            $this->entityManager->flush();

            $response = ['HTTP_OK...'];
            $this->statusCode = Response::HTTP_OK;
        } catch (Exception $e) {
            $response = ['HTTP_BAD_REQUEST...'];
            $this->statusCode = Response::HTTP_BAD_REQUEST;
            $logger->info('Error on get item data, ' . $e);
        }
        return $this->json($response, $this->statusCode);
    }

}