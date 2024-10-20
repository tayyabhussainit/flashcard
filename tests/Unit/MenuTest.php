<?php

/**
 * MenuTest class file
 *
 * PHP Version 8.3
 *
 * @category Class
 * @package  Console_Command
 * @author   Tayyab <tayyab.hussain.it@gmail.com>
 * @license  https://github.com/tayyabhussainit Private Repo
 * @link     https://github.com/tayyabhussainit/flashcard
 */

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\MenuService;

/**
 * MenuTest class
 *
 * This class contains test cases related to menu
 *
 * @category Class
 * @package  Console_Command
 * @author   Tayyab <tayyab.hussain.it@gmail.com>
 * @license  https://github.com/tayyabhussainit Private Repo
 * @link     https://github.com/tayyabhussainit/flashcard
 */
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
