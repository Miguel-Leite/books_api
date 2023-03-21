<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class BookController extends AbstractController
{
  #[Route('/books', name: 'books_list', methods: ['GET'])]
  public function index(BookRepository $bookRepository): JsonResponse
  {
    return $this->json(['data' => $bookRepository->findAll()]);
  }

  #[Route('/books/{book}', name: 'books_single', methods: ['GET'])]
  public function single(int $book, BookRepository $bookRepository): JsonResponse
  {
    $book = $bookRepository->find($book);
    if (!$book) throw $this->createNotFoundException();
    return $this->json(['data' => $book]);
  }

  #[Route('/books', name: 'books_create', methods: ['POST'])]
  public function create(Request $request, BookRepository $bookRepository): JsonResponse
  {
    $data = $request->toArray();
    $book = new Book();
    $book->setTitle($data['title']);
    $book->setIsbn($data['isbn']);
    $book->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Africa/Luanda')));
    $book->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Africa/Luanda')));

    $bookRepository->save($book, true);
    return $this->json([
      'data' => 'Book created successfully'
    ], 201);
  }

  #[Route('/books/{book}', name: 'books_update', methods: ['PUT','PATCH'])]
  public function update(int $book, Request $request, ManagerRegistry $doctrine, BookRepository $bookRepository): JsonResponse
  {
    $data = $request->toArray();
    $book = $bookRepository->find($book);
    $book->setTitle($data['title']);
    $book->setIsbn($data['isbn']);
    $book->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Africa/Luanda')));

    $doctrine->getManager()->flush();
    
    return $this->json([
      'data' => $book
    ], 202);
  }

  #[Route('/books/{book}', name: 'books_delete', methods: ['DELETE'])]
  public function delete(int $book, Request $request, BookRepository $bookRepository): JsonResponse
  {
    $book = $bookRepository->find($book);
    $bookRepository->remove(entity: $book, flush: true);
    return $this->json([
      'success' => true
    ]);
  }
}