<?php

namespace App\Repositories\Interfaces;

use App\Models\Calendar;
use Illuminate\Http\Request;

interface CalendarRepositoryInterface
{
    public function calendar_recipe_store(Calendar $calendar, Request $request);

    public function assign_recipe(Calendar $calendar, int $id);

    public function unsign_recipe(Calendar $calendar, int $id);
}
