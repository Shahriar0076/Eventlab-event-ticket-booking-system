<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Orders';
        $orders = $this->eventData();
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function completed()
    {
        $pageTitle = 'Completed Orders';
        $orders = $this->eventData('completed');
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function paymentPending()
    {
        $pageTitle = 'Payment Pending Orders';
        $orders = $this->eventData('paymentPending');
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function cancelled()
    {
        $pageTitle = 'Cancelled Orders';
        $orders = $this->eventData('cancelled');
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function paid()
    {
        $pageTitle = 'Paid Orders';
        $orders = $this->eventData('paid');
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function unpaid()
    {
        $pageTitle = 'Unpaid Orders';
        $orders = $this->eventData('unpaid');
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function refunded()
    {
        $pageTitle = 'Refunded Orders';
        $orders = $this->eventData('refunded');
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    protected function eventData($scope = null)
    {
        if ($scope) {
            $orders = Order::$scope()->with(['event', 'user', 'event.organizer']);
        } else {
            $orders = Order::with(['event', 'user', 'event.organizer']);
        }

        $orders = $orders->searchable(['event:name', 'user:username'])->dateFilter();

        return $orders->latest()->paginate(getPaginate());
    }

    public function details($id)
    {
        $pageTitle = 'Order Details';
        $order = Order::findOrFail($id);
        return view('admin.order.details', compact('pageTitle', 'order'));
    }

}
