<?php

/**
 * MenuService class file
 *
 * PHP Version 8.3
 *
 * @category Class
 * @package  Console_Command
 * @author   Tayyab <tayyab.hussain.it@gmail.com>
 * @license  https://github.com/tayyabhussainit Private Repo
 * @link     https://github.com/tayyabhussainit/flashcard
 */
namespace App\Services;

use App\Enums\MenuItem;

/**
 * MenuService class
 *
 * This class is to manage menu and menu items
 *
 * @category Class
 * @package  Console_Command
 * @author   Tayyab <tayyab.hussain.it@gmail.com>
 * @license  https://github.com/tayyabhussainit Private Repo
 * @link     https://github.com/tayyabhussainit/flashcard
 */
class MenuService
{

    /**
     * Returns the list of menu items
     * 
     * @return string[]
     */
    public function getMenu()
    {
        return [
            MenuItem::CREATE_FLASHCARD->value,
            MenuItem::LIST_FLASHCARD->value,
            MenuItem::PRACTICE->value,
            MenuItem::STATS->value,
            MenuItem::RESET->value,
            MenuItem::EXIT->value,
        ];
    }
}
