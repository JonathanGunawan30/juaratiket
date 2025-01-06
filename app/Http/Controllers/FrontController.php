<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Seller;
use App\Models\Ticket;
use App\Services\FrontService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FrontController extends Controller
{
    protected FrontService $frontService;

    public function __construct(FrontService $frontService)
    {
        $this->frontService = $frontService;
    }

    public function index()
    {
        $data = $this->frontService->getFrontPageData();
        return view('front.index', $data);
    }

    public function details(Ticket $ticket)
    {
        return view('front.details', compact('ticket'));
    }

    public function category(Category $category)
    {
         return view('front.category', compact('category'));
    }

    public function seller(Seller $seller): View
    {
        return view('front.seller', compact('seller'));
    }


}
