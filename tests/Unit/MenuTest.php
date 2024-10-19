<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase; ERROR
use Tests\TestCase;
use App\Services\MenuService;

class MenuTest extends TestCase
{

    public function test_get_menu_options()
    {
        $menuService = new MenuService();
        $options = $menuService->getMenu();

        $this->assertContains('Create Flashcard', $options);
        $this->assertContains('List Flashcards', $options);
        $this->assertContains('Practice', $options);
        $this->assertContains('Stats', $options);
        $this->assertContains('Reset', $options);
        $this->assertContains('Exit', $options);
    }

}
