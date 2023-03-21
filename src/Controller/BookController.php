<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
  #[Route('/books', name: 'books_list', methods: ['GET'])]
  public function index(BookRepository $bookRepository): JsonResponse
  {
    return $this->json(['data' => $bookRepository->findAll()]);
  }

  #[Route('/books', name: 'books_create', methods: ['POST'])]
  public function create(Request $request, BookRepository $bookRepository): JsonResponse
  {
    $data = $request->request->all();
    $book = new Book();
    $book->setTitle($data['title']);
    $book->setIsbn($data['isbn']);
    $book->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Africa/Luanda')));
    $book->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Africa/Luanda')));

    $bookRepository->save($book, true);
    return $this->json([
      'message' => 'Book created successfully'
    ], 201);
  }
}