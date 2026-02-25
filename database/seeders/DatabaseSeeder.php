<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@discofor.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create test user
        User::create([
            'name' => 'João Silva',
            'email' => 'joao@discofor.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // Create tags
        $tags = [
            'Inteligência Artificial',
            'Educação',
            'Tecnologia',
            'Pesquisa',
            'Inovação',
            'Ciência de Dados',
            'Desenvolvimento',
            'Segurança',
        ];

        foreach ($tags as $tagName) {
            Tag::create([
                'name' => $tagName,
                'slug' => Str::slug($tagName),
            ]);
        }

        // Create sample articles
        $articles = [
            [
                'title' => 'O Futuro da Inteligência Artificial na Educação',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'image' => null,
                'status' => 'published',
                'tags' => [1, 2, 3],
            ],
            [
                'title' => 'Segurança em Aplicações Web Modernas',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'image' => null,
                'status' => 'published',
                'tags' => [3, 8],
            ],
        ];

        foreach ($articles as $articleData) {
            $tags = $articleData['tags'];
            unset($articleData['tags']);

            $article = Article::create([
                'user_id' => 1,
                'slug' => Str::slug($articleData['title']) . '-' . Str::random(8),
                ...$articleData,
            ]);

            $article->tags()->attach($tags);
        }
    }
}
