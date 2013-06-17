<?php
namespace Chat\Plugin;

interface InitializePluginInterface
{
    public function __construct(array $options);

    public function initialize();

    public function getEventListeners();
}