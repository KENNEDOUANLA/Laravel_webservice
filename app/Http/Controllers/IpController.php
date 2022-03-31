<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIpRequest;
use App\Http\Requests\UpdateIpRequest;
use App\Models\Ip;

class IpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreIpRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreIpRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ip  $ip
     * @return \Illuminate\Http\Response
     */
    public function show(Ip $ip)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ip  $ip
     * @return \Illuminate\Http\Response
     */
    public function edit(Ip $ip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateIpRequest  $request
     * @param  \App\Models\Ip  $ip
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateIpRequest $request, Ip $ip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ip  $ip
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ip $ip)
    {
        //
    }
}
