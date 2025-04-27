<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface ApiBaseControllerInterface
{
    public function index(Request $request): Response;

    public function show($id): Response;

    public function store(Request $request);

    public function update(Request $request, $id);

    public function destroy($id);
}