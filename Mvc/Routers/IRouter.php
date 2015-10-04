<?php

namespace DH\Mvc\Routers;

interface IRouter
{
    public function getURI();

    public function getPost();
}