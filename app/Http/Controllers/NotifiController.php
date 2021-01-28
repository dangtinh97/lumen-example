<?php
namespace App\Http\Controllers;
use App\Services\NotifiService;
use Illuminate\Http\Request;

class NotificationController extends Controller{
    protected $notifiService;
    public function __construct(NotifiService $notifiService)
    {
        $this->notifiService = $notifiService;
    }
}