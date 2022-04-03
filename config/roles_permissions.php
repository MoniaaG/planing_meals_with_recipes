<?php

//After any changes run "php artisan permission:assign"

return [
    'roles' => [
        'admin',
        'moderator',
        'user',
    ],

    'permissions' => [
        'product.create',
        'product.update',
        'product.edit',
        'product.delete',
        'product.offer',
        'product.accept',

        'recipe.create',
        'recipe.show',
        'recipe.edit',
        'recipe.update',
        'recipe.delete',

        'product_category.create',
        'product_category.edit',
        'product_category.update',
        'product_category.delete',

        'recipe_category.create',
        'recipe_category.edit',
        'recipe_category.update',
        'recipe_category.delete',

        'category.create',
        'category.edit',
        'category.update',
        'category.delete',

        'calendar.show',
        'calendar.add',
        'calendar.edit',
        'calendar.delete',
        'calendar.update',

        'pantry.add',
        'pantry.edit',
        'pantry.update',
        'pantry.delete',

        'comment.add',
        'comment.delete',
        'comment.edit',
        'comment.update',
        'comment.block',

        'opinion.add',

        'dashboard',
    ],

    'assigns' => [
        'admin' => [
            'product.create',
            'product.update',
            'product.edit',
            'product.delete',
            'product.offer',
            'product.accept',

            'recipe.create',
            'recipe.show',
            'recipe.edit',
            'recipe.update',
            'recipe.delete',

            'product_category.create',
            'product_category.edit',
            'product_category.update',
            'product_category.delete',

            'recipe_category.create',
            'recipe_category.edit',
            'recipe_category.update',
            'recipe_category.delete',


            'category.create',
            'category.edit',
            'category.update',
            'category.delete',

            'calendar.show',
            'calendar.add',
            'calendar.edit',
            'calendar.delete',
            'calendar.update',

            'pantry.add',
            'pantry.edit',
            'pantry.update',
            'pantry.delete',

            'comment.add',
            'comment.delete',
            'comment.edit',
            'comment.update',
            'comment.block',

            'opinion.add',

            'dashboard',
        ],

        'moderator' => [
            'product.accept',
            'product.offer',
            'recipe.create',
            'recipe.show',
            'recipe.edit',
            'recipe.update',
            'recipe.delete',

            'calendar.show',
            'calendar.add',
            'calendar.edit',
            'calendar.delete',
            'calendar.update',

            'pantry.add',
            'pantry.edit',
            'pantry.update',
            'pantry.delete',

            'comment.add',
            'comment.delete',
            'comment.edit',
            'comment.update',

            'opinion.add',

            'dashboard',

        ],

        'user' => [
            'product.offer',

            'recipe.create',
            'recipe.show',
            'recipe.edit',
            'recipe.update',
            'recipe.delete',

            'calendar.show',
            'calendar.add',
            'calendar.edit',
            'calendar.delete',
            'calendar.update',

            'pantry.add',
            'pantry.edit',
            'pantry.update',
            'pantry.delete',

            'comment.add',
            'comment.delete',
            'comment.edit',
            'comment.update',

            'opinion.add',
        ],
    ],
];