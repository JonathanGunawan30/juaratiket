<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\SellerRepositoryInterface;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Repositories\SellerRepository;
use App\Repositories\TicketRepository;

class FrontService
{
    protected CategoryRepository $categoryRepository;
    protected TicketRepository $ticketRepository;
    protected SellerRepository $sellerRepository;

    public function __construct( CategoryRepository $categoryRepository, SellerRepository $sellerRepository, TicketRepository $ticketRepository,)
    {
        $this->categoryRepository = $categoryRepository;
        $this->ticketRepository = $ticketRepository;
        $this->sellerRepository = $sellerRepository;
    }

    public function getFrontPageData(): array
    {
        $categories = $this->categoryRepository->getALlCategories();
        $sellers = $this->sellerRepository->getAllSellers();
        $popularTickets = $this->ticketRepository->getPopularTickets();
        $newTickets = $this->ticketRepository->getAllNewTickets();

        return compact('categories', 'sellers', 'popularTickets', 'newTickets');
    }

}
